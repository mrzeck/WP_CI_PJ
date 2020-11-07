<?php
/**
 * ORDER
 */
if( !function_exists('get_order') ) {
	/**
	 * [get_order lấy đơn hàng]
	 * @since 2.3.4
	 */
	function get_order( $args = '', $detail = true, $metadata = true ) {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order');
		if( is_numeric($args) ) {
			$cache_id = 'order_'.$args.'_get';
			$args = array( 'where' => array('id' => (int)$args ) );
		}
		if( !have_posts($args) ) return array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where 	= $args['where'];
        $params = $args['params'];
		if( isset($cache_id) && cache_exists($cache_id) !== false ) return get_cache($cache_id);
		if(!empty($args['operator'])) {
			$col = $args['operator']['col'];
			$operator = (!empty($args['operator']['operator']))?$args['operator']['operator']:'sum';
			$orders = $model->operatorby($where, $col, $operator);
			$orders = (have_posts($orders))?$orders->$col:0;
			if( isset($cache_id) ) save_cache($cache_id, $order);
		}
		else {
			$order =  $model->get_where($where, $params );
			if( have_posts($order) ) {
				//get danh sách product item
				$model->settable('wcmc_order_detail');
				$order->items = $model->gets_where(array('order_id' => $order->id), array('orderby' => 'created desc'));
				//get danh sách metadat
				$order_info = get_order_meta($order->id, '', false);
				if( have_posts($order_info) ) {
					$order_info->shipping_fullname =  (!empty($order_info->shipping_address))?$order_info->shipping_fullname:$order_info->billing_fullname;
					$order_info->shipping_address  =  (!empty($order_info->shipping_address))?$order_info->shipping_address:$order_info->billing_address;
					$order_info->shipping_phone    =  (!empty($order_info->shipping_phone))?$order_info->shipping_phone:$order_info->billing_phone;
					$order_info->shipping_email    =  (!empty($order_info->shipping_email))?$order_info->shipping_email:$order_info->billing_email;
				}
				$order = (object)array_merge( (array)$order_info, (array)$order );
				//lưu capche
				if( isset($cache_id) ) save_cache($cache_id, $order);
			}
		}
        return $order;
	}
}
if( !function_exists('gets_order') ) {
	/**
	 * [gets_order lấy danh sách đơn hàng]
	 * @since 2.3.4
	 */
	function gets_order( $args = '', $detail = true, $metadata = true ) {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order');
        $model->settable_metabox('wcmc_order_metadata');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) $args = array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where 	= $args['where'];
        $params = $args['params'];
        $orders = array();
		if(!empty($args['operator'])) {
			$col = $args['operator']['col'];
			$operator = (!empty($args['operator']['operator']))?$args['operator']['operator']:'sum';
			$orders = $model->operatorby($where, $col, $operator);
			$orders = (have_posts($orders))?$orders->$col:0;
		}
        else $orders =  $model->gets_data($args, 'order');
        if( have_posts($orders) && ( $detail == true || $metadata == true ) ) {
        	foreach ($orders as &$order) {
        		$order = wcmc_get_order( $order->id );	
        	}
        }
        return $orders;
	}
}
if( !function_exists('count_order') ) {
	/**
	 * [count_order điếm sớ lượng đơn hàng]
	 * @since 2.3.4
	 */
	function count_order($args = '') {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) $args = array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where 	= $args['where'];
        $params = $args['params'];
        $orders = array();
        if( !empty($args['meta_key']) ) {
			$model->settable('wcmc_order_metadata');
			$compare = (!empty($args['meta_compare'])) ? $args['meta_compare'] : '=';
			$meta = array();
			$meta_param = array('groupby' => 'object_id', 'select' => 'object_id' );
			if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {
				$compare = ( $compare == '==' ) ? '' : ' '.$compare ;
				$meta = $model->count_where( array('meta_key'.$compare => $args['meta_key']), $meta_param );
			}
			else if( $compare == 'like' ) {
				$meta = $model->count_where_like( array( 'like' => array('meta_key' => array($args['meta_key']) ) ), array(), $meta_param );
			}
			$data = array( 'field' => 'id', 'data' => array() );
			if( have_posts($meta) ) {
				foreach ($meta as $value) {
					$data['data'][] = $value->object_id;
				}
				$model->settable('wcmc_order');
				$orders = $model->count_where_in( $data, $where, $params);
			}
		}
		else if( !empty($args['meta_value']) ) {
			$model->settable('wcmc_order_metadata');
			$compare = (!empty($args['meta_compare'])) ? $args['meta_compare'] : '=';
			$meta = array();
			$meta_param = array('groupby' => 'object_id', 'select' => 'object_id' );
			if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {
				$compare = ( $compare == '==' ) ? '' : ' '.$compare ;
				$meta = $model->gets_where( array('meta_value'.$compare => $args['meta_value']), $meta_param );
			}
			else if( $compare == 'like' ) {
				$meta = $model->gets_where_like( array( 'like' => array('meta_value' => array($args['meta_value']) ) ), array(), $meta_param );
			}
			$data = array( 'field' => 'id', 'data' => array() );
			if( have_posts($meta) ) {
				foreach ($meta as $value) {
					$data['data'][] = $value->object_id;
				}
				$model->settable('wcmc_order');
				$orders = $model->count_where_in( $data, $where, $params);
			}
		}
		else if( !empty($args['meta_query']) ) {
			$meta_query = $args['meta_query'];
			$relation 	= 'AND';
			$meta 		= array();
			if( !empty($meta_query['relation']) ) {
				$relation = $meta_query['relation']; unset($meta_query['relation']);
			}
			$sql = 'SELECT `object_id` FROM `cle_wcmc_order_metadata` WHERE ';
			if( count($meta_query) == 1 ) {
				foreach ($meta_query as $data) {
					$sql .= ( !empty($data['key']) ) ? '`meta_key` = \''.$data['key'].'\'' : '';
					$compare = (!empty($data['compare'])) ? $data['compare'] : '=';
					if( !empty($data['value']) ) {
						if( !empty($data['key']) ) $sql .= ' AND ';
						if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {
							$compare = ( $compare == '==' ) ? '' : ' '.$compare ;
							$sql .= '`meta_value`'.$compare.' \''.$data['value'].'\'';
						}
						else if( $compare == 'LIKE' ) {
							$sql .= '`meta_value` LIKE \'%'.$data['value'].'%\'';
						}
						else if( $compare == 'NOT LIKE' ) {
							$sql .= '`meta_value` NOT LIKE \'%'.$data['value'].'%\'';
						}
					}
				}
			}
			else {
				foreach ($meta_query as $data) {
					$sql .= '(';
					$sql .= ( !empty($data['key']) ) ? '`meta_key` = \''.$data['key'].'\'' : '';
					$compare = (!empty($data['compare'])) ? $data['compare'] : '=';
					if( !empty($data['value']) ) {
						if( !empty($data['key']) ) $sql .= ' AND ';
						if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {
							$compare = ( $compare == '==' ) ? '' : ' '.$compare ;
							$sql .= '`meta_value`'.$compare.' \''.$data['value'].'\'';
						}
						else if( $compare == 'LIKE' ) {
							$sql .= '`meta_value` LIKE %'.$data['value'].'%';
						}
						else if( $compare == 'NOT LIKE' ) {
							$sql .= '`meta_value` NOT LIKE %'.$data['value'].'%';
						}
					}
					$sql .= ') '.$relation.' ';
				}
				$sql = trim( $sql, ' '.$relation.' ' );
			}
			$sql .= ' GROUP BY `object_id`';
			$model->settable('wcmc_order_metadata');
			$query = $model->query($sql);
			foreach ($query->result() as $row ) {
				$meta[] = $row->object_id;
			}
			if( have_posts($meta) ) {
				$data = array( 'field' => 'id', 'data' => $meta );
				$model->settable('wcmc_order');
				$orders = $model->count_where_in( $data, $where, $params);
			}
		}
        else $orders =  $model->count_where($where, $params );
        return $orders;
	}
}
if( !function_exists('update_order') ) {
	/**
	 * [update_order cập nhật thông tin đơn hàng]
	 * @since 2.3.4
	 */
	function update_order( $data = '', $args = '' ) {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order');
		if( is_numeric($args) ) $args = array( 'id' => (int)$args );
		if( !have_posts($args) ) return array();
		if( have_posts( $data )) {
			return $model->update_where( $data, $args );
		}
        return false;
	}
}
if( !function_exists('insert_order') ) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function insert_order( $order = '', $metadata = '' ) {
		$ci =& get_instance();
        $model = get_model('products');
        $model->settable('wcmc_order');
        $user = get_user_current();
        if ( ! empty( $order['id'] ) ) {
			$id 			= (int) $order['id'];
			$update 	   = true;
			$old_order = get_order( $id, false, false );
			if ( ! $old_order ) return new SKD_Error( 'invalid_order_id', __( 'ID đơn hàng không chính xác.' ) );
			$user_updated = ( have_posts($user) ) ? $user->id : 0;
			$user_created = $old_order->user_created;
		}
		else {
			$update = false;
			$user_updated = 0;
			$user_created = ( have_posts($user) ) ? $user->id : 0;
		}
		if ( (empty($order['items']) || !have_posts($order['items'])) && !$update ) return new SKD_Error( 'empty_order_item', __( 'Đơn hàng không có sản phẩm nào.' ) );
		$items =  empty( $order['items'] ) ? array() : $order['items'];
		if(!empty($order['user_created'])) $user_created = (int)$order['user_created'];
		if( $update == false) {
			$total   = empty( $order['total'] ) ? 0 : (int)$order['total'];
			$status  = empty( $order['status'] ) ? 'wc-wait-confim' : removeHtmlTags($order['status']);
		}	
		else {
			$total   = empty( $order['total'] ) ? $old_order->total : (int)$order['total'];
			$status  = empty( $order['status'] ) ? $old_order->status : removeHtmlTags($order['status']);
		}
		if(!empty($order['user_share'])) $user_share = (int)$order['user_share'];
		$code    = empty( $order['code'] ) ? '' : removeHtmlTags($order['code']);
		if( $update ) $code = $old_order->code;
		$data = compact( 'code', 'total', 'status', 'user_created', 'user_updated','user_share');
	    $data = apply_filters( 'pre_insert_order_data', $data, $update, $update ? (int) $id : null );
	    if( $update ) {
	    	$model->settable('wcmc_order');
	    	$model->update_where( $data, compact( 'id' ) );
	    	$order_id = $id;
	    }
	    else{
	    	$model->settable('wcmc_order');
	    	$order_id = $model->add( $data );
	    	$model->update_where( array('code' => wcmc_order_creat_code( $order_id ) ), array( 'id'=> $order_id ));
	    	//insert product item
	    	foreach ($items as $item) {
	    		$meta_item = array();
	    		if( !empty($item['metadata']) && have_posts($item['metadata']) ) {
	    			$meta_item = $item['metadata'];
	    			unset($item['metadata']);
	    		}
	    		insert_order_item( $item, $order_id, $meta_item );
	    	}
	    	//insert metada
	    	if( have_posts($metadata) ) {
	    		foreach ($metadata as $meta_key => $meta_value) {
	    			update_order_meta( $order_id, $meta_key, removeHtmlTags($meta_value) );
	    		}
	    	}
	    }
	    if( !is_skd_error($order_id) && $update ) delete_cache( 'order_'.$order_id, true );
		return $order_id;
	}
}
if( !function_exists('delete_order_by_id') ) {
	function delete_order_by_id( $order_id = '' ) {
		$ci =& get_instance();
        $model = get_model('products');
        delete_order_item_by( 'order_id', $order_id );
        delete_order_meta($order_id);
        $model->settable('wcmc_order');
        if( $model->delete_where( array( 'id' => $order_id ) ) ) {
        	delete_cache( 'order_'.$order_id, true );
        	$model->settable('products');
        	return true;
        }
        $model->settable('products');
        return false;
	}
}
if( !function_exists('get_order_meta') ) {
	function get_order_meta( $order_id, $key = '', $single = true) {
		$data = get_metadata('wcmc_order', $order_id, $key, $single);
		return $data;
	}
}
if( !function_exists('update_order_meta') ) {
	function update_order_meta($order_id, $meta_key, $meta_value) {
		delete_cache( 'order_'.$order_id, true );
		return update_metadata('wcmc_order', $order_id, $meta_key, $meta_value);
	}
}
if( !function_exists('delete_order_meta') ) {
	function delete_order_meta($order_id, $meta_key = '', $meta_value = '') {
		return delete_metadata('wcmc_order', $order_id, $meta_key, $meta_value);
	}
}
/**
 * ORDER ITEM
 */
