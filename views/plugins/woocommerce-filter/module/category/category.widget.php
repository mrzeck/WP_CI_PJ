<?php
class widget_product_category_filter extends widget {
    function __construct() {
        parent::__construct('widget_product_category_filter', 'Tìm Kiếm danh mục');
        $this->tags = array('sidebar', 'footer');
    }
    function widget( $option ) {
        ?>
        <div class="sidebar-title"><h3><?php echo $this->name;?></h3></div>
        <?php
        woocommerce_filter_category_html( $option->category_id );
    }
    function form( $left = array(), $right = array()) {
        $left[] = array('field' => 'category_id', 'label' =>'Danh mục sản phẩm', 'type' => 'product_categories');
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');
        parent::form($left, $right);
    }
}
//register_widget('widget_product_category_filter');