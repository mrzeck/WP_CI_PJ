<?php
include 'navigation-option.php';

function store_theme_navigation_html( $ci ) { include_once 'navigation-html.php'; }

add_action('cle_header_navigation', 'store_theme_navigation_html');

function store_theme_navigation_css() { include 'navigation-css.php'; }
add_menu_option('socot', array( 'field' => 'socot', 'label' => 'số cột group con', 'type' => 'select','value'=>1, 'options'   => array(1,2,3,4)));
add_menu_option('vitri', array( 'field' => 'vitri', 'label' => 'Vị trí cột', 'type' => 'select','value'=>1, 'options'   => array(1,2,3,4)));

add_action('theme_custom_css_no_tag', 'store_theme_navigation_css');

function store_theme_navigation_script() { include 'navigation-script.php'; }

add_action('theme_custom_script_no_tag', 'store_theme_navigation_script');


function store_register_nav_menus() {
	register_nav_menus(['main-vertical'	=> 'Menu danh mục trái','main-mobile'    => 'Menu mobile',]);
}

add_action('init', 'store_register_nav_menus');

$args = array(
	'name'          => 'Slider Top',
	'id'            => 'home-slider',
	'description'   => '',
);

register_sidebar( $args );


class store_nav_menu_vertical extends walker_nav_menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

        $class          = have_posts($item->child) ?'dropdown ':'';
        $class          .= isset($item->class) ?$item->class:'';
        $output         .= '<li class="nav-item '.$class.'">';
        $atts           = array();

        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? get_url($item->slug)       : '';
        $atts['class']  = 'nav-link';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<a '.$attributes.'>'.$item->name.'</a>' ;
        if( have_posts($item->child) ) $output .= '<i class="fal fa-angle-right"></i>';
    }


    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '</li>';
    }
}