if(!function_exists('get_order_item' ) ) {
	function get_order_item( $args = '', $metadata = true ) {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order_detail');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) return array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where 	= $args['where'];
        $params = $args['params'];
        $item =  $model->get_where($where, $params );
        return $item;
	}
}
if(!function_exists('gets_order_item' ) ) {
	function gets_order_item( $args = '', $metadata = true ) {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('wcmc_order_detail');
		if( is_numeric($args) ) $args = array( 'where' => array('order_id' => (int)$args ) );
		if( !have_posts($args) ) return array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where 	= $args['where'];
        $params = $args['params'];
        $items =  $model->gets_where($where, $params );
        $model->settable('products');
        return $items;
	}
}
if(!function_exists('delete_order_item_by' ) ) {
	function delete_order_item_by( $field = '', $data = '' ) {
		$ci =& get_instance();
		if ( ! $field ) return new SKD_Error( 'empty_order_item_field', __( 'ID File không được bỏ trống.' ) );
        $model = get_model('products');
        $model->settable('wcmc_order_detail');
        $items = $model->gets_where( array( $field => $data ) );
        if( have_posts($items) ) {
        	foreach ($items as $item) {
        		delete_order_item_meta($item->id);
        		$model->delete_where( array( 'id' => $item->id ) );
        	}
        	return true;
        }
		return 	false;
	}
}
if(!function_exists('insert_order_item' ) ) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function insert_order_item( $item = '', $order_id = 0, $metadata = '' ) {
		$ci =& get_instance();
        $model = get_model('products');
        $model->settable('wcmc_order_detail');
        if ( ! empty( $item['id'] ) ) {
			$id 			= (int) $item['id'];
			$update 	    = true;
			$old_order_item = get_order_item( $id, false, false );
			if ( ! $old_order_item ) return new SKD_Error( 'invalid_order_item_id', __( 'ID item đơn hàng không chính xác.' ) );
		}
		else {
			$update = false;
		}
		if ( empty($order_id) ) return new SKD_Error( 'empty_order_id', __( 'ID Đơn hàng không chính xác.' ) );
		if ( empty($item['product_id']) ) return new SKD_Error( 'empty_product_id', __( 'ID sản phẩm không chính xác.' ) );
		if ( empty($item['title']) ) return new SKD_Error( 'empty_product_title', __( 'ID tiêu đề sản phẩm không được để trống.' ) );
		$product_id    	= (int)$item['product_id'];
		$title    		= removeHtmlTags($item['title']);
		$price   		= empty( $item['price'] ) ? 0 : (int)$item['price'];
		$quantity   	= empty( $item['quantity'] ) ? 0 : (int)$item['quantity'];
		$subtotal 		= empty( $item['subtotal'] ) ? $price*$quantity : (int)$item['subtotal'];
		$option    		= empty( $item['option'] ) ? '' : removeHtmlTags($item['option']);
		$option    		= ( is_array( $option ) || is_object($option) ) ? serialize($option) : removeHtmlTags($option);
		if( !empty( $item['image'] ) ) {
			$image    = removeHtmlTags($item['image']);
			$image    = process_file($image);
		}
		$data = compact( 'order_id', 'image', 'title', 'product_id', 'quantity', 'price', 'subtotal', 'option');
	    $data = apply_filters( 'pre_insert_order_item_data', $data, $update, $update ? (int) $id : null );
	    if( $update ) {
	    	$model->settable('wcmc_order_detail');
	    	$model->update_where( $data, compact( 'id' ) );
	    	$order_item_id = $id;
	    }
	    else {
	    	$model->settable('wcmc_order_detail');
	    	$order_item_id = $model->add( $data );
	    }
	    if( have_posts($metadata) && $order_item_id != 0 ) {
	    	foreach ( $metadata as $meta_key => $meta_value ) {
	    		update_order_item_meta($order_item_id, $meta_key, $meta_value);
	    	}
	    }
		return $order_item_id;
	}
}
if(!function_exists('get_order_item_meta') ) {
	function get_order_item_meta( $order_item_id, $key = '', $single = true) {
		$data = get_metadata('wcmc_order_detail', $order_item_id, $key, $single);
		return $data;
	}
}
if(!function_exists('update_order_item_meta') ) {
	function update_order_item_meta($order_item_id, $meta_key, $meta_value) {
		// delete_cache( 'order_'.$order_id, true );
		return update_metadata('wcmc_order_detail', $order_item_id, $meta_key, $meta_value);
	}
}
if(!function_exists('delete_order_item_meta') ) {
	function delete_order_item_meta($order_item_id, $meta_key = '', $meta_value = '') {
		return delete_metadata('wcmc_order_detail', $order_item_id, $meta_key, $meta_value);
	}
}
/**
 * ORDER STATUS
 */
