<?php
if( !function_exists('shipping_basic_tabs') ) {

	function shipping_basic_tabs( $tabs ) {

		$tabs['shipping-base'] 	= array( 'label' => 'Phí ship cơ bản', 	'callback' => 'wcmc_setting_tab_shipping_basic');

		return $tabs;
	}
	add_filter( 'woocommerce_cart_settings_tabs_shipping', 'shipping_basic_tabs' );
}

if( !function_exists('wcmc_setting_tab_shipping_basic') ) {
	/**
	 * [wcmc_setting_tab_shipping hiển thị trang setting shipping trong admin]
	 * @param  [type] $ci  [description]
	 * @param  [type] $tab [description]
	 * @return [type]      [description]
	 */
	function wcmc_setting_tab_shipping_basic($ci, $tab) {
		
		plugin_get_include(SHIP_BASIC_NAME,'admin/views/html-settings-tab-shipping-basic');
	}
}

if( !function_exists('wcmc_setting_shipping_basic_save') ) {

	function wcmc_setting_shipping_basic_save( $key, $data ) {

		if($key == 'shipping-base') {
			
			$shipping = [];

			if(!empty($data['shipping_base_required']))
				$shipping['shipping_base_required'] = 1;
			else
				$shipping['shipping_base_required'] = 0;

			//ngân hàng
			foreach ($data['shipping_name'] as $key => $name) {
				if( empty($name) ) continue;
				$shipping['shipping'][$key]['shipping_name'] 		= $name;
				$shipping['shipping'][$key]['shipping_key'] 		= $data['shipping_key'][$key];
				$shipping['shipping'][$key]['shipping_price'] 		= $data['shipping_price'][$key];
			}

			if( have_posts($shipping) ) {
				update_option( '_setting_shipping', $shipping );
			}
		}
	}

	add_action('wcmc_shipping_shipping-base_setting_save', 'wcmc_setting_shipping_basic_save', 2, 2);
}