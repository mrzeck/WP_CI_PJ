<?php
if( !function_exists('theme_system_social_save') ) {

	function theme_system_social_save($result, $data) {

        $socials = get_theme_social();
        
        foreach ($socials as $key => $social) {

            $field = $social['field'];
            
            update_option( $field , (isset($data[$field])) ? removeHtmlTags($data[$field]) : '' );
        }

		return $result;
	}

	add_filter('system_theme_social_save','theme_system_social_save',10,2);
}

if( !function_exists('theme_system_gallery_save') ) {

	function theme_system_gallery_save($result, $data) {

		update_option( 'gallery_template_support' , $data['gallery_template_support'] );

		return $result;
	}

	add_filter('system_theme_gallery_save','theme_system_gallery_save',10,2);
}

if( !function_exists('theme_system_seo_save') ) {

	function theme_system_seo_save($result, $data) {

        $seo_input = get_theme_seo_input();
        
        foreach ($seo_input as $key => $input) {

            $field = $input['field'];
            
            update_option( $field , (isset($data[$field])) ? $data[$field] : '' );
        }

		return $result;
	}

	add_filter('system_theme_seo_save','theme_system_seo_save',10,2);
}