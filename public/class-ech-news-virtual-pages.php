<?php

class Ech_News_Virtual_Pages extends Ech_Dr_Media_News_Public
{
    /************************************************************************
     * To avoid the error "generated X characters of unexpected output" ocurred during plugin activation,
     * initialize_createVP function is called in define_public_hooks, add_action('init')
     * (folder: includes/class-ech-blog.php)
     * initialize_createVP() fires after WordPress has finished loading, but before any headers are sent.
     ************************************************************************/
    public static function ECHD_initialize_createVP()
    {
        // add an option to make use ECHD_setupVP is triggered once per VP. Delete this option once all VP are created.
        add_option('ECHD_run_init_createVP', 1);
    }


    public function ECHD_createVP()
    {
        if (get_option('ECHD_run_init_createVP') == 1) {
            $this->ECHD_setupVP('Dr Media News Content', 'news-content', '[ech_news_single_post_output]');

            // Delete this option once all VP are created.
            delete_option('ECHD_run_init_createVP');
        }
    }

    private static function ECHD_setupVP($pageTitle, $pageSlug, $pageShortcode)
    {
        // Get parent page and get its id
        $get_parent_page = get_page_by_path('dr-media-news');

        $v_page = array(
            'post_type' => 'page',
            'post_title' => $pageTitle,
            'post_name' => $pageSlug,
            'post_content' => $pageShortcode,  // shortcode from this plugin
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_parent' => $get_parent_page->ID,
        );

        wp_insert_post($v_page, true);
    } // ECHD_setupVP




    /******************************** VP SHORTCODE ********************************/

