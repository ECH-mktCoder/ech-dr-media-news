<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://#
 * @since             1.0.0
 * @package           Ech_Dr_Media_News
 *
 * @wordpress-plugin
 * Plugin Name:       ECH Dr Media News
 * Plugin URI:        https://#
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Rowan Chang
 * Author URI:        https://#
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ech-dr-media-news
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ECH_DR_MEDIA_NEWS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ech-dr-media-news-activator.php
 */
function activate_ech_dr_media_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-dr-media-news-activator.php';
	Ech_Dr_Media_News_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ech-dr-media-news-deactivator.php
 */
function deactivate_ech_dr_media_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-dr-media-news-deactivator.php';
	Ech_Dr_Media_News_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ech_dr_media_news' );
register_deactivation_hook( __FILE__, 'deactivate_ech_dr_media_news' );

/****************************************
 * Create an option "run_init_createVP" once plugin is activated
 ****************************************/
function ECHD_activate_initialize_createVP() {
	require_once plugin_dir_path( __FILE__ ) . 'public/class-ech-news-virtual-pages.php';
	Ech_News_Virtual_Pages::ECHD_initialize_createVP();
}
register_activation_hook( __FILE__, 'ECHD_activate_initialize_createVP' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ech-dr-media-news.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ech_dr_media_news() {

	$plugin = new Ech_Dr_Media_News();
	$plugin->run();

}
run_ech_dr_media_news();
