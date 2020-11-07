<?php
if( !function_exists('get_gallery') ) {
	/**
	 *  Get add item of object 
	 * */
	function get_gallery( $params = array(), $group_id = null, $object_id = 0, $object_type = null, $type = null ) {

		$ci =  &get_instance();

		$cache_id = '';

		$where = array();

		if ( 0 !== $object_id ) {

			$cache_id .= '_object_'.$object_id;

			$where['object_id'] = $object_id;
		}
		
		if ( null !== $group_id ) {

			$cache_id .= '_group_'.$group_id;

			$where['group_id'] = $group_id;
		}

		if ( null !== $object_type ) {

			$cache_id .= '_object_type_'.$object_type;

			$where['object_type'] = $object_type;
		}

		if ( null !== $type ) {

			$cache_id .= '_type_'.$type;

			$where['type'] = $type;
		}


		if(have_posts($params)) {
			$cache_id = 'gallery_'.md5($cache_id.serialize($params));
		} else $cache_id = 'gallery_'.md5($cache_id);

		if( cache_exists($cache_id) == false ) {

			$model = get_model('home');

			$model->settable('gallerys');

			$gItem = $model->gets_where($where, $params);

			foreach ($gItem as $key => &$items) {

				$items->options = @unserialize($items->options);
			}

			save_cache($cache_id, $gItem);

			return $gItem;

		}
		else return get_cache($cache_id);
	}
}

if( !function_exists('_get_gallery') ) {

    function _get_gallery( $args = array() ) {

        $ci =& get_instance();

        $model  = get_model('gallery', 'backend');

        $model->settable('gallerys');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where  = $args['where'];

        $params = $args['params'];

        $cache_id = 'gallery_'.md5( serialize($args) );

        if( cache_exists($cache_id) == false ) {

        	if( isset($args['where_in'])) {

	            $data = $args['where_in'];

	            $gallery = $model->get_where_in( $data, $where, $params );
	        }
	        else $gallery = $model->get_where( $where, $params );

	        if( have_posts($gallery) ) {

	        	$meta = get_gallery_meta( $gallery->id ,'', false);

	        	$gallery = (object)array_merge( (array)$meta, (array)$gallery );

	        	save_cache($cache_id, $gallery);
	        }

        } else {

        	$gallery = get_cache($cache_id);
        }

        return apply_filters('get_gallery', $gallery, $args);
    }
}

if( !function_exists('gets_gallery') ) {
	/**
	 *  Get add item of object
	 * */
	function gets_gallery( $args ) {

		$ci =  &get_instance();

		$model 		= get_model('gallery', 'backend');

		$model->settable('gallerys');

        $model->settable_metabox('gallerys_metabox');

        if( is_numeric($args) ) $args = array( 'where' => array('group_id' => (int)$args ) );

        if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $cache_id = 'gallery_'.md5( serialize($args) ).'_s';

        if( cache_exists($cache_id) == false ) {

        	$where 	= $args['where'];

	        $params = $args['params'];

			$gallery = $model->gets_data($args, 'gallerys');

        	$model->settable('gallerys');

	        save_cache($cache_id, $gallery);

        } else {

        	$gallery = get_cache($cache_id);
        }

        return apply_filters( 'gets_gallery', $gallery, $args );
	}
}

if( !function_exists('count_gallery') ) {
	/**
	 *  Get add item of object
	 * */
	function count_gallery( $args ) {

		$ci =  &get_instance();

		$model 		= get_model('gallery', 'backend');

		$model->settable('gallerys');

        $model->settable_metabox('gallerys_metabox');

        if( is_numeric($args) ) $args = array( 'where' => array('group_id' => (int)$args ) );

        if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $cache_id = 'gallery_count_'.md5( serialize($args) ).'_s';

        if( cache_exists($cache_id) == false ) {

        	$where 	= $args['where'];

	        $params = $args['params'];

			$gallery = $model->count_data($args, 'gallerys');

        	$model->settable('gallerys');

	        save_cache($cache_id, $gallery);

        } else {

        	$gallery = get_cache($cache_id);
        }

        return apply_filters( 'gets_gallery', $gallery, $args );
	}
}

