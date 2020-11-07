<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists('add_meta_box') ) {
	/**
     * @since  2.0.0
     */
	function add_meta_box ( $id, $title, $callback, $module = null, $position = 1, $content = 'leftb', $content_box = '') {

		$ci =& get_instance();

		$check = false;

		if( have_posts($ci->metaboxs)) {

			$group = array();

			for ( $i = 0; $i < 20000; $i++ ) {

				foreach ($ci->metaboxs as $key => $metabox) {

					if( $metabox['position'] == $i ) {

						$group[$i][$key] = $metabox;

						unset($ci->metaboxs[$key]);
					}
				}

				if( !have_posts($ci->metaboxs) ) break;
			}

			$metaboxs = array();

			foreach ($group as $i => $value) {

				foreach ($value as $key => $metabox) {
					$metaboxs[$key] = $metabox;
				}
			}

			foreach ($metaboxs as $key => $metabox) {

				if( $metabox['position'] == $position ) {

					$ci->metaboxs[$id] = array(
						'label' 	=> $title,
						'callback' 	=> $callback,
						'module'	=> $module,
						'position'  => $position,
						'content'   => $content,
						'content_box'   => $content_box,
					);

					$check = true;
				}

				$ci->metaboxs[$key] = $metabox;
			}
		}

		if( $check == false ) {

			$ci->metaboxs[$id] = array(
				'label' 	=> $title,
				'callback' 	=> $callback,
				'module'	=> $module,
				'position'  => $position,
				'content'   => $content,
				'content_box'   => $content_box,
			);

		}
		
	}
}

if( !function_exists('remove_meta_box') ) {
	/**
     * @since  2.0.0
     */
	function remove_meta_box ( $id ) {
		
		$ci =& get_instance();

		if( isset($ci->metaboxs[$id]) ) unset($ci->metaboxs[$id]);
	}
}

if( !function_exists('add_metadata') ) {
	/**
     * @since  2.0.0
     */
	function add_metadata($object_type, $object_id, $meta_key, $meta_value) {

		$ci =& get_instance();

		$model = get_model('plugins', 'backend');

		$model->settable('metabox');

		if ( ! $object_type || ! $meta_key || ! is_numeric( $object_id ) ) {
			return false;
		}

		$check = apply_filters( "add_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value );

		if ( null !== $check )
			return $check;

		do_action( "add_{$object_type}_meta", $object_id, $meta_key, $meta_value );

		//kiểm tra có table
		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );

			$mid = $model->add( array(
				'object_id' 	=> $object_id,
				'meta_key' 		=> $meta_key,
				'meta_value' 	=> ( is_array($meta_value) || is_object($meta_value) ) ? serialize($meta_value) : $meta_value
			) );
		}
		else {

			$mid = $model->add( array(
				'object_id' 	=> $object_id,
				'object_type' 	=> $object_type,
				'meta_key' 		=> $meta_key,
				'meta_value' 	=> ( is_array($meta_value) || is_object($meta_value) ) ? serialize($meta_value) : $meta_value
			) );

		}

		
		if ( ! $mid )
			return false;

		delete_cache( 'metabox_', true );

		do_action( "added_{$object_type}_meta", $mid, $object_id, $meta_key, $meta_value );

		return $mid;
	}
}

if( !function_exists('update_metadata') ) {
	/**
     * @since  2.0.0
     */
	function update_metadata($object_type, $object_id, $meta_key, $meta_value) {

		$ci =& get_instance();

		$cache_id = 'metabox_'.$object_id.'_'.$object_type;

		if ( ! $object_type || ! $meta_key || ! is_numeric( $object_id ) ) {
			return false;
		}

		$check = apply_filters( "update_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value );
		if ( null !== $check )
			return (bool) $check;

		$model = get_model('plugins', 'backend');

		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );

			$where = array( 'object_id' => $object_id, 'meta_key' => $meta_key );
		}
		else {

			$model->settable('metabox');

			$where = array( 'object_id' => $object_id, 'object_type' => $object_type, 'meta_key' => $meta_key );
		}

		$meta_ids = $model->gets_where( $where );


		if ( !have_posts( $meta_ids ) ) {

			$mid = add_metadata( $object_type, $object_id, $meta_key, $meta_value );

			return $mid;
		}


		foreach ( $meta_ids as $meta_id ) {
			do_action( "update_{$object_type}_meta", $meta_id, $object_id, $meta_key, $meta_value );
		}

		$data['meta_value'] = ( is_array($meta_value) || is_object($meta_value) ) ? serialize($meta_value) : $meta_value;

		$result = $model->update_where( $data, $where );

		if ( ! $result )
			return false;

		delete_cache( 'metabox_', true );

		return true;
	}
}

if( !function_exists('delete_metadata') ) {
	/**
     * @since  2.0.0
     */
	function delete_metadata($object_type, $object_id, $meta_key = '', $meta_value = '', $delete_all = false) {

		$ci =& get_instance();

		if ( ! $object_type || ! is_numeric( $object_id ) && ! $delete_all ) {
			return false;
		}

		$check = apply_filters( "delete_{$object_type}_metadata", null, $object_id, $meta_key, $meta_value, $delete_all );

		if ( null !== $check ) return (bool) $check;

		$model = get_model('plugins', 'backend');

		if ( !$delete_all ) 	$where['object_id'] = $object_id;

		if( !empty($meta_key) ) $where['meta_key'] 		= $meta_key;

		if(!empty($meta_value)) $where['meta_value'] 	= $meta_value;

		do_action( "delete_{$object_type}_meta", $where, $object_id, $meta_key, $meta_value );

		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );
		}
		else {

			$model->settable('metabox');

			$where['object_type'] 	= $object_type;
		}

		$count =  $model->delete_where($where);

		if ( !$count )
			return false;

		delete_cache( 'metabox_', true );

		do_action( "deleted_{$object_type}_meta", $where, $object_id, $meta_key, $meta_value );

		return true;
	}
}

