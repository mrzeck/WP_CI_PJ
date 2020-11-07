<?php
class widget_attribute_filter extends widget {
    function __construct() {
        parent::__construct('widget_attribute_filter', 'Tìm Kiếm Theo Tùy Chọn');
        $this->tags = array('sidebar', 'footer');
    }
    function widget( $option ) {
        ?>
        <div class="sidebar-title"><h3><?php echo $this->name;?></h3></div>
        <div class="wcmc-filter-list">
        <?php
        woocommerce_filter_attribute_html( $option->attribute_id );
        ?>
        </div>
        <?php 
    }
    function form( $left = array(), $right = array()) {
        $data = woocommerce_options_gets();
        $_listAttribute = array();
        foreach ($data as $value) {
            $_listAttribute[$value->id] = $value->title;
        }
        $left[] = array('field' => 'attribute_id', 'label' =>'Tùy Chọn', 'type' => 'select', 'options' => $_listAttribute);
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');
        parent::form($left, $right);
    }
}
register_widget('widget_attribute_filter');