if( !function_exists('insert_gallery') ) {
	/**
	 * [insert_gallery thêm hoặc cập nhật gallery item]
	 * @param  [type] $gallery_arr [description]
	 * @return [type]              [description]
	 */
	function insert_gallery( $gallery_arr ) {

		$ci =&get_instance();

        $model = get_model('gallery','backend');

        $model->settable('gallerys');

        $update = false;

        if( ! empty( $gallery_arr['id'] ) ) {

            $id             = (int) $gallery_arr['id'];

            $update        = true;

            $old_gallery = _get_gallery($id);

            if ( ! $old_gallery ) return new SKD_Error( 'invalid_post_id', __( 'ID gallery item không chính xác.' ) );

            if( empty( $gallery_arr['value'] ) ) $gallery_arr['value'] = $old_gallery->value;

        }

        if( empty( $gallery_arr['value'] ) ) return new SKD_Error('empty_value', __('Không được để trống đường dẫn File.') );

		if( !empty( $gallery_arr['object_type'] ) )  	$object_type = removeHtmlTags($gallery_arr['object_type']);

		if( !empty( $gallery_arr['group_id'] ) )  		$group_id    = (int)removeHtmlTags($gallery_arr['group_id']);

		if( isset( $gallery_arr['order'] ) && is_numeric($gallery_arr['order']) )  	$order = (int)removeHtmlTags($gallery_arr['order']);

		if( !empty( $gallery_arr['object_id'] ) )  		$object_id   = (int)removeHtmlTags($gallery_arr['object_id']);

		$options = (isset($gallery_arr['options']) && have_posts($gallery_arr['options'])) ? $gallery_arr['options'] : array();

		if( !$update ) {

			$type 		 	= ( !empty( $gallery_arr['type'] ) ) ? removeHtmlTags($gallery_arr['type']) : 'image';

			$object_type 	= ( !empty( $gallery_arr['object_type'] ) ) ? removeHtmlTags($gallery_arr['object_type']) : 'post_post';
		}
		else {

			$type 			= ( !empty( $gallery_arr['type'] ) ) ? removeHtmlTags($gallery_arr['type']) : $old_gallery->type;

			$object_type 	= ( !empty( $gallery_arr['object_type'] ) ) ? removeHtmlTags($gallery_arr['object_type']) : $old_gallery->object_type;
		}

		$value = process_file(removeHtmlTags($gallery_arr['value']));

		$data = compact( 'group_id', 'object_id', 'object_type', 'value', 'order', 'type' );

		$data = apply_filters( 'pre_insert_gallery_data', $data, $gallery_arr, $update ? (int) $id : null );

		if ( $update ) {

			$model->update_where( $data, compact( 'id' ) );

			$gallery_id = (int) $id;

			if( have_posts($options) ) {

				foreach ($options as $meta_key => $meta_value ) {
					update_gallery_meta( $gallery_id, $meta_key, $meta_value );
				}
			}

		} else {

			if( have_posts( json_decode($value)) ) {

				$value = json_decode($value);

				foreach ($value as $path) {

					$data['value'] = $path;

					$data['type'] = get_file_type($path);

					$gallery_id[] = $model->add( $data );
				}

				foreach ($gallery_id as $id) {

					foreach ($options as $meta_key => $meta_value ) {
						update_gallery_meta( $id, $meta_key, $meta_value );
					}
					
				}
			}
			else {

				$gallery_id = $model->add( $data );

				foreach ($options as $meta_key => $meta_value ) {
					update_gallery_meta( $gallery_id, $meta_key, $meta_value );
				}
			}
        }

        delete_cache('gallery_', true);

        return $gallery_id;
	}
}

if( !function_exists('delete_gallery') ) {
	/**
	 *  Get add item of object
	 * */
	function delete_gallery( $id ) {

		$ci =  &get_instance();

		$model 		= get_model('gallery', 'backend');

		$model->settable('gallerys');

		$data = $id;
		
		if(is_numeric($id)) $data = [$id];

		if(!have_posts($data)) return false;

		if($model->delete_where_in(['field' => 'id', 'data' => $data])) {

			foreach ($data as $key => $id) {

				delete_metadata_by_mid('gallerys', $id);
			}

			delete_cache('gallery_', true);

			return $data;
		}

		return false;
	}
}

if( !function_exists('delete_gallery_by_object') ) {
	/**
	 *  Get add item of object
	 * */
	function delete_gallery_by_object( $id, $object_type ) {

		$ci =  &get_instance();

		$model 		= get_model('gallery', 'backend');

		$model->settable('gallerys');

		$data = $id;
		
		if(is_numeric($id)) $data = [$id];

		if(!have_posts($data)) return false;

		$args['where_in'] 	= ['field' => 'object_id', 'data' => $data];

		$args['where'] 		= ['object_type' => $object_type];

		$gallery = gets_gallery($args);

		$model->settable('gallerys');

		if($model->delete_where_in($args['where_in'], $args['where'])) {

			foreach ($gallery as $key => $gl) {

				delete_metadata_by_mid('gallerys', $gl->id);
			}

			delete_cache('gallery_', true);

			return $data;
		}

		return false;
	}
}
/* METADATA *********************************************************/
if( !function_exists('get_gallery_meta') ) {

	function get_gallery_meta( $gallery_id, $key = '', $single = true) {

		$data = get_metadata('gallerys', $gallery_id, $key, $single);

		return $data;
	}
}