function order_status() {
	$status = array(
		'wc-wait-confim' => array(
			'label' => __('Chờ xác nhận', 'wc-wait-confim'),
			'color' => '#DFE4E8',
		),
		'wc-confim'      => array(
			'label' => __('Đã xác nhận', 'wc-confim'),
			'color' => '#57d616',  
		),
		'wc-processing'  => array(
			'label' => __('Đang xử lý', 'wc-processing'),
			'color' => '#57d616',  
		),
		'wc-pending'     => array(
			'label' => __('Chờ thanh toán', 'wc-pending'),
			'color' => '#57d616',
		),
		'wc-completed'   => array(
			'label' => __('Đã hoàn thành', 'wc-completed'),
			'color' => 'green',  
		),
		'wc-cancelled'   => array(
			'label' => __('Đã hủy', 'wc-cancelled'),
			'color' => 'red',  
		),
		// 'wc-refunded'    => array(
		// 	'label' => __('Đã hoàn tiền', 'wc-refunded'),
		// 	'color' => 'red',  
		// ),
		// 'wc-failed'      => array(
		// 	'label' => __('Đã thất bại', 'wc-failed'),
		// 	'color' => 'red',  
		// ),
	);
	return apply_filters( 'order_status', $status );
}
function order_status_label( $key = '') {
	$status = order_status();
	if( isset($status[$key]) ) return apply_filters( 'order_status_label', $status[$key]['label'], $key );
}
function order_status_color( $key = '') {
	$status = order_status();
	if( isset($status[$key]) ) return  apply_filters( 'order_status_label', $status[$key]['color'], $key );
}
/**
 * Function hỗ trợ phiên bản nhỏ hơn 2.3.4
 */
