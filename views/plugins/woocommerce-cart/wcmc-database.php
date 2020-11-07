<?php
function wcmc_cart_notice_database_update() {

	$version = get_option('wcmc_cart_database_version', 0 );

	if( version_compare( WCMC_CART_DATABASE, $version ) === 1 ) {

		echo notice( 'error', 'Phiên bản Database woocommerce cart của bạn hiện đã củ <a href="admin/plugins?page=woocommerce_cart_database" class="btn btn-info">click vào đây</a> để nâng cấp.');
	}
}

if( is_admin() ) {

	add_action('admin_notices', 'wcmc_cart_notice_database_update');

	register_admin_nav('woocommerce_cart_database', 'woocommerce_cart_database', 'plugins?page=woocommerce_cart_database', '', array('callback' => 'woocommerce_cart_admin_database_update', 'hidden' => true));
}

function woocommerce_cart_admin_database_update() {

	$version = get_option('wcmc_cart_database_version', 0 );

	if( version_compare( WCMC_CART_DATABASE, $version ) === 1 ) {

		wcmc_cart_database_update();

		delete_cache( 'products_', true );
		delete_cache( 'order_', true );

		echo notice('success', 'Database đã được cập nhật hoàn tất!');
	}
	else {

		echo notice('success', 'Database woocommerce cart của bạn hiện tại là phiên bản mới nhất!');
	}
}

