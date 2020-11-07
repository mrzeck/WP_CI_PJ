<?php
class widget_facebook_fanpage extends widget {

    function __construct() {
        parent::__construct('widget_facebook_fanpage', 'Facebook Fanpage');
    }

    function widget( $option ) {

        $param['height']    = $option->fb_height.'px';

        $param['small']     = 'false';

        $param['cover']     = 'false';

        $param['tab']       = 'timeline';

        $box = $this->container_box('widget_facebook_fanpage', $option);

        echo $box['before'];

        ?>
        <?php if($this->name != '') {?> <div class="header-title"><h3 class="header"><?= $this->name;?></h3></div> <?php } ?>
        
        <div class="fb-page"
            data-height="<?=$param['height'];?>" 
            data-href="<?= get_option('social_facebook');?>&__mref=message_bubble" 
            data-tabs="<?= $param['tab'];?>" data-small-header="false" 
            data-adapt-container-width="true" 
            data-hide-cover="<?= $param['cover'];?>" 
            data-show-facepile="true">
            <div class="fb-xfbml-parse-ignore">
                <blockquote cite="<?= get_option('social_facebook');?>&__mref=message_bubble">
                    <a href="<?= get_option('social_facebook');?>&__mref=message_bubble">FACEBOOK</a>
                </blockquote>
            </div>
        </div>
        <?php

        echo $box['after'];
    }

    function form( $left = array(), $right = array()) {
        
        $left[] = array("field"=>"fb_height", "label"=>"Chiều cao", "type"=>"number", "value" => '300');

        $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>12);

        $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>12);

        $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>12);

        parent::form($left, $right);
    }
}

register_widget('widget_facebook_fanpage');
