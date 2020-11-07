<?php 

$input = array('field' => '', 'id' => 'value', 'label' => 'File dữ liệu', 'value'=>'','type' => 'file'); echo _form($input);

$class = $ci->template->class;

$option = array();

if(  $class == 'post') {

	if( isset($ci->gallery_options['object'][$class][$ci->post_type]) && have_posts($ci->gallery_options['object'][$class][$ci->post_type]) ) {
		$option = $ci->gallery_options['object'][$class][$ci->post_type];
	}

}

else if(  $class == 'post_categories') {

	if( isset($ci->gallery_options['object'][$class][$ci->cate_type]) && have_posts($ci->gallery_options['object'][$class][$ci->cate_type]) ) {
		$option = $ci->gallery_options['object'][$class][$ci->cate_type];
	}
}

else if( isset($ci->gallery_options['object'][$class]) && have_posts($ci->gallery_options['object'][$class]) ) {
	
	$option = $ci->gallery_options['object'][$class];

}

if( have_posts($option)) {

	foreach ($option as $key => $input) {

		$input['field'] = 'option_'.$input['field'].'';

		echo _form($input);
	}
}