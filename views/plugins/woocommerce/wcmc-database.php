<?php
function wcmc_noice_database_update() {

	$version = get_option('wcmc_database_version', 0 );

	if( version_compare( WCMC_DATABASE, $version ) === 1 ) {

		echo notice( 'error', 'Phiên bản Database woocommerce của bạn hiện đã củ <a href="admin/plugins?page=woocommerce_database" class="btn btn-info">click vào đây</a> để nâng cấp.');
	}
	
}

if( is_admin() ) {

	add_action('admin_notices', 'wcmc_noice_database_update');

	register_admin_nav('woocommerce_database', 'woocommerce_database', 'plugins?page=woocommerce_database', '', array('callback' => 'woocommerce_admin_database_update', 'hidden' => true));

}

function woocommerce_admin_database_update() {

	$version = get_option('wcmc_database_version', 0 );

	if( version_compare( WCMC_DATABASE, $version ) === 1 ) {

		wcmc_database_update();

		delete_cache( 'products_', true );

		echo notice('success', 'Database đã được cập nhật hoàn tất!');
	}
	else {

		echo notice('success', 'Database woocommerce của bạn hiện tại là phiên bản mới nhất!');
	}
}

function wcmc_database_update() {

	$list_db_version = array( '1.1', '1.2', '1.3', '1.4' );

	$model = get_model('plugins', 'backend');

	$version = get_option('wcmc_database_version', 0 );

	foreach ($list_db_version as $db ) {

		if( version_compare( $db, $version ) == 1 ) {

			$database_update_function = 'wcmc_database_update_'.str_replace('.','_',$db);

			if(function_exists($database_update_function)) $database_update_function( $model );
		}
	}	
}

