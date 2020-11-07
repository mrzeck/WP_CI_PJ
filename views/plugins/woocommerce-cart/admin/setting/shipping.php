<?php
if( !function_exists('wcmc_setting_tab_shipping') ) {

	function wcmc_setting_tab_shipping($ci, $tab) {

		wcmc_get_include_cart('admin/views/setting/html-settings-tab-shipping');
	}
}

if( !function_exists('woocommerce_cart_settings_tabs_shipping') ) {

	function woocommerce_cart_settings_tabs_shipping( ) {

		$tabs = [];

        return apply_filters('woocommerce_cart_settings_tabs_shipping', $tabs);
	}
}

if( !function_exists('wcmc_ajax_setting_shipping_save') ) {

	function wcmc_ajax_setting_shipping_save( $ci, $model ) {

        $result['status']  = 'error';
        
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data = $ci->input->post();

			$wcmc_shipping_key = removeHtmlTags($data['wcmc_shipping_key']);

			$wcmc_shipping = get_option('wcmc_shipping', []);

			if(!have_posts($wcmc_shipping)) $wcmc_shipping = [];

			$wcmc_shipping[$wcmc_shipping_key]['enabled'] 			= removeHtmlTags($data['wcmc_shipping_enabled']);

			$wcmc_shipping[$wcmc_shipping_key]['label'] 			= removeHtmlTags($data['wcmc_shipping_label']);

			$wcmc_shipping[$wcmc_shipping_key]['price_default'] 	= removeHtmlTags($data['wcmc_shipping_price_default']);

			if(!empty($data['wcmc_shipping_default'])) $wcmc_shipping_default 	= removeHtmlTags($data['wcmc_shipping_default']);

			if( have_posts($wcmc_shipping) ) {

				do_action('wcmc_shipping_'.$wcmc_shipping_key.'_setting_save', $wcmc_shipping_key, $data);

				update_option( 'wcmc_shipping', $wcmc_shipping );

				if(!empty($data['wcmc_shipping_default'])) update_option( 'wcmc_shipping_default', $wcmc_shipping_default );

				$result['status']  = 'success';

				$result['message'] = __('Lưu dữ liệu thành công.');
			}
		}

        echo json_encode($result);
	}

	register_ajax_admin('wcmc_ajax_setting_shipping_save');
}