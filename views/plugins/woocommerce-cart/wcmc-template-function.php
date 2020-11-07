<?php
include 'include/wcmc-template-cart.php';

include 'include/wcmc-template-checkout.php';

include 'include/wcmc-template-user.php';

if ( ! function_exists( 'woocommerce_cart_style_header' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_cart_style_header() {
		$ci =& get_instance();
		if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {
			cle_register_style('wcmc-cart', $ci->plugin->get_path('woocommerce-cart').'assets/css/wcmc-cart-style.css');
		} else {
			cle_register_style('wcmc-cart', $ci->plugin->get_path('woocommerce-cart').'assets/css/wcmc-cart-style.1.2.x.css');
		}
	}

	add_action('cle_enqueue_style', 	'woocommerce_cart_style_header');
}

if ( ! function_exists( 'woocommerce_cart_style_footer' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_cart_style_footer() {
		$ci =& get_instance();
		cle_register_script('wcmc-product-detail', $ci->plugin->get_path('woocommerce-cart').'assets/js/SerializeJSON/SerializeJSON.js');
		cle_register_script('wcmc-product-detail', $ci->plugin->get_path('woocommerce-cart').'assets/js/wcmc-add-to-cart.js?v=2.2.7');
	}

	add_action('cle_enqueue_script', 	'woocommerce_cart_style_footer');
}

if ( ! function_exists( 'woocommerce_cart_heading_bar' ) ) {

	function woocommerce_cart_heading_bar() {
		if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {
			wcmc_get_template_cart('heading-bar');
		}
	}

	add_action('woocommerce_before_cart', 	'woocommerce_cart_heading_bar');
	add_action('woocommerce_before_checkout', 	'woocommerce_cart_heading_bar');
	add_action('woocommerce_before_success', 	'woocommerce_cart_heading_bar');
}