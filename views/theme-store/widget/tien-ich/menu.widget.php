<?php
class widget_menu extends widget {
    function __construct() {
        parent::__construct('widget_menu', 'Menu');
    }
    function widget( $option ) {
        $box = $this->container_box('widget_menu', $option);
        echo $box['before'];
        ?>
        <div class="menu_inner">
        <?php if($this->name != '') {?> <div class="header-title"><h3 class="header"><?= $this->name;?></h3></div> <?php } ?>
        <ul> <?php echo cle_nav_menu(array( 'theme_id' => $option->menu, 'walker' => 'store_bootstrap_nav_menu'));?> </ul>
        </div>
        <style>
            .widget_menu{text-align:center;}
            .widget_menu .menu_inner{display: inline-table;float:none;text-align:left;}
            @media(max-width:991px){
              .widget_menu{text-align:left;}  
            }
        </style>
        <?php
        echo $box['after'];
    }
    function form( $left = array(), $right = array()) {
        $left[] = array("field"=>"menu", "label"=>"Menu", "type"=>"menu");
        $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>12);
        $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>12);
        $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>12);
        parent::form( $left, $right );
    }
}
register_widget('widget_menu');