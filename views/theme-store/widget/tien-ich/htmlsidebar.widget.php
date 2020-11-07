<?php
class widget_html_sidebar extends widget {

    function __construct() {

        parent::__construct('widget_html_sidebar', 'Nội Dung');

        $this->tags = array('sidebar', 'footer');
        
    }

    function widget( $option ) {

        if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            if($ci->language['current'] != $ci->language['default']) {

                if(isset($option->{'content_'.$ci->language['current']})) $option->content = $option->{'content_'.$ci->language['current']};
            }
        }
        
        $box = $this->container_box('widget_html', $option);

         echo $box['before'];
        ?>
        <?php if($this->name != '') {?> <div class="header-title"><h3 class="header"><?= $this->name;?></h3></div> <?php } ?>
        <?php echo $option->content;?>
        <?php
         echo $box['after'];
    }

    function form( $left = array(), $right = array()) {

        $left[]  = array("field"=>"content", "label"=>"Nội dung", "type"=>"wysiwyg");

        $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>12, 'args' => array('max' => 13));

        $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>12, 'args' => array('max' => 13));

        $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>12, 'args' => array('max' => 13));

        parent::form($left, $right);
    }
}

register_widget('widget_html_sidebar');