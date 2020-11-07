<?php
class widget_product_thuonghieu_filter extends widget {
    function __construct() {
        parent::__construct('widget_product_thuonghieu_filter', 'Tìm Kiếm Theo thương hiệu');
        $this->tags = array('sidebar', 'footer');
    }
    function widget( $option ) {
        ?>
        <div class="wcmc-filter-thuonghieu">
        <div class="sidebar-title">
            <h2><?php echo $this->name;?></h2>
        </div>
        <?php
        woocommerce_filter_thuonghieu_html( $option->taxonomy_key );
        ?>
        </div>
        <?php
    }
    function form( $left = array(), $right = array()) {
        $ci =& get_instance();
        $taxonomy = array();
        foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
            if( $taxonomy_value['post_type'] == 'products' ) {
                $taxonomy[$taxonomy_key] = $taxonomy_value['labels']['name'];
            }
        }
        $left[] = array('field' => 'taxonomy_key', 'label' =>'Taxonomy', 'type' => 'select', 'options' => (array)$taxonomy);
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');
        parent::form($left, $right);
    }
}
function register_widget_product_thuonghieu_filter() {
    register_widget('widget_product_thuonghieu_filter');
}
add_action('init', 'register_widget_product_thuonghieu_filter', 100 );