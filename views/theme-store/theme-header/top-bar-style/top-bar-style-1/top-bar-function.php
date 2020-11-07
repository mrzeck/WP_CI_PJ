<?php
include 'top-bar-option.php';

function store_theme_top_bar_html( $ci ) { if( get_option('top_bar_public') == 1) include_once 'top-bar-html.php'; }

add_action('cle_header_top_bar', 'store_theme_top_bar_html');

function store_theme_top_bar_css() { include 'top-bar-css.php'; }

add_action('theme_custom_css_no_tag', 'store_theme_top_bar_css');