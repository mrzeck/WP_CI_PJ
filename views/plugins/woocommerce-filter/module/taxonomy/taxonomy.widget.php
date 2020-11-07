<?php
class widget_product_taxonomy_filter extends widget {
    function __construct() {
        parent::__construct('widget_product_taxonomy_filter', 'Tìm Kiếm Theo Taxonomy');
        $this->tags = array('sidebar', 'footer');
    }
    function widget( $option ) {
        ?>
        <div class="sidebar-title">
            <h3><?php echo $this->name;?></h3>
        </div>
        <?php
        woocommerce_filter_taxonomy_html( $option->taxonomy_key );
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
function register_widget_product_taxonomy_filter() {
    register_widget('widget_product_taxonomy_filter');
}
add_action('init', 'register_widget_product_taxonomy_filter', 100 );