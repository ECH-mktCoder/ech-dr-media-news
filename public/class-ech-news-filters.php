<?php




class Ech_News_Filters extends Ech_Dr_Media_News_Public
{
    public function __construct() {}

    public function ECHD_get_dr_categories_list( $filterDrID = null)
    {
        $drID_ary = !empty($filterDrID) ? explode(',', $filterDrID) : [];

        $full_api = parent::ECHD_gen_doctor_api_link();
        $get_drCateList_json = parent::ECHD_wp_remote_get_news_json($full_api);
        $json_arr = json_decode($get_drCateList_json, true);

        $html = '';
        $html .= '<div class="dr-filter">';
        $html .= '<h4>' . parent::ECHD_echolang(['Doctor','醫生','医生']) . '</h4>';
        $html .= '<ul>';
        foreach ($json_arr as $dr) {
            if (empty($drID_ary) || in_array($dr['id'], $drID_ary)) {
                $html .= '<li>';
                $html .= '<input id="doctor' . $dr['id'] . '" type="checkbox" name="doctor[]" value="' . $dr['id'] . '">';
                $html .= '<label for="doctor' . $dr['id'] . '">';
                $html .= parent::ECHD_echolang([$dr['name_en'], $dr['name_zh'], $dr['name_sc']]);
                $html .= '</label>';
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>'; //dr-filter

        return $html;

    }

    public function ECHD_get_specialty_categories_list($filterSpecID = null)
    {
        $specID_ary = !empty($filterSpecID) ? explode(',', $filterSpecID) : [];

        $full_api = parent::ECHD_gen_specialty_api_link();
        $get_specCateList_json = parent::ECHD_wp_remote_get_news_json($full_api);
        $json_arr = json_decode($get_specCateList_json, true);

        $html = '';
        $html .= '<div class="specialty-filter">';
        $html .= '<h4>' . parent::ECHD_echolang(['Specialty','專科','专科']) . '</h4>';
        $html .= '<ul>';
        foreach($json_arr as $spec) {
            if (empty($specID_ary) || in_array($spec['id'], $specID_ary)) {
                $html .= '<li>';
                $html .= '<input id="specialty' . $spec['id'] . '" type="checkbox" name="specialty[]" value="' . $spec['id'] . '">';
                $html .= '<label for="specialty' . $spec['id'] . '">';
                $html .= parent::ECHD_echolang([$spec['name_en'], $spec['name_zh'], $spec['name_sc'] ]);
                $html .= '</label>';
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>'; //specialty-filter

        return $html;

    }
    public function ECHD_get_brand_categories_list($filterBrandID = null)
    {
        $brandID_ary = !empty($filterBrandID) ? explode(',', $filterBrandID) : [];

        $full_api = parent::ECHD_gen_brand_api_link();
        $get_brandCateList_json = parent::ECHD_wp_remote_get_news_json($full_api);
        $json_arr = json_decode($get_brandCateList_json, true);

        $html = '';
        $html .= '<div class="brand-filter">';
        $html .= '<h4>' . parent::ECHD_echolang(['brand','品牌','品牌']) . '</h4>';
        $html .= '<ul>';
        foreach($json_arr as $brand) {
            if (empty($brandID_ary) || in_array($brand['id'], $brandID_ary)) {

                $html .= '<li>';
                $html .= '<input id="brand' . $brand['id'] . '" type="checkbox" name="brand[]" value="' . $brand['id'] . '">';
                $html .= '<label for="brand' . $brand['id'] . '">';
                $html .= parent::ECHD_echolang([$brand['name_en'], $brand['name_zh'], $brand['name_sc'] ]);
                $html .= '</label>';
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>'; //brand-filter

        return $html;

    }

} // class
