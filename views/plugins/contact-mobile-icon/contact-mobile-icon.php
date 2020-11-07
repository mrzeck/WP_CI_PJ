<?php
/**
Plugin name     : Contact Mobile Icon
Plugin class    : contact_mobile_icon
Plugin uri      : http://vitechcenter.com
Description     : Tạo các icon liên hệ mobile như Call, Sms, Email...
Author          : SKDSoftware Dev Team
Version         : 2.1.0
*/

define( 'CMI_NAME', 'contact-mobile-icon' );

define( 'CMI_PATH', plugin_dir_path(CMI_NAME) );

class contact_mobile_icon {

    private $name = 'contact_mobile_icon';

    public  $ci;

    public $style1 = array();

    public $style2 = array();

    public $style3 = array();

    public $style4 = array();

    public $option = array();

    function __construct() {

        $this->ci =&get_instance();

        $this->option();
    }

    public function active() {

    	$ci = $this->ci;

        //active style
        add_option( 'cmi_active', serialize(array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0, 'style-4' => 0)) );

        //Style 1
        add_option('cmi_style', serialize( $this->option ) );
    }

    public function uninstall() { 
        delete_option('cmi_active');
        delete_option('cmi_style');
    }

    public function option() {

        $this->style1 = array(
            'cmi_fb_messenger'        => '',
            'cmi_zalo'                => get_option('contact_phone'),
            'cmi_sms'                 => get_option('contact_phone'),
            'cmi_call'                => get_option('contact_phone'),
            'cmi_desktop_enable_fb'   => 1,
            'cmi_desktop_enable_zalo' => 1,
            'cmi_desktop_enable_sms'  => 1,
            'cmi_desktop_enable_call' => 1,

            'cmi_tablet_enable_fb'    => 1,
            'cmi_tablet_enable_zalo'  => 1,
            'cmi_tablet_enable_sms'   => 1,
            'cmi_tablet_enable_call'  => 1,

            'cmi_mobile_enable_fb'    => 1,
            'cmi_mobile_enable_zalo'  => 1,
            'cmi_mobile_enable_sms'   => 1,
            'cmi_mobile_enable_call'  => 1,
        );

        $this->style2 = array(
            'cmi_call'          => get_option('contact_phone'),
            'cmi_sms'           => get_option('contact_phone'),
            'cmi_contact'       => get_option('contact_phone'),

            'cmi_title_call'    => 'Gọi điện',
            'cmi_title_sms'     => 'SMS',
            'cmi_title_contact' => 'Chỉ Đường',

            'cmi_bg'            => '',
        );

        $this->style3 = array(
            'cmi_call'          => get_option('contact_phone'),
            'cmi_position'      => 'left',
            'cmi_bottom'        => '210',
            'cmi_color_icon'    => '',
            'cmi_color_border1' => '',
            'cmi_color_border2' => '',
        );

        $this->style4 = array(
            'icon'              => array(),
            'cmi_position'      => 'left',
            'cmi_bottom'        => '310',
            'cmi_margin'        => 10,
            'cmi_width_image'   => 30,
        );

        $this->option['style-1'] = $this->style1;

        $this->option['style-2'] = $this->style2;

        $this->option['style-3'] = $this->style3;

        $this->option['style-4'] = $this->style4;
    }
}

function cmi_fix_version( $value = '' ) {

    global $skd_version; 

    if( version_compare( $skd_version, '2.2.0' ) < 0 ) {

        $value = unserialize($value);
    }

    return $value;
}

include 'contact-mobile-icon-admin.php';

include 'contact-mobile-icon-ajax.php';

include 'module/style-1.php';

include 'module/style-2.php';

include 'module/style-3.php';

include 'module/style-4.php';

//LOAD STYLE AND CSS
if ( ! function_exists( 'cmi_style_header' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function cmi_style_header() {

        if( is_admin() )
            admin_register_style('cmi-style', CMI_PATH.'assets/css/cmi-admin.css');

        admin_register_style('cmi-style', CMI_PATH.'assets/css/cmi-style.css');
        cle_register_style('cmi-style', CMI_PATH.'assets/css/cmi-style.css');
    }

    add_action('cle_enqueue_style', 'cmi_style_header');
}





if ( ! function_exists( 'cmi_script_footer' ) ) {

    /**

     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm

     */

    function cmi_script_footer() {

        if( is_admin() )

            admin_register_script('cmi-script', CMI_PATH.'assets/js/cmi-admin.js');

    }



    add_action('cle_enqueue_script', 'cmi_script_footer');

}



//LOAD STYLE ACTIVE

if ( ! function_exists( 'cmi_the_style' ) ) {

    /**

     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm

     */

    function cmi_the_style() {



        $active = cmi_fix_version(get_option('cmi_active'));



        $active = array_merge( array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0, 'style-4' => 0), $active);



        $option = cmi_fix_version(get_option('cmi_style'));



        $contact_mobile_icon = new contact_mobile_icon();



        $option = array_merge($contact_mobile_icon->option, $option);



        if( is_admin() ) {



            $tab = get_instance()->input->get('tab');



            $tab = removeHtmlTags($tab);



            do_action('cmi_the_style', $active, $option, $tab );

        }

        else  {

            do_action('cmi_the_style', $active, $option );

        }

    }

}



//LOAD CSS SETING

if ( ! function_exists( 'cmi_the_css' ) ) {

    /**

     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm

     */

    function cmi_the_css() {



        $option = cmi_fix_version(get_option('cmi_style'));



        $contact_mobile_icon = new contact_mobile_icon();



        $option = array_merge($contact_mobile_icon->option, $option);



        $css_inline = apply_filters('cmi_the_css', '', $option);



        echo '<style>'.$css_inline.'</style>';

    }

}



//INIT

function cmi_init() {



    if( is_admin() ) {

        add_action('cmi_tab_review', 'cmi_the_style');

        add_action('cmi_tab_review', 'cmi_the_css');

    }

    else {

        add_action('cle_footer',     'cmi_the_style');

        add_action('cle_footer',     'cmi_the_css');

    }

}



add_action( 'init', 'cmi_init' );