if( ! function_exists('wcmc_get_order') ) {
	function wcmc_get_order( $args = '', $detail = true, $metadata = true ) {
        return get_order($args, $detail, $metadata);
	}
}
if( ! function_exists('wcmc_gets_order') ) {
	function wcmc_gets_order( $args = '', $detail = true, $metadata = true ) {
        return gets_order($args, $detail, $metadata);
	}
}
if( ! function_exists('wcmc_count_order') ) {
	/**
	 * [wcmc_count_order điếm sớ lượng đơn hàng]
	 * @since 2.3.1
	 */
	function wcmc_count_order($args = '') {
        return count_order($args);
	}
}
if( !function_exists('wcmc_update_order') ) {
	function wcmc_update_order( $data = '', $args = '' ) {
        return update_order($data, $args);
	}
}
if( !function_exists('wcmc_delete_order_by_id') ) {
	function wcmc_delete_order_by_id( $order_id = '' ) {
        return delete_order_by_id($order_id);
	}
}
if(!function_exists('wcmc_get_item_order' ) ) {
	function wcmc_get_item_order( $args = '', $metadata = true ) {
        return get_order_item($args, $metadata);
	}
}
if(! function_exists('wcmc_gets_item_order' ) ) {
	function wcmc_gets_item_order( $args = '', $metadata = true ) {
        return gets_order_item($args, $metadata);
	}
}
if(!function_exists('wcmc_delete_order_item_by' ) ) {
	function wcmc_delete_order_item_by( $field = '', $data = '' ) {
		return delete_order_item_by($field, $data);
	}
}
function woocommerce_order_status() {
	$status = order_status();
	return apply_filters( 'woocommerce_order_status', $status );
}
function woocommerce_order_status_label( $key = '') {
	$status = order_status_label($key);
	return apply_filters( 'woocommerce_order_status_label', $status, $key );
}
function woocommerce_order_status_color( $key = '') {
	$status = order_status_color($key);
	return  apply_filters( 'woocommerce_order_status_label', $status, $key );
}