<?php
/**
 * Settings for GP Translate Update API.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

/**
 * Settings class.
 *
 * This class handles the settings for the GP Translate Update API plugin.
 */
class Settings {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_settings' ), 10 );
	}

	/**
	 * Initialize settings.
	 *
	 * @return void
	 */
	public function init_settings(): void {
		// Section: Update API.
		add_settings_section(
			'gptua_section',
			__( 'Update API', 'gp-translate-update-api' ),
			array( $this, 'render_section' ),
			'gptua_settings'
		);

		// Option: API Key.
		$this->register_field_api_key();
	}

	/**
	 * Render section.
	 *
	 * @return void
	 */
	public function render_section(): void {
		esc_html_e( 'Settings for Update API access.', 'gp-translate-update-api' );
	}

	/**
	 * Register settings field API Key.
	 *
	 * @return void
	 */
	public function register_field_api_key(): void {
		$field_name    = 'gptua_api_key';
		$section_name  = 'gptua_section';
		$settings_name = 'gptua_settings';

		register_setting(
			$settings_name,
			$field_name,
			array(
				'label'             => __( 'Update API Key', 'gp-translate-update-api' ),
				'description'       => __( 'Enter the Update API Key.', 'gp-translate-update-api' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Update API Key', 'gp-translate-update-api' ),
			array( $this, 'render_field_api_key' ),
			$settings_name,
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Render settings field API Key.
	 *
	 * @return void
	 */
	public function render_field_api_key(): void {
		$field_name = 'gptua_api_key';

		$api_key = get_option( $field_name, '' );
		?>
		<input type="text" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text">
		<p class="description"><?php esc_html_e( 'Enter the Update API Key.', 'gp-translate-update-api' ); ?></p>
		<?php
	}
}
