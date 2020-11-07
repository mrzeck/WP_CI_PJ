<?php
$chedo=get_option('general_public_km');
$star=get_option('general_day_sta');
$end=get_option('general_day_end');
$date=time();
if (strtotime($star) < $date  && $date  < strtotime($end) ) {
        if ($chedo==0) {
               $ci =& get_instance();
               $model      = get_model('home');
               $model->settable('system');
               update_option('general_public_km',1);
               delete_cache('system');
               
       }

}else{
        if ($chedo==1) {
                $ci =& get_instance();
                $model      = get_model('home');
                $model->settable('system');
                update_option('general_public_km',0);
                delete_cache('system');
                
        }
        
}
include_once 'mobile-header.php';

do_action('cle_header_desktop', $ci);
