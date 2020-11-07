<?php
function woocommerce_options_type_label( $label ) {

	if($label == 'label') $label = 'Text (Label)';

	if($label == 'color') $label = 'Màu (Color)';

	if($label == 'image') $label = 'Hình (Image)';
	
	return apply_filters( 'woocommerce_options_type_label' , $label );
}

function woocommerce_attributes_item_type( $value, $type ) {

	$output = '';

	if($type == 'label') $output = '<p>'.$value.'</p>';

	if($type == 'image') $output = get_img($value, '', array('style'=>'max-width:50px;'), 'medium', true);

	if($type == 'color') $output = '<div style="background-color:'.$value.';border-radius:50%; width:30px; height:30px;box-shadow: 0px 0px 8px #ccc;"></div>';

	return apply_filters('woocommerce_attributes_item_type', $output);
}

/*===================== ATTRIBUTE ===============================*/
function get_attribute( $args = [] ) {

	$ci =& get_instance();

	$model = get_model('plugins','backend');

	$model->settable('wcmc_options');

	$model->settable_metabox('metabox');

	if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

	if( !have_posts($args) ) $args = array();

	$args = array_merge( array('where' => array(), 'params' => array() ), $args );

	$attribute = $model->get_data( $args, 'wcmc_attribute' );

	return apply_filters('get_attribute', $attribute, $args);
}

function gets_attribute( $args = [] ) {

	$ci =& get_instance();

	$model = get_model('plugins','backend');

	$model->settable('wcmc_options');

	$model->settable_metabox('metabox');

	if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

	if( !have_posts($args) ) $args = array();

	$args = array_merge( array('where' => array(), 'params' => array() ), $args );

	if(isset($args['product_id'])) {

		$product_id = (int)$args['product_id'];

		$resutl = [];

		$attributes = get_metadata( 'product', $product_id, 'attributes', true );

		if( have_posts($attributes) ) {

			foreach ($attributes as $key => $value) {

				$option = get_attribute($value['id']);

				if( have_posts($option) ) {

					$resutl[$key]['id'] 		 = $value['id'];

					$resutl[$key]['product_id']  = $product_id;

					$resutl[$key]['title'] 		 = $option->title;

					$resutl[$key]['option_type'] = $option->option_type;

					$model->settable('relationships');

					$attributes_item = $model->gets_where(array('object_id' => $product_id, 'category_id' => 'attribute_op_'.$value['id'], 'object_type' => 'attributes'));
					
					foreach ($attributes_item as $key_item => $value_item) {

						$attributes_item[$key_item] = $value_item->value;

					}

					$resutl[$key]['items'] 			 = gets_attribute_item( [
						'product' => [
							'product_id' => $product_id,
							'attribute'	 => 'attribute'.$key
						],
					]);

					$resutl[$key]['attributes_item'] = $attributes_item;

				}

			}

		}

		$attributes = $resutl;

	}
	else $attributes = $model->gets_data( $args, 'wcmc_attribute' );

	return apply_filters('gets_attribute', $attributes, $args);
}

function get_attribute_item( $args = [] ) {

	$ci =& get_instance();

	$model = get_model('plugins','backend');

	$model->settable('wcmc_options_item');

	$model->settable_metabox('metabox');

	if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

	if( !have_posts($args) ) $args = array();

	$args = array_merge( array('where' => array(), 'params' => array() ), $args );

	$attribute = $model->get_data( $args, 'wcmc_attribute_item' );

	return apply_filters('get_attribute_item', $attribute, $args);
}

