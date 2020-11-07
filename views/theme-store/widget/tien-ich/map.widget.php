<?php
class widget_map extends widget {

    function __construct() {
        parent::__construct('widget_map', 'Bản đồ');
    }

    function form( $left = array(), $right = array()) {

        $left[] = array("field"=>"map_code", "label"=>"Mã nhúng", "type"=>"textarea");

        $left[] = array("field"=>"map_height", "label"=>"Chiều cao", "type"=>"number", "value" => '300');

        $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>12);

        $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>12);

        $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>12);

        parent::form($left, $right);
    }

    function widget( $option ) {
        
        if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            if($ci->language['current'] != $ci->language['default']) {

                if(isset($option->{'title_'.$ci->language['current']})) $this->name = $option->{'title_'.$ci->language['current']};
            }
        }

        if(empty($option->map_code)) $option->map_code = get_option('maps_embed');
        
        $box = $this->container_box('widget_map', $option);
        
        echo $box['before'];
        ?>
        <?php if($this->name != '') {?> <div class="header-title"><h3 class="header"><?= $this->name;?></h3></div> <?php } ?>
        <?php echo $option->map_code;?>
        <style type="text/css"> .widget_map iframe { height:<?php echo $option->map_height;?>px;  } </style>
        <?php
        echo $box['after'];
    }
}

register_widget('widget_map');