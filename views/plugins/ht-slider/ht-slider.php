<?php
/**
Plugin name     : HT Slider
Plugin class    : HTSlider
Plugin uri      : http://vitechcenter.com
Description     : Tạo và quản lý slider với nhiều hiệu ứng chuyển động.
Author          : SKDSoftware Dev Team
Version         : 1.0.9
*/
define( 'HTSlider_NAME', 'ht-slider' );

define( 'HTSlider_URL', URL_ADMIN.'/plugins?page='.HTSlider_NAME.'&view=' );

class HTSlider {

    private $name = 'HTSlider';

    public  $ci;

    function __construct() {
        $this->ci =&get_instance();
    }

    //active plugin
    public function active() {

    	$model = get_model('plugins', 'backend');
    }

    //Gở bỏ plugin
    public function uninstall() {

    	$model = get_model('plugins', 'backend');

    	//Xóa slider đã tạo
    	$model->settable('group');

    	$model->delete_where(array('object_type' => 'ht-slider'));

    	//Xóa slider item đã tạo
    	$model->settable('gallerys');

    	$model->delete_where(array('object_type' => 'ht-slider'));
    }
}

include 'ht-slider-function.php';

include 'ht-slider-ajax.php';

include 'ht-slider-admin.php';



