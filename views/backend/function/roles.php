<?php
function skd_admin_roles_group( $group ) {

    $group['general'] = array(
		'label' => __('Chung'),
		'capbilities' => array_keys(skd_admin_capbilities_general())
    );
    
    $group['page'] = array(
		'label' => __('Trang nội dung'),
		'capbilities' => array_keys(skd_admin_capbilities_page())
    );

    $group['post'] = array(
		'label' => __('Bài viết'),
		'capbilities' => array_keys(skd_admin_capbilities_post())
    );

    $group['gallery'] = array(
		'label' => __('Thư viện ảnh'),
		'capbilities' => array_keys(skd_admin_capbilities_gallery())
    );

    $group['theme'] = array(
		'label' => __('Giao diện'),
		'capbilities' => array_keys(skd_admin_capbilities_theme())
    );
    
    $group['setting'] = array(
        'label' => __('Hệ Thống'),
        'capbilities' => array_keys(skd_admin_capbilities_setting())
    );

    $group['plugin'] = array(
        'label' => __('Plugin'),
        'capbilities' => array_keys(skd_admin_capbilities_plugin())
    );
    
    $group['user'] = array(
		'label' => __('Thành viên'),
		'capbilities' => array_keys(skd_admin_capbilities_user())
    );

	return $group;
}

add_filter( 'user_role_editor_group', 'skd_admin_roles_group', 1 );

function skd_admin_roles_label( $label ) {

	$label = array_merge( $label, skd_admin_capbilities_general() );
	$label = array_merge( $label, skd_admin_capbilities_theme() );
	$label = array_merge( $label, skd_admin_capbilities_setting() );
	$label = array_merge( $label, skd_admin_capbilities_post() );
	$label = array_merge( $label, skd_admin_capbilities_page() );
	$label = array_merge( $label, skd_admin_capbilities_gallery() );
	$label = array_merge( $label, skd_admin_capbilities_user() );
	$label = array_merge( $label, skd_admin_capbilities_plugin() );

	return $label;
}

add_filter( 'user_role_editor_label', 'skd_admin_roles_label', 1 );

/**
 * Quyền chung
 */
function skd_admin_capbilities_general() {

    $label['loggin_admin']       = 'Đăng nhập admin';

	return apply_filters( 'skd_admin_capbilities_general', $label );
}

/**
 * Quyền cấu hình theme
 */
function skd_admin_capbilities_theme() {

	$label['edit_themes'] 		 = 'Giao diện';
	$label['switch_themes'] 	 = 'Giao diện : Thay đổi theme';
	$label['update_themes'] 	 = 'Giao diện : Cập nhật theme';
	$label['delete_themes'] 	 = 'Giao diện : Xóa theme';
	$label['install_themes'] 	 = 'Giao diện : Cài đặt theme';

	$label['edit_theme_options'] = 'Giao diện : cấu hình';
	$label['edit_theme_menus']   = 'Giao diện : menu';
	$label['edit_theme_widgets'] = 'Giao diện : widget';
	$label['edit_theme_editor']  = 'Giao diện : Trình editor';

	return apply_filters( 'skd_admin_capbilities_theme', $label );
}

/**
 * Quyền cấu hình hệ thống
 */
function skd_admin_capbilities_setting() {

	$label['edit_setting']          = 'Hệ thống';
	$label['edit_smtp']             = 'Cấu hình SMTP';
	$label['edit_cms_status']       = 'Cấu hình trạng thái hệ thống'; //@singe 3.0.0
	$label['edit_setting_cache']    = 'Quản lý cache';
	$label['edit_setting_audit']    = 'Quản lý nhật ký hoạt động';
    $label['edit_setting_tinymce']  = 'Cấu hình trình soạn thảo';
    
	return apply_filters( 'skd_admin_capbilities_setting', $label );
}

/**
 * Quyền cấu hình bài viết
 */
function skd_admin_capbilities_post() {

	$label['manage_categories']  = 'Quản lý danh mục bài viết';
	$label['delete_categories']  = 'Xóa danh mục bài viết';
	$label['view_posts']        = 'Xem bài viết'; //@singe 3.0.0
	$label['add_posts']          = 'Thêm bài viết'; //@singe 3.0.0
	$label['edit_posts']         = 'Sửa bài viết';
	$label['delete_posts']       = 'Xóa bài viết';
	
	return apply_filters( 'skd_admin_capbilities_post', $label );
}

/**
 * Quyền cấu hình bài viết
 */
function skd_admin_capbilities_page() {

	$label['view_pages']         = 'Xem trang nội dung'; //@singe 3.0.0
	$label['add_pages']          = 'Thêm trang nội dung'; //@singe 3.0.0
	$label['edit_pages']         = 'Sửa trang nội dung';
	$label['delete_pages']       = 'Xóa trang nội dung';
	
	return apply_filters( 'skd_admin_capbilities_page', $label );
}

/**
 * Quyền cấu hình thư viện
 */
function skd_admin_capbilities_gallery() {

	$label['edit_gallery']    		= 'Quản lý thư viện';
	$label['delete_gallery']    	= 'Xóa thư viện';

	return apply_filters( 'skd_admin_capbilities_gallery', $label );
}

/**
 * Quyền cấu hình plugin
 */
function skd_admin_capbilities_plugin() {

	$label['edit_plugins']     = 'Plugin : quản lý';
	$label['install_plugins']  = 'Plugin : cài đặt';
	$label['update_plugins']   = 'Plugin : cập nhật';
	$label['activate_plugins'] = 'Plugin : kích hoạt';
	$label['delete_plugins']   = 'Plugin : Xóa';

	return apply_filters( 'skd_admin_capbilities_plugin', $label );
}

/**
 * Quyền cấu hình user
 */
function skd_admin_capbilities_user() {

	$label['edit_users']         = 'Cập nhật user';
	$label['create_users']       = 'Tạo user';
	$label['list_users']         = 'Xem danh sách user';
	$label['remove_users']       = 'Xóa user';
	$label['delete_users']       = 'Xóa vĩnh viển user';

	return apply_filters( 'skd_admin_capbilities_user', $label );
}