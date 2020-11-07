<?php
function cmi_the_style_2 ( $active, $option, $tab = '' ) {

	if($active['style-2'] == 1) {

		$style = array(
            'cmi_call'          => '',
            'cmi_sms'           => '',
            'cmi_contact'       => '',
            'cmi_title_call'    => 'Gọi điện',
            'cmi_title_sms'     => 'SMS',
            'cmi_title_contact' => 'Chỉ Đường',
        );

        if( isset($option['style-2']) ) $style = $option['style-2'];

		if( is_admin()) { if($tab == 'style-2') include 'html-style-2.php'; return; }

		else include 'html-style-2.php';
	}
}

function cmi_the_css_style_2 ($css_inline, $option) {

	if( is_admin()) {
		$css_inline .= '.box-style-2 { display:block!important; }';
	}

	if( isset($option['style-2'])) {

		$style = $option['style-2'];

		if($style['cmi_bg'] 		!= '') 	$css_inline .= '.box-style-2 { background-color:'.$style['cmi_bg'].'; }';
	}

	return $css_inline;
}

add_action('cmi_the_style', 'cmi_the_style_2', 11, 3);
add_filter('cmi_the_css', 	'cmi_the_css_style_2', 11, 2);
