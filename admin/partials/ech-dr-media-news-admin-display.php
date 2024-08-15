<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Dr_Media_News
 * @subpackage Ech_Dr_Media_News/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
    $plugin_info = new Ech_Dr_Media_News();
    $ADMIN_ECHD_func = new Ech_Dr_Media_News_Admin($plugin_info->get_plugin_name(), $plugin_info->get_version());
    $access_token = (get_option('ech_dmn_access_token')) ? get_option('ech_dmn_access_token') : '';
    $drID_full_api = $ADMIN_ECHD_func->ADMIN_ECHD_gen_doctor_api_link();
    $drID_get_json = $ADMIN_ECHD_func->ADMIN_ECHD_wp_remote_get_news_json($drID_full_api);
    $drID_json_arr = json_decode($drID_get_json, true);
    $specID_full_api = $ADMIN_ECHD_func->ADMIN_ECHD_gen_specialty_api_link();
    $specID_get_json = $ADMIN_ECHD_func->ADMIN_ECHD_wp_remote_get_news_json($specID_full_api);
    $specID_json_arr = json_decode($specID_get_json, true);
    $brandID_full_api = $ADMIN_ECHD_func->ADMIN_ECHD_gen_brand_api_link();
    $brandID_get_json = $ADMIN_ECHD_func->ADMIN_ECHD_wp_remote_get_news_json($brandID_full_api);
    $brandID_json_arr = json_decode($brandID_get_json, true);
    $check_Token = true;
    if(empty($access_token) || isset($drID_json_arr['code'])) {
        $check_Token = false;
    }
?>
    <!-- *********** Custom styling *************** -->
    <?php if (!empty(get_option('ech_dmn_submitBtn_color')) || !empty(get_option('ech_dmn_submitBtn_hoverColor') || !empty(get_option('ech_dmn_submitBtn_text_color')) || !empty(get_option('ech_dmn_submitBtn_text_hoverColor')))) :?>
        <style>

        #copyShortcode, button[type="submit"] {
        <?=(!empty(get_option('ech_dmn_submitBtn_color'))) ? 'background-color:' . get_option('ech_dmn_submitBtn_color') . ' !important;' : '';?>

        <?=(!empty(get_option('ech_dmn_submitBtn_text_color'))) ? 'color:' . get_option('ech_dmn_submitBtn_text_color') . ' !important;' : '';?>
        }

        #copyShortcode:hover, button[type="submit"]:hover {
        <?=(!empty(get_option('ech_dmn_submitBtn_hoverColor'))) ? 'background-color:' . get_option('ech_dmn_submitBtn_hoverColor') . ' !important;' : '';?>
        <?=(!empty(get_option('ech_dmn_submitBtn_text_hoverColor'))) ? 'color:' . get_option('ech_dmn_submitBtn_text_hoverColor') . ' !important;' : '';?>
        }

        </style>
    <?php endif;?>
    <!-- (END) Custom styling -->
