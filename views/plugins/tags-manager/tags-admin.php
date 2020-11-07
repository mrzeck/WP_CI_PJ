<?php
if( !function_exists('tags_admin_assets') ) {

    function tags_admin_assets() {

		$ci =& get_instance();

		admin_register_script('tags', TAG_PATH.'assets/js/tags-script.js');
		
        admin_register_style('tags', TAG_PATH.'assets/css/tags-style.css');
    }

    add_action('cle_enqueue_script',    'tags_admin_assets');
}

include 'admin/tags-metabox.php';

include 'admin/tags-menu.php';