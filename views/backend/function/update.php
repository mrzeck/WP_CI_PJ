<?php
function admin_notices_update_version() {

	global $skd_version; 

	global $skd_db_version;

	$ci = &get_instance();

	if( !is_super_admin() ) return false;

	//kiểm tra cms
	$ci->load->library('service_api');

	if( empty($_SESSION['cms_version'])) {

		$_SESSION['cms_version'] = $ci->service_api->cms_version();

		$_SESSION['cms_version_time'] 	= time();
	}

	$version = $_SESSION['cms_version'];

	if( $version != 'error' ):

		if ( version_compare( $version, $skd_version ) === 1 ):
			echo notice('info', '<a href="'.URL_ADMIN.'/home/update_core"><b style="font-size: 18px; font-weight: bold; text-align:left">Đã có phiên bản CMS mới.</b> bạn có thể cập nhật ngay bây giờ.</a>');
		endif;

		//kiểm tra plugins
		$pl = get_plugins();

		foreach ($pl as $plc) {

			if( empty($_SESSION['cms_plugins'][$plc['name']])) $_SESSION['cms_plugins'][$plc['name']] = $ci->service_api->plugin_version($plc['name']);

			$version = $_SESSION['cms_plugins'][$plc['name']];

			if( !have_posts($version) && version_compare($version, $plc['version']) === 1 ) {
				echo notice('warning', 'Plugins '.$plc['label'].' đã có phiên bản mới! click vào đây để cập nhật ngay');
			}
		}

	endif;
}

add_action('admin_notices', 'admin_notices_update_version');

function update_core() {

	if( empty(get_user_meta( 1, 'capabilities' )) && empty(get_user_meta( 2, 'capabilities' )) )  {

		populate_roles_220();

		update_user_meta( 1, 'capabilities', serialize(array('root' => 1)) );

		update_user_meta( 2, 'capabilities', serialize(array('administrator' => 1)) );
	}

	if( is_admin() && is_user_logged_in() ) {

		global $skd_version; 

		global $skd_db_version;

		global $skd_version;

		//kiểm tra database
		$db_version 	= get_option('db_version');

		$upcore = false;

		if ( version_compare( $skd_db_version, $db_version ) === 1 ) {

			database_update( $db_version );

			$upcore = true;
		}

		//kiểm tra database
		$roles_version = get_option('roles_version');

		if ( version_compare( $skd_version, $roles_version ) === 1 ) {

			populate_roles_update( $roles_version );

			$upcore = true;
		}

		//File upload
		$cms_version = get_option('cms_version');

		if ( version_compare( $skd_version, $cms_version ) === 1 ) {

			file_cms_update( $cms_version );

			$upcore = true;
		}

		if( $upcore == true ) {

			update_option('cms_version', $skd_version );
			
			redirect( admin_url('home/about') );
		}
	}
}

add_action('init', 'update_core');

function database_update( $database_version = 0 ) {

	$list_db_version = array( '2018.1', '2018.2', '2019.1' );

	$model = get_model('plugins', 'backend');

	foreach ($list_db_version as $db ) {

		if( version_compare( $db, $database_version ) == 1 ) {

			$database_update_function = 'database_update_'.str_replace('.','_',$db);

			if(function_exists($database_update_function)) $database_update_function( $model );
		}
	}
}

function database_update_2018_1( $model = null ) {

	if( $model == null ) $model = get_model('plugins', 'backend');

	if( !$model->db_table_exists('users_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."users_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		update_user_meta( 1, 'capabilities', serialize(array('root' => 1)) );

    	update_user_meta( 2, 'capabilities', serialize(array('administrator' => 1)) );
	}

	$users = gets_user();

	foreach ($users as $user) {
		if( isset($user->address) ) update_user_meta( $user->id, 'address', $user->address);
		if( isset($user->birthday) ) update_user_meta( $user->id, 'birthday', $user->birthday);
	}

	if( $model->db_field_exists('info', 'users') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."users` DROP `info`");
	}

	if( $model->db_field_exists('avatar', 'users') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."users` DROP `avatar`");
	}

	if( $model->db_field_exists('address', 'users') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."users` DROP `address`");
	}

	if( $model->db_field_exists('birthday', 'users') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."users` DROP `birthday`");
	}

	if( $model->db_field_exists('key', 'categories') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."categories` DROP `key`");
	}

	update_option('db_version', '2018.1' );	
}

