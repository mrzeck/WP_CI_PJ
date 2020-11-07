<?php
function user_role_editor_label() {

	$label = [];

	if(version_compare( '3.0.0', cms_info('version')) < 0 ) {

		$label = user_role_editor_label_2x();
	}
	
	$label = apply_filters( 'user_role_editor_label', $label );

	return $label;
}

function user_role_editor_group() {

	$group = [];

	if(version_compare( '3.0.0', cms_info('version')) < 0 ) {

		$label = user_role_editor_group_2x();
	}

	$group = apply_filters( 'user_role_editor_group', $group );

	return $group;
}
//Add quyền vào admin
function user_role_editor_capbilities_user($label) {

	$label['role_editor']         = 'Phân quyền cho nhóm';
	
	$label['role_editor_user']    = 'Phân quyền cho user';

	return $label;
}

add_filter( 'skd_admin_capbilities_user', 'user_role_editor_capbilities_user' );

//Version 2.x.x
function user_role_editor_label_2x() {

	$label = [];

	$label['loggin_admin']       = 'Đăng nhập admin';

	$label['edit_themes'] 		 = 'Giao diện';
	$label['switch_themes'] 	 = 'Giao diện : Thay đổi theme';
	$label['update_themes'] 	 = 'Giao diện : Cập nhật theme';
	$label['delete_themes'] 	 = 'Giao diện : Xóa theme';
	$label['install_themes'] 	 = 'Giao diện : Cài đặt theme';

	$label['edit_theme_options'] = 'Giao diện : cấu hình';
	$label['edit_theme_menus']   = 'Giao diện : menu';
	$label['edit_theme_widgets'] = 'Giao diện : widget';
	$label['edit_theme_editor']  = 'Giao diện : Trình editor';

	$label['edit_plugins']     = 'Plugin : quản lý';
	$label['install_plugins']  = 'Plugin : cài đặt';
	$label['update_plugins']   = 'Plugin : cập nhật';
	$label['activate_plugins'] = 'Plugin : kích hoạt';
	$label['delete_plugins']   = 'Plugin : Xóa';

	
	$label['manage_categories']  = 'Quản lý danh mục bài viết';
	$label['delete_categories']  = 'Xóa danh mục bài viết';

	$label['edit_posts']         = 'Xem / sửa bài viết';
	$label['delete_posts']       = 'Xóa bài viết';
	
	$label['edit_pages']         = 'Xem / sửa trang nội dung';
	$label['delete_pages']       = 'Xóa trang nội dung';
	
	$label['edit_users']         = 'Cập nhật user';
	$label['create_users']       = 'Tạo user';
	$label['list_users']         = 'Xem danh sách user';
	$label['remove_users']       = 'Xóa user';
	$label['delete_users']       = 'Xóa vĩnh viển user';

	$label['role_editor']         = 'Phân quyền cho nhóm';
	$label['role_editor_user']    = 'Phân quyền cho user';

	$label['edit_gallery']    		= 'Quản lý thư viện';
	$label['delete_gallery']    	= 'Xóa thư viện';

	if( version_compare( '2.4.1', cms_info('version')) != 1 ) {

		$label['edit_setting'] = 'Hệ thống';
		$label['edit_smtp'] = 'Cấu hình SMTP';
	}

	if( version_compare( '2.4.2', cms_info('version')) != 1 ) {

		$label['edit_setting_cache'] = 'Quản lý cache';
		$label['edit_setting_audit'] = 'Quản lý nhật ký hoạt động';
		$label['edit_setting_tinymce'] = 'Cấu hình trình soạn thảo';
	}

	return $label;
}

function user_role_editor_group_2x() {

	$group['general'] = array(
		'label' => __('Chung'),
		'capbilities' => array(
			'loggin_admin',
		)
	);

	$group['theme'] = array(
		'label' => __('Giao diện'),
		'capbilities' => array(
			'edit_themes',
			'edit_theme_options',
			'edit_theme_menus',
			'edit_theme_widgets',
		)
	);

	if( version_compare( '2.4.1', cms_info('version')) != 1 ) {
		$group['setting'] = array(
			'label' => __('Hệ Thống'),
			'capbilities' => array(
				'edit_setting',
				'edit_smtp',
				'edit_setting_cache',
				'edit_setting_audit',
				'edit_setting_tinymce'
			)
		);
	}

	$group['post'] = array(
		'label' => __('Bài viết'),
		'capbilities' => array(
			'manage_categories',
			'delete_categories',
			'edit_posts',
			'delete_posts',
		)
	);

	$group['page'] = array(
		'label' => __('Trang nội dung'),
		'capbilities' => array(
			'edit_pages',
			'delete_pages',
		)
	);

	$group['gallery'] = array(
		'label' => __('Thư viện ảnh'),
		'capbilities' => array(
			'edit_gallery',
			'delete_gallery',
		)
	);

	$group['user'] = array(
		'label' => __('Thành viên'),
		'capbilities' => array(
			'edit_users',
			'create_users',
			'list_users',
			'remove_users',
			'role_editor',
			'role_editor_user',
		)
	);

	$user = get_user_current();

	if( is_super_admin($user->id) ) {

		$group['theme']['capbilities'][] = 'switch_themes';
		$group['theme']['capbilities'][] = 'update_themes';
		$group['theme']['capbilities'][] = 'delete_themes';
		$group['theme']['capbilities'][] = 'install_themes';
		$group['theme']['capbilities'][] = 'edit_theme_editor';

		$group['user']['capbilities'][] = 'delete_users';

		$group['plugin'] = array(
			'label' => __('Plugin'),
			'capbilities' => array(
				'edit_plugins',
				'install_plugins',
				'update_plugins',
				'activate_plugins',
				'delete_plugins',
			)
		);
	}

	return $group;
}