if( !function_exists('update_gallery_meta') ) {

	function update_gallery_meta($gallery_id, $meta_key, $meta_value) {
		
		delete_cache( 'gallery_'.$gallery_id, true );

		return update_metadata('gallerys', $gallery_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_gallery_meta') ) {

	function delete_gallery_meta($gallery_id, $meta_key = '', $meta_value = '') {

		return delete_metadata('gallerys', $gallery_id, $meta_key, $meta_value);

	}
}

/* REGISTER TEMPLATE *********************************************************/
if( !function_exists('gallery_template_support') ) {
	/**
	 *  Get all item of object
	 * */
	function gallery_template_support( $object_type = array() ) {

		$ci =& get_instance();

		foreach ($object_type as $key => $value) {

			if( $value == 'post_type' ) continue;

			$ci->gallery_support[$value] = true;
		}
	}
}

if( !function_exists('gallery_template_support_cate_type') ) {
	/**
	 *  Get all item of object
	 * */
	function gallery_template_support_cate_type( $cate_type = array() ) {

		$ci =& get_instance();

		foreach ($cate_type as $key => $value) {
			
			$ci->gallery_support['cate_type'][$value] = true;
		}
	}
}

if( !function_exists('gallery_template_support_post_type') ) {
	/**
	 *  Get all item of object
	 * */
	function gallery_template_support_post_type( $post_type = array() ) {

		$ci =& get_instance();

		foreach ($post_type as $key => $value) {

			$ci->gallery_support['post_type'][$value] = true;
		}
	}
}

/* OPTION *********************************************************/
if( !function_exists('add_option_gallery') ) {

	function add_option_gallery ( $type = null, $object = null, $input = array() , $position = 1) {

		$ci =& get_instance();

		if( !$type || !$object || !have_posts($input) ) return false;

		if( isset($ci->gallery_options[$type][$object]) ) {
			$ci->gallery_options[$type][$object][] = $input;
		}
		else {

			if( !isset($ci->gallery_options) || !have_posts($ci->gallery_options) )  $ci->gallery_options = array();
			
			$ci->gallery_options[$type] = array(
				$object => array( $input),
			);
		}
	}
}

if( !function_exists('add_option_gallery_object') ) {

	function add_option_gallery_object ( $args = array() ) {

		$ci =& get_instance();

		$default = array(
			'id'		=> false,
			'input' 	=> array(),
			'object' 	=> false,
			'type' 		=> '',
			'position' 	=> 1,
		);

		if( !have_posts($args) ) return false;

		$args = array_merge($default, $args);

		if( !$args['id'] || !$args['object'] || !have_posts($args['input']) ) return false;

		$options = $ci->gallery_options;

		if( $args['object'] == 'post' || $args['object'] == 'post_categories') {

			$options['object'][$args['object']][$args['type']][$args['id']] = $args['input'];
	
		}
		else {
			$options['object'][$args['object']][$args['id']] = $args['input'];
		}
		

		$ci->gallery_options = $options;

	}
}

if( !function_exists('remove_option_gallery_object') ) {

	function remove_option_gallery_object ( $id = null, $object = null, $type = null ) {

		$ci =& get_instance();

		if( !$id || !$object ) return false;

		$options = $ci->gallery_options;

		if( $object == 'post' || $object == 'post_categories' ) {
			if( !$type ) return false;
			unset($options['object'][$object][$type][$id]);
		}
		else {
			unset($options['object'][$object][$id]);
		}
		
		$ci->gallery_options = $options;
	}
}

/* OPTION *********************************************************/
function fix_strtolower($str)
{
	if (function_exists('mb_strtoupper'))
	{
		return mb_strtolower($str);
	}
	else
	{
		return strtolower($str);
	}
}

function image_watermark() {

	include(FCPATH."scripts/rpsfmng/filemanager/include/php_image_magician.php");

	$magicianObj = new imageLib(FCPATH.'uploads/source/tintuc/tai-sao-au-mobile-la-noi-quy-tu-nhieu-gai-xinh-nhat-cong-dong-ga-8cdbc3.jpg');
	
	$magicianObj->addWatermark(FCPATH.'uploads/source/icon/vi.png', 'br',  '10');
	
	$magicianObj->saveImage(FCPATH.'uploads/test/tintuc/tai-sao-au-mobile-la-noi-quy-tu-nhieu-gai-xinh-nhat-cong-dong-ga-8cdbc3.jpg', 80);	
}
