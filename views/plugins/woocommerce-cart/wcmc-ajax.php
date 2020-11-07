<?php
function woocommerce_register_ajax() {
	$prefix = 'wcmc_ajax_';
	$ajax_events = array(
		'attribute_load'      	=> false,
		'attribute_add'       	=> false,
		'attribute_add_all'   	=> false,
		'attribute_save'      	=> false,
		'attribute_del'       	=> false,
		'variations_load'     	=> false,
		'variations_add'      	=> false,
		'variations_save'     	=> false,
		'variations_del'      	=> false,
		'product_variations'  	=> true,
		//tỉnh thành quận huyện
		'load_districts'        => true,
		'load_ward'        		=> true,
		//page giỏ hàng
		'cart_add'            	=> true,
		'cart_update_quantity'  => true,
		//page đơn hàng
		'checkout_save'       	=> true,
		'update_order_review' 	=> true,
		//đơn hàng admin
		'order_del'           	=> false,
		//customer
		'customer_edit'		    => false,
		'customer_active_account' => false,
		'customer_reset_password' => false,
		//order created
		'order_save_customer'	=> false,
		'order_save_review'     => false,
		'order_save_submit' => false
	);
	foreach ( $ajax_events as $ajax_event => $nopriv ) {
		//chạy ở tất cả
		if ( $nopriv ) {
			register_ajax($prefix.$ajax_event);
		}
		//chỉ chạy ở admin
		else {
			register_ajax_admin($prefix.$ajax_event);
		}
	}
}
add_action( 'init', 'woocommerce_register_ajax' );
/**
 * =====================================================================================================================
 * THUỘC TÍNH SẢN PHẨM
 * =====================================================================================================================
 */
