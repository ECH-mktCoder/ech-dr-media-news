<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/admin
 * @author     Rowan Chang <rowanchang@prohaba.com>
 */
class Ech_Dr_Media_News_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ech_Dr_Media_News_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ech_Dr_Media_News_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ech-dr-media-news-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ech_Dr_Media_News_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ech_Dr_Media_News_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        // Apply below files only in this plugin admin page
        if(isset($_GET['page']) && $_GET['page'] == 'reg_ech_dmn_general_settings') {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ech-dr-media-news-admin.js', array( 'jquery' ), $this->version, false);
        }

    }

    /**
     *  ^^^ Add ECH DR Media News admin menu
     */
    public function ech_dr_media_news_admin_menu()
    {
        add_menu_page('ECH DR Media News Settings', 'ECH DR Media News', 'manage_options', 'reg_ech_dmn_general_settings', array($this, 'ech_dr_media_news_admin_page'), 'dashicons-buddicons-buddypress-logo', 110);
    }
    // return view
    public function ech_dr_media_news_admin_page()
    {
        require_once('partials/ech-dr-media-news-admin-display.php');
    }

    /**
     * ^^^ Register custom fields for plugin settings
     *
     * @since    1.0.0
     */
    public function reg_ech_dmn_general_settings()
    {
        // Register all settings for general setting page
        register_setting('dmm_gen_settings', 'ech_dmn_access_token');
        register_setting('dmm_gen_settings', 'ech_dmn_submitBtn_color');
        register_setting('dmm_gen_settings', 'ech_dmn_submitBtn_hoverColor');
        register_setting('dmm_gen_settings', 'ech_dmn_submitBtn_text_color');
        register_setting('dmm_gen_settings', 'ech_dmn_submitBtn_text_hoverColor');
        register_setting('dmm_gen_settings', 'ech_dr_media_news_ppp');
        register_setting('dmm_gen_settings', 'ech_dmn_default_post_featured_img');
        register_setting('dmm_gen_settings', 'ech_dmn_form_shortcode');
        register_setting('dmm_gen_settings', 'ech_dmn_enable_dr_filter');
        register_setting('dmm_gen_settings', 'ech_dmn_enable_spec_filter');
        register_setting('dmm_gen_settings', 'ech_dmn_enable_brand_filter');
        register_setting('dmm_gen_settings', 'ech_news_dr_filter');
        register_setting('dmm_gen_settings', 'ech_news_spec_filter');
        register_setting('dmm_gen_settings', 'ech_news_brand_filter');
    }
    
	public function ADMIN_ECHD_gen_doctor_api_link()
	{
		$full_api = 'https://echealthcaremc.com/wp-json/echmcwp-api/v1/dr_name_categories';

		return $full_api;
	}

	public function ADMIN_ECHD_gen_specialty_api_link()
	{
		$full_api = 'https://echealthcaremc.com/wp-json/echmcwp-api/v1/specialties_categories';

		return $full_api;
	}
	public function ADMIN_ECHD_gen_brand_api_link()
	{
		$full_api = 'https://echealthcaremc.com/wp-json/echmcwp-api/v1/brand_categories';

		return $full_api;
	}
    /****************************************
     * Get News JSON Using API
     ****************************************/

    public function ADMIN_ECHD_wp_remote_get_news_json($api_link)
    {
        $getAccessToken = get_option('ech_dmn_access_token');
        $api_headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $getAccessToken,
        );

        $response = wp_remote_get($api_link, array(
            'headers' => $api_headers,
        ));

        if (is_wp_error($response)) {
            return 'Error: ' . $response->get_error_message();
        }

        $result = wp_remote_retrieve_body($response);

        return $result;
    }


}
