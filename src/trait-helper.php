<?php
/**
 * Trait helper.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

use GP_Locale;

/**
 * Helper trait.
 */
trait Helper {

	/**
	 * Returns the language code for the given locale.
	 *
	 * @param GP_Locale $locale The locale object to get the language code for.
	 *
	 * @return string The language code.
	 */
	public function get_language_code( GP_Locale $locale ): string {
		if ( ! empty( $locale->wp_locale ) ) {
			return $locale->wp_locale;
		}

		if ( ! empty( $locale->facebook_locale ) ) {
			return $locale->facebook_locale;
		}

		return $locale->slug;
	}
}