function gets_attribute_item( $args = [] ) {

	$ci =& get_instance();

	$model = get_model('plugins','backend');

	$model->settable('wcmc_options_item');

	$model->settable_metabox('metabox');

	if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

	if( !have_posts($args) ) $args = array();

	$args = array_merge( array('where' => array(), 'params' => array() ), $args );

	if(isset($args['attribute'])) {

		$attributes = $model->gets_where(array('option_id' => $args['attribute']));
	}
	else if(isset($args['product'])) {

		$product_id 	= $args['product']['product_id'];

		$attribute_op 	= $args['product']['attribute'];

		$model->settable('relationships');

		$attributes 		=  $model->gets_where(array('object_id' => $product_id, 'category_id' => $attribute_op, 'object_type' => 'attributes' ));

		$list_attributes_id =  array();

		if(have_posts($attributes)) {

			foreach ($attributes as $key => $attribute) {
				$list_attributes_id[] = $attribute->value;
			}

			$model->settable('wcmc_options_item');

			// ksort($list_attributes_id);
			// $list_attributes_id2 = implode(',',$list_attributes_id);
			// show_r($list_attributes_id2);

			// $model->db->_protect_identifiers = false;
			// $model->db->order_by('FIELD (id,'.$list_attributes_id2.')');
			// $model->db->_protect_identifiers = true;

			$attributes = $model->gets_where_in(
				array('field' => 'id', 'data' => $list_attributes_id), 
				array(), 
				array(
					'select' => 'id, title, value, image, type, order',
					'orderby' => 'order'
				)
			);
		}

	}
	else $attributes = $model->gets_data( $args, 'wcmc_attribute_item' );

	return apply_filters('gets_attribute_item', $attributes, $args);
}

function insert_attribute( $attribute = array() ) {

	$ci =& get_instance();

    $model      = get_model('products');

    $model->settable('wcmc_options');

    if ( ! empty( $attribute['id'] ) ) {

		$id 			= (int) $attribute['id'];

		$update 	   	= true;

		$old_attribute 	= get_attribute($id);

		if ( ! $old_attribute ) return new SKD_Error( 'invalid_product_id', __( 'ID nhóm thuộc tính không chính xác.' ) );
	}
	else {

		$update = false;
	}

	if(empty($attribute['title']) ) {

		$language = $ci->language;

		if(!empty($attribute[$language['default']]['title'])) {
			$attribute['title'] = $attribute[$language['default']]['title'];
		}
	}

	if(empty($attribute['language']) ) {

		$language = $ci->language;

		foreach ($language['language_list'] as $key => $label) {

			if( $key != $language['default'] ) {

				if(!empty($attribute[$key]['title'])) {

					$attribute['language'][$key] = $attribute[$key];
				}
			}
		}
	}

	if( ! $update ) {

		if ( empty( $attribute['title'] ) ) return new SKD_Error('empty_option_title', __('Không thể cập nhật nhóm thuộc tính khi tên tên nhóm trống.') );

		$slug = $ci->create_slug( removeHtmlTags( $attribute['title'] ), $model );
	}
	else {

		$slug = empty( $attribute['slug'] ) ? $old_attribute->slug : slug($attribute['slug']);

		if( $slug != $old_attribute->slug ) $slug = $ci->edit_slug( $slug , $id, $model );

		if ( empty( $attribute['title'] ) ) $attribute['title'] = $old_attribute->title;
	}

	$title 		= removeHtmlTags( $attribute['title'] );

	$pre_title 	= apply_filters( 'pre_title', $title );

	$title 		= trim( $pre_title );

	if( !empty( $attribute['option_type'] ) ) $option_type    =  removeHtmlTags($attribute['option_type']);

	$data = compact( 'title', 'slug', 'option_type' );

	$data = apply_filters( 'pre_insert_attribute_data', $data, $update, $update ? (int) $id : null );

	$language 		= !empty( $attribute['language'] ) ? $attribute['language'] : array();

	$language_default = $ci->language['default'];

	if(isset($language[$language_default])) unset($language[$language_default]);

	if ( $update ) {

    	$model->update_where( $data, compact( 'id' ) );

    	$attribute_id = (int) $id;

    	/*=============================================================
		ROUTER
		=============================================================*/
		$model->settable('routes');

		$router['object_type']  = 'wcmc_attribute';

		$router['object_id']    = $attribute_id;

		if( $model->update_where( array('slug' => $slug ), $router ) == 0 ) {

			$router['slug']         = $slug;

			$router['directional']  = 'wcmc_attribute';

			$router['controller']   = 'frontend_home/home/page/';

			$router['callback']     = 'wcmc_attribute_frontend';

			$model->add($router);
		}

		if( have_posts($language) ) {

			$model->settable('language');

			foreach ($language as $key => $val) {
				
				$lang['language']       = $key;

				$lang['object_id']      = $attribute_id;

				$lang['object_type']    = 'wcmc_attribute';

				if($model->count_where($lang)) {

					$model->update_where(['name' => removeHtmlTags($val['name'])], $lang);
				}
				else {

					$lang['name']          = removeHtmlTags($val['name']);

					$model->add($lang);
				}
			}
		}
    }
    else {

    	$attribute_id = $model->add( $data );
		/*=============================================================
		ROUTER
		=============================================================*/
		$model->settable('routes');

		$router['slug']         = $slug;

		$router['directional']  = 'wcmc_attribute';

		$router['controller']   = 'frontend_home/home/page/';

		$router['callback']     = 'wcmc_attribute_frontend';

		$router['object_id']    = $attribute_id;

		$model->add($router);

		/*=============================================================
		LANGUAGE
		=============================================================*/
		if( have_posts($language) ) {

			$model->settable('language');

			foreach ($language as $key => $val) {

				$lang['title']          = removeHtmlTags($val['title']);
				
				$lang['language']       = $key;

				$lang['object_id']      = $attribute_id;

				$lang['object_type']    = 'wcmc_attribute';

				$model->add($lang);
			}
		}
    }

    $model->settable('products');

    return $attribute_id;
}

