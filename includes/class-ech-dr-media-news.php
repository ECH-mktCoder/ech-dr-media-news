<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/includes
 * @author     Rowan Chang <rowanchang@prohaba.com>
 */
class Ech_Dr_Media_News
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ech_Dr_Media_News_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('ECH_DR_MEDIA_NEWS_VERSION')) {
            $this->version = ECH_DR_MEDIA_NEWS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ech-dr-media-news';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ech_Dr_Media_News_Loader. Orchestrates the hooks of the plugin.
     * - Ech_Dr_Media_News_i18n. Defines internationalization functionality.
     * - Ech_Dr_Media_News_Admin. Defines all hooks for the admin area.
     * - Ech_Dr_Media_News_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ech-dr-media-news-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ech-dr-media-news-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ech-dr-media-news-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ech-dr-media-news-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ech-news-virtual-pages.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ech-news-filters.php';
				
        $this->loader = new Ech_Dr_Media_News_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Ech_Dr_Media_News_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Ech_Dr_Media_News_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Ech_Dr_Media_News_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // ^^^ Add admin menu items
        $this->loader->add_action('admin_menu', $plugin_admin, 'ech_dr_media_news_admin_menu');

        // ^^^ Register our plugin settings
        $this->loader->add_action('admin_init', $plugin_admin, 'reg_ech_dmn_general_settings');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Ech_Dr_Media_News_Public($this->get_plugin_name(), $this->get_version());
        $virtual_page_public = new Ech_News_Virtual_Pages( $this->get_plugin_name(), $this->get_version() );


        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // ^^^ Register AJAX functions
        $this->loader->add_action('wp_ajax_nopriv_ECHD_load_more_posts', $plugin_public, 'ECHD_load_more_posts');
        $this->loader->add_action('wp_ajax_ECHD_load_more_posts', $plugin_public, 'ECHD_load_more_posts');

        $this->loader->add_action('wp_ajax_nopriv_ECHD_filter_news_list', $plugin_public, 'ECHD_filter_news_list');
        $this->loader->add_action('wp_ajax_ECHD_filter_news_list', $plugin_public, 'ECHD_filter_news_list');

        // ^^^ Add shortcodes
        $this->loader->add_shortcode('ech_dr_media_news', $plugin_public, 'ech_news_func');
        $this->loader->add_shortcode('ech_news_single_post_output', $virtual_page_public, 'ech_news_single_post_output');
        $this->loader->add_shortcode('ech_news_content_form', $plugin_public, 'ech_news_content_form');

        // ^^^ Create VP after WordPress has finished loading, but before any headers are sent
        $this->loader->add_action('init', $virtual_page_public, 'ECHD_createVP');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ech_Dr_Media_News_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
