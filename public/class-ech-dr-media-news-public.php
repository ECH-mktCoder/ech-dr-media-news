<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://
 * @since      1.0.0
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/public
 * @author     Rowan Chang <rowanchang@prohaba.com>
 */
class Ech_Dr_Media_News_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->filters = new Ech_News_Filters();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        if (strpos($_SERVER['REQUEST_URI'], "dr-media-news") !== false) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ech-dr-media-news-public.css', array(), $this->version, 'all');
        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
        if (strpos($_SERVER['REQUEST_URI'], "dr-media-news") !== false) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ech-dr-media-news-public.js', array( 'jquery' ), $this->version, false);
        }

    }
    public function ech_news_func($atts)
    {
        // per page post
        $ppp = get_option('ech_dr_media_news_ppp');

        // check if specific filters are set.
        $filterDrID = get_option('ech_news_dr_filter');
        $filterSpecID = get_option('ech_news_spec_filter');
        $filterBrandID = get_option('ech_news_brand_filter');

        //display filters default is 0
        $enableFilterDrID = get_option('ech_dmn_enable_dr_filter') ? 0 : 1;
        $enableFilterSpecID = get_option('ech_dmn_enable_spec_filter') ? 0 : 1;
        $enableFilterBrandID = get_option('ech_dmn_enable_brand_filter') ? 0 : 1;


        $api_args = array(
            'ppp' => $ppp,
            'dr' => $filterDrID,
            'specialties' => $filterSpecID,
            'brand' => $filterBrandID,
        );
        $api_link = $this->ECHD_gen_news_link_api_link($api_args);
        $get_news_json = $this->ECHD_wp_remote_get_news_json($api_link);
        $json_arr = json_decode($get_news_json, true);

        $output = '';

        // *********** Custom styling ***************/
        if (!empty(get_option('ech_dmn_submitBtn_color')) || !empty(get_option('ech_dmn_submitBtn_hoverColor') || !empty(get_option('ech_dmn_submitBtn_text_color')) || !empty(get_option('ech_dmn_submitBtn_text_hoverColor')))) {
            $output .= '<style>';

            $output .= '.ech-dmn-dr-news-container .news-btn button { ';
            (!empty(get_option('ech_dmn_submitBtn_color'))) ? $output .= 'background:' . get_option('ech_dmn_submitBtn_color') . ';' : '';
            (!empty(get_option('ech_dmn_submitBtn_text_color'))) ? $output .= 'color:' . get_option('ech_dmn_submitBtn_text_color') . ';' : '';
            $output .= '}';

            $output .= '.ech-dmn-dr-news-container .news-btn button:hover { ';
            (!empty(get_option('ech_dmn_submitBtn_hoverColor'))) ? $output .= 'background:' . get_option('ech_dmn_submitBtn_hoverColor') . ';' : '';
            (!empty(get_option('ech_dmn_submitBtn_text_hoverColor'))) ? $output .= 'color:' . get_option('ech_dmn_submitBtn_text_hoverColor') . ';' : '';
            $output .= '}';

            $output .= '</style>';
        }
        // *********** (END) Custom styling ****************/

        $output .= '<div class="ech-dmn-dr-news-container">';

        /***** Filters *****/

        $output .= '<div class="ech-dmn-filter-container">';

        if($enableFilterDrID) {
            $output .= $this->filters->ECHD_get_dr_categories_list($filterDrID);
        }
        if($enableFilterSpecID) {
            $output .= $this->filters->ECHD_get_specialty_categories_list($filterSpecID);
        }
        if($enableFilterBrandID) {
            $output .= $this->filters->ECHD_get_brand_categories_list($filterBrandID);
        }


        $output .= '<div class="news-btn">';
        $output .= '<button id="newsSearchBtn" type="button" disabled>' . $this->ECHD_echolang(['Search', '搜尋', '搜寻']) . '</button>';
        $output .= '<button id="resetBtn" type="button" disabled>' . $this->ECHD_echolang(['Reset', '清除', '清除']) . '</button>';
        $output .= '</div>';
        $output .= '</div>'; //ech-dmn-filter-container
        /***** (end)Filters *****/


        /*********** POST LIST ************/
        $output .= '<div class="ech-dmn-news-container" >';

        $output .= '<div class="news-list" data-ajaxurl="' . get_admin_url(null, 'admin-ajax.php') . '" data-ppp="' . $ppp . '" data-page="1" data-specialties="' . $filterSpecID . '" data-dr="' . $filterDrID . '" data-brand="' . $filterBrandID . '">';
        foreach ($json_arr['posts_data'] as $post) {
            $output .= $this->ECHD_load_post_card_template($post);
        }
        $output .= '</div>'; //news-list

        /*** loading div ***/
        $output .= '<div class="loading-news">' . $this->ECHD_echolang(['Loading...', '載入中...', '载入中...']) . '</div>';
        /*** (end) loading div ***/
        $output .= '<div class="news-btn">';
        $output .= '<button id="moreNewsBtn" type="button">' . $this->ECHD_echolang(['More articles', '更多文章', '更多文章']) . '</button>';
        $output .= '</div>';


        $output .= '</div>'; //ech-dmn-news-container

        /*********** (END) POST LIST ************/

        $output .= '</div>'; //ech-dmn-dr-news-container


        return $output;
    } // ech_news_func()

    /****************************************
     * Load Single Post Template
     ****************************************/
    public function ECHD_load_post_card_template($post)
    {
        $html = '';
        $spec_category_id = [];
        $spec_category_name = [];
        $dr_category_id = [];
        $dr_category_name = [];
        $brand_category_id = [];
        $brand_category_name = [];
		$featured_image = ($post['featured_image']['has_featured_image']) ? $post['featured_image']['url'] : get_option('ech_dmn_default_post_featured_img');
				
        foreach ($post['spec_category'] as $spec) {
            array_push($spec_category_id, $spec['id']);
            array_push($spec_category_name, $this->ECHD_echolang([$spec['name_en'],$spec['name_zh'],$spec['name_sc']]));
        }
        foreach ($post['dr_category'] as $dr) {
            array_push($dr_category_id, $dr['id']);
            array_push($dr_category_name, $this->ECHD_echolang([$dr['name_en'],$dr['name_zh'],$dr['name_sc']]));
        }
        foreach ($post['brand_category'] as $brand) {
            array_push($brand_category_id, $brand['id']);
            array_push($brand_category_name, $this->ECHD_echolang([$brand['name_en'],$brand['name_zh'],$brand['name_sc']]));
        }
        $html .= '<div class="news-card" data-news="' . $post['id'] . '" data-specialties="' . implode(',', $spec_category_id) . '" data-dr="' . implode(',', $dr_category_id) . '" data-brand="' . implode(',', $brand_category_id) . '">';
				$html .= '<div class="featured-image">';
        $html .= '<img class="' . ($post['featured_image']['has_featured_image'] ? '' : 'default-logo') . '" src="' . $featured_image . '" alt="' . $post['featured_image']['alt_text'] . '">';
				$html .= '</div>';
        $html .= '<div class="news-info">';
        $html .= '<div class="news-title"><a href="' . site_url() . '/dr-media-news/news-content/?postid=' . $post['id'] . '"><h1>' . $this->ECHD_echolang([$post['acf']['title_en'],$post['acf']['title_zh'],$post['acf']['title_sc']]) . '</h1></a></div>';
        $html .= '<h4 class="news-specialty"><i aria-hidden="true" class="fas fa-tags"></i> ' . implode(' ', $spec_category_name) . '</h4>';
        $html .= '<h4 class="news-doctor"><i aria-hidden="true" class="fas fa-user-tag"></i> ' . implode(' ', $dr_category_name) . '</h4>';
        $html .= '<h4 class="news-brand"><i aria-hidden="true" class="fas fa-building"></i> ' . implode(' ', $brand_category_name) . '</h4>';
        $html .= '<a href="' . site_url() . '/dr-media-news/news-content/?postid=' . $post['id'] . '">' . $this->ECHD_echolang(['Read More','閱讀更多','阅读更多']) . '</a>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /****************************************
     * Load more posts
     ****************************************/
    public function ECHD_load_more_posts()
    {
        $ppp = (isset($_POST['ppp']) && !is_null($_POST['ppp'])) ? $_POST['ppp'] : '';
        $page = (isset($_POST['page']) && !is_null($_POST['page'])) ? $_POST['page'] : '';
        $filterSpec = (isset($_POST['specialties']) && !is_null($_POST['specialties'])) ? $_POST['specialties'] : '';
        $filterDr = (isset($_POST['dr']) && !is_null($_POST['dr'])) ? $_POST['dr'] : '';
        $filterBrand = (isset($_POST['brand']) && !is_null($_POST['brand'])) ? $_POST['brand'] : '';

        $api_args = array(
            'ppp' => $ppp,
            'page' => $page,
            'specialties' => $filterSpec,
            'dr' => $filterDr,
            'brand' => $filterBrand,
        );
        $api_link = $this->ECHD_gen_news_link_api_link($api_args);

        $get_blog_json = $this->ECHD_wp_remote_get_news_json($api_link);
        $json_arr = json_decode($get_blog_json, true);

        $html = '';
        $max_page = 1;
        $current_page = $json_arr['current_page'];
        if (isset($json_arr['posts_data']) && $json_arr['total_filtered_posts'] != 0) {
            $total_posts = $json_arr['total_filtered_posts'];
            $max_page = ceil($total_posts / $ppp);
            foreach ($json_arr['posts_data'] as $post) {
                $html .= $this->ECHD_load_post_card_template($post);
            }
        } else {
            $html .= $this->ECHD_echolang(['No posts ...', '沒有文章', '没有文章']);
        }

        echo json_encode(array('html' => $html,'result' => $json_arr, 'max_page' => $max_page,'current_page' => $current_page), JSON_UNESCAPED_SLASHES);

        wp_die();
    }

    /****************************************
     * Filter Dr Media News posts
     * filter: specialties, dr
     ****************************************/
    public function ECHD_filter_news_list()
    {
        $ppp = (isset($_POST['ppp']) && !is_null($_POST['ppp'])) ? $_POST['ppp'] : '';
        $page = (isset($_POST['page']) && !is_null($_POST['page'])) ? $_POST['page'] : '';
        $filterSpec = (isset($_POST['specialties']) && !is_null($_POST['specialties'])) ? $_POST['specialties'] : '';
        $filterDr = (isset($_POST['dr']) && !is_null($_POST['dr'])) ? $_POST['dr'] : '';
        $filterBrand = (isset($_POST['brand']) && !is_null($_POST['brand'])) ? $_POST['brand'] : '';

        $api_args = array(
            'ppp' => $ppp,
            'page' => $page,
            'specialties' => $filterSpec,
            'dr' => $filterDr,
            'brand' => $filterBrand,
        );
        $api_link = $this->ECHD_gen_news_link_api_link($api_args);
        $get_news_json = $this->ECHD_wp_remote_get_news_json($api_link);
        $json_arr = json_decode($get_news_json, true);

        $html = '';
        $current_page = $json_arr['current_page'];
        $max_page = 1;
        if (isset($json_arr['posts_data']) && $json_arr['total_filtered_posts'] != 0) {
            $total_posts = $json_arr['total_filtered_posts'];
            $max_page = ceil($total_posts / $ppp);
            foreach ($json_arr['posts_data'] as $post) {
                $html .= $this->ECHD_load_post_card_template($post);
            }
        } else {
            $html .= '<h4>' . $this->ECHD_echolang(['No posts ...', '沒有相關文章', '没有相关文章']) . '</h4>';
        }
        echo json_encode(array('html' => $html,'result' => $json_arr ,'max_page' => $max_page,'current_page' => $current_page), JSON_UNESCAPED_SLASHES);

        wp_die();
    }


    /**************************** API ****************************/

    /***********************************************************
     * Get API domain
     ***********************************************************/
    public function ECHD_getAPIDomain()
    {
        $domain = "https://echealthcaremc.com";

        return $domain;
    }

    /****************************************
     * Filter and merge value and return a full API Dr Media News List link.
     * Array key: ppp, page, specialties, dr, brand
     ****************************************/
    public function ECHD_gen_news_link_api_link(array $args)
    {
        $full_api = $this->ECHD_getAPIDomain() . '/wp-json/echmcwp-api/v1/doctor_media_news_list?';

        if(!empty($args)) {
            if(isset($args['ppp']) && !empty($args['ppp'])) {
                $full_api .= 'ppp=' . $args['ppp'];
            }
            if(isset($args['page']) && !empty($args['page'])) {
                $full_api .= '&page=' . $args['page'];
            }
            if(isset($args['specialties']) && !empty($args['specialties'])) {
                $full_api .= '&specialties=' . $args['specialties'];
            }
            if(isset($args['dr']) && !empty($args['dr'])) {
                $full_api .= '&dr=' . $args['dr'];
            }
            // if(isset($args['brand']) && !empty($args['brand'])){
            // 	$full_api .='&brand='.$args['brand'];
            // }
            if(isset($args['id']) && !empty($args['id'])) {
                $full_api .= '&id=' . $args['id'];
            }
        }
        return $full_api;
    }

    public function ECHD_gen_doctor_api_link()
    {
        $full_api = $this->ECHD_getAPIDomain() . '/wp-json/echmcwp-api/v1/dr_name_categories';

        return $full_api;
    }

    public function ECHD_gen_specialty_api_link()
    {
        $full_api = $this->ECHD_getAPIDomain() . '/wp-json/echmcwp-api/v1/specialties_categories';

        return $full_api;
    }

    public function ECHD_gen_brand_api_link()
    {
        $full_api = $this->ECHD_getAPIDomain() . '/wp-json/echmcwp-api/v1/brand_categories';

        return $full_api;
    }

    /****************************************
     * Filter and merge value and return a full API Post Content link.
     * Array key: postid
     ****************************************/
    public function ECHD_gen_post_api_link(array $args)
    {
        $full_api = $this->ECHD_getAPIDomain() . '/wp-json/echmcwp-api/v1/single_post?';

        if (!empty($args['postid'])) {
            $full_api .= '&';
            $full_api .= 'postid=' . $args['postid'];
        }

        return $full_api;
    }

    /**************************** (end)API ****************************/

    /****************************************
     * Get News JSON Using API
     ****************************************/

    public function ECHD_wp_remote_get_news_json($api_link)
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

    /****************************************
     * DISPLAY SPECIFIC LANGUAGE
     ****************************************/
    public function ECHD_echolang($stringArr)
    {
        global $TRP_LANGUAGE;

        switch ($TRP_LANGUAGE) {
            case 'zh_HK':
                $langString = $stringArr[1];
                break;
            case 'zh_CN':
                $langString = $stringArr[2];
                break;
            default:
                $langString = $stringArr[0];
        }

        if (empty($langString) || $langString == '' || $langString == null) {
            $langString = $stringArr[1]; //zh_HK
        }

        return $langString;
    }
    /********** (END)DISPLAY SPECIFIC LANGUAGE **********/

}
