<?php
/**
Plugin name     : Facebook Manager
Plugin class    : facebook_manager
Plugin uri      : http://vitechcenter.com
Description     : Tạo và quản lý các ứng dụng liên quan facebook.
Author          : SKDSoftware Dev Team
Version         : 1.1.1
*/
define( 'FBM_NAME', 'facebook-manager' );

define( 'FBM_PATH', plugin_dir_path(FBM_NAME) );

class facebook_manager {

    private $name = 'facebook_manager';

    public  $ci;
    function __construct() {
        $this->ci =&get_instance();
    }

    public function active() {

        add_option( 'fbm_active', serialize(array( 'fbm-send-message' => 0, 'fbm-tab' => 0)) );

        $option = array(
            'fbm-send-message' => array(
                'fbm_desktop'    => 1,
                'fbm_tablet'     => 0,
                'fbm_mobile'     => 0,
                'fbm_title'      => 'Facebook Chat',
                'fbm_color_bg'   => '',
                'fbm_color_text' => '',
                'fbm_position'   => 'left',
            ),
            'fbm-tab' => array(
                'fbm_desktop'    => 1,
                'fbm_tablet'     => 0,
                'fbm_mobile'     => 0,
                'fbm_position'   => 'left',
                'fbm_height'   => 300,
                'fbm_bottom'   => 300,
            ),
        );

        add_option('fbm_setting', serialize( $option ) );

    }

    public function uninstall() { 

        delete_option('fbm_active');

        delete_option('fbm_setting');
    }

}

function fbm_fix_version( $value = '' ) {

    global $skd_version; 

    if( version_compare( $skd_version, '2.2.0' ) < 0 ) {

        $value = unserialize($value);
    }

    return $value;
}

/* các hàm */
include 'facebook-manager-function.php';

/* xử lý quản lý admin */
include 'admin/facebook-manager-admin.php';

include 'facebook-manager-ajax.php';

include 'module/send-message.php';

include 'module/tab.php';

//LOAD CSS, SCRIPT FILE
if ( ! function_exists( 'fbm_style_header' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function fbm_style_header() {

        if( is_admin() ) {

            admin_register_style('fbm-style', FBM_PATH.'assets/css/fbm-admin.css');

            admin_register_style('fbm-style', FBM_PATH.'assets/css/fbm-style.css');
        }

        cle_register_style('fbm-style', FBM_PATH.'assets/css/fbm-style.css');
    }

    add_action('cle_enqueue_style',         'fbm_style_header');
}

if ( ! function_exists( 'fbm_style_footer' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function fbm_style_footer() {
        if( is_admin() )
            admin_register_script('fbm-script', FBM_PATH.'assets/js/fbm-admin.js');

        cle_register_script('fbm-script', FBM_PATH.'assets/js/fbm-script.js');
    }

    add_action('cle_enqueue_script',        'fbm_style_footer');
}

//LOAD HTML FILE
if ( ! function_exists( 'fbm_the_html' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function fbm_the_html() {


        $active = fbm_fix_version(get_option('fbm_active'));

        $option = fbm_fix_version(get_option('fbm_setting'));

        if( is_admin() ) {

            $tab = get_instance()->input->get('tab');

            $tab = removeHtmlTags($tab);

            do_action('fbm_the_html', $active, $option, $tab );
        }
        else  {
            do_action('fbm_the_html', $active, $option );
        }
    }
}

//LOAD CSS SETING
if ( ! function_exists( 'fbm_the_css' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function fbm_the_css() {

        $option = fbm_fix_version(get_option('fbm_setting'));

        $css_inline = apply_filters('fbm_the_css', '', $option);

        echo '<style>'.$css_inline.'</style>';
    }
}

//INIT
function fbm_init() {

    if( is_admin() ) {
        // add_action('fbm_tab_review', 'fbm_the_html');
        // add_action('fbm_tab_review', 'fbm_the_css');
    }
    else {
        add_action('cle_footer',     'fbm_the_html', 0);
        add_action('cle_footer',     'fbm_the_css', 0);
    }
}

add_action( 'init', 'fbm_init' );

