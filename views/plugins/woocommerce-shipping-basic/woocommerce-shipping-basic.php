<?php
/**
Plugin name     : woocommerce shipping basic
Plugin class    : woocommerce_shipping_basic
Plugin uri      : http://sikido.vn
Description     : Tạo giao hàng đơn giản.
Author          : SKDSoftware Dev Team
Version         : 2.0.0
*/
define( 'SHIP_BASIC_NAME', 'woocommerce-shipping-basic' );

class woocommerce_shipping_basic {

    private $name = 'woocommerce_shipping_basic';
    
    public  $ci;

    public function active() {

    	$ci = &get_instance();

    	$model = get_model('plugins');

    	if( !class_exists('woocommerce_cart')) {
    		echo notice('error', 'Bạn phải cài đặt plugins <b>WOOCOMMERCE CART</b> trước khi cài đặt plugin Shipping!', true);
    		die;
    	}
    }

    public function uninstall() {

    	$ci = &get_instance();

    	$model = get_model('plugins', 'backend');
    }


    public function get_options() {
        
        $option = array(
            '_setting_shipping'     => array( 'woocommerce_shipping_enabled'=> 1 ),
        );

        return $option;
    }
}

if( is_admin() ) include 'admin/ship-basic.php';


/**
 * [woocommerce_checkout_shipping Hiển thị select khu vực shipping]
 * @return [type] [description]
 */
function checkout_shipping_basic() {

    $shipping   = get_option('_setting_shipping');

    if( have_posts($shipping)) {
        plugin_get_include(SHIP_BASIC_NAME,'shipping-select', array('shipping' => $shipping ));
    }
}

if(function_exists('wcmc_cart_get_template_version') && version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {

	add_action('wcmc_checkout_content', 'checkout_shipping_basic', 40);
}
else {
	add_action( 'checkout_more', 'checkout_shipping_basic', 10);
}
/**
 * @Lấy phí ship
 */
if ( !function_exists('shipping_base_price') ) {

	function shipping_base_price( $citi ='' ) {

		$ci =& get_instance();

        if( $citi == '' ) return false;

        $price = 0;
        
        $ship = get_option('_setting_shipping');

        foreach ($ship['shipping'] as $key => $value) {

            if( $value['shipping_key'] == $citi ) {

                $price = $value['shipping_price'];

                break;
            }
        }

		return $price;
	}
}


/**
 * [checkout_review_shipping_basic Hiển thị review shipping]
 * @return [type] [description]
 */
if ( ! function_exists( 'shipping_base_price_review' ) ) {

	add_filter('wcmc_shipping_price_shipping_base', 	'shipping_base_price_review');
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function shipping_base_price_review($price) {

		$ci =& get_instance();

		$zone = array();

		if( $ci->input->post() ) {

			$citi 			= $ci->input->post('shipping');

			$price = shipping_base_price($citi);
		}

		return $price;
	}
}

if ( ! function_exists( 'shipping_base_check_submit' ) ) {

	add_action( 'woocommerce_checkout_process', 'shipping_base_check_submit' );
	/**
	 * Update the order meta with field value
	 */
	function shipping_base_check_submit() {

		$ci =& get_instance();

		$shipping_type = $ci->input->post('shipping_type');

		if($shipping_type == 'shipping-base') {

			$shipping_base_setting = get_option('_setting_shipping');

			$shipping 		= $ci->input->post('shipping');

			if(!empty($shipping_base_setting['shipping_base_required'])) {

				$city = $ci->input->post('shipping');

				if( $city === 0 || empty($city) ) {
					wcmc_add_notice( __( 'Vui lòng chọn khu vực giao hàng.' ), 'error' );
				}
			}

		}
	}
}