<?php
/**
 * REST API controller for GP Translate Update API.
 *
 * This class handles the REST API requests for checking updates of themes, plugins, and core translations.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use GP;
use GP_Locales;

/**
 * REST API controller for GP Translate Update API.
 */
class Rest extends WP_REST_Controller {

	use Helper;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ), 10 );
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			'gp/translations',
			'/update-check/0.1',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_update_check' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle update check.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function handle_update_check( WP_REST_Request $request ) {
		$body = $request->get_body();
		$data = json_decode( $body, true );

		if ( ! is_array( $data ) ) {
			return new WP_Error( 'invalid_data', __( 'Invalid data received.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		if ( empty( $data['item'] ) || ! is_string( $data['item'] ) ) {
			return new WP_Error( 'invalid_item', __( 'Invalid item data.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		// Which locales we have to check for updates.
		if ( empty( $data['locale'] ) || ! is_array( $data['locale'] ) ) {
			return new WP_Error( 'invalid_locale', __( 'Invalid locale data.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		// What are the current translations for the item.
		if ( ! isset( $data['translations'] ) || ! is_array( $data['translations'] ) ) {
			return new WP_Error( 'invalid_translations', __( 'Invalid translations data.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}
		$current_translations = $data['translations'];

		$locales           = array();
		$supported_locales = wp_list_pluck( GP_Locales::locales(), 'slug' );
		foreach ( $data['locale'] as $locale ) {
			// Remove country code; e.g. pl_PL -> pl.
			$locale_slug = explode( '_', $locale )[0];
			if ( in_array( $locale_slug, $supported_locales, true ) ) {
				$locales[] = $locale_slug;
			}
		}

		$locales = array_unique( $locales );
		if ( empty( $locales ) ) {
			return new WP_Error( 'no_locales', __( 'No supported locales found.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		$update_translations = $this->project_translations_check( $data['item'], $locales );
		if ( empty( $update_translations ) ) {
			return new WP_Error( 'no_translations', __( 'No translations found for the specified item and locales.', 'gp-translate-update-api' ), array( 'status' => 404 ) );
		}

		// Filter out translations that are already up-to-date.
		$final_translations = $this->exclude_up_to_date_translations( $update_translations, $current_translations );

		return new WP_REST_Response( $final_translations, 200 );
	}

	/**
	 * Check for project translations.
	 *
	 * @param string $project Project to check.
	 * @param array  $locales Locales to check.
	 *
	 * @return array
	 */
	public function project_translations_check( string $project, array $locales ): array {
		// Standardize project path.
		$project_path = ltrim( $project, '/projects' );
		$project_path = rtrim( $project_path, '/' );

		$project = GP::$project->by_path( $project_path );
		if ( ! $project ) {
			gp_error_log( sprintf( 'Project not found: %s', $project_path ) );
			return array();
		}

		$translation_sets = GP::$translation_set->by_project_id( $project->id );
		if ( empty( $translation_sets ) ) {
			gp_error_log( sprintf( 'No translation sets found for project: %s', $project_path ) );
			return array();
		}

		$response = array();

		foreach ( $locales as $locale ) {
			$locale_obj = GP_Locales::by_slug( $locale );
			if ( ! $locale_obj ) {
				gp_error_log( sprintf( 'Locale not found: %s', $locale ) );
				continue;
			}

			foreach ( $translation_sets as $translation_set ) {
				if ( $locale !== $translation_set->locale ) {
					continue;
				}

				$response[] = array(
					'language' => $this->get_language_code( $locale_obj ),
					'updated'  => GP::$translation->last_modified( $translation_set ),
					'package'  => $this->get_download_url( $project_path, $locale, $translation_set->slug ),
				);
			}
		}

		return $response;
	}

	/**
	 * Exclude translations that are already up-to-date.
	 *
	 * @param array $update_translations Translations to check.
	 * @param array $current_translations Current translations.
	 *
	 * @return array Filtered translations.
	 */
	protected function exclude_up_to_date_translations( array $update_translations, array $current_translations ): array {
		$filtered_translations = array();

		// No updates to filter, return as is.
		if ( empty( $update_translations ) || empty( $current_translations ) ) {
			return $update_translations;
		}

		foreach ( $update_translations as $update_translation ) {
			$update_locale = $update_translation['language'];
			$update_date   = strtotime( $update_translation['updated'] );

			foreach ( $current_translations as $current_locale => $current_translation ) {
				$current_date = strtotime( $current_translation['PO-Revision-Date'] );

				if ( $update_locale !== $current_locale ) {
					continue;
				}

				if ( $update_date > $current_date ) {
					$filtered_translations[] = $update_translation;
					break; // No need to check further for this locale.
				}
			}
		}

		return $filtered_translations;
	}

	/**
	 * Get download URL for the translation package.
	 *
	 * @param string $project_path Project path.
	 * @param string $locale Locale slug.
	 * @param string $slug Slug of the translation set.
	 *
	 * @return string
	 */
	protected function get_download_url( string $project_path, string $locale, string $slug ): string {
		$project_path = ltrim( $project_path, '/' );
		$project_path = rtrim( $project_path, '/' );

		// Construct the download URL.
		$projects_url = home_url( '/projects/' );
		$download_url = sprintf(
			$projects_url . '%1$s/%2$s/%3$s/export-translations/?format=zip',
			$project_path,
			$locale,
			$slug
		);

		return $download_url;
	}
}
