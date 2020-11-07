<?php
function cmi_the_style_4 ( $active, $option, $tab = '' ) {

	if($active['style-4'] == 1) {

		$style = array(
			'cmi_call'          => '',
			'cmi_position' 		=> 'left',
	    );

		if( isset($option['style-4']) ) $style = $option['style-4'];

		if( is_admin()) { if($tab == 'style-4') include 'html-style-4.php'; return; }

		else include 'html-style-4.php';
	}
}

function cmi_the_css_style_4 ($css_inline, $option) {

	if( isset($option['style-4'])) {

		$style = $option['style-4'];

		if($style['cmi_bottom'] != '') 	{
			$css_inline .= '.cmi-box-style-4 { bottom:'.$style['cmi_bottom'].'px; }';
		}
		if($style['cmi_width_image'] != '') 	{
			$css_inline .= '.cmi-box-style-4 ul li a img{ width:'.$style['cmi_width_image'].'px; }';
		}

		if($style['cmi_margin'] != '') 	{
			$css_inline .= '.cmi-box-style-4 ul li { margin-bottom:'.$style['cmi_margin'].'px; }';
		}
	}

	return $css_inline;
}

add_action('cmi_the_style', 'cmi_the_style_4', 12, 3);
add_filter('cmi_the_css', 	'cmi_the_css_style_4', 12, 2);