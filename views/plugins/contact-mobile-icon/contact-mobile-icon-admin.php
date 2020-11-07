<?php
/**
 * [ctf_register_navigation tạo menu admin]
 * @return [type] [description]
 */
function cmi_register_navigation() {

	$ci =&get_instance();

	register_admin_nav('Icon mobile', 'cmi', 'plugins?page=cmi&view=settings','theme',array('icon' => '<img src="'.$ci->plugin->get_path(CMI_NAME).'assets/images/icon-nav.png" />', 'callback' => 'cmi_main'));
}

add_action('init', 'cmi_register_navigation');

/**
 * Main page setting
 */

function cmi_main() {

	$ci 	= &get_instance();

	$views 	= $ci->input->get('view');

	$tab 	= (int)$ci->input->get('tab');

	$contact_mobile_icon = new contact_mobile_icon();

	plugin_get_include(CMI_NAME, 'admin/views/html-setting', array('contact_mobile_icon' => $contact_mobile_icon));
}

if( !function_exists('cmi_settings_tabs') ) {

	function cmi_settings_tabs() {
		return apply_filters('cmi_settings_tabs', array(
			'general' 	=> array( 'label' => 'Chung', 					'callback' => 'cmi_setting_tab'),
			'style-1' 	=> array( 'label' => 'Cấu hình style 1', 		'callback' => 'cmi_setting_tab'),
			'style-2' 	=> array( 'label' => 'Cấu hình style 2', 		'callback' => 'cmi_setting_tab'),
			'style-3' 	=> array( 'label' => 'Cấu hình style 3', 		'callback' => 'cmi_setting_tab'),
			'style-4' 	=> array( 'label' => 'Cấu hình style 4', 		'callback' => 'cmi_setting_tab'),
		));
	}
}

if( !function_exists('cmi_setting_tab') ) {

	function cmi_setting_tab($ci, $tab, $contact_mobile_icon) {

		$action = cmi_fix_version(get_option('cmi_active'));

		$option = cmi_fix_version(get_option('cmi_style'));

		$action = array_merge( array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0, 'style-4' => 0), $action);

		plugin_get_include(CMI_NAME, 'admin/views/html-setting-tab-'.$tab, array('active' => $action, 'option' => $option, 'contact_mobile_icon' => $contact_mobile_icon));

	}
}