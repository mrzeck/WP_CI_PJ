<?php
if(is_admin()) {

    include 'theme-system-ajax.php';

    function theme_admin_system( $tabs ) {

        $tabs['theme-social']   = ['label' => 'Mạng xã hội', 	'callback' => 'skd_system_theme_social',    'icon' => '<i class="fal fa-users"></i>'];

        $tabs['theme-gallery']  = ['label' => 'Gallery',        'callback' => 'skd_system_theme_gallery',   'icon' => '<i class="fal fa-images"></i>', 'root' => true];
        
        $tabs['theme-seo']      = ['label' => 'Seo',            'callback' => 'skd_system_theme_seo',   'icon' => '<i class="fal fa-megaphone"></i>'];
        
        return $tabs;
    }

    add_filter( 'skd_system_tab' , 'theme_admin_system' );

    if( !function_exists('skd_system_theme_social') ) {

        function skd_system_theme_social($ci, $tab) {

            $socials = get_theme_social();

            include 'html/system-theme-social.php';
        }
    }

    if( !function_exists('skd_system_theme_gallery') ) {

        function skd_system_theme_gallery($ci, $tab) {

            include 'html/system-theme-gallery.php';
        }
    }

    if( !function_exists('skd_system_theme_seo') ) {

        function skd_system_theme_seo($ci, $tab) {

            include 'html/system-theme-seo.php';
        }
    }
}