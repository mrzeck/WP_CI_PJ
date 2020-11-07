<?php
class widget_price_filter extends widget {

    function __construct() {

        parent::__construct('widget_price_filter', 'Tìm Kiếm Theo Giá');

        $this->tags = array('sidebar', 'footer');
        
    }

    function widget( $option ) {
        ?>
        <div class="wcmc-filter-price">
        <div class="sidebar-title"><h3><?php echo $this->name;?></h3></div>
        <?php
        woocommerce_filter_price_html();
        ?>
        </div>
        <?php
    }

    function form( $left = array(), $right = array()) {

        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');

        parent::form($left, $right);
    }
}

register_widget('widget_price_filter');