<?php 
if( !function_exists('wcmc_product_delete_capche') ) {

    function wcmc_product_delete_capche( $module ) {

        if( $module == 'products_categories') delete_cache( 'products_', true );
    }

    //xóa cache khi xóa danh mục
    add_action('ajax_delete_before_success',    'wcmc_product_delete_capche', 10, 1);

    //xóa cache khi up hiển thị
    add_action('up_boolean_success',            'wcmc_product_delete_capche', 10, 1);

    //xóa cache khi up thứ tự
    add_action('up_table_success',              'wcmc_product_delete_capche', 10, 1);
}

//Xóa dữ liệu dữ liệu khi save product
if( !function_exists('wcmc_product_save_delete_capche') ) {
    
    function wcmc_product_save_delete_capche($product_id, $model) {

        $ci =& get_instance();

        if( $ci->data['module'] == 'products_categories' )
            wcmc_product_delete_capche( 'products_categories' );

    }

    add_action('save_object', 'wcmc_product_save_delete_capche', '', 2);
}

if( !function_exists('wcmc_setting_cache_manager') ) {
    
    function wcmc_setting_cache_manager( $cache ) {

        $cache['product_category'] = array(
            'label' => 'Clear product category: Xóa dữ liệu cache danh mục sản phẩm.',
            'btnlabel' => 'Xóa cache product category',
            'color'=> 'green',
            'callback' => 'wcmc_setting_cache_product_category'
        );

        return $cache;
    }

    add_filter('cache_manager_object', 'wcmc_setting_cache_manager', 1);
}

if( !function_exists('wcmc_setting_cache_product_category') ) {
    
    function wcmc_setting_cache_product_category( ) {
        delete_cache('products_categories_', true);
        delete_cache('products_category_', true);
    }
}