<?php
function cmi_the_style_3 ( $active, $option, $tab = '' ) {

	if($active['style-3'] == 1) {

		$style = array(
			'cmi_call'          => '',
			'cmi_position' 		=> 'left',
	    );

		if( isset($option['style-3']) ) $style = $option['style-3'];

		if( is_admin()) { if($tab == 'style-3') include 'html-style-3.php'; return; }

		else include 'html-style-3.php';
	}
}

function cmi_the_css_style_3 ($css_inline, $option) {

	if( isset($option['style-3'])) {

		$style = $option['style-3'];

		if($style['cmi_color_icon'] != '') 	{
			$css_inline .= '.quick-alo-phone.quick-alo-green .quick-alo-ph-img-circle, .quick-alo-ph-img-circle { background-color:'.$style['cmi_color_icon'].'; }';
		}

		if($style['cmi_color_border1'] != '') 	{
			$css_inline .= '.quick-alo-ph-circle-fill { background-color:'.$style['cmi_color_border1'].'; }';
		}

		if($style['cmi_color_border2'] != '') 	{
			$css_inline .= '.quick-alo-ph-circle { border-color:'.$style['cmi_color_border2'].'; }';
		}

		if($style['cmi_bottom'] != '') 	{
			$css_inline .= '.cmi-box-style-3 { bottom:'.$style['cmi_bottom'].'px; }';
		}

		
	}

	return $css_inline;
}

add_action('cmi_the_style', 'cmi_the_style_3', 12, 3);
add_filter('cmi_the_css', 	'cmi_the_css_style_3', 12, 2);