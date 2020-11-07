<?php
include 'setting/checkout.php';

include 'setting/email.php';

include 'setting/shipping.php';

if( !function_exists('woocommerce_cart_settings') ) {
	/**
	 * [woocommerce_settings hàm callback khi chạy trang cấu hình
	 */
	function woocommerce_cart_settings($ci, $model) {

		$ci    =& get_instance();
		
		$views = $ci->input->get('view');
		
		$tab   = (int)$ci->input->get('tab');

		wcmc_get_include_cart('admin/views/setting/html-settings');
	}
}

if( !function_exists('woocommerce_cart_settings_tabs') ) {

	function woocommerce_cart_settings_tabs() {

		return apply_filters('woocommerce_cart_settings_tabs', array(
			'checkout' 	=> array( 'label' => 'Thanh Toán', 	'callback' => 'wcmc_setting_tab_checkout', 'icon' => '<i class="fas fa-comment-dollar"></i>'),
			'email' 	=> array( 'label' => 'Email', 		'callback' => 'wcmc_setting_tab_email',    'icon' => '<i class="fal fa-envelope"></i>'),
			'shipping' 	=> array( 'label' => 'Shipping', 	'callback' => 'wcmc_setting_tab_shipping', 'icon' => '<i class="fal fa-shipping-fast"></i>'),
		));
	}
}