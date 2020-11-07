<?php
function skd_add_input_seo($input) {

	$input[] = array('field' => 'skd_seo_robots', 'type'	=> 'textarea', 'label' => 'Nội dung file robots');

	return $input;
}

add_filter('get_theme_seo_input', 'skd_add_input_seo');