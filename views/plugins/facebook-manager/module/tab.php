<?php
function fbm_the_html_tab ( $active, $option, $tab = '' ) {

	if($active['fbm-tab'] == 1) {

		$setting = array(
			'fbm_desktop'    => 1,
			'fbm_tablet'     => 0,
			'fbm_mobile'     => 0,
			'fbm_position'   => 'left',
	    );

		if( isset($option['fbm-tab']) ) $setting = $option['fbm-tab'];

		if( is_admin()) { if($tab == 'fbm-tab') include 'html-tab.php'; return; }

		else include 'html-tab.php';
		
	}
}

function fbm_the_css_tab ($css_inline, $option) {


	if( isset($option['fbm-tab'])) {

		$style = $option['fbm-tab'];

		if($style['fbm_bottom'] != 0) 	 {
			$css_inline .= '.fbm_tab { top:'.$style['fbm_bottom'].'px; }';
		}

		if($style['fbm_desktop'] == 0) {
			$css_inline .= '@media(min-width:769px) {';
			$css_inline .= '.fbm_tab { display: none;}';
			$css_inline .= '}';
		}

		if($style['fbm_tablet'] == 0) {
			$css_inline .= '@media(max-width:768px) and (min-width:500px) {';
			$css_inline .= '.fbm_tab { display: none;}';
			$css_inline .= '}';
		}

		if($style['fbm_mobile'] == 0) {
			$css_inline .= '@media(max-width:499px) {';
			$css_inline .= '.fbm_tab { display: none;}';
			$css_inline .= '}';
		}
	}

	return $css_inline;
}

add_action('fbm_the_html', 'fbm_the_html_tab', 10, 3);
add_filter('fbm_the_css',  'fbm_the_css_tab', 10, 2);
