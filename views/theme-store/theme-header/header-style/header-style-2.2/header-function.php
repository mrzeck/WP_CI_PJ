<?php
include 'header-option.php';

function store_theme_header_html( $ci ) {
	?>
	<header class="hidden-xs">
		<!-- top bar -->
        <?php
            do_action('cle_header_top_bar', $ci);
            include_once 'header-html.php';
            do_action('cle_header_navigation', $ci);
        ?>
	</header>
	<?php
}

add_action('cle_header_desktop', 'store_theme_header_html');
/**
 * REGISTER MENU
 */
function store_theme_header_register_navigation() {

    register_nav_menus(['main-nav' => 'Menu Chính','share-nav' => 'Menu chia sẻ kinh nghiệm']);
}

add_action('init', 'store_theme_header_register_navigation');
/**
 * INCLUDE CSS FILE
 */
function store_theme_header_css() { include 'header-css.php'; }

add_action('theme_custom_css_no_tag', 'store_theme_header_css');

/**
 * INCLUDE SCRIPT FILE
 */
function store_theme_header_script() { include 'header-script.php'; }

add_action('theme_custom_script_no_tag', 'store_theme_header_script');