    public function ech_news_single_post_output($atts)
    {
        if (!get_option('ECHD_run_init_createVP')) {
            global $wp;

            $atts = shortcode_atts(array(
                'postid'    => isset($_GET['postid']) ? sanitize_key($_GET['postid']) : '',
            ), $atts);


            $postID  = $atts['postid'];

            if (!isset($postID) || empty($postID)) {
                echo '<script>window.location.replace("/dr-media-news");</script>';
            }

            $args = array(
                'postid' => $postID,
            );

            $api_link = parent::ECHD_gen_post_api_link($args);
            $get_post_json = parent::ECHD_wp_remote_get_news_json($api_link);
            $json_arr = json_decode($get_post_json, true);

            if (!isset($json_arr['id']) || empty($json_arr['id'])) {
                echo '<script>window.location.replace("/dr-media-news");</script>';
            }

            $post = $json_arr;
            $post_title = parent::ECHD_echolang([$post['acf']['title_en'], $post['acf']['title_zh'], $post['acf']['title_sc']]);
            $post_content = parent::ECHD_echolang([$post['acf']['news_content_en'], $post['acf']['news_content_zh'], $post['acf']['news_content_sc']]);
            $meta_description = parent::ECHD_echolang([$post['acf']['meta_description_en'], $post['acf']['meta_description_zh'], $post['acf']['meta_description_sc']]);
            $formTemplete = get_option('ech_dmn_form_shortcode');
            $display_spec_tag = get_option('ech_dmn_display_spec_tag') ? 0 : 1;
            $display_dr_tag = get_option('ech_dmn_display_dr_tag') ? 0 : 1;
            $display_brand_tag = get_option('ech_dmn_display_brand_tag') ? 0 : 1;
            $spec_category_id = [];
            $spec_category_name = [];
            $dr_category_id = [];
            $dr_category_name = [];
            $brand_category_id = [];
            $brand_category_name = [];
            foreach ($post['spec_category'] as $spec) {
                array_push($spec_category_id, $spec['id']);
                array_push($spec_category_name, parent::ECHD_echolang([$spec['name_en'],$spec['name_zh'],$spec['name_sc']]));
            }
            foreach ($post['dr_category'] as $dr) {
                array_push($dr_category_id, $dr['id']);
                array_push($dr_category_name, parent::ECHD_echolang([$dr['name_en'],$dr['name_zh'],$dr['name_sc']]));
            }
            foreach ($post['brand_category'] as $brand) {
                array_push($brand_category_id, $brand['id']);
                array_push($brand_category_name, parent::ECHD_echolang([$brand['name_en'],$brand['name_zh'],$brand['name_sc']]));
            }

            $html = '';
            // *********** Custom styling ***************/
            if (!empty(get_option('ech_dmn_submitBtn_color')) || !empty(get_option('ech_dmn_submitBtn_hoverColor') || !empty(get_option('ech_dmn_submitBtn_text_color')) || !empty(get_option('ech_dmn_submitBtn_text_hoverColor')))) {
                $html .= '<style>';

                $html .= '.back-to-news-list-btn > a { ';
                (!empty(get_option('ech_dmn_submitBtn_color'))) ? $html .= 'background:' . get_option('ech_dmn_submitBtn_color') . ';' : '';
                (!empty(get_option('ech_dmn_submitBtn_text_color'))) ? $html .= 'color:' . get_option('ech_dmn_submitBtn_text_color') . ';' : '';
                $html .= '}';

                $html .= '.back-to-news-list-btn > a:hover { ';
                (!empty(get_option('ech_dmn_submitBtn_hoverColor'))) ? $html .= 'background:' . get_option('ech_dmn_submitBtn_hoverColor') . ';' : '';
                (!empty(get_option('ech_dmn_submitBtn_text_hoverColor'))) ? $html .= 'color:' . get_option('ech_dmn_submitBtn_text_hoverColor') . ';' : '';
                $html .= '}';

                $html .= '</style>';
            }
            // *********** (END) Custom styling ****************/
            $html .= '<div class="ech-dmn-single-news-container" data-news="' . $post['id'] . '" data-specialties="' . implode(',', $spec_category_id) . '" data-dr="' . implode(',', $dr_category_id) . '" data-brand="' . implode(',', $brand_category_id) . '">';
            $html .= '<div class="news-heading-title">';
            $html .= '<h1>' . $post_title . '</h1>';
            $html .= '</div>'; //.news-heading-title

            $html .= '<div class="single-news-container">';
            $html .= '<div class="post-info">';
            $html .= '<ul>';
            $html .= '<li class="post-date"><i aria-hidden="true" class="fas fa-calendar"></i> ' . date('d m月, Y', strtotime($post['published_date'])) . '</li>';
            if($display_spec_tag) {
                $html .= '<li class="post-specialty"><i aria-hidden="true" class="fas fa-tags"></i> ' . implode(',', $spec_category_name) . '</li>';
            }
            if($display_dr_tag) {
                $html .= '<li class="post-doctor"><i aria-hidden="true" class="fas fa-user-tag"></i> ' . implode(' ', $dr_category_name) . '</li>';
            }
            if($display_brand_tag) {
                $html .= '<li class="post-brand"><i aria-hidden="true" class="fas fa-building"></i> ' . implode(' ', $brand_category_name) . '</li>';
            }
            $html .= '</ul>';

            $html .= '<div class="back-to-news-list">';
            $html .= '<a href="' . site_url() . '/dr-media-news/"> < ' . parent::ECHD_echolang(['Back to Doctor News', '返回媒體訪問', '返回媒体访问']) . '</a>';
            $html .= '</div>'; //.back-to-news-list
            $html .= '</div>'; // .post-info

            $html .= '<div class="news-content-container">';
            $html .= '<div class="post-content">' . $post_content . '</div>'; // .post_content
            $html .= '</div>'; // .news-content-container
            $html .= '<div class="back-to-news-list-btn">';
            $html .= '<a href="' . site_url() . '/dr-media-news/">' . parent::ECHD_echolang(['Back to Doctor News', '返回媒體訪問', '返回媒体访问']) . '</a>';
            $html .= '</div>'; // .back-to-news-list-btn
            if($formTemplete) {
                $html .= '<div class="news-form-templete">' . $this->ech_news_content_form($formTemplete) . '</div>';
            }
            $html .= '</div>'; // .single-news-container
            $html .= '</div>'; // .ech-dmn-single-news-container

            return $html;
        } // if ECHD_run_init_createVP
    }  //--end ech_news_single_post_output()

    public function ech_news_content_form($formShortcode)
    {
        $html = '';
        $html .= do_shortcode($formShortcode);

        return $html;
    }  //--end ech_news_content_form()

    /******************************** (end) VP SHORTCODE ********************************/



} // class