function wcmc_cart_database_update() {

	$list_db_version = array( '1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '2.0' );

	$model = get_model('plugins', 'backend');

	$version = get_option('wcmc_cart_database_version', 0 );

	foreach ($list_db_version as $db ) {

		if( version_compare( $db, $version ) == 1 ) {

			$database_update_function = 'wcmc_cart_database_update_'.str_replace('.','_',$db);

			if(function_exists($database_update_function)) $database_update_function( $model );
		}
	}	
}

/*================================== UPDATE ====================================*/
function wcmc_cart_database_update_1_2( $model ) {

	$model->settable('wcmc_order');

	$orders = $model->gets_where();

	foreach ($orders as $key => $order) {

		$model->settable('metabox');

		$metabox = ['object_type' => 'woocommerce_order', 'object_id' => $orders->id];
		
		if(!empty($order->billing_fullname)) {
			$metabox['meta_key'] 	= 'billing_fullname';
			$metabox['meta_value'] 	= $order->billing_fullname;
			$model->add($metabox);
		}

		if(!empty($order->billing_email)) {
			$metabox['meta_key'] 	= 'billing_email';
			$metabox['meta_value'] 	= $order->billing_email;
			$model->add($metabox);
		}

		if(!empty($order->billing_phone)) {
			$metabox['meta_key'] 	= 'billing_phone';
			$metabox['meta_value'] 	= $order->billing_phone;
			$model->add($metabox);
		}

		if(!empty($order->billing_address)) {
			$metabox['meta_key'] 	= 'billing_address';
			$metabox['meta_value'] 	= $order->billing_address;
			$model->add($metabox);
		}

		if(!empty($order->shipping_fullname)) {
			$metabox['meta_key'] 	= 'shipping_fullname';
			$metabox['meta_value'] 	= $order->shipping_fullname;
			$model->add($metabox);
		}

		if(!empty($order->shipping_email)) {
			$metabox['meta_key'] 	= 'shipping_email';
			$metabox['meta_value'] 	= $order->shipping_email;
			$model->add($metabox);
		}

		if(!empty($order->shipping_phone)) {
			$metabox['meta_key'] 	= 'shipping_phone';
			$metabox['meta_value'] 	= $order->shipping_phone;
			$model->add($metabox);
		}

		if(!empty($order->shipping_address)) {
			$metabox['meta_key'] 	= 'shipping_address';
			$metabox['meta_value'] 	= $order->shipping_address;
			$model->add($metabox);
		}
	}

	if( $model->db_field_exists('billing_fullname','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_fullname`");
	}

	if( $model->db_field_exists('billing_email','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_email`");
	}

	if( $model->db_field_exists('billing_phone','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_phone`");
	}

	if( $model->db_field_exists('billing_address','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `billing_address`");
	}

	if( $model->db_field_exists('shipping_fullname','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_fullname`");
	}

	if( $model->db_field_exists('shipping_email','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_email`");
	}

	if( $model->db_field_exists('shipping_phone','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_phone`");
	}

	if( $model->db_field_exists('shipping_address','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `shipping_address`");
	}

	$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_options` ADD `slug` VARCHAR(255) NULL AFTER `order`");

	$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` CHANGE `status` `status` VARCHAR(255) NOT NULL DEFAULT 'wc-wait-confim'");

	$model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= 'wc-wait-confim' 	WHERE `status`= 1");

	$model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= 'wc-confim' 		WHERE `status`= 2");

	$model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= 'wc-completed' 	WHERE `status`= 3");

	$model->query("UPDATE `".CLE_PREFIX."wcmc_order` SET `status`= 'wc-failed' 		WHERE `status`= 4");

	$model->query("UPDATE `".CLE_PREFIX."relationships` SET `value` = 'products_categories' WHERE `".CLE_PREFIX."relationships`.`object_type` = 'products'");

	$model->settable('wcmc_options');

	$options = $model->gets_where();

	if( have_posts( $options ) ) {
		foreach ($options as $option) {
			$model->update_where( array( 'slug' => slug($option->title) ), array( 'id' => $option->id ) );
		}
	}

	update_option( 'wcmc_cart_database_version', '1.2' );
}

function wcmc_cart_database_update_1_3( $model ) {

	if( !$model->db_table_exists('wcmc_order_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	}

	
	if( !$model->db_field_exists('user_created','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` ADD `user_created` INT NOT NULL DEFAULT '0' AFTER `status`, ADD `user_updated` INT NOT NULL DEFAULT '0' AFTER `user_created`;");
	}

	if( $model->db_field_exists('order_note','wcmc_order') ) {
		$model->query("ALTER TABLE `".CLE_PREFIX."wcmc_order` DROP `order_note`;");
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

	if( !$model->db_table_exists('wcmc_variations_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_variations_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	}

	$check['order_metadata']      = false;
	$check['product_metadata']    = false;
	$check['variations_metadata'] = false;

	if( $model->db_table_exists('wcmc_order_metadata') ) {

		$model->settable('metabox');

		$metadatas = $model->gets_where( array('object_type' => 'woocommerce_order'));

		if( have_posts($metadatas) ) {

			$model->settable('wcmc_order_metadata');

			foreach ($metadatas as $meta) {
				update_order_meta( $meta->object_id, $meta->meta_key, $meta->meta_value );
			}

			$model->settable('metabox');

			$model->delete_where( array('object_type' => 'woocommerce_order'));
		}

		$check['order_metadata'] = true;
	}

	if( $model->db_table_exists('product_metadata') ) {

		$model->settable('metabox');

		$metadatas = $model->gets_where( array('object_type' => 'woocommerce_attributes', 'meta_key' => '_product_attributes'));

		if( have_posts($metadatas) ) {

			$model->settable('product_metadata');

			foreach ($metadatas as $meta) {
				update_metadata( 'product', $meta->object_id, 'attributes', $meta->meta_value );
			}

			$model->settable('metabox');

			$model->delete_where( array('object_type' => 'woocommerce_attributes', 'meta_key' => '_product_attributes'));
		}

		$check['product_metadata'] = true;
	}

	if( $model->db_table_exists('wcmc_variations_metadata') ) {

		$model->settable('metabox');

		$metadatas = $model->gets_where( array('object_type' => 'woocommerce_variations'));

		if( have_posts($metadatas) ) {

			$model->settable('wcmc_variations_metadata');

			foreach ($metadatas as $meta) {
				update_metadata( 'wcmc_variations', $meta->object_id, $meta->meta_key, $meta->meta_value );
			}

			$model->settable('metabox');

			$model->delete_where( array('object_type' => 'woocommerce_variations') );
		}

		$check['variations_metadata'] = true;
	}

	$model->settable('relationships');

	$model->update_where(array('object_type' => 'attributes'), array('object_type' => 'woocommerce_attributes'));

	if( $check['order_metadata'] == true && $check['order_metadata'] == true && $check['order_metadata'] == true ) {
		update_option( 'wcmc_cart_database_version', '1.3' );
	}
}

function wcmc_cart_database_update_1_4( $model ) {

	$orders = wcmc_gets_order();

	foreach ($orders as $order) {

		$quantity = 0;

		foreach ($order->items as $item) {
			$quantity += $item->quantity;
		}

		update_order_meta( $order->id, 'quantity', $quantity );
	}

	$role = get_role('root');

	$role->add_cap('wcmc_order_list');

    $role->add_cap('wcmc_order_edit');

    $role->add_cap('wcmc_order_delete');

    $role->add_cap('wcmc_attributes_list');

    $role->add_cap('wcmc_attributes_edit');

    $role->add_cap('wcmc_attributes_delete');


    $role = get_role('administrator');

    $role->add_cap('wcmc_order_list');

    $role->add_cap('wcmc_order_edit');

    $role->add_cap('wcmc_order_delete');

    $role->add_cap('wcmc_attributes_list');

    $role->add_cap('wcmc_attributes_edit');

    $role->add_cap('wcmc_attributes_delete');

    update_option( 'wcmc_cart_database_version', '1.4' );
}

function wcmc_cart_database_update_1_5( $model ) {

	$orders = wcmc_gets_order();

	foreach ($orders as $order) {

		$quantity = 0;

		foreach ($order->items as $item) {
			$quantity += $item->quantity;
		}

		update_order_meta( $order->id, 'quantity', $quantity );
	}

	if( !$model->db_table_exists('wcmc_order_detail_metadata') ) {

		$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_detail_metadata` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`object_id` int(11) DEFAULT NULL,
			`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_value` text COLLATE utf8_unicode_ci,
			`created` datetime DEFAULT NULL,
			`updated` datetime DEFAULT NULL,
			`order` int(11) DEFAULT '0'
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	}

	$check = false;

	if( $model->db_table_exists('wcmc_order_detail_metadata') ) {

		$order_items = wcmc_gets_item_order();

		foreach ($order_items as $item) {
			update_order_item_meta( $item->id, 'attribute', $item->option );
		}

		$check  = true;
	}

	if( $check == true ) {
		update_option( 'wcmc_cart_database_version', '1.5' );
	}
}

function wcmc_cart_database_update_1_6( $model ) {

	if( !$model->db_field_exists('order_count','users') ) {
		               
		if(!$model->db_field_exists('order_count','users')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_count` INT NOT NULL DEFAULT '0' AFTER `order`;");

		if(!$model->db_field_exists('order_total','users')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_total` INT NOT NULL DEFAULT '0' AFTER `order`;");

		if(!$model->db_field_exists('customer','users')) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `customer` INT NOT NULL DEFAULT '0' AFTER `order`;");

		$orders = wcmc_gets_order();

		$order_total = array();

		foreach ($orders as $key => $order) {

			if(!isset($order_total[$order->billing_email])) {

				$order_total[$order->billing_email] = [
					'order_count' => 1,
					'order_total' => 0,
                    'order'		  => $order,
                    'order_recent'=> $order->code,
                ];
                
                if($order->status == 'wc-completed') {

                    $order_total['order_total'] = $order->total;
                }
			}
			else {

				$order_total[$order->billing_email]['order_count'] += 1;

                $order_total[$order->billing_email]['order'] = $order;

                $order_total[$order->billing_email]['order_recent'] = $order->code;
                
                if($order->status == 'wc-completed') {

                    $order_total[$order->billing_email]['order_total'] += $order->total;
                }
			}
        }

		foreach ($order_total as $email => $data) {
			
            $user = get_user([
                'where' => [
                    'status <>' => 'null',
                    'email'     => $email
                ]
            ]);

			if(have_posts($user)) {

				$user->order_total = $data['order_total'];

				$user->order_count = $data['order_count'];
				
				$user->customer    = 2;

                insert_user((array)$user);
                
                update_user_meta( $user->id, 'order_recent', $data['order_recent']);
			}
			else {

				$fullname =  explode(' ', $data['order']->billing_fullname);

				$lastname 	= array_pop($fullname);
				
				$firstname 	= str_replace( ' '.$lastname, '', $data['order']->billing_fullname );

				$user = [
					'firstname' 	=> $firstname,
					'lastname'  	=> $lastname,
					'email'			=> $data['order']->billing_email,
					'phone'			=> $data['order']->billing_phone,
					'order_total' 	=> $data['order_total'],
					'order_count' 	=> $data['order_count'],
					'status' 	    => 'public',
					'customer' 	    => 1
				];

				$model->settable('users');

                $user_id = $model->add($user);
                
                if(!is_skd_error($user_id)) {

                    user_set_role( $user_id, 'customer');
                
                    update_user_meta( $user_id, 'order_recent', $data['order_recent']);
                }
            }
        }

        foreach ($orders as $key => $order) {

            $user = get_user([
                'where' => [
                    'status <>' => 'null',
                    'email'     => $order->billing_email
                ]
			]);

            if(have_posts($user)) {

                insert_order([
                    'id' => $order->id,
                    'user_created' => $user->id
                ]);
            }
        }
	}

	$role = get_role('root');

	$role->add_cap('customer_list');

	$role->add_cap('customer_active');
	
	$role->add_cap('customer_add');
	
	$role->add_cap('customer_edit');
	
	$role->add_cap('customer_reset_password');
	
	$role->add_cap('wcmc_attributes_add');
	
	$role->add_cap('wcmc_order_add');
	
    $role->add_cap('wcmc_order_copy');


    $role = get_role('administrator');

    $role->add_cap('customer_list');

	$role->add_cap('customer_active');
	
	$role->add_cap('customer_add');
	
	$role->add_cap('customer_edit');
	
	$role->add_cap('customer_reset_password');
	
	$role->add_cap('wcmc_attributes_add');
	
	$role->add_cap('wcmc_order_add');
	
    $role->add_cap('wcmc_order_copy');

	update_option( 'wcmc_cart_database_version', '1.6' );
}

function wcmc_cart_database_update_1_7( $model ) {

	$model->settable('language');

	$role = get_role('root');

	$role->add_cap('wcmc_order_setting');
	
    $role = get_role('administrator');

	$role->add_cap('wcmc_order_setting');

	$orders = gets_order();

	foreach ($orders as $key => $order) {

		if( $order->shipping_fullname == $order->billing_fullname && $order->shipping_address == $order->billing_address &&  $order->shipping_phone == $order->billing_phone &&  $order->shipping_email == $order->billing_email ) {
			
			update_order_meta($order->id, 'other_delivery_address', false);

		} else {
			
			update_order_meta($order->id, 'other_delivery_address', true);
		}
	}
	
	//Delete File
	$path = FCPATH.WCMC_CART_PATH.'/';

	$Files = [
		//options
		'admin/wcmc-product-options.php',
		'admin/views/options/html-product-options-add.php',
		'admin/views/options/html-product-options-edit.php',
		'admin/views/options/html-product-options-item-add.php',
		'admin/views/options/html-product-options-item-edit.php',
		//customer
		'admin/customer/html/created/html-content.php',
		'admin/customer/html/created/html-note.php',
		'admin/customer/html/detail/html-content-order.php',
		'admin/customer/html/detail/html-content.php',
		'admin/customer/html/detail/html-customer-info.php',
		'admin/customer/html/html-customer-created.php',
		'admin/customer/html/html-customer-detail.php',
		'admin/customer/html/html-customer-list.php',

	];

	foreach ($Files as $file) {
		if(file_exists($path.$file)) unlink($path.$file);
	}

	$Folders = [
		'admin/views/options',
		'admin/customer/html/created',
		'admin/customer/html/detail',
		'admin/customer/html'
	];

	foreach ($Folders as $folder) {

		if(is_dir($path.$folder)) rmdir($path.$folder);
	}


	if(!$model->db_field_exists('code','wcmc_variations')) $model->query("ALTER TABLE `".CLE_PREFIX."wcmc_variations` ADD `code` VARCHAR(255) NULL AFTER `id`;");

	update_option( 'wcmc_cart_database_version', '1.7' );
}

function wcmc_cart_database_update_2_0( $model ) {

	if($model->db_field_exists('status','products')) {

		$model->query("ALTER TABLE `".CLE_PREFIX."products` CHANGE `status` `status` VARCHAR(255) NOT NULL DEFAULT 'public';");

		$model->query("UPDATE `".CLE_PREFIX."products` SET `status`= 'public' WHERE 1;");

		echo notice('success', '
			<p>Row <b>products::status</b> change type data from INT to VARCHAR</p>
			<p>Row <b>products::status</b> set all value to public</p>
		');
	}

	if(!$model->db_field_exists('type','products')) {

		$model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `type` VARCHAR(255) NULL DEFAULT 'product' AFTER `status3`;");

		$model->query("UPDATE `".CLE_PREFIX."products` SET `type`= 'product' WHERE 1;");

		echo notice('success', '
			<p>Table <b>products</b> add row type</p>
			<p>Row <b>products::type</b> set all value to product</p>
		');
	}

	if(!$model->db_field_exists('parent_id','products')) {

		$model->query("ALTER TABLE `".CLE_PREFIX."products` ADD `parent_id` INT NULL DEFAULT 0 AFTER `status3`;");

		$model->query("UPDATE `".CLE_PREFIX."products` SET `parent_id`= 0 WHERE 1;");

		echo notice('success', '
			<p>Table <b>products</b> add row parent_id</p>
			<p>Row <b>products::parent_id</b> set all value to 0</p>
		');
	}

	if($model->db_table_exists('wcmc_variations')) {

		$success = [];

		$model->settable('wcmc_variations');

		$variations = $model->gets_where(['object_type' => 'public']);

		if(have_posts($variations)) {

			foreach ($variations as $key => $variation) {

				$model->settable('products');

				$product = $model->get_where(['id' => $variation->object_id]);

				$product_variation = [
					'title' 	=> $product->title,
					'code' 		=> $variation->code,
					'status' 	=> 'public',
					'type' 		=> 'variations',
					'created' 	=> $variation->created,
					'updated' 	=> $variation->updated,
					'parent_id' => $variation->object_id,
				];

				$product_variation['price'] 		= get_metadata( 'wcmc_variations', $variation->id, '_price', true );

				$product_variation['price_sale'] 	= get_metadata( 'wcmc_variations', $variation->id, '_price_sale', true );

				$product_variation['image'] 		= get_metadata( 'wcmc_variations', $variation->id, '_image', true );

				$model->settable('products');

				$id = $model->add($product_variation);

				if($id != 0) {

					$model->settable('wcmc_variations');

					$model->delete_where(['id' => $variation->id]);

					$model->settable('wcmc_variations_metadata');

					$model->delete_where(['object_id' => $variation->id, 'meta_key' => '_price']);

					$model->delete_where(['object_id' => $variation->id, 'meta_key' => '_price_sale']);

					$model->delete_where(['object_id' => $variation->id, 'meta_key' => '_image']);

					$metadata = $model->gets_where(['object_id' => $variation->id]);

					if(have_posts($metadata)) {

						foreach ($metadata as $meta) {
							
							update_product_meta($id, $meta->meta_key, $meta->meta_value);
						}

						$model->settable('wcmc_variations_metadata');

						$model->delete_where(['object_id' => $variation->id]);
					}
				}
			}

			$model->settable('wcmc_variations');

			$variations = $model->gets_where(['object_type' => 'public']);

			if(!have_posts($variations)) {

				$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations`");
			}

			$model->settable('wcmc_variations_metadata');

			$metadata = $model->gets_where();

			if(!have_posts($metadata)) {

				$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations_metadata`");
			}
		}
		else {
			
			$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations`");

			$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_variations_metadata`");
		}
	}

	update_option( 'wcmc_cart_database_version', '2.0' );
}

/*================================== UPDATE ====================================*/

/*================================== ADD NEW ====================================*/
function wcmc_cart_database_add_table() {

	$model = get_model('plugins', 'backend');

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_options` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  	`title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  	`slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  	`option_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  	`public` int(11) NOT NULL DEFAULT '1',
	  	`created` datetime DEFAULT NULL,
	  	`updated` datetime DEFAULT NULL,
	  	`order` int(11) NOT NULL DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_options_item` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`option_id` int(11) NOT NULL DEFAULT '0',
		`title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`order` int(11) NOT NULL DEFAULT '0',
		`public` int(11) NOT NULL DEFAULT '1',
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`total` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		`order` int(11) NOT NULL DEFAULT '0',
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`user_created` INT NOT NULL DEFAULT '0',
		`user_updated` INT NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_metadata` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`object_id` int(11) DEFAULT NULL,
		`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`meta_value` text COLLATE utf8_unicode_ci,
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`order` int(11) DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_detail_metadata` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`object_id` int(11) DEFAULT NULL,
		`meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`meta_value` text COLLATE utf8_unicode_ci,
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`order` int(11) DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_order_detail` (
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`order_id` int(11) DEFAULT NULL,
		`product_id` int(11) DEFAULT NULL,
		`title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`quantity` int(11) NOT NULL DEFAULT '0',
		`image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`option` text COLLATE utf8_unicode_ci,
		`price` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		`subtotal` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`order` int(11) NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."wcmc_session` (
		`session_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`session_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		`session_value` text COLLATE utf8_unicode_ci,
		`session_expiry` int(11) NOT NULL DEFAULT '0',
		`created` datetime DEFAULT NULL,
		`updated` datetime DEFAULT NULL,
		`order` int(11) NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	if( !$model->db_field_exists('order_count','users') ) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_count` INT NOT NULL DEFAULT '0' AFTER `order`;");

	if( !$model->db_field_exists('order_total','users') ) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `order_total` INT NOT NULL DEFAULT '0' AFTER `order`;");

	if( !$model->db_field_exists('customer','users') ) $model->query("ALTER TABLE `".CLE_PREFIX."users` ADD `customer` INT NOT NULL DEFAULT '0' AFTER `order`;");

	$role = get_role('root');

	$role->add_cap('wcmc_order_list');

    $role->add_cap('wcmc_order_edit');

	$role->add_cap('wcmc_order_delete');
	
	$role->add_cap('wcmc_order_add');
	
	$role->add_cap('wcmc_order_copy');

	$role->add_cap('wcmc_order_setting');

	$role->add_cap('wcmc_attributes_list');
	
	$role->add_cap('wcmc_attributes_add');

    $role->add_cap('wcmc_attributes_edit');

	$role->add_cap('wcmc_attributes_delete');
	

	//khách hàng
	$role->add_cap('customer_list');

	$role->add_cap('customer_active');
	
	$role->add_cap('customer_add');
	
	$role->add_cap('customer_edit');
	
	$role->add_cap('customer_reset_password');
	
	
    $role = get_role('administrator');

    $role->add_cap('wcmc_order_list');

    $role->add_cap('wcmc_order_edit');

	$role->add_cap('wcmc_order_delete');
	
	$role->add_cap('wcmc_order_add');
	
	$role->add_cap('wcmc_order_copy');

	$role->add_cap('wcmc_order_setting');
	
	$role->add_cap('wcmc_attributes_list');
	
	$role->add_cap('wcmc_attributes_add');

    $role->add_cap('wcmc_attributes_edit');

	$role->add_cap('wcmc_attributes_delete');
	
	//khách hàng
	$role->add_cap('customer_list');

	$role->add_cap('customer_active');
	
	$role->add_cap('customer_add');
	
	$role->add_cap('customer_edit');
	
	$role->add_cap('customer_reset_password');
}

/*================================== UNSTALL ====================================*/
function wcmc_cart_database_drop_table() {
	$model = get_model('plugins', 'backend');
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_options`");
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_options_item`");
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_order`");
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_order_metadata`");
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_order_detail`");
	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."wcmc_session`");
}