function insert_attribute_item( $attribute = array() ) {

	$ci =& get_instance();

    $model      = get_model('products');

    $model->settable('wcmc_options_item');

    if ( ! empty( $attribute['id'] ) ) {

		$id 			= (int) $attribute['id'];

		$update 	   	= true;

		$old_attribute 	= get_attribute_item($id);

		if ( ! $old_attribute ) return new SKD_Error( 'invalid_product_id', __( 'ID thuộc tính không chính xác.' ) );
	}
	else {

		$update = false;
	}

	if(empty($attribute['title']) ) {

		$language = $ci->language;

		if(!empty($attribute[$language['default']]['title'])) {
			$attribute['title'] = $attribute[$language['default']]['title'];
		}
	}

	if(empty($attribute['language']) ) {

		$language = $ci->language;

		foreach ($language['language_list'] as $key => $label) {

			if( $key != $language['default'] ) {

				if(!empty($attribute[$key]['title'])) {

					$attribute['language'][$key] = $attribute[$key];
				}
			}
		}
	}

	if( ! $update ) {

		if ( empty( $attribute['title'] ) ) return new SKD_Error('empty_option_title', __('Không thể cập nhật nhóm thuộc tính khi tên tên nhóm trống.') );

		if ( empty( $attribute['option_id'] ) ) return new SKD_Error('empty_option_id', __('ID option không đúng.') );

		$slug = $ci->create_slug( removeHtmlTags( $attribute['title'] ), $model );

		$option_id = $attribute['option_id'];
	}
	else {

		$option_id = ( empty( $attribute['option_id'] ) ) ? $old_attribute->option_id : $attribute['option_id'];

		$slug = empty( $attribute['slug'] ) ? $old_attribute->slug : slug($attribute['slug']);

		if( $slug != $old_attribute->slug ) $slug = $ci->edit_slug( $slug , $id, $model );

		if ( empty( $attribute['title'] ) ) $attribute['title'] = $old_attribute->title;
	}

	$title 		= removeHtmlTags( $attribute['title'] );

	$pre_title 	= apply_filters( 'pre_title', $title );

	$title 		= trim( $pre_title );

	if( !empty( $attribute['type'] ) ) $type    =  removeHtmlTags($attribute['type']);

	if( !empty( $attribute['value'] ) ) $value    =  removeHtmlTags($attribute['value']);

	if( !empty( $attribute['image'] ) ) $image    =  removeHtmlTags($attribute['image']);

	$data = compact( 'option_id', 'title', 'value', 'image', 'type' );

	$data = apply_filters( 'pre_insert_attribute_item_data', $data, $update, $update ? (int) $id : null );

	$language 	= !empty( $attribute['language'] ) ? $attribute['language'] : array();

	$language_default = $ci->language['default'];

	if(isset($language[$language_default])) unset($language[$language_default]);

	if ( $update ) {

		$model->settable('wcmc_options_item');

    	$model->update_where( $data, compact( 'id' ) );

    	$attribute_id = (int) $id;

    	/*=============================================================
		ROUTER
		=============================================================*/
		$model->settable('routes');

		$router['object_type']  = 'wcmc_attribute_item';

		$router['object_id']    = $attribute_id;

		if( $model->update_where( array('slug' => $slug ), $router ) == 0 ) {

			$router['slug']         = $slug;

			$router['directional']  = 'wcmc_attribute_item';

			$router['controller']   = 'frontend_home/home/page/';

			$router['callback']     = 'wcmc_attribute_item_frontend';

			$model->add($router);
		}

		if( have_posts($language) ) {

			$model->settable('language');

			foreach ($language as $key => $val) {
				
				$lang['language']       = $key;

				$lang['object_id']      = $attribute_id;

				$lang['object_type']    = 'wcmc_attribute_item';

				if($model->count_where($lang)) {

					$model->update_where(['title' => removeHtmlTags($val['title'])], $lang);
				}
				else {

					$lang['title']          = removeHtmlTags($val['title']);

					$model->add($lang);
				}
			}
		}
    }
    else {

		$model->settable('wcmc_options_item');

    	$attribute_id = $model->add( $data );
		/*=============================================================
		ROUTER
		=============================================================*/
		$model->settable('routes');

		$router['slug']         = $slug;

		$router['directional']  = 'wcmc_attribute_item';

		$router['controller']   = 'frontend_home/home/page/';

		$router['callback']     = 'wcmc_attribute_item_frontend';

		$router['object_id']    = $attribute_id;

		$model->add($router);

		/*=============================================================
		LANGUAGE
		=============================================================*/
		if( have_posts($language) ) {

			$model->settable('language');

			foreach ($language as $key => $val) {

				$lang['title']          = removeHtmlTags($val['title']);
				
				$lang['language']       = $key;

				$lang['object_id']      = $attribute_id;

				$lang['object_type']    = 'wcmc_attribute_item';

				$model->add($lang);
			}
		}
    }

    $model->settable('products');

    return $attribute_id;
}

