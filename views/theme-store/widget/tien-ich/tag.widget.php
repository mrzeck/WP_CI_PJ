<?php
class widget_tag extends widget {

    function __construct() {

        parent::__construct('widget_tag', 'Tag nội dung');

        $this->tags = array('sidebar', 'footer');
        
    }

    function widget( $option ) {

        if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            if($ci->language['current'] != $ci->language['default']) {

                if(isset($option->{'content_'.$ci->language['current']})) $option->content = $option->{'content_'.$ci->language['current']};
            }
        }
        
        $box = $this->container_box('widget_tag', $option);

        echo $box['before'];
        ?>
        <div class="box-cointent">
           <div class="header-title"><h2 class="header"><?= $this->name;?>:</h2></div>
           <?php echo $option->content;?>
       </div>
     <style>
        .widget_tag .header-title{display: inline-table;float:left;line-height: 25px;margin-right:5px;}
        .widget_tag .header-title h2.header{margin:0;font-size:14px;   line-height: 25px;}
        .widget_tag a{background-color:transparent;color:#62A8EA;margin-right:5px;padding:5px 10px;border-radius:5px;    line-height: 25px;}
        .widget_tag a:hover{background-color:#263A53;color:#fff;}
         .widget_tag  .box-cointent{padding:0 15px}
    </style>
    <?php
    echo $box['after'];
}

function form( $left = array(), $right = array()) {

    $left[]  = array("field"=>"content", "label"=>"Nội dung", "type"=>"wysiwyg");

    $left[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
    $left[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
    $right[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');
    $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');

    parent::form($left, $right);
}
}

register_widget('widget_tag');