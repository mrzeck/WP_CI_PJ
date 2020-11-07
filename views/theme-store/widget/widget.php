<?php
/**
 * Register Widget
 */
$args = array(
	'name'          => 'Trang chủ',
	'id'            => 'home-index',
	'description'   => '',
);
register_sidebar( $args );

$args = array(
	'name'          => 'Sidebar',
	'id'            => 'sidebar-main',
	'description'   => '',
);
register_sidebar( $args );
$args = array(
	'name'          => 'Footer top',
	'id'            => 'footer-top',
	'description'   => '',
);
register_sidebar( $args );
$args = array(
	'name'          => 'Footer',
	'id'            => 'footer-main',
	'description'   => '',
);
register_sidebar( $args );

//auto load
foreach (glob(VIEWPATH.$this->data['template']->name.'/widget/*.php') as $filename)
{
    include_once $filename;
}

foreach (glob(VIEWPATH.$this->data['template']->name.'/widget/*', GLOB_ONLYDIR) as $foldername)
{
    foreach (glob($foldername.'/*.php') as $filename)
	{
	    include_once $filename;
	}
}

//add style add script
if(!function_exists('store_widget_style_header'))  {
    
	function store_widget_style_header() {
		
		$ci =& get_instance();

		$active = get_option('theme_current');

        admin_register_style('store_widget',  'views/'.$active.'/assets/css/admin/store-widget.css');
    }

    add_action('cle_enqueue_style', 'store_widget_style_header');
}

if(!function_exists('store_widget_script_footer'))  {

	function store_widget_script_footer() {

		$ci =& get_instance();

		$active = get_option('theme_current');

        admin_register_script('store_widget', 'views/'.$active.'/assets/js/store-widget.js');
    }

    add_action('cle_enqueue_script', 'store_widget_script_footer');
}