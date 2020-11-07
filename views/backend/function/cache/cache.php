<?php
include_once 'cache-manager-ajax.php';

function cache_manager_object() {
	$cache = array(
		'cms' => array(
			'label' => 'Clear CMS caching: database caching, static blocks... Chạy lệnh này nếu bạn không thấy sự thay đổi khi cập nhật dữ liệu.',
			'btnlabel' => 'Clear All CMS Cache',
			'color'=> 'red'
		),
		'option' => array(
			'label' => 'Clear CMS option: Xóa cache các option cấu hình của website',
			'btnlabel' => 'Xóa cache cấu hình website',
			'color'=> 'green'
		),
		'widget' => array(
			'label' => 'Clear CMS widget: Xóa cache widget, server widget',
			'btnlabel' => 'Xóa cache widget',
			'color'=> 'green'
		),
		'gallery' => array(
			'label' => 'Clear CMS gallery: Xóa cache gallery bài viết, sản phẩm, trang...',
			'btnlabel' => 'Xóa cache gallery',
			'color'=> 'green'
		),
		'category' => array(
			'label' => 'Clear CMS category: Xóa cache danh mục bài viết',
			'btnlabel' => 'Xóa cache category',
			'color'=> 'green'
		),
		'user' => array(
			'label' => 'Clear CMS user: Xóa cache thông tin thành viên',
			'btnlabel' => 'Xóa cache user',
			'color'=> 'green'
		),
		'metadata' => array(
			'label' => 'Clear CMS metadata: Xóa cache dữ liệu metadata',
			'btnlabel' => 'Xóa cache metadata',
			'color'=> 'green'
		),
	);

	return apply_filters('cache_manager_object', $cache );
}

function cache_manager() {

	$cache = cache_manager_object();

	include_once 'cache-manager-view.php';
}

//Xóa cache khi lưu taxonomy
if( !function_exists('categories_save_delete_capche') ) {
    
    function categories_save_delete_capche( $table ) {

        $ci =& get_instance();

        if( $table == 'categories' ) delete_cache( 'post_category_', true );
    }

	add_action('up_table_success', 'categories_save_delete_capche', 10, 1);
	
    add_action('up_boolean_success', 'categories_save_delete_capche', 10, 1);
}