/** [wcmc_ajax_attribute_add click thêm thuộc tính vào sản phẩm] */
function wcmc_ajax_attribute_add($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Thêm dữ liệu không thành công!';
	if($ci->input->post()) {
		$id 		= (int)$ci->input->post('id');
		$product_id = (int)$ci->input->post('object_id');
		$attribute = get_attribute($id);
		if(have_posts($attribute)) {
			$result['type'] = 'success';
			$result['data'] = wcmc_get_include_cart('admin/views/metabox/html-product-metabox-attribute-item',array('id' => $id, 'product_id' => $product_id, 'attributes_item' => array()),true);
		}
		else {
			$result['message'] = 'Thuộc tính không tồn tại.';
		}
	}
	echo json_encode($result);
}
function wcmc_ajax_attribute_add_all($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Thêm dữ liệu không thành công!';
	if($ci->input->post()) {
		$product_id = (int)$ci->input->post('object_id');
		$attributes_name = $ci->input->post('attribute_names');
		$attributes = gets_attribute();
		if(have_posts($attributes)) {
			$result['data'] = '';
			foreach ($attributes as $attribute) {
				if( have_posts($attributes_name) && in_array( $attribute->id, $attributes_name ) !== false ) continue;
				$result['data'] .= wcmc_get_include_cart('admin/views/metabox/html-product-metabox-attribute-item',array('id' => $attribute->id, 'product_id' => $product_id, 'attributes_item' => array()),true);
			}
			$result['type'] = 'success';
		}
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_add load atr] */
function wcmc_ajax_attribute_load($ci, $model) {
	$result['type'] 	= 'success';
	$result['data'] 	= '';
	if($ci->input->post()) {
		$product_id = (int)$ci->input->post('object_id');
		if($product_id != 0) {
			$attributes = gets_attribute( ['product_id' => $product_id] );
			foreach ($attributes as $key => $value) {
				$result['data'] .= wcmc_get_include_cart( 'admin/views/metabox/html-product-metabox-attribute-item', $value, true );
			}
		}
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_add save lưu thay đổi option item thuộc tính sản phẩm] */
function wcmc_ajax_attribute_save($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Lưu dữ liệu không thành công!';
	if($ci->input->post()) {
		$product_id 	= (int)$ci->input->post('object_id');
		$data 			= $ci->input->post();
		//nếu thêm
		if($product_id == 0) {
			$model->settable('wcmc_session');
			if( !isset($data['attribute_names']) || !have_posts( $data['attribute_names'] ) ) {
				$result['message'] = 'Bạn chưa chọn thuộc tính cho sản phẩm!';
				echo json_encode($result);
				return false;
			}
			$session_id 				= (int)$ci->input->post('session_id');
			$session['session_value'] 	= serialize($data);
			$session['session_expiry'] 	= time() + 24*60*60;
			if( !$session_id ){
				$session['session_key'] 	= md5($ci->data['user']->id.time().$ci->security->get_csrf_hash());
				$session_id = $model->add($session);
			}
			else {
				$model->update_where($session, array('session_id' => $session_id));
			}
			$result['session_id'] 	= $session_id;
		}
		else {
			$attribute 		= array();
			$attribute_value = array();
			if( !empty($data['attribute_names']) && have_posts($data['attribute_names']) ) {
				foreach ($data['attribute_names'] as $key => $value) {
					if(isset($data['attribute_values'][$value]) && have_posts($data['attribute_values'][$value])) {
						$option 							= get_attribute($value);
						$attribute['_op_'.$value]['name'] 	= $option->title;
						$attribute['_op_'.$value]['id'] 	= $option->id;
						foreach ($data['attribute_values'][$value] as $val) {
							$attribute_value['attribute_op_'.$value][] = $val;
						}
						unset($data['attribute_values'][$value]);
					}
					unset($data['attribute_names'][$key]);
				}
			}
			$metabox_attr['_product_attributes'] 		= serialize($attribute);
			$metabox_attr['_product_attributes_value'] 	= $attribute_value;
			//product attributes
			update_product_meta($product_id, 'attributes', $metabox_attr['_product_attributes'] );
			foreach ($metabox_attr['_product_attributes_value'] as $meta_key => $meta_values) {
				$model->settable('relationships');
				$model->delete_where(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes' ));
				foreach ($meta_values as $value) {
					$model->add(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes', 'value' =>  $value ));
				}
			}
		}
		$result['message'] 		= 'Lưu dữ liệu thành công!';
		$result['type'] 		= 'success';
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_del xóa attribute] */
function wcmc_ajax_attribute_del($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Xóa dữ liệu không thành công!';
	if($ci->input->post()) {
		$id 				= (int)$ci->input->post('data');
		$product_id 		= (int)$ci->input->post('product_id');
		//nếu thêm
		if($product_id == 0) {
			$model->settable('wcmc_session');
			$session_id 				= (int)$ci->input->post('session_id');
			$session = $model->get_where(array('session_id' => $session_id));
			//chưa lưu vào session
			if( ! have_posts( $session ) ) {
				$result['type'] = 'success';
				echo json_encode($result);
				return false;
			}
			//đã lưu vào session
			$session->session_value 	= unserialize($session->session_value);
			if(isset($session->session_value['attribute_values'][$id])) {
				unset($session->session_value['attribute_values'][$id]);
			}
			if(isset($session->session_value['attribute_names'][$id])) {
				unset($session->session_value['attribute_names'][$id]);
			}
			if( !isset( $session->session_value ) || !have_posts( $session->session_value )) $session->session_value = array();
			$session->session_value 	= serialize($session->session_value);
			$session = (array)$session;
			$model->update_where($session, array('session_id' => $session_id));
			$result['type'] = 'success';
			$result['message'] = 'Xóa dữ liệu thành công!';
		}
		if($id != 0) {
			$metabox = get_product_meta($product_id, 'attributes' );
			if( have_posts( $metabox->attributes ) ) {
				if( isset( $metabox->attributes['_op_'.$id] )) unset( $metabox->attributes['_op_'.$id] );
				update_product_meta($product_id, 'attributes', $metabox->attributes );
				$model->settable('relationships');
				$model->delete_where(array('object_id' => $product_id, 'object_type' => 'attributes', 'category_id' => 'attribute_op_'.$id));
				$model->settable('product_metadata');
				$variations_metadata = $model->gets_where( array('meta_key'=> 'attribute_op_'.$id) );
				if( have_posts($variations_metadata) ) { 
					foreach ($variations_metadata as $meta) {
						$model->settable('product_metadata');
						delete_metadata( 'product', $meta->object_id, $meta->meta_key );
						$metadata = get_product_meta($meta->object_id );
						$count = 0;
						foreach ($metadata as $key_meta => $meta_value) {
							if(substr($key_meta,0,13) == 'attribute_op_') $count++;
						}
						if( $count == 0 ) {
							delete_product($meta->object_id);
						}
					}
				}
				$result['type'] = 'success';
				$result['message'] = 'Xóa dữ liệu thành công!';
			}
		}
	}
	echo json_encode($result);
}
/**
 * =====================================================================================================================
 * SẢN PHẨM BIẾN THỂ
 * =====================================================================================================================
 */
/** [wcmc_ajax_attribute_add add atr] */
function wcmc_ajax_variations_load($ci, $model) {
	$result['type'] = 'success';
	$result['data'] = '';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('object_id');
		if($id == 0) {
			$session_id = (int)$ci->input->post('session_id');
			$model->settable('wcmc_session');
			$session = $model->get_where(array('session_id' => $session_id));
			if(have_posts($session)) {
				$temp = @unserialize($session->session_value);
				if( isset($temp['attribute_values']) && have_posts($temp['attribute_values']) ) {
					foreach ($temp['attribute_values'] as $key => $value) {
						$attributes['attribute'][$key]['value'] = $value;
					}
				}
				else {
					$result['data'] = notice('error', 'Vui lòng chọn chủng loại cho thuộc tính sau đó bấm lưu để có thể tạo các biến thể');
					echo json_encode($result);
					return false;
				}
				$id = $session_id;
			}
		}
		else {
			$result['type'] = 'success';
			$result['data'] = '';
			$attributes = get_product_meta($id, 'attributes', true);
			if(have_posts($attributes)) {
				$temp = array();
				foreach ($attributes as $key => $value) {
					$temp['attribute'][$value['id']]['id'] 		= $value['id'];
					$temp['attribute'][$value['id']]['value'] 	= array();
					$model->settable('relationships');
					$attributes_item = $model->gets_where(array('object_id' => $id, 'category_id' => 'attribute_op_'.$value['id'], 'object_type' => 'attributes'));
					foreach ($attributes_item as $k => $val) {
						$temp['attribute'][$value['id']]['value'][] = $val->value;
					}
				}
				$attributes = $temp;
			}
		}
		$variations = gets_product(['where' => array('parent_id' => $id, 'type' => 'variations')]);
		if(have_posts($variations) && have_posts($attributes)) {
			foreach ($variations as $key => $value) {
				$ci->data['variation']   	 = $value;
				$ci->data['variations_id']   = $value->id;
				$ci->data['variations_code'] = $value->code;
				$result['data'] .= wcmc_get_include_cart('admin/views/metabox/html-product-metabox-variation-item', $attributes, true);
			}
		}
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_add add atr] */
function wcmc_ajax_variations_add($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Thêm dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('id');
		if($id == 0) {
			$session_id = (int)$ci->input->post('session_id');
			$model->settable('wcmc_session');
			$session = $model->get_where(array('session_id' => $session_id));
			if(have_posts($session)) {
				$session->session_value = unserialize($session->session_value);
				foreach ($session->session_value['attribute_names'] as $key => $value) {
					$attribute[$key] = array('value' => $session->session_value['attribute_values'][$key]);
				}
				$variations = [];
				$variations['parent_id'] = $session_id;
				$variations['status']    = 'draft';
				$variations['type']    	 = 'variations';
				$ci->data['variations_id'] = insert_product($variations);
				$result['type'] = 'success';
				$result['data'] = wcmc_get_include_cart('admin/views/metabox/html-product-metabox-variation-item', array('attribute' => $attribute),true);
			}
		}
		else {
			$product_id = (int)$ci->input->post('id');
			$attributes = get_product_meta($product_id, 'attributes', true);
			if(have_posts($attributes)) {
				$temp = array();
				foreach ($attributes as $key => $value) {
					$temp['attribute'][$value['id']]['id'] 		= $value['id'];
					$temp['attribute'][$value['id']]['value'] 	= array();
					$model->settable('relationships');
					$attributes_item = $model->gets_where(array('object_id' => $product_id, 'category_id' => 'attribute_op_'.$value['id'], 'object_type' => 'attributes'));
					foreach ($attributes_item as $k => $val) {
						$temp['attribute'][$value['id']]['value'][] = $val->value;
					}
				}
				$data['attribute'] = $temp['attribute'];
				$variations = [];
				$variations['parent_id'] = $product_id;
				$variations['status']    = 'draft';
				$variations['type']    	 = 'variations';
				$ci->data['variations_id'] = insert_product($variations);
				$model->settable('relationships');
				if( $ci->data['variations_id'] ) {
					$result['type'] = 'success';
					$result['data'] = wcmc_get_include_cart('admin/views/metabox/html-product-metabox-variation-item', $data,true);
				}
			}
		}
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_add add atr] */
function wcmc_ajax_variations_save($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Lưu dữ liệu không thành công!';
	if($ci->input->post()) {
		$product_id 	= (int)$ci->input->post('object_id');
		$data 			= $ci->input->post();
		unset($data['action']);
		//xử lý dữ liệu
		$data_format = array();
		$codes 		= $data['variable_code']; unset($data['variable_code']);
		$price 		= $data['variable_price']; unset($data['variable_price']);
		$price_sale = $data['variable_price_sale']; unset($data['variable_price_sale']);
		$image 		= $data['upload_image']; unset($data['upload_image']);
		foreach ($codes as $id => $code) {
			$product = [
				'id' 		 => $id, 
				'code' 		 => removeHtmlTags($code),
				'image'		 => process_file($image[$id]),
				'price' 	 => $price[$id],
				'price_sale' => $price_sale[$id],
			];
			insert_product($product);
		}
		if(have_posts($data_format)) {
			foreach ($data_format as $object_id => $metadata) {
				if(have_posts($metadata)) {
					foreach ($metadata as $meta_key => $meta_value) {
						update_product_meta($object_id, $meta_key, $meta_value);
					}
				}
			}
		}
		do_action('woocommerce_save_product_variation', $product_id, $data );
		$result['message'] 		= 'Lưu dữ liệu thành công!';
		$result['type'] 		= 'success';
	}
	echo json_encode($result);
}
/** [wcmc_ajax_attribute_del xóa attribute] */
function wcmc_ajax_variations_del($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Xóa dữ liệu không thành công!';
	if($ci->input->post()) {
		$id 				= (int)$ci->input->post('data');
		$product_id 		= (int)$ci->input->post('product_id');
		$session_id 		= (int)$ci->input->post('session_id');
		$where = array(
			'id' => $id,
			'type' => 'variations'
		);
		if($product_id != 0) {
			$where['parent_id'] = $product_id;
		}
		else if($session_id != 0) {
			$where['parent_id'] = $session_id;
		}
		$variations = count_product(['where' => $where]);
		if($variations != 0 && have_posts($where)) {
			delete_product($id);
			$result['type'] = 'success';
			$result['message'] = 'Xóa dữ liệu thành công!';
		}
	}
	echo json_encode($result);
}
/**
 * =====================================================================================================================
 * TỈNH THÀNH QUẬN HUYỆN
 * =====================================================================================================================
 */
if(!function_exists('wcmc_ajax_load_districts')) {
	function wcmc_ajax_load_districts($ci, $model) {
		$ci =& get_instance();
		$result['type'] = 'error';
		$result['message'] = 'Lưu dữ liệu thất bại';
		if( $ci->input->post() ) {
			$post = $ci->input->post();
			$result['type'] = 'success';
			$result['data'] = '<option value="">Chọn quận huyện</option>';
			if( $post['province_id'] != '') {
				$province_id = removeHtmlTags( $post['province_id'] );
				if(empty($province_id)) {
					echo json_encode( $result );
					return false;
				}
				$district_id = '';
				if(!empty($post['district_id'])) $district_id = removeHtmlTags( $post['district_id'] );
				$districts   = wcmc_shipping_states_districts($province_id);
				if( have_posts( $districts ) ) {
					ksort($districts);
					foreach ($districts as $key => $name ) {
						$result['data'] .= '<option value="'.$key.'" '.(($district_id == $key)?'selected':'').'>'.$name.'</option>';
					}
				}
			}
		}
		echo json_encode( $result );
	}
}
if(!function_exists('wcmc_ajax_load_ward')) {
	function wcmc_ajax_load_ward($ci, $model) {
		$ci =& get_instance();
		$result['type'] = 'error';
		$result['message'] = 'Lưu dữ liệu thất bại';
		if( $ci->input->post() ) {
			$post = $ci->input->post();
			$result['type'] = 'success';
			$result['data'] = '<option value="">Chọn phường xã</option>';
			if( $post['district_id'] != '') {
				$district_id = removeHtmlTags( $post['district_id'] );
				if(empty($district_id)) {
					echo json_encode( $result );
					return false;
				}
				$ward_id = '';
				if(!empty($post['ward_id'])) $ward_id = removeHtmlTags( $post['ward_id'] );
				$ward   = wcmc_shipping_states_ward($district_id);
				if( have_posts( $ward ) ) {
					ksort($ward);
					foreach ($ward as $key => $name ) {
						$result['data'] .= '<option value="'.$key.'" '.(($ward_id == $key)?'selected':'').'>'.$name.'</option>';
					}
				}
			}
		}
		echo json_encode( $result );
	}
}
/**
 * =====================================================================================================================
 * ĐẶT HÀNG
 * =====================================================================================================================
 */
function wcmc_ajax_product_variations($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Lưu dữ liệu không thành công!';
	if($ci->input->post()) {
		$product_id 	= (int)$ci->input->post('product_id');
		$data 			= $ci->input->post();
		unset($data['action']);
		$variations = gets_variations(['product' => $product_id]);
		if(have_posts($variations) && count($data['option'])) {
			$metadata = array();
			foreach ($variations as $variation) {
				if( count($variation->items) != count($data['option'])  ) continue;
				if( !have_posts(array_diff( $variation->items, $data['option'])) ) {
					$result['variation'] 	= $variation;
					$metadata = $variation; break;
				}
			}
			if( have_posts($metadata) ) {
				$result['data'] 		= wcmc_get_template_cart('detail/ajax_price_variations', array('wcmc_data' => $metadata), true );
				$result['type'] 		= 'success';
			}
		}
		else {
			$result['type'] 		= 'warning';
		}
	}
	echo json_encode($result);
}
function wcmc_ajax_cart_add($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Thêm sản phẩm vào giỏ hàng không thành công!';
	if($ci->input->post()) {
		$product_id 	= (int)$ci->input->post('product_id');
		$data 			= $ci->input->post();
		unset($data['action']);
		unset($data['product_id']);
		$model->settable('products');
		$product = $model->get_where(array('id' => $product_id));
		//kiểm tra sản phẩm có tồn tại
		if( !have_posts($product) ) {
			$result['type'] 	= 'error';
			$result['message'] 	= 'Sản phẩm không tồn tại';
			$result['data'] = notice('error', $result['message'], true);
			echo json_encode($result);
			return false;
		}
		$option['product_id'] 		= $product->id;
        $option['product_image'] 	= $product->image;
		$cart = array(
			'id'     => $product->id,
			'qty'    => $data['qty'],
			'price'  => (!empty($product->price_sale))?$product->price_sale:$product->price,
			'name'   => $product->title,
			'option' => $option,
		);
		unset($data['qty']);
		$variations = gets_variations(['product' => $product_id]); //$model->gets_where(array('object_id' => $product_id));
		//có biến thể nhưng không chọn
		if(have_posts($variations) && ( !isset($data) || !have_posts($data) ) ) {
			$result['type'] 	= 'error';
			$result['message'] 	= 'Bạn chưa chọn tùy chọn cho sản phẩm';
			$result['data'] = notice('error', $result['message'], true);
			echo json_encode($result);
			return false;
		}
		//có tùy chọn biến thể
		if(have_posts($variations)) {
			$options = get_product_meta($product_id, 'attributes', true);
			//kiểm tra đã chọn đủ tùy chọn chưa
			if( (have_posts($options) && !isset($data['option'])) || (count($options) != count($data['option'])) ) {
				$result['type'] 		= 'warning';
				$result['message'] 		= 'Bạn chưa chọn đầy đủ tùy chọn cho sản phẩm.';
				$result['data'] 		= notice($result['type'] , $result['message'], true);
				echo json_encode($result);
				return false;
			}
			//lấy thông tin sản phẩm tùy biến nếu có
			foreach ($variations as $key => $variable) {
				$metadata = array();
				if( !have_posts(array_diff( $variable->items, $data['option'])) ) {
					$metadata = $variable;
					$cart['variable'] = $variable->id;
					$cart['id'] 	  = $cart['id'].'_'.$variable->id;
					break;
				}
			}
			//chọn đúng 1 trong các tùy chọn biến thể
			if( !have_posts($metadata) ) {
				$result['message'] 	= 'Shop hiện không kinh doanh sản phẩm này!';
				echo json_encode($result);
				return false;
			}
			$cart['option']['attribute'] = array();
			foreach ($data['option'] as $key => $value) {
				$attribute = get_attribute_item($value);
				$cart['option']['attribute'][] = $attribute->title;
			}
			$cart['name'] = trim($cart['name'], ',');
			$cart['price'] = (!empty($metadata->price_sale))?$metadata->price_sale:$metadata->price;
			$cart['option']['product_image'] = (!empty($metadata->image))?$metadata->image:$cart['option']['product_image'];
			$cart = apply_filters( 'wcmc_cart_add_variations', $cart, $ci->input->post(), $product );
		}
		//sản phẩm bình thường không có tùy biến
		else {
			$cart['option']['attribute'] = array();
			if( isset($data['option']) && have_posts($data['option']) ) {
				foreach ($data['option'] as $key => $value) {
					$attribute = get_attribute_item($value);
					$cart['option']['attribute'][] = $attribute->title;
					$cart['id'] .= '_'.$attribute->id;
				}
			}
			$cart = apply_filters( 'wcmc_cart_add_no_variations', $cart, $ci->input->post(), $product );
		}
		$list_cart = $ci->cart->contents();
		foreach ($list_cart as $item) {
			if( $item['id'] == $cart['id'] ) {
				$cart['qty'] += $item['qty'];
			}
		}
		$cart = apply_filters( 'wcmc_cart_add', $cart, $ci->input->post(), $product, $variations );
		if($ci->cart->insert($cart)) {
			$result['total_items']     = $ci->cart->total_items();
            $result['type']     = 'success';
            $result['message'] 	= 'Sản phẩm <strong>'.$product->title.'</strong> đã được thêm vào giỏ hàng!';
        }
	}
	$result['data'] = notice($result['type'], $result['message']);
	echo json_encode($result);
}
function wcmc_ajax_cart_update_quantity($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Cập nhật dữ liệu thất bại.';
	if($ci->input->post()) {
		$rowid 	= removeHtmlTags($ci->input->post('rowid'));
		$qty 	= removeHtmlTags($ci->input->post('qty'));
		if(is_numeric($qty)) {
			$data = array( 'rowid' => $rowid, 'qty' => $qty );
			$ci->cart->update($data);
			do_action('wcmc_cart_update_quantity', $rowid, $qty);
			$result['type'] = 'success';
			$item = $ci->cart->get_item($rowid);
			$result['qty'] 		= $qty;
			$result['price'] 	= $item['price'];
			$result['total'] 	= number_format($ci->cart->total());
			$result['summary_total'] = number_format(wcmc_order_total());
		}
	}
	echo json_encode($result);
}
/**
 * [wcmc_ajax_checkout_save lưu dữ liệu khi submit]
 */
function wcmc_ajax_checkout_save( $ci, $model ) {
	$result['type'] = 'error';
	$result['message'] = 'Đặt hàng không thành công!';
	if($ci->input->post()) {
		$data 			= $ci->input->post();
		$cart 			= $ci->cart->contents();
		$rules = array();
		$billings 	= get_chekout_fields_billing();
		$shippings 	= get_chekout_fields_shipping();
		$orders 	= get_chekout_fields_order();
		$inputs 	= array_merge($billings, $shippings);
		$inputs 	= array_merge($inputs, $orders);
		$rules 		= checkout_fields_rules();
		$rules 		= apply_filters('woocommerce_checkout_rules', $rules);
		if(isset($rules['billings']) && have_posts($rules['billings'])) {
			$ci->form_validation->set_rules($rules['billings']);
		}
		$show_form_shipping = trim(removeHtmlTags($ci->input->post('show-form-shipping')));
		if($show_form_shipping == 'on' && isset($rules['shippings']) && have_posts($rules['shippings'])) {
			$ci->form_validation->set_rules($rules['shippings']);
		}
		if($ci->form_validation->run() == false) {
			$errors = $ci->form_validation->error_array();
			foreach($errors as $error_key => $error_value) {
				wcmc_add_notice( $error_value, $error_key );
			}
		}
		if(!have_posts($cart)) {
			wcmc_add_notice('Không có sản phẩm nào trong giỏ hàng.', 'error' );
		}
		do_action('woocommerce_checkout_process');
		$errors = wcmc_get_notices();
		if( have_posts($errors) ) {
			$result['message'] = [];
			foreach ($errors as $key_error => $list_error) {
				foreach ($list_error as $message) {
					if(empty($result['message'][$key_error])) $result['message'][$key_error] = '';
					if($key_error == 'error' ) $result['message'][$key_error] .= wcmc_print_notice( $message, 'error' );
					else $result['message'][$key_error] .= $message;
				}
			}
			echo json_encode( $result );
        	die;
		}
        if( $ci->form_validation->run() ) {
        	$metadata_order = array();
        	foreach ($inputs as $key => $input) {
        		if( isset( $data[$key]) ) $metadata_order[$key] = removeHtmlTags( $data[$key] );
			}
			if(!empty($data['shipping_type'])) {
				$shipping_type 			= removeHtmlTags($data['shipping_type']);
				$shipping_list 			= woocommerce_cart_settings_tabs_shipping();
				$shipping_list_config 	= get_option('wcmc_shipping', []);
				foreach ($shipping_list as $key => $ship) {
					if(isset($shipping_list_config[$key]) && $shipping_type == $key ) {
						$key_temp = str_replace( '-', '_', $key);
						$metadata_order['_shipping_label'] 	= $shipping_list_config[$key]['label'];
						$metadata_order['_shipping_type'] 	= $shipping_type;
						$metadata_order['_shipping_price'] 	= apply_filters('wcmc_shipping_price_'.$key_temp, 0 );
					}
				}
			}
			$order['total'] = $ci->cart->total();
			if(!empty($metadata_order['_shipping_price']) && is_numeric($metadata_order['_shipping_price'])) {
				$order['total'] = $order['total'] + $metadata_order['_shipping_price'];
			}
			$metadata_order['quantity'] = 0;
			$metadata_order['other_delivery_address'] = false;
			if($show_form_shipping == 'on') $metadata_order['other_delivery_address'] = true;
            foreach ($cart as $key => $item) {
                $order_detail = [
                    'product_id' => $item['option']['product_id'],
                    'title'      => $item['name'],
                    'quantity'   => $item['qty'],
                    'image'      => $item['option']['product_image'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                    'metadata'   => array(),
                ];
                unset($item['option']['product_id']);
                unset($item['option']['product_image']);
                if( isset($item['option']['attribute']) ) {
                	$order_detail['option'] = serialize($item['option']['attribute']);
                	$order_detail['metadata']['attribute'] = $item['option']['attribute'];
                }
                if( !empty($item['variable']) ) {
                	$order_detail['metadata']['variable'] = (int)$item['variable'];
                }
                $order_detail = apply_filters('woocommerce_checkout_order_detail_before_save', $order_detail, $item);
                $order['items'][] = $order_detail;
                $metadata_order['quantity'] += $order_detail['quantity'];
            }
            $order = apply_filters('woocommerce_checkout_order_before_save', $order, $metadata_order, $data, $cart);
           	if (is_user_logged_in()) {
           		$user = get_user_current();
           		$order['user_share']=$user->id;
           	}
            if (isset($_COOKIE['user_affiliate'])) {
            	$cookie=$_COOKIE['user_affiliate'];
            	$where=array('cookie'=>$cookie);
            	$tontai=get_affiliate_history(array('where' => $where, 'params' => array()));
            	$order['user_share']=$tontai->user_id;
            }


           
            $id    = insert_order( $order, $metadata_order );
            if( !is_skd_error( $id ) ) {
				do_action('woocommerce_checkout_order_after_save', $id, $data);
				/**
				 * Thêm khách hàng nếu khách hàng chưa tồn tại
				 *  */
				if(!is_user_logged_in()) {
					$customer = get_user_by('email', $metadata_order['billing_email']);
					$order = wcmc_get_order($id);
					if(have_posts($customer)) {
						$customer->order_count += 1;
						//Nếu đang là nhân viên (0) thì chuyển thành nhân viên và khách hàng (2)
						if($customer->customer == 0) $customer->customer 	= 2;
						else $customer->customer 	= 1;
						insert_user((array)$customer);
						update_user_meta( $customer->id, 'order_recent', $order->code);
					}
					else {
						//Thêm mới user
						$fullname =  explode(' ', $metadata_order['billing_fullname']);
						$lastname 	= array_pop($fullname);
						$firstname 	= str_replace( ' '.$lastname, '', $metadata_order['billing_fullname'] );
						$customer = [
							'firstname' 	=> $firstname,
							'lastname'  	=> $lastname,
							'email'			=> $metadata_order['billing_email'],
							'phone'			=> $metadata_order['billing_phone'],
							'order_total' 	=> 0,
							'order_count' 	=> 1,
							'status' 	    => 'public',
							'customer' 	    => 1
						];
						$model->settable('users');
						$user_id = $model->add($customer);
						user_set_role( $user_id, 'customer');
						update_user_meta( $user_id, 'order_recent', $order->code);
						update_user_meta( $user_id, 'city', 		$order->billing_city);
						update_user_meta( $user_id, 'districts', 	$order->billing_districts);
						update_user_meta( $user_id, 'address', 		$order->billing_address);
						insert_order([
							'id' => $id,
							'user_created' => $user_id
						]);
						delete_cache( 'user_', true );
					}
				}
				else {
					$order = wcmc_get_order($id);
					$customer = get_user_current();
					$customer->order_count += 1;
					if($customer->customer == 0) $customer->customer = 2;
					else $customer->customer = 1;
					insert_user((array)$customer);
					// $customer = get_user_by('email', $metadata_order['billing_email']);
					update_user_meta($customer->id, 'order_recent', $order->code);
					if(!empty($order->billing_city)) 		update_user_meta($customer->id, 'city', $order->billing_city);
					if(!empty($order->billing_districts)) 	update_user_meta($customer->id, 'districts', $order->billing_districts);
					update_user_meta($customer->id, 'address', 		$order->billing_address);
				}
				/**
				 * Tạo token trả về trang thanh toán thành công
				 */
                $token = md5(time());
                $_SESSION['token'] = $token;
                $url = get_url('don-hang').'?id='.$id.'&token='.$token;
                $ci->cart->destroy();
                do_action('woocommerce_checkout_after_success', $id);
                $result['type'] 	= 'success';
				$result['message'] 	= 'Đặt hàng thành công!';
                $result['url'] 		= $url;
                $result = apply_filters('woocommerce_checkout_result_success', $result, $id );
            }
            else {
            	foreach ($error->errors as $error_key => $error_value) {
            		$result['message']['error'] = $error_value[0];
				}
            }
        }
	}
	echo json_encode( $result );
}
function wcmc_ajax_update_order_review( $ci, $model ) {
	$result['type'] = 'error';
	$result['message'] = 'Load dữ liệu không thành công!';
	if($ci->input->post()) {
		$data                   = $ci->input->post();
		$data['cart']           = $ci->cart->contents();
		if(!empty($data['shipping_type'])) {
			$shipping_type = removeHtmlTags($data['shipping_type']);
			$shipping 		= woocommerce_cart_settings_tabs_shipping();
			$wcmc_shipping = get_option('wcmc_shipping', []);
			foreach ($shipping as $key => $ship) {
				if(isset($wcmc_shipping[$key]) && $wcmc_shipping[$key]['enabled'] == false) continue;
				$key_temp = str_replace( '-', '_', $key);
				$data['wcmc_shipping_price_'.$key_temp] = apply_filters('wcmc_shipping_price_'.$key_temp, 0 );
				if($key == $shipping_type) {
					$data['wcmc_shipping_price'] = $data['wcmc_shipping_price_'.$key_temp];
				}
			}
		}
		$ci->data['wcmc_cart_checkout'] = $data;
		$result['type']         = 'success';
		$result['order_review'] = wcmc_get_template_cart('checkout/order-review', $data,true);
		$result = apply_filters('wcmc_ajax_update_order_review', $result, $data );
	}
	echo json_encode( $result );
}
/**
 * =====================================================================================================================
 * ĐƠN HÀNG ADMIN
 * =====================================================================================================================
 */
/** [wcmc_ajax_order_del xóa đơn hàng item] */
function wcmc_ajax_order_del($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Xóa dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('data');
		if($id != 0) {
			$object = wcmc_get_order( $id );
			if(have_posts($object)) {
				if( wcmc_delete_order_by_id($id) ) {
					do_action('before_woocommerce_order_del_success', $object );
					$result['type'] = 'success';
					$result['message'] = 'Xóa dữ liệu thành công!';
				}			
			}
			else $result['message'] = 'Dữ liệu không tồn tại!';
		}
	}
	echo json_encode($result);
}
/**
 * =====================================================================================================================
 * KHÁCH HÀNG
 * =====================================================================================================================
 */
function wcmc_ajax_customer_edit($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Cập nhật dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('customer_id');
		$customer = get_user($id);
		if(have_posts($customer)) {
			$data = $ci->input->post();
			do_action('woocommerce_customer_edit_process');
			$messages = wcmc_get_notices( 'error' );
			if( have_posts($messages) ) {
				$result['message'] = '';
				foreach ($messages as $message) {
					$result['message'] .= wcmc_print_notice_label( $message, 'error' );
				}
				echo json_encode( $result );
				die;
			}
			$customer_array['firstname'] 	= removeHtmlTags($data['firstname']);
			$customer_array['lastname'] 	= removeHtmlTags($data['lastname']);
			$customer_array['phone'] 		= removeHtmlTags($data['phone']);
			$customer_meta['address'] 		= removeHtmlTags($data['address']);
			$customer_meta['city'] 			= removeHtmlTags($data['city']);
			$customer_meta['districts'] 	= removeHtmlTags($data['districts']);
			$customer_array = array_merge((array)$customer, $customer_array);
			$customer_array = apply_filters('customer_edit_data', $customer_array, $customer);
			$customer_meta  = apply_filters('customer_edit_meta', $customer_meta, $customer);
			$error = insert_user($customer_array);
			if(!is_skd_error($error)) {
				foreach ($customer_meta as $meta_key => $meta_value) {
					update_user_meta($customer->id, $meta_key, $meta_value);
				}
				do_action('before_woocommerce_customer_edit_success', $id );
				$result['type'] = 'success';
				$result['message'] = 'Cập nhật dữ liệu thành công!';
			}
		}
		else $result['message'] = 'Dữ liệu không tồn tại!';
	}
	echo json_encode($result);
}
function wcmc_ajax_customer_active_account($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Cập nhật dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('customer_id');
		$customer = get_user($id);
		if(have_posts($customer)) {
			$data = $ci->input->post();
			if(empty($ci->input->post('password')) ) {
				wcmc_add_notice('Mật khẩu không được bỏ trống.', 'error');
			}
			if(empty($ci->input->post('re_password')) ||  $ci->input->post('re_password') != $ci->input->post('password') ) {
				wcmc_add_notice('Nhập lại mật khẩu không trùng khớp.', 'error');
			}
			if(empty($ci->input->post('username')) ) {
				wcmc_add_notice('Không thể tạo user khi tên đăng nhập trống.', 'error');
			} elseif (mb_strlen($ci->input->post('username')) > 60 ) {
				wcmc_add_notice('Tên đăng nhập không thể lớn hơn 60 ký tự.', 'error');
			}
			if(username_exists($ci->input->post('username')) ) {
				wcmc_add_notice('Xin lỗi, Tên đăng nhập đã tồn tại!', 'error');
			}
			do_action('woocommerce_customer_active_account_process');
			$messages = wcmc_get_notices( 'error' );
			if( have_posts($messages) ) {
				$result['message'] = '';
				foreach ($messages as $message) {
					$result['message'] .= wcmc_print_notice_label( $message, 'error' );
				}
				echo json_encode( $result );
				die;
			}
			$customer_array['username'] 	= removeHtmlTags($data['username']);
			$customer_array['salt'] 		= random(32, TRUE);
			$customer_array['password'] 	= removeHtmlTags( $ci->input->post('password') );
			$customer_array['password'] 	= generate_password( $customer_array['password'], $customer_array['username'], $customer_array['salt'] );
			$model->settable('users');
			if($model->update_where($customer_array, ['id' => $customer->id])) {
				do_action('before_woocommerce_customer_edit_success', $id );
				delete_cache( 'user_', true );
				$result['type'] = 'success';
				$result['message'] = 'Cập nhật dữ liệu thành công!';
			}
		}
		else $result['message'] = 'Dữ liệu không tồn tại!';
	}
	echo json_encode($result);
}
function wcmc_ajax_customer_reset_password($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Cập nhật dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('customer_id');
		$customer = get_user($id);
		if(have_posts($customer)) {
			$data = $ci->input->post();
			if(empty($ci->input->post('password')) ) {
				wcmc_add_notice('Mật khẩu không được bỏ trống.', 'error');
			}
			if(empty($ci->input->post('re_password')) ||  $ci->input->post('re_password') != $ci->input->post('password') ) {
				wcmc_add_notice('Nhập lại mật khẩu không trùng khớp.', 'error');
			}
			do_action('woocommerce_customer_reset_password_process');
			$messages = wcmc_get_notices( 'error' );
			if( have_posts($messages) ) {
				$result['message'] = '';
				foreach ($messages as $message) {
					$result['message'] .= wcmc_print_notice_label( $message, 'error' );
				}
				echo json_encode( $result );
				die;
			}
			$customer_array 				= (array)$customer;
			$customer_array['password'] 	= removeHtmlTags( $ci->input->post('password') );
			$error = update_user( $customer_array );
			if(!is_skd_error($error)) {
				do_action('before_woocommerce_customer_reset_password_success', $id );
				$result['type'] = 'success';
				$result['message'] = 'Cập nhật dữ liệu thành công!';
			}
		}
		else $result['message'] = 'Dữ liệu không tồn tại!';
	}
	echo json_encode($result);
}
/**
 * =====================================================================================================================
 * TẠO ĐƠN HÀNG
 * =====================================================================================================================
 */
function wcmc_ajax_order_save_customer($ci, $model) {
	$result['type'] = 'error';
	$result['message'] = 'Cập nhật dữ liệu không thành công!';
	if($ci->input->post()) {
		$id = (int)$ci->input->post('id');
		$customer = get_user($id);
		if(have_posts($customer)) {
			$result['customer_review'] 	= wcmc_get_template_cart('admin/order/save/customer-infomation', [
				'customer' 	=> $customer,
			],true);
			$result['type'] 			= 'success';
			$result['message'] 			= 'Cập nhật dữ liệu thành công!';
		}
		else $result['message'] = 'Dữ liệu không tồn tại!';
	}
	echo json_encode($result);
}
function wcmc_ajax_order_save_review( $ci, $model ) {
	$result['type'] = 'error';
	$result['message'] = 'Load dữ liệu không thành công!';
	if($ci->input->post()) {
		$data                   = $ci->input->post();
		$order_provisional = 0;
		if(isset($data['line_items'])) {
			foreach ($data['line_items'] as $key => $productData) {
				$order_provisional += $productData['productPrice']*$productData['productQuantity'];
			}
		}
		$order_total = $order_provisional;
		$result['type']         = 'success';
		$result['order_review'] = wcmc_get_template_cart('admin/order/save/amount-review',[
			'order_provisional' => $order_provisional,
			'total'				=> $order_total
		],true);
		$result = apply_filters('wcmc_ajax_order_save_review', $result, $data );
	}
	echo json_encode( $result );
}
function wcmc_ajax_order_save_submit( $ci, $model ) {
	$result['type'] = 'error';
	$result['message'] = 'Load dữ liệu không thành công!';
	if($ci->input->post()) {
		$data 			= $ci->input->post();
		$rules = array();
		$billings 	= get_chekout_fields_billing();
		$shippings 	= get_chekout_fields_shipping();
		$inputs 	= array_merge($billings, $shippings);
		$product_items = $ci->input->post('line_items');
		/**
		 * KIỂM TRA CÁC ĐIỀU KIỆN INPUT
		 */
		foreach ($inputs as $key => $input) {
			if(!empty($input['rules'])) $rules[] = array( 'field'   => $key, 'label' => ( isset($input['label_error']) ) ? $input['label_error'] : $input['label'], 'rules' => $input['rules']);
		}
		$rules = apply_filters('woocommerce_checkout_rules', $rules);
		$ci->form_validation->set_rules($rules);
		if( $ci->form_validation->run() == false )  {
			foreach($rules as $row) {
				$field = $row['field'];
				$error = form_error($field);
                if($error) wcmc_add_notice(  $error, 'error' );
			}
		}
		do_action('woocommerce_checkout_process');
		/**
		 * KIỂM TRA ĐIỀU KIỆN SẢN PHẨM
		 */
		if(!have_posts($product_items)) {
			wcmc_add_notice(  'Đơn hàng chưa có sản phẩm nào.', 'error' );
		}
		$messages = wcmc_get_notices( 'error' );
		if( have_posts($messages) ) {
			$result['message'] = '';
			foreach ($messages as $message) {
				$result['message'] .= '<p>'.wcmc_print_notice_label( $message, 'error' ).'</p>';
			}
			echo json_encode( $result );
        	die;
        }
		$order 			= array();
		$metadata_order = array();
		foreach ($inputs as $key => $input) {
			if( isset( $data[$key]) ) $metadata_order[$key] = removeHtmlTags( $data[$key] );
		}
		$order['total'] 			= 0;
		$order['user_created'] 		= removeHtmlTags($data['customer_id']);
		$metadata_order['quantity'] = 0;
		foreach ($product_items as $key => $item) {
			$product = get_product($item['productID']);
			if(have_posts($product)) {
				$order_detail = [
					'product_id' => $product->id,
					'title'		 => $product->title,
					'quantity'   => (int)$item['productQuantity'],
					'image'      => $product->image,
					'price'      => $item['productPrice'],
					'subtotal'   => $item['productQuantity']*$item['productPrice'],
					'metadata'   => [],
				];
				if($item['productVariation'] != 0) {
					$order_detail['metadata']['variable'] = (int)$item['productVariation'];
					$variation = get_variations($item['productVariation']);
					$attr_name = [];
					foreach ($variation->items as $attr_id) {
						$attr = get_attribute_item($attr_id);
						if( have_posts($attr)) $attr_name[] = $attr->title;
					}
					if(have_posts($attr_name)) {
						$order_detail['option'] 				= serialize($attr_name);
						$order_detail['metadata']['attribute']  = $attr_name;
					}
				}
				$order_detail = apply_filters('woocommerce_checkout_order_detail_before_save', $order_detail, $item);
				$order['items'][] 			 = $order_detail;
				$order['total'] 			+= $order_detail['subtotal'];
				$metadata_order['quantity'] += $order_detail['quantity'];
			}
		}
		$order = apply_filters('woocommerce_checkout_order_before_save', $order, $metadata_order, $data);
		$id = insert_order( $order, $metadata_order );
		if( !is_skd_error( $id ) ) {
			do_action('woocommerce_order_save_after_save', $id, $data);
			$customer = get_user($data['customer_id']);
			if(have_posts($customer)) {
				$order = wcmc_get_order($id);
				$customer->order_count += 1;
				insert_user((array)$customer);
				update_user_meta( $customer->id, 'order_recent', $order->code);
			}
			$result['type'] = 'success';
			$result['message'] = 'Lưu đơn hàng thành công!';
			$result = apply_filters('woocommerce_order_save_result_success', $result, $id );
		}
		else {
			foreach ($error->errors as $error_key => $error_value) {
				$result['message'] = $error_value[0];
			}
		}
	}
	echo json_encode( $result );
}