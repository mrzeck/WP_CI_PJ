<?php
/* tạo navigation admin */

function htslider_navigation() {

	$ci =&get_instance();

	if( version_compare( cms_info('version'), '3.0.0') >= 0 ) {

		register_admin_subnav('theme','Slider', 'ht-slider', 'plugins?page=ht-slider&view=sliders',array(

			'icon' 		=> '<img src="'.$ci->plugin->get_path('ht-slider').'assets/images/slider.png" />',

			'callback'	=> 'ht_slider_index',

		),'option');

	}

	else {

		register_admin_nav('Revolution slider', 'ht-slider', 'plugins?page=ht-slider&view=sliders', 'theme',array(

			'icon' 		=> '<img src="'.$ci->plugin->get_path('ht-slider').'assets/images/slider.png" />',

			'callback'	=> 'ht_slider_index',

		));

	}

}

add_action('init', 'htslider_navigation');

/* add libary */
function ht_slider_style() {

	$ci =& get_instance();

	admin_register_script('ht-slider',$ci->plugin->get_path('ht-slider').'assets/js/ht-slider-script.js','plugins');

}

add_action('cle_enqueue_script', 'ht_slider_style');

/**

 * [ht_slider_index HT Slider Main]

 * @param  [type] $ci    [description]

 * @param  [type] $model [description]

 * @return [type]        [description]

 */

function ht_slider_index($ci, $model) {

	extract($ci->data);

	$view = $ci->input->get('view');

	include 'views/admin/top.php';

	switch ($view) {

		case 'sliders': include 'views/admin/index.php'; break;

		case 'slider':

			$id 		= (int)$ci->input->get('id');

			$slider_id 	= (int)$ci->input->get('slider');

			$model->settable('group');

			$slider 	= $model->get_where(array('object_type' => 'ht-slider', 'id' => $slider_id));

			if( have_posts( $slider ) ) include 'views/admin/item.php';

			break;

		default: break;

	}

	include 'views/admin/bottom.php';
}