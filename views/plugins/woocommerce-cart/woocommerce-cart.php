<?php
/**
Plugin name     : woocommerce cart
Plugin class    : woocommerce_cart
Plugin uri      : http://sikido.vn
Description     : Tạo giỏ hàng và quản lý sản phẩm thương mại của bạn.
Author          : SKDSoftware Dev Team
Version         : 2.5.1
*/
define( 'WCMC_CART_NAME', 'woocommerce-cart' );

define( 'WCMC_CART_PATH', plugin_dir_path( WCMC_CART_NAME ) );

define( 'WCMC_CART_DATABASE', '2.0' );

define( 'WCMC_CART_TEMPLATE', '1.3.2' );

class woocommerce_cart {

    private $name = 'woocommerce_cart';
    
    public  $ci;

    public function active() {

    	$ci = &get_instance();

    	$model = get_model('plugins');

    	if( !class_exists('woocommerce')) {
    		echo notice('error', 'Bạn phải cài đặt plugins <b>WOOCOMMERCE</b> trước khi cài đặt plugin giỏ hàng!', true);
    		die;
    	}

    	wcmc_cart_database_add_table();
        
		// ADD TRANG NỘI DUNG
		$page = array(
			'title' 	=> 'Giỏ hàng',
			'content' 	=> '[woocommerce_cart]',
		);

		$page_cart 		= $ci->insert_page($page);

		$page = array(
			'title' 	=> 'Thanh toán',
			'content' 	=> '[woocommerce_checkout]',
		);

		$page_checkout = $ci->insert_page($page);

		$page = array(
			'title' 	=> 'Đơn hàng',
			'content' 	=> '[woocommerce_success]',
		);

		$page_success = $ci->insert_page($page);

		if(isset($page_cart['id'])) 	add_option( 'woocommerce_page_cart_id', 	$page_cart['id'] );
		if(isset($page_checkout['id'])) add_option( 'woocommerce_page_checkout_id', $page_checkout['id'] );
		if(isset($page_success['id'])) 	add_option( 'woocommerce_page_success_id',  $page_success['id'] );

        $options = $this->get_options();

        foreach ( $options as $option_key => $option_value ) {
            add_option( $option_key, $option_value );
        }

    }

    public function uninstall() {

    	$ci = &get_instance();

    	$model = get_model('plugins');

        //xóa database
		wcmc_cart_database_drop_table();

        //Delete metadata
        $this->delete_metadata();

        $model = get_model('plugins');

        $model->settable('relationships');

        $model->delete_where(array('object_type' => 'woocommerce_attributes'));

        /**
         * XÓA OPTION CẤU HÌNH
         */
        $page_cart      = get_option( 'woocommerce_page_cart_id' );

        $page_checkout  = get_option( 'woocommerce_page_checkout_id');

        $page_success   = get_option( 'woocommerce_page_success_id');

        $ci->delete_page($page_cart);

        $ci->delete_page($page_checkout);

        $ci->delete_page($page_success);

        delete_option('woocommerce_page_cart_id');

        delete_option('woocommerce_page_checkout_id');

        delete_option('woocommerce_page_success_id');

        $options = $this->get_options();
        
        foreach ( $options as $option_key => $option_value ) {
            delete_option( $option_key );
        }
    }


    static function get_options() {
        
        $option = array(
            'wcmc_cart_database_version' => WCMC_CART_DATABASE,
            '_setting_checkout_cod'     => array( 'woocommerce_cod_enabled'=> 1,     'woocommerce_cod_title' => 'Trả tiền khi nhận hàng (COD)'),
            '_setting_checkout_bacs'    => array( 'woocommerce_bacs_enabled'=> 1,    'woocommerce_bacs_title' => 'Chuyển khoản ngân hàng'),
        );

        return $option;
    }

    public function delete_metadata() {

        //XÓA THÔNG TIN LIÊN QUAN PRODUCT
        delete_all_metadata( 'woocommerce', '_status' );

        delete_all_metadata( 'woocommerce', '_stock' );

        delete_all_metadata( 'woocommerce_attributes', '_product_attributes' );

        delete_all_metadata( 'woocommerce_variations' );

        //XÓA THÔNG TIN ĐƠN HÀNG
        delete_all_metadata( 'woocommerce_order' );
        
    }
}

if( version_compare( cms_info('version'), '2.5.4') < 0 ) {
    echo show_error('Phiên bản CMS phải lớn hơn hoặc bằng 2.5.4.');
    die;
}

//hiển thị product ngoài user
include 'wcmc-core-function.php';

//update base
include 'wcmc-database.php';

//cấu hình ajax
include 'wcmc-ajax.php';

//Quản lý cache
include 'wcmc-cache.php';

//cấu hình trong admin
if(is_admin()) include 'wcmc-admin.php';

//cấu hình trong emails
include 'wcmc-emails.php';

//cart
include 'wcmc-checkout-field.php';

//hiển thị product ngoài user
include 'wcmc-template-function.php';

//shortcode
include 'wcmc-shortcode.php';

include 'wcmc-payment.php';

//shortcode
add_action( 'init', 'wcmc_shortcode' );
