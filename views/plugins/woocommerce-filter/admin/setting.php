<?php
if ( ! function_exists( 'woocommerce_filter_setting' ) ) {
    function woocommerce_filter_setting() {
        $ci =&get_instance();
        $tab   = (int)$ci->input->get('tab');
        wcmc_filter_get_include('admin/views/html-settings');
    }
}
if( !function_exists('woocommerce_filter_setting_tabs') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function woocommerce_filter_setting_tabs() {
		return apply_filters('woocommerce_filter_setting_tabs', array(
			'general' 	=> array( 'label' => 'Chung', 		'callback' => 'wcmc_filter_setting_tab_general'),
		));
	}
}
if( !function_exists('wcmc_filter_setting_tab_general') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function wcmc_filter_setting_tab_general() {
		wcmc_filter_get_include('admin/views/html-settings-tab-general');
	}
}