<?php
function fbm_the_html_send_message ( $active, $option, $tab = '' ) {

	if($active['fbm-send-message'] == 1) {

		$setting = array(
			'fbm_desktop'    => 1,
			'fbm_tablet'     => 0,
			'fbm_mobile'     => 0,
			
			'fbm_title'      => 'Facebook Chat',
			'fbm_color_bg'   => '',
			'fbm_color_text' => '',
			'fbm_position'   => 'left',
	    );

		if( isset($option['fbm-send-message']) ) $setting = $option['fbm-send-message'];

		if( is_admin()) { if($tab == 'fbm-send-message') include 'html-send-message.php'; return; }

		else include 'html-send-message.php';
		
	}
}

function fbm_the_css_send_message ($css_inline, $option) {


	if( isset($option['fbm-send-message'])) {

		$style = $option['fbm-send-message'];

		if($style['fbm_color_bg'] 	== 0) 	 {
			$css_inline .= '.box-fbm-chat .fbm-chat-header { background-color:'.$style['fbm_color_bg'].'}';
		}

		if($style['fbm_color_text'] 	== 0) 	 {
			$css_inline .= '.box-fbm-chat .fbm-chat-header .fbm-chat-title { color:'.$style['fbm_color_text'].'; }';
		}

		if($style['fbm_desktop'] == 0) {
			$css_inline .= '@media(min-width:769px) {';
			$css_inline .= '.box-fbm-chat { display: none;}';
			$css_inline .= '#fbm_box_show { display: none;}';
			$css_inline .= '}';
		}

		if($style['fbm_tablet'] == 0) {
			$css_inline .= '@media(max-width:768px) and (min-width:500px) {';
			$css_inline .= '.box-fbm-chat { display: none;}';
			$css_inline .= '#fbm_box_show { display: none;}';
			$css_inline .= '}';
		}

		if($style['fbm_mobile'] == 0) {
			$css_inline .= '@media(max-width:499px) {';
			$css_inline .= '.box-fbm-chat { display: none;}';
			$css_inline .= '#fbm_box_show { display: none;}';
			$css_inline .= '}';
		}
	}

	return $css_inline;
}

add_action('fbm_the_html', 'fbm_the_html_send_message', 10, 3);
add_filter('fbm_the_css',  'fbm_the_css_send_message', 10, 2);