function delete_attribute( $id ) {

	$model = get_model('products');

	$model->settable('wcmc_options');

	$object = get_attribute($id);

	if( have_posts($object) ) {

		//xóa các thuộc tính
		delete_attribute_item_by_attribute($object->id);

		//xóa các thuộc tính ở sản phẩm
		$model->settable('relationships');

		$model->delete_where(array('category_id' => 'attribute_op_'.$object->id, 'object_type' => 'attributes'));

		//Xóa ngôn ngữ
		$model->settable('language');

		$model->delete_where(array('object_id' => $object->id, 'object_type' => 'wcmc_attribute'));

		//Xóa Router
		$model->settable('routes');

		$model->delete_where(array('object_id' => $object->id, 'object_type' => 'wcmc_attribute'));

		//variations
		$model->settable('product_metadata');

		$variations_metadata = $model->gets_where( array('meta_key'=> 'attribute_op_'.$id) );

		if( have_posts($variations_metadata) ) { 

			foreach ($variations_metadata as $meta) {

				$model->settable('product_metadata');

				delete_metadata( 'wcmc_variations', $meta->object_id, $meta->meta_key );

				$metadata = get_metadata( 'wcmc_variations', $meta->object_id );

				$count = 0;

				foreach ($metadata as $key_meta => $meta_value) {

					if(substr($key_meta,0,13) == 'attribute_op_') $count++;
				}

				if( $count == 0 ) {

					$model->settable('wcmc_variations');

					$model->delete_where(array('id' => $meta->object_id));

					delete_metadata( 'wcmc_variations', $meta->object_id );
				}
				
			}
		}

		$model->settable('product_metadata');

		$product_attributes = $model->gets_where( array( 'meta_key' => 'attributes') );

		foreach ($product_attributes as $attributes) {

			$attributes->meta_value = @unserialize($attributes->meta_value);

			unset($attributes->meta_value['_op_'.$id]);

			if( !have_posts($attributes->meta_value) ) {

				delete_metadata( 'product', $attributes->object_id, 'attributes' );

			} else {

				update_metadata( 'product', $attributes->object_id, 'attributes', $attributes->meta_value );
			}
		}

		//xóa metabox
		$model->settable('wcmc_options');

		if($model->delete_where(array('id' => $object->id))) {
			return [$object->id];
		}
	}

	return false;
}

function delete_list_attribute( $attributeID ) {

	$ci =& get_instance();

	$result = array();

	if(!have_posts($attributeID)) return false;

	foreach ($attributeID as $key => $id) {

		if( delete_attribute($id) != false ) $result[] = $id;
	}

	if(have_posts($result)) return $result;

	return false;
}

