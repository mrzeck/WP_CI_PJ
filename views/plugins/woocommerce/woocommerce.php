<?php
/**
Plugin name     : woocommerce
Plugin class    : woocommerce
Plugin uri      : http://sikido.vn
Description     : Tạo và quản lý sản phẩm thương mail của bạn.
Author          : Nguyễn Hữu Trọng
Version         : 2.0.3
*/
define( 'WCMC_NAME', 'woocommerce' );

define( 'WCMC_URL', URL_ADMIN.'/plugins?page='.WCMC_NAME.'&view=' );

define( 'WCMC_PATH', plugin_dir_path( WCMC_NAME ) );

define( 'WCMC_DATABASE', '1.4' );

define( 'WCMC_TEMPLATE', '1.1.1' );

class woocommerce {

    private $name = 'woocommerce';

    public  $ci;

    function __construct() {
        $this->ci =&get_instance();
    }

    public function active() {

    	$ci = $this->ci;

        wcmc_database_add_table();

        $options = $this->get_options();

        foreach ( $options as $option_key => $option_value ) {
            add_option( $option_key, $option_value );
        }
    }

    public function uninstall() {

    	$model = get_model('plugins', 'backend');

        wcmc_database_drop_table();

    	/**
         * XÓA METADATA
         */
    	delete_all_metadata('woocommerce');

        /**
         * XÓA OPTION CẤU HÌNH
         */
        $options = $this->get_options();
        
        foreach ( $options as $option_key => $option_value ) {
            delete_option( $option_key );
        }
    }

    public function get_options() {
        
        $option = array(
            'woocommerce_currency'      => 'đ',
            'woocommerce_price_contact' => __('Liên hệ', 'lien-he'),
            'product_pr_page'           => 16,
            'category_row_count'        => 4,
            'category_row_count_tablet' => 3,
            'category_row_count_mobile' => 2,
            'product_hover_effect'      => 'square effect7',
            'product_title_color'       => '',
            'product_hiden_img'         => 1,
            'product_hiden_title'       => 1,
            'product_hiden_price'       => 1,
            'product_hiden_description' => 0,
            'product_border_size'       => 0,
            'product_border_style'      => '',
            'product_border_color'      => '',
            'product_shadow'            => 0, // version 1.8.3
            'product_shadow_hover'      => 0, // version 1.8.3
            'product_price_color'       => 0, // version 1.8.3
            'wcmc_database_version'     => WCMC_DATABASE,
        );

        return $option;
    }
}

include 'wcmc-core-function.php';

include 'wcmc-database.php';

include 'wcmc-form-function.php';

include 'wcmc-admin.php';

include 'wcmc-cache.php';

include 'wcmc-controller.php';

include 'wcmc-template-function.php';