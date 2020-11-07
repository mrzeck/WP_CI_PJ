<?php
/**
 * [ctf_register_navigation tạo menu admin]
 * @return [type] [description]
 */
function fbm_admin_navigation() {

	$ci =&get_instance();
	
	register_admin_subnav('system', 'Facebook manager', 'fbm', 'plugins?page=fbm&view=settings', array('callback' => 'facebook_manager_settings'), 'audit-log');
}

add_action('init', 'fbm_admin_navigation');

/**
 * Main page setting
 */
function facebook_manager_settings() {

	$ci 	= &get_instance();

	$views 	= $ci->input->get('view');

	$tab 	= (int)$ci->input->get('tab');

	plugin_get_include(FBM_NAME, 'admin/views/html-setting');
}

if( !function_exists('fbm_settings_tabs') ) {

	function fbm_settings_tabs() {

		return apply_filters('fbm_settings_tabs', array(
			'general'          => array( 'label' => 'Chung', 					'callback' => 'fbm_setting_tab'),
			'fbm-send-message' => array( 'label' => 'Fanpage Send Message', 	'callback' => 'fbm_setting_tab'),
			'fbm-tab'          => array( 'label' => 'Tab', 						'callback' => 'fbm_setting_tab'),
		));
	}
}

if( !function_exists('fbm_setting_tab') ) {

	function fbm_setting_tab($ci, $tab) {

		$action = fbm_fix_version(get_option('fbm_active'));

		$option = fbm_fix_version(get_option('fbm_setting'));

		plugin_get_include(FBM_NAME, 'admin/views/html-setting-tab-'.$tab, array('active' => $action, 'option' => $option));

	}

}