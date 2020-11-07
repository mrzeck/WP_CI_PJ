<?php 
if( !function_exists('skd_system_cms_status') ) {

	function skd_system_cms_status($ci, $tab) {

		include 'system-cms-status.php';
	}
}

if( !function_exists('skd_system_cms_contact') ) {

	function skd_system_cms_contact($ci, $tab) {

		include 'system-cms-contact.php';
	}
}

if( !function_exists('skd_system_cms_smtp') ) {

	function skd_system_cms_smtp($ci, $tab) {

		include 'system-cms-smtp.php';
	}
}

if( !function_exists('skd_system_cms_status_save') ) {

	function skd_system_cms_status_save($result, $data) {

		$cms['cms_status']   	= removeHtmlTags($data['cms_status']);

		$cms['cms_password']   	= removeHtmlTags($data['cms_password']);

		$cms['cms_close_title']   	= removeHtmlTags($data['cms_close_title']);

		$cms['cms_close_content']   	= removeHtmlTags($data['cms_close_content']);

		foreach ($cms as $key => $value) {
			update_option( $key , $value );
		}

		return $result;
	}

	add_filter('system_cms_status_save','skd_system_cms_status_save',10,2);
}

if( !function_exists('skd_system_cms_contact_save') ) {

	function skd_system_cms_contact_save($result, $data) {

		$contact['contact_mail']   	= removeHtmlTags($data['contact_mail']);

		$contact['contact_phone']   = removeHtmlTags($data['contact_phone']);

		$contact['contact_address'] = removeHtmlTags($data['contact_address']);

		$contact = apply_filters('skd_system_cms_contact_save', $contact, $data);

		foreach ($contact as $key => $value) {
			update_option( $key , $value );
		}

		return $result;
	}

	add_filter('system_cms_contact_save','skd_system_cms_contact_save',10,2);
}

if( !function_exists('skd_system_cms_smtp_save') ) {

	function skd_system_cms_smtp_save($result, $data) {
		
		$ci =& get_instance();
		
		$ci->load->library('skd_mail');

		$mail = new skd_mail();

		$smtp['smtp-user']   = (!empty($data['smtp-user']))? removeHtmlTags($data['smtp-user']) 	: $mail->user;

		$smtp['smtp-pass']   = (!empty($data['smtp-pass']))? removeHtmlTags($data['smtp-pass']) 	: $mail->pass;

		$smtp['smtp-server'] = (!empty($data['smtp-server']))? removeHtmlTags($data['smtp-server']) : $mail->host;

		$smtp['smtp-port']   = (!empty($data['smtp-port']))? removeHtmlTags($data['smtp-port']) 	: $mail->port;

		foreach ($smtp as $key => $value) {
			update_option( $key , $value );
		}

		return $result;
	}

	add_filter('system_cms_smtp_save','skd_system_cms_smtp_save',10,2);
}