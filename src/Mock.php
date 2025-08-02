<?php
/**
 * Mock class for testing purposes.
 *
 * This class provides mock data for testing the update themes, plugins, and core requests.
 *
 * @package Meloniq\GpTranslateUpdateApi
 */

namespace Meloniq\GpTranslateUpdateApi;

/**
 * Mock class for testing purposes.
 */
class Mock {

	/**
	 * Get the request arguments for the update themes request.
	 *
	 * @return array The request arguments.
	 */
	public function get_update_themes_request_args() {
		$args = array(
			'timeout'    => 3,
			'body'       => array(
				'themes'       => array(
					'active' => 'twentytwentyfour',
					'themes' => array(
						'twentytwentyfive' => array(
							'Name'       => 'Twenty Twenty-Five',
							'Title'      => 'Twenty Twenty-Five',
							'Version'    => '1.2',
							'Author'     => 'the WordPress team',
							'Author URI' => 'https://wordpress.org',
							'UpdateURI'  => '',
							'Template'   => 'twentytwentyfive',
							'Stylesheet' => 'twentytwentyfive',
						),
						'twentytwentyfour' => array(
							'Name'       => 'Twenty Twenty-Four',
							'Title'      => 'Twenty Twenty-Four',
							'Version'    => '1.3',
							'Author'     => 'the WordPress team',
							'Author URI' => 'https://wordpress.org',
							'UpdateURI'  => '',
							'Template'   => 'twentytwentyfour',
							'Stylesheet' => 'twentytwentyfour',
						),
					),
				),
				'translations' => array(
					'twentytwentyfour' => array(
						'pl_PL' => array(
							'POT-Creation-Date'  => '',
							'PO-Revision-Date'   => '2025-01-17 09:17:15+0000',
							'Project-Id-Version' => 'Themes - Twenty Twenty-Four',
							'X-Generator'        => 'GlotPress/4.0.1',
						),
					),
				),
				'locale'       => array( 'pl_PL' ),
			),
			'user-agent' => 'WordPress/6.8; https://glotpress.test/',
		);

		return $args;
	}

	/**
	 * Get the response body for the update themes request.
	 *
	 * @return array The response body.
	 */
	public function get_update_themes_response_body() {
		$response = array(
			'themes'       => array(),
			'no_update'    => array(
				'twentytwentyfive' => array(
					'theme'        => 'twentytwentyfive',
					'new_version'  => '1.2',
					'url'          => 'https://wordpress.org/themes/twentytwentyfive/',
					'package'      => 'https://downloads.wordpress.org/theme/twentytwentyfive.1.2.zip',
					'requires'     => '6.7',
					'requires_php' => '7.2',
				),
				'twentytwentyfour' => array(
					'theme'        => 'twentytwentyfour',
					'new_version'  => '1.3',
					'url'          => 'https://wordpress.org/themes/twentytwentyfour/',
					'package'      => 'https://downloads.wordpress.org/theme/twentytwentyfour.1.3.zip',
					'requires'     => '6.4',
					'requires_php' => '7.0',
				),
			),
			'translations' => array(),
		);

		return $response;
	}

	/**
	 * Get the request arguments for the update plugins request.
	 *
	 * @return array The request arguments.
	 */
	public function get_update_plugins_request_args() {
		$args = array(
			'timeout'    => 4,
			'body'       => array(
				'plugins'      => array(
					'plugins' => array(
						'glotpress/glotpress.php' => array(
							'Name'            => 'GlotPress',
							'PluginURI'       => 'https://wordpress.org/plugins/glotpress/',
							'Version'         => '4.0.1',
							'Description'     => 'GlotPress is a tool to help translators collaborate.',
							'Author'          => 'the GlotPress team',
							'AuthorURI'       => 'https://glotpress.blog',
							'TextDomain'      => 'glotpress',
							'Network'         => false,
							'RequiresWP'      => '4.6',
							'RequiresPHP'     => '7.4',
							'Title'           => 'GlotPress',
							'AuthorName'      => 'the GlotPress team',
							'TranslationsAPI' => 'https://glotpress.test',
							'TranslationsURI' => 'https://glotpress.test/projects/appthemes/clipper/2.0.x/',
						),
						'gp-machine-translate/gp-machine-translate.php' => array(
							'Name'            => 'GP Machine Translate',
							'PluginURI'       => 'https://wordpress.org/plugins/gp-machine-translate/',
							'Version'         => '2.0',
							'Description'     => 'Machine translation for GlotPress.',
							'Author'          => 'the GlotPress team',
							'AuthorURI'       => 'https://glotpress.blog',
							'TextDomain'      => 'gp-machine-translate',
							'Network'         => false,
							'RequiresWP'      => '4.6',
							'RequiresPHP'     => '7.4',
							'Title'           => 'GP Machine Translate',
							'AuthorName'      => 'the GlotPress team',
							'TranslationsAPI' => 'https://glotpress.test',
							'TranslationsURI' => 'https://glotpress.test/projects/appthemes/clipper/2.0.x/',
						),
					),
					'active'  => array(
						'glotpress/glotpress.php',
						'gp-machine-translate/gp-machine-translate.php',
					),
				),
				'translations' => array(
					'glotpress'            => array(
						'pl_PL' => array(
							'POT-Creation-Date'  => '',
							'PO-Revision-Date'   => '2023-06-07 12:25:49+0000',
							'Project-Id-Version' => 'Plugins - GlotPress - Stable (latest release)',
							'X-Generator'        => 'GlotPress/4.0.1',
						),
					),
					'gp-machine-translate' => array(
						'pl_PL' => array(
							'POT-Creation-Date'  => '',
							'PO-Revision-Date'   => '2023-12-26 17:47:10+0000',
							'Project-Id-Version' => 'Plugins - GP Machine Translate - Stable (latest release)',
							'X-Generator'        => 'GlotPress/4.0.1',
						),
					),
				),
				'locale'       => array( 'pl_PL' ),
				'all'          => true,
			),
			'user-agent' => 'WordPress/6.8; https://glotpress.test/',
		);

		return $args;
	}

