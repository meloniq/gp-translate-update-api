<?php
/**
 * Core functionality.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

use GP_Project;
use GP_Locale;

/**
 * Core class.
 */
class Core {

	use Helper;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'gpzip_file_name', array( $this, 'modify_zip_file_name' ), 10, 3 );
	}

	/**
	 * Modify the file names to match with desired text domain.
	 *
	 * @param string     $file_name The original file name.
	 * @param GP_Project $project The project object.
	 * @param GP_Locale  $locale The locale object.
	 *
	 * @return string Modified file name.
	 */
	public function modify_zip_file_name( string $file_name, GP_Project $project, GP_Locale $locale ): string {
		if ( empty( $_GET['textdomain'] ) || ! is_string( $_GET['textdomain'] ) ) { // phpcs:ignore
			return $file_name;
		}

		$textdomain    = sanitize_text_field( wp_unslash( $_GET['textdomain'] ) ); // phpcs:ignore
		$language_code = $this->get_language_code( $locale );

		$file_name = $textdomain . '-' . $language_code;

		return $file_name;
	}
}
