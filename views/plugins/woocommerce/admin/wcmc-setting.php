<?php
/**
 * SETTING WOOCOMERCE
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */

if( !function_exists('woocommerce_settings') ) {
	/**
	 * [woocommerce_settings hàm callback khi chạy trang cấu hình
	 */
	function woocommerce_settings($ci, $model) {

		$ci    =& get_instance();
		
		$views = $ci->input->get('view');
		
		$tab   = (int)$ci->input->get('tab');

		wcmc_get_include('admin/views/setting/html-settings');
	}
}
if( !function_exists('woocommerce_exel') ) {
	/**
	 * [woocommerce_settings hàm callback khi chạy trang cấu hình
	 */
	function woocommerce_exel($ci, $model) {

		$ci    =& get_instance();
		
		$views = $ci->input->get('view');
		
		$tab   = (int)$ci->input->get('tab');

		wcmc_get_include('admin/views/setting/html-exel');
	}
}

if( !function_exists('woocommerce_exel') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function woocommerce_settings_tabs() {

		return apply_filters('woocommerce_settings_tabs', array(
			'general' 	=> array( 'label' => 'Chung', 	'callback' => 'wcmc_setting_tab_general', 'icon' => '<i class="fab fa-elementor"></i>'),
			'product' 	=> array( 'label' => 'Sản phẩm', 'callback' => 'wcmc_setting_tab_product', 'icon' => '<i class="fal fa-box-full"></i>'),
		));
	}
}

/*======================== GENERAL TAB ========================*/
if( !function_exists('wcmc_setting_tab_general') ) {
	function wcmc_setting_tab_general($ci, $tab) {
		if($ci->input->post()) {
			$woocommerce_option['woocommerce_currency'] 		= $ci->input->post('woocommerce_currency');
			$woocommerce_option['woocommerce_price_contact'] 	= $ci->input->post('woocommerce_price_contact');
			foreach ($woocommerce_option as $key => $value) {
				update_option( $key, $value );
			}
		}
		wcmc_get_include('admin/views/setting/html-settings-tab-general');
	}
}


/*======================== PRODUCT TAB ========================*/
if( !function_exists('woocommerce_settings_tabs_sub_product') ) {
	/**
	 * Tab con cấu hình sản phẩm
	 */
	function woocommerce_settings_tabs_sub_product( ) {

		$tabs['object'] 	= array( 'label' => 'Sản phẩm', 			'callback' 	=> '_settings_tabs_sub_product');

		$tabs['index'] 		= array( 'label' => 'Trang danh mục', 	'callback' 	=> '_settings_tabs_sub_product');

		$tabs['detail'] 	= array( 'label' => 'Trang chi tiết', 		'callback' 	=> '_settings_tabs_sub_product');

		return apply_filters('woocommerce_settings_tabs_sub_product', $tabs);
	}
}

if( !function_exists('_settings_tabs_sub_product') ) {

	function _settings_tabs_sub_product( $ci, $section ) {

		wcmc_get_include('admin/views/setting/html-settings-tab-product-'.$section);
	}
}

if( !function_exists('wcmc_setting_tab_product') ) {

	function wcmc_setting_tab_product($ci, $tab) {

		wcmc_get_include('admin/views/setting/html-settings-tab-product');
	}
}

if( !function_exists('wcmc_ajax_setting_product_save') ) {

	function wcmc_ajax_setting_product_save( $ci, $model ) {

        $result['status']  = 'error';
        
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$woocommerce_option = $ci->input->post();

			foreach ($woocommerce_option as $key => $value) {
				update_option( $key, $value );
			}

			$result['status']  = 'success';

			$result['message'] = __('Lưu dữ liệu thành công.');
		}

        echo json_encode($result);
	}

	register_ajax_admin('wcmc_ajax_setting_product_save');
}