<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/includes
 * @author     Rowan Chang <rowanchang@prohaba.com>
 */
class Ech_Dr_Media_News_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ech-dr-media-news',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
