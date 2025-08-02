<?php
/**
 * Admin Page.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

/**
 * Admin Page class.
 */
class AdminPage {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 10 );
	}

	/**
	 * Add menu page.
	 *
	 * @return void
	 */
	public function add_menu_page(): void {
		add_submenu_page(
			'options-general.php',
			__( 'GP Translate Update API', 'gp-translate-update-api' ),
			__( 'GP Translate Update API', 'gp-translate-update-api' ),
			'manage_options',
			'gp-translate-update-api',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'GP Translate Update API', 'gp-translate-update-api' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'gptua_settings' );
				do_settings_sections( 'gptua_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
