<?php
/**
Plugin name     : woocommerce filter
Plugin class    : woocommerce_filter
Plugin uri      : http://sikido.vn
Description     : Tìm kiếm sản phẩm nâng cao
Author          : Hữu Trọng
Version         : 1.1.0
*/
define( 'WCMCF_NAME', 'woocommerce_filter' );

define( 'WCMCF_FOLDER', 'woocommerce-filter' );

define( 'WCMCF_PATH', plugin_dir_path( WCMCF_FOLDER ) );

class woocommerce_filter {

    private $name = 'woocommerce_filter';

    public  $ci;

    function __construct() {
        $this->ci =&get_instance();
    }

    public function active() {
    	$ci = $this->ci;
    }

    public function uninstall() {
    }


    public function html() {
        wcmc_filter_get_template('search');
    }
}

include 'wcmc-filter-core-function.php';

include 'wcmc-filter-admin.php';

include 'module/price/price.php';
include 'module/price_range_slider/price_range_slider.php';


include 'module/category/category.php';

include 'module/taxonomy/taxonomy.php';
// include 'module/thuonghieu/thuonghieu.php';

include 'module/attribute/attribute.php';

if ( ! function_exists( 'woocommerce_filter_style_header' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function woocommerce_filter_style_header() {

        $ci =& get_instance();

        cle_register_style('wcmc-filter', WCMCF_PATH.'assets/css/wcmc-filter-style.css');
    }

    add_action('cle_enqueue_style',     'woocommerce_filter_style_header');
}
if ( ! function_exists( 'ajax_search_fillter' ) ) {
    function ajax_search_fillter($ci, $model) {
        $result['status'] = 'error';
        $result['message'] = 'load liệu không thành công.';
        if($ci->input->post()) {
            $data = $ci->input->post();
            $url=explode('&', $data['url']);
            $url2=explode('?', $url[0]);
            unset($url[0]);
            
            // $url=array_merge($url2,$url);
            // show_r($url);
            $new=explode('&', $data['href']);
            $url=array_merge($url2,$new);
            // show_r($new);
            
            $link=$url[0];
            $group=array();
            foreach ($url as $key => $val) {
                if ($key !=0) {
                $a=explode('=', $val);
                
                if (is_array($a) && count($a) == 2) {
                        $group[$a[0]]=$a[1];
                    
                }
                }
            }
            $link2='';
            foreach ($group as $key3 => $val2) {
               $link2.=$key3.'='.$val2.'&';
            }

            $link.='?'.trim($link2,'&');

        // unset($data['action']);
        // unset($data['post_type']);
        // unset($data['cate_type']);
        // $form = [];
        // foreach ($data as $key => $value) {
        //     $form[$key] = removeHtmlTags($value);
        // }
            $result['data']=$link;
            $result['status'] = 'success';
            $result['message'] = 'load liệu thành công.';
        }
        echo json_encode($result);
    }
    register_ajax_admin('ajax_search_fillter');
}