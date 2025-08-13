<?php
/**
 * Plugin Name:       GP Translate Update API
 * Plugin URI:        https://blog.meloniq.net/gp-translate-update-api/
 *
 * Description:       GlotPress Translate Update API
 * Tags:              glotpress, translate, update, api
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version:           1.0
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://meloniq.net/
 *
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       gp-translate-update-api
 *
 * Requires Plugins:  glotpress, gp-format-zip
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

use GP;

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GPTUA_TD', 'gp-translate-update-api' );
define( 'GPTUA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GPTUA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Setup plugin data.
 *
 * @return void
 */
function setup() {
	global $gptua_translate;

	require_once trailingslashit( __DIR__ ) . 'src/class-admin-page.php';
	require_once trailingslashit( __DIR__ ) . 'src/class-settings.php';
	require_once trailingslashit( __DIR__ ) . 'src/class-rest.php';

	$gptua_translate['admin-page'] = new AdminPage();
	$gptua_translate['settings']   = new Settings();
	$gptua_translate['rest']       = new Rest();
}
add_action( 'after_setup_theme', 'Meloniq\GpTranslateUpdateApi\setup' );

/**
 * Error logging.
 *
 * @param mixed $message The message to log.
 *
 * @return void
 */
function gp_error_log( $message ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	if ( is_array( $message ) || is_object( $message ) ) {
		error_log( print_r( $message, true ) ); // phpcs:ignore
	} else {
		error_log( $message ); // phpcs:ignore
	}
}
