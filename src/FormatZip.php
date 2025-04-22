<?php
namespace Meloniq\GpTranslateUpdateApi;

use GP;
use GP_Locale;
use GP_Format;
use GP_Translation_Set;
use GP_Translation;
use GP_Project;
use ZipArchive;

class FormatZip extends GP_Format {

	/**
	 * Name of file format, used in file format dropdowns.
	 *
	 * @var string
	 */
	public $name = 'File archive (.zip)';

	/**
	 * File extension of the file format, used to autodetect formats and when creating the output file names.
	 *
	 * @var string
	 */
	public $extension = 'zip';

	/**
	 * Generates a string the contains the $entries to export in the Zip file format.
	 *
	 * @param GP_Project         $project         The project the strings are being exported for, not used
	 *                                            in this format but part of the scaffold of the parent object.
	 * @param GP_Locale          $locale          The locale object the strings are being exported for. not used
	 *                                            in this format but part of the scaffold of the parent object.
	 * @param GP_Translation_Set $translation_set The locale object the strings are being
	 *                                            exported for. not used in this format but part
	 *                                            of the scaffold of the parent object.
	 * @param GP_Translation     $entries         The entries to export.
	 *
	 * @return string|false The exported Zip file.
	 */
	public function print_exported_file( $project, $locale, $translation_set, $entries ) {
		$language_code = $this->get_language_code( $locale );
		if ( false === $language_code ) {
			$language_code = $locale->slug;
		}

		//GP::$translation->last_modified( $translation_set )

		$po_object = GP::$formats['po'];
		$mo_object = GP::$formats['mo'];

		// Note: Use @ to suppress PHP Deprecated errors from the PO and MO objects.
		$po_file = @$po_object->print_exported_file( $project, $locale, $translation_set, $entries );
		$mo_file = @$mo_object->print_exported_file( $project, $locale, $translation_set, $entries );

		// Prepare the file name with the project path and language code.
		$parents = array_reverse( $project->path_to_root() );
		$slugs = wp_list_pluck( $parents, 'slug' );

		$file_name = implode( '-', $slugs ) . '-' . $language_code . '.zip';
		$file_name = apply_filters( 'gptua_zip_file_name', $file_name, $project, $locale, $translation_set, $entries );

		$zip_file = $this->create_zip( $po_file, $mo_file, $file_name );
		if ( false === $zip_file ) {
			error_log( 'Failed to create Zip file for ' . $file_name );
			return false;
		}

		return $zip_file;
	}

	/**
	 * Creates a Zip file from the given files.
	 *
	 * @param string $po_file The path to the PO file.
	 * @param string $mo_file The path to the MO file.
	 * @param string $file_name The name of the files in the Zip file.
	 *
	 * @return string|false The created Zip file.
	 */
	public function create_zip( $po_file, $mo_file, $file_name ) {
		if ( ! class_exists( 'ZipArchive' ) ) {
			error_log( 'ZipArchive class not found' );
			return false;
		}

		$zip = new ZipArchive();
		$zip_file = $file_name . '.zip';

		$zip_open = $zip->open( $zip_file, ZipArchive::CREATE );
		if ( $zip_open !== true ) {
			error_log( 'Failed to open Zip file: ' . $zip_open );
			return false;
		}

		// Add the files from strings to the Zip file.
		$res_po = $zip->addFromString( $file_name . '.po', $po_file );
		$res_mo = $zip->addFromString( $file_name . '.mo', $mo_file );
		if ( $res_po === false || $res_mo === false ) {
			error_log( 'Failed to add files to Zip file: ' . $file_name );
			$zip->close();
			return false;
		}

		$zip->close();

		// Read file content to return it.
		$zip_file_content = file_get_contents( $zip_file );
		if ( $zip_file_content === false ) {
			error_log( 'Failed to read Zip file: ' . $file_name );
			return false;
		}

		return $zip_file_content;
	}

	/**
	 * Reads a set of original strings from a Zip file.
	 *
	 * @param string $file_name The name of the uploaded Zip file.
	 *
	 * @return false Always returns false, as this is not currently implemented.
	 */
	public function read_originals_from_file( $file_name ) {
		// TODO: Either implement in a secure way or mark as unsupported.
		return false;
	}

	/**
	 * Reads a set of translations from a Zip file.
	 *
	 * @param string     $file_name The name of the uploaded Zip file.
	 * @param GP_Project $project   Unused. The project object to read the translations into.
	 *
	 * @return false Always returns false, as this is not currently implemented.
	 */
	public function read_translations_from_file( $file_name, $project = null ) {
		// TODO: Either implement in a secure way or mark as unsupported.
		return false;
	}

	/**
	 * Returns the language code for the given locale.
	 *
	 * @param GP_Locale $locale The locale object to get the language code for.
	 *
	 * @return string The language code.
	 */
	public function get_language_code( $locale ) {
		if ( ! empty( $locale->wp_locale ) ) {
			return $locale->wp_locale;
		}

		if ( ! empty( $locale->facebook_locale ) ) {
			return $locale->facebook_locale;
		}

		return $locale->slug;
	}

}
