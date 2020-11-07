<?php
/**
Plugin name     : Demo data
Plugin class    : demo_data
Plugin uri      : http://vitechcenter.com
Description     : Tạo dữ liệu demo cho website
Author          : Hữu Trọng
Version         : 1.0.0
*/
define( 'DMD_NAME', 'demo-data' );

define( 'DMD_PATH', plugin_dir_path( DMD_NAME ) );

class demo_data {

    private $name = 'demo_data';
    
    public  $ci;

    public function active() {
    	$ci = &get_instance();
    }

    public function uninstall() {
    	$ci = &get_instance();
    }
}

if( is_admin() ) include 'demo-data-admin.php';
