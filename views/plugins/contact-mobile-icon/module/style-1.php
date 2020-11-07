<?php
function cmi_the_style_1 ( $active, $option, $tab = '' ) {

	if($active['style-1'] == 1) {

		$style = array();

        if( isset($option['style-1']) ) $style = $option['style-1'];

		if( is_admin()) { if($tab == 'style-1') include 'html-style-1.php'; return; }

		else include 'html-style-1.php';
	}
}

function cmi_the_css_style_1 ($css_inline, $option) {


	if( isset($option['style-1'])) {

		$style = $option['style-1'];

		/* big landscape tablets, laptops, and desktops */
		$css_inline .= '@media (min-width:962px)  {';
		if($style['cmi_desktop_enable_fb'] 		== 0) 	$css_inline .= '#messengerButton { display:none; }';
		if($style['cmi_desktop_enable_zalo'] 	== 0) 	$css_inline .= '#zaloButton { display:none; }';
		if($style['cmi_desktop_enable_sms'] 	== 0) 	$css_inline .= '#registerNowButton { display:none; }';
		if($style['cmi_desktop_enable_call'] 	== 0) 	$css_inline .= '#callNowButton { display:none; }';
		$css_inline .= '}';

		/* tablet, landscape iPad, lo-res laptops ands desktops */
		$css_inline .= '@media (max-width:961px) and (min-width:482px)  {';
		if($style['cmi_tablet_enable_fb'] 		== 0) 	$css_inline .= '#messengerButton { display:none; }';
		if($style['cmi_tablet_enable_zalo'] 	== 0) 	$css_inline .= '#zaloButton { display:none; }';
		if($style['cmi_tablet_enable_sms'] 		== 0) 	$css_inline .= '#registerNowButton { display:none; }';
		if($style['cmi_tablet_enable_call'] 	== 0) 	$css_inline .= '#callNowButton { display:none; }'; 
		$css_inline .= '}';

		/* portrait e-readers (Nook/Kindle), smaller tablets @ 600 or @ 640 wide. */
		$css_inline .= '@media (max-width:481px)  {';
		if($style['cmi_mobile_enable_fb'] 		== 0) 	$css_inline .= '#messengerButton { display:none; }';
		if($style['cmi_mobile_enable_zalo'] 	== 0) 	$css_inline .= '#zaloButton { display:none; }';
		if($style['cmi_mobile_enable_sms'] 		== 0) 	$css_inline .= '#registerNowButton { display:none; }';
		if($style['cmi_mobile_enable_call'] 	== 0) 	$css_inline .= '#callNowButton { display:none; }';
		$css_inline .= '}';
	}

	return $css_inline;
}

add_action('cmi_the_style', 'cmi_the_style_1', 10, 3);
add_filter('cmi_the_css', 	'cmi_the_css_style_1', 10, 2);