	/**
	 * Get the response body for the update plugins request.
	 *
	 * @return array The response body.
	 */
	public function get_update_plugins_response_body() {
		$response = array(
			'plugins'      => array(), // No plugin updates.
			'translations' => array(
				array(
					'type'       => 'plugin',
					'slug'       => 'glotpress',
					'language'   => 'pl_PL',
					'version'    => '4.0.1',
					'updated'    => '2024-06-07 12:25:49',
					'package'    => 'https://downloads.wordpress.org/translation/plugin/glotpress/4.0.1/pl_PL.zip',
					'autoupdate' => true,
				),
				array(
					'type'       => 'plugin',
					'slug'       => 'gp-machine-translate',
					'language'   => 'pl_PL',
					'version'    => '2.0',
					'updated'    => '2024-12-26 17:47:10',
					'package'    => 'https://downloads.wordpress.org/translation/plugin/gp-machine-translate/2.0/pl_PL.zip',
					'autoupdate' => true,
				),
			),
		);

		return $response;
	}

	/**
	 * Get the request arguments for the update code request.
	 *
	 * @return array The request arguments.
	 */
	public function get_update_code_request_args() {
		return array(
			'timeout'    => 3,
			'user-agent' => 'WordPress/6.8; https://glotpress.test/',
			'headers'    => array(
				'wp_install' => 'https://glotpress.test/',
				'wp_blog'    => 'https://glotpress.test/',
			),
			'body'       => array(
				'translations' => wp_json_encode(
					array(
						'admin-network'     => array(
							'pl_PL' => array(
								'POT-Creation-Date'  => '',
								'PO-Revision-Date'   => '2025-03-31 17:22:17+0000',
								'Project-Id-Version' => 'WordPress - 6.8.x - Development - Administration - Network Admin',
								'X-Generator'        => 'GlotPress/4.0.1',
							),
						),
						'admin'             => array(
							'pl_PL' => array(
								'POT-Creation-Date'  => '',
								'PO-Revision-Date'   => '2025-04-09 05:59:50+0000',
								'Project-Id-Version' => 'WordPress - 6.8.x - Development - Administration',
								'X-Generator'        => 'GlotPress/4.0.1',
							),
						),
						'continents-cities' => array(
							'pl_PL' => array(
								'POT-Creation-Date'  => '',
								'PO-Revision-Date'   => '2022-10-12 10:21:45+0000',
								'Project-Id-Version' => 'WordPress - 6.8.x - Development - Continents & Cities',
								'X-Generator'        => 'GlotPress/4.0.1',
							),
						),
						'default'           => array(
							'pl_PL' => array(
								'POT-Creation-Date'  => '',
								'PO-Revision-Date'   => '2025-04-17 12:00:58+0000',
								'Project-Id-Version' => 'WordPress - 6.8.x - Development',
								'X-Generator'        => 'GlotPress/4.0.1',
							),
						),
					)
				),
			),
		);
	}

	/**
	 * Get the response body for the update core request.
	 *
	 * @return array The response body.
	 */
	public function get_update_core_response_body() {
		$response = array(
			'offers'       => array(
				array(
					'response'        => 'latest',
					'download'        => 'https://downloads.wordpress.org/release/wordpress-6.8.zip',
					'locale'          => 'en_US',
					'packages'        => array(
						'full'        => 'https://downloads.wordpress.org/release/wordpress-6.8.zip',
						'no_content'  => 'https://downloads.wordpress.org/release/wordpress-6.8-no-content.zip',
						'new_bundled' => 'https://downloads.wordpress.org/release/wordpress-6.8-new-bundled.zip',
						'partial'     => false,
						'rollback'    => false,
					),
					'current'         => '6.8',
					'version'         => '6.8',
					'php_version'     => '7.2.24',
					'mysql_version'   => '5.5.5',
					'new_bundled'     => '6.7',
					'partial_version' => false,
				),
			),
			'translations' => array(),
		);

		return $response;
	}
}