if( !function_exists('delete_all_metadata') ) {
	/**
     * @since  2.0.0
     */
	function delete_all_metadata($object_type, $meta_key = '') {

		$ci =& get_instance();

		if ( ! $object_type ) {
			return false;
		}

		$check = apply_filters( "delete_all_{$object_type}_metadata", null, $object_type );

		if ( null !== $check ) return (bool) $check;

		$model = get_model('plugins', 'backend');

		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );
		}
		else {

			$model->settable('metabox');

			$where['object_type'] 	= $object_type;
		}

		if( !empty($meta_key) ) $where['meta_key'] 	= $meta_key;

		do_action( "delete_all_{$object_type}_meta", $where, $object_type );

		$count =  $model->delete_where($where);

		if ( !$count )
			return false;

		delete_cache( 'metabox_', true );

		do_action( "deleted_all_{$object_type}_meta", $where, $object_id, $meta_key, $meta_value );

		return true;
	}
	
}

if( !function_exists('delete_metadata_by_mkey') ) {
	/**
     * @since  2.0.0
     */
	function delete_metadata_by_mkey($object_type, $meta_key = '') {

		$ci =& get_instance();

		if ( ! $object_type ) {
			return false;
		}

		$check = apply_filters( "deleted_{$object_type}_metadata_by_mkey", null, $object_type );

		if ( null !== $check ) return (bool) $check;

		$model = get_model('plugins', 'backend');

		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );
		}
		else {

			$model->settable('metabox');

			$where['object_type'] 	= $object_type;
		}

		if( !empty($meta_key) ) $where['meta_key'] 	= $meta_key;

		do_action( "delete_all_{$object_type}_meta", $where, $object_type );

		$count =  $model->delete_where($where);

		if ( !$count )
			return false;

		delete_cache( 'metabox_', true );

		do_action( "deleted_{$object_type}_metadata_by_mkey", $where, $object_id, $meta_key, $meta_value );

		return true;
	}
	
}

if( !function_exists('delete_metadata_by_mid') ) {
	/**
     * @since  2.0.0
     */
	function delete_metadata_by_mid( $object_type, $mid ) {

		$ci =& get_instance();

		if ( ! $object_type ) return false;
		
		if ( ! $mid ) return false;

		$where['object_id'] = $mid;

		$check = apply_filters( "delete_{$object_type}_metadata_by_mid", null, $object_type, $mid );

		if ( null !== $check ) return (bool) $check;

		$model = get_model('plugins', 'backend');

		if( $model->db_table_exists( $object_type.'_metadata' ) ) {

			$model->settable( $object_type.'_metadata' );
		}
		else {

			$model->settable('metabox');

			$where['object_type'] 	= $object_type;
		}

		$count =  $model->delete_where($where);

		if ( !$count ) return false;

		delete_cache( 'metabox_', true );

		do_action( "deleted_{$object_type}_metadata_by_mid", $object_type, $mid );

		return true;
	}
	
}

if( !function_exists('get_metadata') ) {
	/**
     * @since  2.0.0
     */
	function get_metadata($object_type, $object_id = '', $meta_key = '', $single = false) {

		$ci =& get_instance();

		if ( ! $object_type || ! is_numeric( $object_id ) ) {
			return false;
		}

		$check = apply_filters( "get_{$object_type}_metadata", null, $object_id, $meta_key, $single );
		
		if ( null !== $check ) {
			if ( $single && is_array( $check ) )
				return $check[0];
			else
				return $check;
		}


		//load dữ liệu
		$cache_id = $object_id.'_'.$object_type;

		if( !empty($meta_key) ) $cache_id .= '_'.$meta_key;

		if( $single == true ) $cache_id .= '_single';

		$cache_id = 'metabox_'.md5($cache_id);

		//không tồn tại cache
		if( cache_exists($cache_id) == false ) {

			$where['object_id']   = $object_id;

			if ( !empty($meta_key) ) $where['meta_key'] = $meta_key;

			$model = get_model('plugins', 'backend');

			if( $model->db_table_exists( $object_type.'_metadata' ) ) {

				$model->settable( $object_type.'_metadata' );
			}
			else {

				$model->settable('metabox');

				$where['object_type'] 	= $object_type;
			}

			if( $single )
				$meta = $model->get_where($where);
			else
				$meta = $model->gets_where($where);

			//set cache
			if(have_posts($meta)) {

				if ( $single ) {

					save_cache($cache_id, $meta->meta_value);

					return ( is_serialized($meta->meta_value) ) ? unserialize($meta->meta_value) : $meta->meta_value ;
				}
				else {

					$temp = (object)array();

					foreach ($meta as $key => $value) {

						$temp->{$value->meta_key} = ( is_serialized($value->meta_value) ) ? unserialize($value->meta_value) : $value->meta_value ;
					}

					save_cache($cache_id, $temp);

					return $temp;
				}
			}
		}
		else {
			$temp = get_cache($cache_id);
			return ( is_serialized($temp) ) ? unserialize($temp) : $temp ; 
		}

		if ($single)
			return '';
		else
			return array();
	}	
}