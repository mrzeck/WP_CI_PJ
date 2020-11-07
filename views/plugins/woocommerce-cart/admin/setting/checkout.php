<?php
/*======================== CHECKOUT TAB ========================*/
if( !function_exists('wcmc_setting_tab_checkout') ) {

	function wcmc_setting_tab_checkout($ci, $tab) {
		wcmc_get_include_cart('admin/views/setting/html-settings-tab-checkout');
	}
}

if( !function_exists('woocommerce_cart_settings_tabs_checkout') ) {

	function woocommerce_cart_settings_tabs_checkout( ) {

		$tabs['bacs'] 		= array( 'label' => 'Chuyển khoản ngân hàng', 'callback' => '_settings_tabs_checkout_bacs');

		$tabs['cod'] 		= array( 'label' => 'Trả tiền khi nhận hàng', 'callback' => '_settings_tabs_checkout_cod');

		return apply_filters('woocommerce_cart_settings_tabs_checkout', $tabs);
	}
}

/*======================== CHECKOUT BACS ========================*/
if( !function_exists('_settings_tabs_checkout_bacs') ) {

	function _settings_tabs_checkout_bacs( ) {
		wcmc_get_include_cart('admin/views/setting/html-settings-tab-checkout-bacs');
	}
}

if( !function_exists('wcmc_setting_checkout_bacs_save') ) {

	function wcmc_setting_checkout_bacs_save( $ci, $model ) {

		$result['status']  = 'error';
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data = $ci->input->post();

			$bacs['woocommerce_bacs_enabled'] = 1;

			//bật tắt
			if(!isset( $data['woocommerce_bacs_enabled'] ) ) $bacs['woocommerce_bacs_enabled'] = 0;

			//title
			$bacs['woocommerce_bacs_title'] = removeHtmlTags($data['woocommerce_bacs_title']);

			$bacs['woocommerce_bacs_img'] = process_file($data['woocommerce_bacs_img']);

			//ngân hàng
			if( !empty($data['bacs_account_name']) ) {

				foreach ($data['bacs_account_name'] as $key => $name) {
					if( empty($name) ) continue;
					$bacs['bank'][$key]['bacs_account_name']   = $name;
					$bacs['bank'][$key]['bacs_account_number'] = $data['bacs_account_number'][$key];
					$bacs['bank'][$key]['bacs_bank_name']      = $data['bacs_bank_name'][$key];
					$bacs['bank'][$key]['bacs_bank_branch']    = $data['bacs_bank_branch'][$key];
				}

			}
			else $bacs['bank'] = array();
			

			if( have_posts($bacs) ) {

				update_option( '_setting_checkout_bacs', $bacs );

				$result['status']  = 'success';

				$result['message'] = __('Lưu dữ liệu thành công.');

			}

		}

		echo json_encode($result);
	}

	register_ajax_admin('wcmc_setting_checkout_bacs_save');
}
/*======================== CHECKOUT COD ========================*/
if( !function_exists('_settings_tabs_checkout_cod') ) {

	function _settings_tabs_checkout_cod( ) {
		wcmc_get_include_cart('admin/views/setting/html-settings-tab-checkout-cod');
	}
}

if( !function_exists('wcmc_setting_checkout_cod_save') ) {

	function wcmc_setting_checkout_cod_save( $ci, $model ) {

		$result['status']  = 'error';
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data = $ci->input->post();

			$cod['woocommerce_cod_enabled'] = 1;

			//bật tắt
			if(!isset( $data['woocommerce_cod_enabled'] ) ) $cod['woocommerce_cod_enabled'] = 0;

			//title
			$cod['woocommerce_cod_title'] = removeHtmlTags($data['woocommerce_cod_title']);

			$cod['woocommerce_cod_img'] = process_file($data['woocommerce_cod_img']);

			if( have_posts($cod) ) {

				update_option( '_setting_checkout_cod', $cod );

				$result['status']  = 'success';

				$result['message'] = __('Lưu dữ liệu thành công.');

			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('wcmc_setting_checkout_cod_save');
}