function delete_attribute_item( $id ) {

	$model = get_model('products');

	$model->settable('wcmc_options_item');

	$object = get_attribute_item($id);

	if( have_posts($object) ) {

		if($model->delete_where(array('id' => $object->id))) {

			//Xóa ngôn ngữ
			$model->settable('language');

			$model->delete_where(array('object_id' => $object->id, 'object_type' => 'wcmc_attribute_item'));

			//Xóa Router
			$model->settable('routes');

			$model->delete_where(array('object_id' => $object->id, 'object_type' => 'wcmc_attribute_item'));

			//xóa ở liên kết product
			$model->settable('relationships');

			$model->delete_where(array('value' => $object->id, 'category_id' => 'attribute_op_'.$object->option_id, 'object_type' => 'attributes'));

			//xóa liên kết biến thể
			$model->settable('product_metadata');

			$model->delete_where(array('meta_value' => $object->id, 'meta_key' => 'attribute_op_'.$object->option_id));

			delete_cache( 'metabox_', true );

			return [$object->id];
		}
	}

	return false;
}

function delete_list_attribute_item( $attributeID ) {

	$ci =& get_instance();

	$result = array();

	if(!have_posts($attributeID)) return false;

	foreach ($attributeID as $key => $id) {

		if( delete_attribute_item($id) != false ) $result[] = $id;
	}

	if(have_posts($result)) return $result;

	return false;
}

function delete_attribute_item_by_attribute( $attributeID ) {

	$model = get_model('products');

	$model->settable('wcmc_options_item');

	$object = gets_attribute_item(['where' => ['option_id' => $attributeID] ]);

	$attributes = [];

	foreach ($object as $key => $item) {

		$attributes[] = $item->id;
	}

	if( have_posts($attributes) ) {

		return delete_list_attribute_item($attributes);
	}

	return false;
}

/*===================== ATTRIBUTE ===============================*/
if(!function_exists('wcmc_gets_variations')) {

	function wcmc_gets_variations( $product_id = '', $attribute_op = '', $model = '') {

		if($model == '') $model = get_model('products');

		$model->settable('relationships');

		$attributes =  $model->gets_where(array('object_id' => $product_id, 'category_id' => $attribute_op, 'object_type' => 'attributes' ));

		$list_attribute_id =  array();

		if(have_posts($attributes)) {

			foreach ($attributes as $key => $attribute) {

				$list_attribute_id[] = $attribute->value;

			}

			$model->settable('wcmc_options_item');

			$attributes = $model->gets_where_in(array('field' => 'id', 'data' => $list_attribute_id));
		}

		return $attributes;

	}
}

if(!function_exists('gets_variations')) {

	function gets_variations( $args = [] ) {

		$resutl = array();

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

		$args = array_merge( array('where' => array(), 'params' => array() ), $args );

		if(is_array($args) && !isset($args['where']['type'])) $args['where']['type'] = 'variations';

		if(isset($args['product'])) {

			$args['where']['parent_id'] = (int)$args['product'];

			$args['where']['status'] 	= 'public';

			$args['params']['select'] 	= 'id, code, title, image, price, price_sale, parent_id, type';
		}

		$variations = gets_product($args);

		if( have_posts($variations) ) {

			foreach ($variations as $key => &$variable) {

				$metadata = get_metadata('product', $variable->id );

				foreach ($metadata as $key_meta => $meta_value) {

					if(substr($key_meta,0,13) == 'attribute_op_') {

						$variable->items[substr($key_meta, 13)] = $meta_value;

						unset($metadata->{$key_meta});
					}
				}

				$variable = (object)array_merge( (array)$metadata, (array)$variable);
			}
		}

		return $variations;
	}
}

if(!function_exists('get_variations')) {

	function get_variations( $args = [] ) {

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

		$args = array_merge( array('where' => array(), 'params' => array() ), $args );

		if(is_array($args) && !isset($args['where']['type'])) $args['where']['type'] = 'variations';
		
		$variable = gets_product($args);

		if( have_posts($variable) ) {

			$metadata = get_metadata('product', $variable->id );

			foreach ($metadata as $key_meta => $meta_value) {

				if(substr($key_meta,0,13) == 'attribute_op_') {

					$variable->items[substr($key_meta, 13)] = $meta_value;

					unset($metadata->{$key_meta});
				}
			}

			$variable = (object)array_merge( (array)$metadata, (array)$variable);
		}

		return apply_filters('get_variations', $variable, $args);

	}
}