function wcmc_database_update_1_1( $model ) {

	$model->query("UPDATE `".CLE_PREFIX."relationships` SET `value` = 'products_categories' WHERE `".CLE_PREFIX."relationships`.`object_type` = 'products'");

	if( !$model->db_table_exists('products') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."products` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  	`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`excerpt` text COLLATE utf8_unicode_ci,
			`content` text COLLATE utf8_unicode_ci,
			`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`public` tinyint(4) NOT NULL DEFAULT '1',
			`status` int(11) NOT NULL DEFAULT '0',
			`price` int(11) NOT NULL DEFAULT '0',
			`price_sale` int(11) NOT NULL DEFAULT '0',
			`order` int(11) NOT NULL DEFAULT '0',
			`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`seo_description` text COLLATE utf8_unicode_ci,
			`seo_keywords` text COLLATE utf8_unicode_ci,
			`user_created` int(11) DEFAULT NULL,
			`user_updated` int(11) DEFAULT NULL,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`theme_layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`theme_view` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`trash` tinyint(4) NOT NULL DEFAULT '0',
			`status1` tinyint(4) NOT NULL DEFAULT '0',
			`status2` tinyint(4) NOT NULL DEFAULT '0',
			`status3` tinyint(4) NOT NULL DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	if( !$model->db_table_exists('products_categories') ) {
		
		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."products_categories` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  	`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`excerpt` text COLLATE utf8_unicode_ci,
			`content` text COLLATE utf8_unicode_ci,
			`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`seo_description` text COLLATE utf8_unicode_ci,
			`seo_keywords` text COLLATE utf8_unicode_ci,
			`order` int(11) NOT NULL DEFAULT '0',
			`public` tinyint(4) NOT NULL DEFAULT '1',
			`status` int(11) NOT NULL DEFAULT '0',
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`user_created` int(11) DEFAULT NULL,
			`user_updated` int(11) DEFAULT NULL,
			`parent_id` int(11) NOT NULL DEFAULT '0',
			`level` int(11) DEFAULT NULL,
			`lft` int(11) DEFAULT NULL,
			`rgt` int(11) DEFAULT NULL,
			`key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`theme_layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`theme_view` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	if( !$model->db_table_exists('product_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."product_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	update_option('wcmc_database_version', '1.1' );
}

function wcmc_database_update_1_2( $model ) {

	$role = get_role('root');

    $role->add_cap('wcmc_product_list');

    $role->add_cap('wcmc_product_edit');

    $role->add_cap('wcmc_product_delete');

    $role->add_cap('wcmc_product_cate_list');

    $role->add_cap('wcmc_product_cate_edit');

    $role->add_cap('wcmc_product_cate_delete');



    $role = get_role('administrator');

    $role->add_cap('wcmc_product_list');

    $role->add_cap('wcmc_product_edit');

    $role->add_cap('wcmc_product_delete');

    $role->add_cap('wcmc_product_cate_list');

    $role->add_cap('wcmc_product_cate_edit');

    $role->add_cap('wcmc_product_cate_delete');

	update_option('wcmc_database_version', '1.2' );
}

function wcmc_database_update_1_3( $model ) {

	if( !$model->db_table_exists('suppliers') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."suppliers` (
			`id` INT NOT NULL AUTO_INCREMENT, 
			`name` VARCHAR(255) NOT NULL, 
			`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`firstname` VARCHAR(255) NULL, 
			`lastname` VARCHAR(255) NULL, 
			`email` VARCHAR(255) NULL, 
			`phone` VARCHAR(255) NULL, 
			`address` VARCHAR(255) NULL, 
			`created` DATETIME NULL, 
			`updated` DATETIME NULL,
			`user_created` int(11) DEFAULT NULL,
			`user_updated` int(11) DEFAULT NULL,
			`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`seo_description` text COLLATE utf8_unicode_ci,
			`seo_keywords` text COLLATE utf8_unicode_ci,
			`order` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		if(!$model->db_field_exists('supplier_id','products')) {

			$model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `supplier_id` INT NOT NULL DEFAULT '0' AFTER `price_sale`;");
		}
	}

	if(!$model->db_field_exists('code','products')) {

		$model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `code` VARCHAR(255) NULL AFTER `id`;");
	}

	//Phân quyền
	$role = get_role('root');

	$role->add_cap('wcmc_product_setting');
	
    $role = get_role('administrator');

	$role->add_cap('wcmc_product_setting');
	
	//Xóa File
	$path = FCPATH.WCMC_PATH.'/';

	$Files = [
		'admin/views/html-settings-tab-general.php',
		'admin/views/html-settings-tab-product-detail.php',
		'admin/views/html-settings-tab-product-index.php',
		'admin/views/html-settings-tab-product-object.php',
		'admin/views/html-settings-tab-product.php',
	];

	foreach ($Files as $file) {

		if(file_exists($path.$file)) {

			unlink($path.$file);
		}
	}

	update_option( 'wcmc_database_version', '1.3' );
}

function wcmc_database_update_1_4( $model ) {

	if(!$model->db_field_exists('type','products')) {

		$model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `type` VARCHAR(255) NULL DEFAULT 'product' AFTER `status3`;");

		$model->query("UPDATE `".CLE_PREFIX."products` SET `type`= 'product' WHERE 1;");

		echo notice('success', '
			<p>Table <b>products</b> add row type</p>
			<p>Row <b>products::type</b> set all value to product</p>
		');
	}
	
	update_option( 'wcmc_database_version', '1.4' );
}

function wcmc_database_add_table() {

	$model = get_model('plugins', 'backend');

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."products` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`code` varchar(255) COLLATE utf8_unicode_ci NULL,
	  	`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`excerpt` text COLLATE utf8_unicode_ci,
		`content` text COLLATE utf8_unicode_ci,
		`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`public` tinyint(4) NOT NULL DEFAULT '1',
		`status`  varchar(255) COLLATE utf8_unicode_ci DEFAULT 'public',
		`type`  varchar(255) COLLATE utf8_unicode_ci DEFAULT 'product',
		`price` int(11) NOT NULL DEFAULT '0',
		`price_sale` int(11) NOT NULL DEFAULT '0',
		`supplier_id` int(11) NOT NULL DEFAULT '0',
		`order` int(11) NOT NULL DEFAULT '0',
		`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`seo_description` text COLLATE utf8_unicode_ci,
		`seo_keywords` text COLLATE utf8_unicode_ci,
		`user_created` int(11) DEFAULT NULL,
		`user_updated` int(11) DEFAULT NULL,
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`theme_layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`theme_view` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`trash` tinyint(4) NOT NULL DEFAULT '0',
		`status1` tinyint(4) NOT NULL DEFAULT '0',
		`status2` tinyint(4) NOT NULL DEFAULT '0',
		`status3` tinyint(4) NOT NULL DEFAULT '0',
		`parent_id` int(11) NOT NULL DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."products_categories` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  	`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`excerpt` text COLLATE utf8_unicode_ci,
		`content` text COLLATE utf8_unicode_ci,
		`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`seo_description` text COLLATE utf8_unicode_ci,
		`seo_keywords` text COLLATE utf8_unicode_ci,
		`order` int(11) NOT NULL DEFAULT '0',
		`public` tinyint(4) NOT NULL DEFAULT '1',
		`status` int(11) NOT NULL DEFAULT '0',
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`user_created` int(11) DEFAULT NULL,
		`user_updated` int(11) DEFAULT NULL,
		`parent_id` int(11) NOT NULL DEFAULT '0',
		`level` int(11) DEFAULT NULL,
		`lft` int(11) DEFAULT NULL,
		`rgt` int(11) DEFAULT NULL,
		`key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`theme_layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`theme_view` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."product_metadata` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`object_id` int(11) DEFAULT NULL,
		`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`meta_value` text COLLATE utf8_unicode_ci,
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`order` int(11) DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."suppliers` (
		`id` INT NOT NULL AUTO_INCREMENT, 
		`name` VARCHAR(255) NOT NULL, 
		`slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`firstname` VARCHAR(255) NULL, 
		`lastname` VARCHAR(255) NULL, 
		`email` VARCHAR(255) NULL, 
		`phone` VARCHAR(255) NULL, 
		`address` VARCHAR(255) NULL, 
		`created` DATETIME NULL, 
		`updated` DATETIME NULL,
		`user_created` int(11) DEFAULT NULL,
		`user_updated` int(11) DEFAULT NULL,
		`seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`seo_description` text COLLATE utf8_unicode_ci,
		`seo_keywords` text COLLATE utf8_unicode_ci,
		`order` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");


	$role = get_role('root');

    $role->add_cap('wcmc_product_list');

    $role->add_cap('wcmc_product_edit');

    $role->add_cap('wcmc_product_delete');

    $role->add_cap('wcmc_product_cate_list');

    $role->add_cap('wcmc_product_cate_edit');

	$role->add_cap('wcmc_product_cate_delete');
	
	$role->add_cap('wcmc_product_setting');



    $role = get_role('administrator');

    $role->add_cap('wcmc_product_list');

    $role->add_cap('wcmc_product_edit');

    $role->add_cap('wcmc_product_delete');

    $role->add_cap('wcmc_product_cate_list');

    $role->add_cap('wcmc_product_cate_edit');

	$role->add_cap('wcmc_product_cate_delete');
	
	$role->add_cap('wcmc_product_setting');

}

function wcmc_database_drop_table() {

	$model = get_model('plugins', 'backend');

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."products`");

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."products_categories`");

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."product_metadata`");

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."suppliers`");
}