<div class="echPlg_wrap">
    <h1>ECH DR Media News General Settings</h1>
    <div class="plg_intro">
        <p> More shortcode attributes and guidelines, visit <a href="#" target="_blank">Github</a>. </p>
        <div class="shtcode_container">
            <pre id="sample_shortcode">[ech_dr_media_news]</pre>
            <div id="copyMsg"></div>
            <button id="copyShortcode">Copy Shortcode</button>
        </div>
        
    </div>
    <div class="form_container">
        <form method="post" id="dmm_gen_settings_form">
            <?php
            settings_fields('dmm_gen_settings');
            do_settings_sections('dmm_gen_settings');
            ?>
            <h2>General</h2>
            <div class="form_row">
                <?= !$check_Token ? '<h1 style="color:red;">請先輸入Access Token或有誤</h1>' : '';?>
                <label>API Access Token: </label>
                <input type="text" name="ech_dmn_access_token" value="<?= htmlspecialchars(get_option('ech_dmn_access_token'))?>" id="" />
            </div>

            <div class="form_row">
                <?php $getPPP = get_option('ech_dr_media_news_ppp'); ?>
                <label>Post per page : </label>
                <input type="number" name="ech_dr_media_news_ppp" id="ech_dr_media_news_ppp" pattern="[0-9]{1,}" value="<?=$getPPP?>">
            </div>
            <div class="form_row">
                <?php $getFeaturedImg = get_option('ech_dmn_default_post_featured_img'); ?>
                <label>Default post featured image : </label>
                <input type="text" name="ech_dmn_default_post_featured_img" id="ech_dmn_default_post_featured_img" value="<?=$getFeaturedImg?>">
            </div>
            <div class="form_row">
                <?php $getFormShortcode = get_option('ech_dmn_form_shortcode'); ?>
                <label>Display Form on "Dr Media News Content page" (enter form or templete shortcode) : </label>
                <textarea name="ech_dmn_form_shortcode" id="" cols="100" rows="5"><?=$getFormShortcode?></textarea>
            </div>
            <div class="form_row">
                <?php $getDrFilterStatus = get_option('ech_dmn_enable_dr_filter'); ?>
                <label>Display Doctor Filter on "Dr Media News" page : </label>
                <select name="ech_dmn_enable_dr_filter" id="">
                    <option value="0" <?= ($getDrFilterStatus == 0) ? 'selected' : '' ?>>Enable</option>
                    <option value="1" <?= ($getDrFilterStatus == 1) ? 'selected' : '' ?>>Disable</option>
                </select>
            </div>
            <div class="form_row">
                <?php $getSpecFilterStatus = get_option('ech_dmn_enable_spec_filter'); ?>
                <label>Display Specialty Filter on "Dr Media News" page : </label>
                <select name="ech_dmn_enable_spec_filter" id="">
                    <option value="0" <?= ($getSpecFilterStatus == 0) ? 'selected' : '' ?>>Enable</option>
                    <option value="1" <?= ($getSpecFilterStatus == 1) ? 'selected' : '' ?>>Disable</option>
                </select>
            </div>
            <div class="form_row">
                <?php $getBrandFilterStatus = get_option('ech_dmn_enable_brand_filter'); ?>
                <label>Display Brand Filter on "Dr Media News" page : </label>
                <select name="ech_dmn_enable_brand_filter" id="">
                    <option value="0" <?= ($getBrandFilterStatus == 0) ? 'selected' : '' ?>>Enable</option>
                    <option value="1" <?= ($getBrandFilterStatus == 1) ? 'selected' : '' ?>>Disable</option>
                </select>
            </div>
            <div class="form_row api_info_container">
                <p>Filter Doctor IDs</p>
                <div class="info_list">
                    <?php if(!isset($drID_json_arr['code'])):?>
                        <?php foreach($drID_json_arr as $dr):?>
                            <div>
                                <?=$dr['name_zh'] . ' : ' . $dr['id']?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div> <!-- api_info_container -->
            <div class="form_row">
                <?php $getFilteredDr = get_option('ech_news_dr_filter'); ?>
                <label>Filtered Doctor Categories (use comma to separate them) : </label>
                <input type="text" name="ech_news_dr_filter" id="" pattern="[0-9,]{1,}" value="<?=$getFilteredDr;?>">
            </div>
            <div class="form_row api_info_container">
                <p>Filter Specialty IDs</p>
                <div class="info_list">
                    <?php if(!isset($specID_json_arr['code'])):?>
                        <?php foreach($specID_json_arr as $spec):?>
                            <div>
                                <?=$spec['name_zh'] . ' : ' . $spec['id']?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div> <!-- api_info_container -->
            <div class="form_row">
                <?php $getFilteredSpec = get_option('ech_news_spec_filter'); ?>
                <label>Filtered Specialty Categories (use comma to separate them) : </label>
                <input type="text" name="ech_news_spec_filter" id="" pattern="[0-9,]{1,}" value="<?=$getFilteredSpec;?>">
            </div>
            <div class="form_row api_info_container">
                <p>Filter Brand IDs</p>
                <div class="info_list">
                    <?php if(!isset($brandID_json_arr['code'])):?>
                        <?php foreach($brandID_json_arr as $brand):?>
                            <div>
                                <?=$brand['name_zh'] . ' : ' . $brand['id']?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div> <!-- api_info_container -->
            <div class="form_row">
                <?php $getFilteredBrand = get_option('ech_news_brand_filter'); ?>
                <label>Filtered Brand Categories (use comma to separate them) : </label>
                <input type="text" name="ech_news_brand_filter" id="" pattern="[0-9,]{1,}" value="<?=$getFilteredBrand;?>">
            </div>

            <h3>Button Style</h3>
            <div class="form_row">
                <label>Button Color (HEX code only): </label>
                <input type="text" name="ech_dmn_submitBtn_color" value="<?= htmlspecialchars(get_option('ech_dmn_submitBtn_color'))?>" id="" pattern="^(#)[A-Za-z0-9]{3,6}" id="ech_dmn_submitBtn_color">
            </div>
            <div class="form_row">
                <label>Button Text Color (HEX code only): </label>
                <input type="text" name="ech_dmn_submitBtn_text_color" value="<?= htmlspecialchars(get_option('ech_dmn_submitBtn_text_color'))?>" id="" pattern="^(#)[A-Za-z0-9]{3,6}">
            </div>        
            
            <h3>Button hover color</h3>
            <div class="form_row">
                <label>Button Hover Color (HEX code only): </label>
                <input type="text" name="ech_dmn_submitBtn_hoverColor" value="<?= htmlspecialchars(get_option('ech_dmn_submitBtn_hoverColor'))?>" id="" pattern="^(#)[A-Za-z0-9]{3,6}">
            </div>
            <div class="form_row">
                <label>Button Text Hover Color (HEX code only): </label>
                <input type="text" name="ech_dmn_submitBtn_text_hoverColor" value="<?= htmlspecialchars(get_option('ech_dmn_submitBtn_text_hoverColor'))?>" id="" pattern="^(#)[A-Za-z0-9]{3,6}">
            </div>
        
            
            
            <div class="form_row">
                <button type="submit"> Save </button>
            </div>
        </form>
        <div class="statusMsg"></div>


    </div> <!-- form_container -->
</div>