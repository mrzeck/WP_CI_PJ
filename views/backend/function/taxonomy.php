<?php
register_post_type('post', 
	array(
		'labels' => array(
            'name'          => 'Bài viết',
            'singular_name' => 'Bài viết',
        ),
        'public' => true,
        'capibilitie' => array(
            'view'      => 'view_posts',
            'add'       => 'add_posts',
            'edit'      => 'edit_posts',
            'delete'    => 'delete_posts',
        ),
	)
);


register_cate_type('post_categories', 'post',
	array(
        'labels' => array(
            'name'          => 'Chuyên mục bài viết',
            'singular_name' => 'Chuyên mục',
        ),
        'public' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'parent' => true,
        'capibilitie' => array(
            'edit'      => 'manage_categories',
            'delete'    => 'delete_categories',
        ),
    )
);

if( !function_exists('post_categories_delete_cache') ) {

    function post_categories_delete_cache( $module ) {

        if( $module == 'post_categories') delete_cache( 'post_category_', true );
    }

    //xóa cache khi xóa danh mục
    add_action('ajax_delete_before_success',    'post_categories_delete_cache', 10, 1);

    //xóa cache khi up hiển thị
    add_action('up_boolean_success',            'post_categories_delete_cache', 10, 1);

    //xóa cache khi up thứ tự
    add_action('up_table_success',              'post_categories_delete_cache', 10, 1);
}

//Xóa dữ liệu dữ liệu khi save product
if( !function_exists('post_categories_save_delete_cache') ) {
    
    function post_categories_save_delete_cache($product_id, $model) {

        $ci =& get_instance();

        if( $ci->data['module'] == 'post_categories' )
            post_categories_delete_cache( 'post_categories' );

    }

    add_action('save_object', 'post_categories_save_delete_cache', '', 2);
}