function database_update_2018_2( $model = null ) {

	if( $model == null ) $model = get_model('plugins', 'backend');

	if( !$model->db_table_exists('gallerys_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."gallerys_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	$user = get_user_current();

	if( isset($user->group_id) && !isset($user->status) ) {

		$model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `group_id` `status` VARCHAR(50) NOT NULL");

		$model->query("UPDATE `".CLE_PREFIX."users` SET `status`='public'");

		$model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `status` `status` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public'");
	}

	$check['gallerys_metadata']      = false;

	$i = 0;

	while ( $check['gallerys_metadata'] == false && $i < 10 ) {

		if( $model->db_table_exists('gallerys_metadata') ) {

			$model->settable('gallerys');

			$gallerys = $model->gets_where();

			if( have_posts($gallerys) ) {

				foreach ($gallerys as $gallery) {
					
					$options = @unserialize($gallery->options);

					if( have_posts($options) ) {

						foreach ( $options as $meta_key => $meta_value ) {
							update_gallery_meta( $gallery->id, $meta_key, $meta_value );
						}
					}
				}
			}

			$i = 10;

			$check['gallerys_metadata'] = true;
		}

		$i++;
	}

	update_option('db_version', '2018.2' );
}

function database_update_2019_1( $model = null ) {

	if( $model == null ) $model = get_model('plugins', 'backend');

	$model->query("ALTER TABLE `".CLE_PREFIX."page` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");

	$model->query("ALTER TABLE `".CLE_PREFIX."post` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");

	$model->query("ALTER TABLE `".CLE_PREFIX."language` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");

	$model->query("ALTER TABLE `".CLE_PREFIX."system` ADD INDEX( `option_name`)");

	$model->query("ALTER TABLE `".CLE_PREFIX."page` ADD INDEX(`title`,`slug`)");

	$model->query("ALTER TABLE `".CLE_PREFIX."post` ADD INDEX(`title`,`slug`)");

	$model->query("ALTER TABLE `".CLE_PREFIX."routes` ADD INDEX(`slug`)");

	$model->query("ALTER TABLE `".CLE_PREFIX."relationships` ADD INDEX( `object_id`, `category_id`)");

	update_option('seo_favicon', get_option('seo_favicon'));

	update_option('seo_google_masterkey', get_option('seo_google_masterkey'));

	update_option('header_script', get_option('header_script'));

	update_option('footer_script', get_option('footer_script'));

	update_option('db_version', '2019.1');
}

//Phân quyền
function populate_roles_update( $roles_version = '' ) {

	$populate_roles_version = array( '2.2.0', '2.3.1', '2.4.1', '2.4.2', '3.0.0' );

	$upload_roles = false;

	foreach ($populate_roles_version as $role ) {

		if( version_compare( $role, $roles_version ) == 1 ) {

			$upload_roles = true;

			$populate_roles_function = 'populate_roles_'.str_replace('.','',$role);

			if(function_exists($populate_roles_function)) $populate_roles_function();

			update_option('roles_version', $role );	
		}
	}

	if( $upload_roles == false ) update_option( 'roles_version', cms_info('version') );
}

function populate_roles_220() {

	add_role('root', 'Root');

	add_role('administrator', 'Administrator');

	add_role('subscriber', 'Subscriber');

	$role = get_role('administrator');

	// Add caps for Root role
    $role = get_role('root');

    $role->add_cap('loggin_admin');

    $role->add_cap('switch_themes');
    $role->add_cap('edit_themes');
    $role->add_cap('update_themes');
    $role->add_cap('delete_themes');
    $role->add_cap('install_themes');

    $role->add_cap('edit_theme_options');
    $role->add_cap('edit_theme_menus');
    $role->add_cap('edit_theme_widgets');
    $role->add_cap('edit_theme_editor');

    $role->add_cap('activate_plugins');
    $role->add_cap('edit_plugins');
    $role->add_cap('update_plugins');
    $role->add_cap('delete_plugins');
    $role->add_cap('install_plugins');

    $role->add_cap('edit_posts');
    $role->add_cap('delete_posts');

    $role->add_cap('edit_pages');
    $role->add_cap('delete_pages');

    $role->add_cap('read');

    $role->add_cap('edit_users');
    $role->add_cap('delete_users');
    $role->add_cap('create_users');
    $role->add_cap('list_users');
    $role->add_cap('remove_users');

    $role->add_cap('update_core');

    // Add caps for Administrator role
    $role = get_role('administrator');

    $role->add_cap('loggin_admin');

    $role->add_cap('edit_themes');

    $role->add_cap('edit_theme_options');
    $role->add_cap('edit_theme_menus');
    $role->add_cap('edit_theme_widgets');

    $role->add_cap('edit_posts');
    $role->add_cap('delete_posts');

    $role->add_cap('edit_pages');
    $role->add_cap('delete_pages');

    $role->add_cap('read');

    // Add caps for Subscriber role
    $role = get_role('subscriber');
    
    $role->add_cap('read');
}

/**
 * [populate_roles_231 Các quyền thêm vào]
 * @return [edit_gallery] [cập nhật thư viện]
 * @return [delete_gallery] [xóa thư viện]
 */
function populate_roles_231() {

    $role = get_role('root');

    $role->add_cap('edit_gallery');

    $role->add_cap('delete_gallery');

    $role->add_cap('manage_categories');

    $role->add_cap('delete_categories');

    $role = get_role('administrator');

    $role->add_cap('edit_gallery');

    $role->add_cap('delete_gallery');

    $role->add_cap('manage_categories');
    
    $role->add_cap('delete_categories');
}
/**
 * [populate_roles_241 Các quyền thêm vào]
 * @return [edit_gallery] [cập nhật thư viện]
 * @return [delete_gallery] [xóa thư viện]
 */
function populate_roles_241() {

    $role = get_role('root');

    $role->add_cap('edit_setting');

    $role->add_cap('edit_smtp');

    $role = get_role('administrator');

    $role->add_cap('edit_setting');

    $role->add_cap('edit_smtp');
}

function populate_roles_242() {

    $role = get_role('root');

    $role->add_cap('edit_setting_cache');

    $role->add_cap('edit_setting_tinymce');
    
    $role->add_cap('edit_setting_audit');

    $role = get_role('administrator');

    $role->add_cap('edit_setting_tinymce');
}

function populate_roles_300() {

    $role = get_role('root');

    $role->add_cap('view_posts');
    $role->add_cap('add_posts');
    $role->add_cap('view_pages');
    $role->add_cap('add_pages');

	$role = get_role('administrator');
	
	$role->add_cap('view_posts');
    $role->add_cap('add_posts');
    $role->add_cap('view_pages');
    $role->add_cap('add_pages');
}

//Cms upload
function file_cms_update( $cms_version = 0 ) {

	$list_cms_version = array( '3.0.0' );

	$model = get_model('plugins', 'backend');

	foreach ($list_cms_version as $cms ) {

		if( version_compare( $cms, $cms_version ) == 1 ) {

			$cms_update_function = 'file_cms_update_'.str_replace('.','_',$cms);

			if(function_exists($cms_update_function)) $cms_update_function( $model );
		}
	}
}

function file_cms_update_3_0_0($model) {

	$path = FCPATH.VIEWPATH.'backend/';

	$Files = [
		//smtp :: đã duy chuyển vào system
		'email/email-smtp.php',
		'email/email-smtp-html.php',
		//gallery :: đã duy chuyển vào function
		'gallery/gallerys.php',
		'gallery/gallery-function.php',
		'gallery/gallery-ajax.php',
		'gallery/html/gallery-action-bar.php',
		'gallery/html/gallery-box.php',
		'gallery/html/gallery-form.php',
		'gallery/html/gallery-tab.php',
		'gallery/html/object_gallery_item.php',
		//Tinymce :: đã duy chuyển vào function
		'tinymce/tinymce-ajax.php',
		'tinymce/tinymce-tab-font.php',
		'tinymce/tinymce-tab-general.php',
		'tinymce/tinymce.php',
		//Audit-log :: đã duy chuyển vào function
		'audit-log/audit-log.php',
		'audit-log/audit_log_manager-view.php',
		'audit-log/audit-log-function.php',
		//Cache :: đã duy chuyển vào function
		'cache/cache.php',
		'cache/cache-manager-view.php',
		'cache/cache-manager-ajax.php',
	];

	foreach ($Files as $file) {

		if(file_exists($path.$file)) {

			unlink($path.$file);
		}
	}

	$Folders = [
		'email',
		'gallery/html',
		'gallery',
		'tinymce',
		'audit-log',
		'cache'
	];

	foreach ($Folders as $folder) {

		if(is_dir($path.$folder)) {

			rmdir($path.$folder);
		}
	}
}