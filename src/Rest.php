<?php
namespace Meloniq\GpTranslateUpdateApi;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

class Rest extends WP_REST_Controller {

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
	public function register_routes() : void {
		register_rest_route(
			'translations',
			'/update-check/1.1',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'handle_update_check' ),
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

		// Which locales we have to check for updates.
		if ( empty( $data['locale'] ) || ! is_array( $data['locale'] ) ) {
			return new WP_Error( 'invalid_locale', __( 'Invalid locale data.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		$locales = array();
		$supported_locales = wp_list_pluck( GP_Locales::locales(), 'slug' );//wp_locale
		foreach ( $data['locale'] as $locale ) {
			// Remove country code; e.g. pl_PL -> pl
			$locale_slug = explode( '_', $locale )[0];
			if ( in_array( $locale_slug, $supported_locales, true ) ) {
				$locales[] = $locale_slug;
			}
		}

		$locales = array_unique( $locales );
		if ( empty( $locales ) ) {
			return new WP_Error( 'no_locales', __( 'No supported locales found.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		// Translations that already exist on the site.
		if ( ! is_array( $data['translations'] ) ) {
			$data['translations'] = array();
		}


		if ( empty( $data['plugins']['plugins'] ) && empty( $data['themes']['themes'] ) ) {
			return new WP_Error( 'no_plugins_themes', __( 'No plugins or themes found.', 'gp-translate-update-api' ), array( 'status' => 400 ) );
		}

		if ( ! is_array( $data['plugins']['plugins'] ) ) {
			$data['plugins']['plugins'] = array();
		}

		if ( ! is_array( $data['themes']['themes'] ) ) {
			$data['themes']['themes'] = array();
		}

		if ( ! empty( $data['plugins']['plugins'] ) ) {
			return $this->plugin_translations_check( $data['plugins']['plugins'], $data['translations'], $locales );
		}

		if ( ! empty( $data['themes']['themes'] ) ) {
			return $this->theme_translations_check( $data['themes']['themes'], $data['translations'], $locales );
		}

		$response = array(
			'plugins'      => array(),
			'themes'       => array(),
			'translations' => array(),
			'no_update'    => array(),
		);

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Check for plugin translations.
	 *
	 * @param array $plugins Plugins to check.
	 * @param array $translations Existing translations.
	 * @param array $locales Locales to check.
	 *
	 * @return WP_REST_Response
	 */
	public function plugin_translations_check( array $plugins, array $translations, array $locales ) : WP_REST_Response {
		$response = array(
			'plugins'      => array(),
			'translations' => array(),
			'no_update'    => array(),
		);

		foreach ( $plugins as $plugin ) {
			if ( ! isset( $plugin['TranslationsURI'] ) || ! isset( $plugin['TranslationsAPI'] ) ) {
				continue;
			}

			// Check if site_url host match TranslationsURI.
			$site_url = parse_url( site_url() );
			$translations_uri = parse_url( $plugin['TranslationsURI'] );
			if ( ! isset( $site_url['host'] ) || ! isset( $translations_uri['host'] ) ) {
				continue;
			}
			if ( $site_url['host'] !== $translations_uri['host'] ) {
				continue;
			}

			// Get the project path from TranslationsURI.
			$project_path = ltrim( '/projects', $translations_uri['path'] );
			$project_path = rtrim( $project_path, '/' );

			$project = GP::$project->by_path( $project_path );
			if ( ! $project ) {
				continue;
			}

			foreach ( $locales as $locale ) {
				// TODO
			}
		}

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Check for theme translations.
	 *
	 * @param array $themes Themes to check.
	 * @param array $translations Existing translations.
	 * @param array $locales Locales to check.
	 *
	 * @return WP_REST_Response
	 */
	public function theme_translations_check( array $themes, array $translations, array $locales ) : WP_REST_Response {
		$response = array(
			'themes'       => array(),
			'translations' => array(),
			'no_update'    => array(),
		);

		foreach ( $themes as $theme ) {
			if ( ! isset( $plugin['TranslationsURI'] ) || ! isset( $plugin['TranslationsAPI'] ) ) {
				continue;
			}

			// Check if site_url host match TranslationsURI.
			$site_url = parse_url( site_url() );
			$translations_uri = parse_url( $plugin['TranslationsURI'] );
			if ( ! isset( $site_url['host'] ) || ! isset( $translations_uri['host'] ) ) {
				continue;
			}
			if ( $site_url['host'] !== $translations_uri['host'] ) {
				continue;
			}

			// Get the project path from TranslationsURI.
			$project_path = ltrim( '/projects', $translations_uri['path'] );
			$project_path = rtrim( $project_path, '/' );

			$project = GP::$project->by_path( $project_path );
			if ( ! $project ) {
				continue;
			}

			foreach ( $locales as $locale ) {
				// TODO
			}
		}

		return new WP_REST_Response( $response, 200 );
	}


}
