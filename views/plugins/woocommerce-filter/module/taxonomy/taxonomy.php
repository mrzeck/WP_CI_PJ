<?php
include 'taxonomy.widget.php';
include 'thuonghieu.widget.php';
function woocommerce_filter_taxonomy_html( $key = '' ) {
	$ci =& get_instance();
	$taxonomy = array();
	if( $key != null ) {
		$taxonomy[$key] = gets_post_category( array( 'where' => array( 'cate_type' =>  $key ), 'tree' => array() ) );
	}
	else {
		foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
			if( $taxonomy_value['post_type'] == 'products' ) {
				$taxonomy[$taxonomy_key] = gets_post_category( array( 'where' => array( 'cate_type' =>  $taxonomy_key ), 'tree' => array() ) );
			}
		}
	}
	$data['_listTaxonomy'] = $taxonomy;
	wcmc_filter_get_template('html-taxonomy', $data );
}
add_action('woocommerce_filter_html', 'woocommerce_filter_taxonomy_html', 20);
function woocommerce_filter_thuonghieu_html( $key = '' ) {
	$ci =& get_instance();
	$taxonomy = array();
	if( $key != null ) {
		$taxonomy[$key] = gets_post_category( array( 'where' => array( 'cate_type' =>  $key ), 'tree' => array() ) );
	}
	else {
		foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
	        if( $taxonomy_value['post_type'] == 'products' ) {
	        	$taxonomy[$taxonomy_key] = gets_post_category( array( 'where' => array( 'cate_type' =>  $taxonomy_key ), 'tree' => array() ) );
	        }
	    }
	}
    $data['_listTaxonomy'] = $taxonomy;
   
	wcmc_filter_get_template('html-thuonghieu', $data );
}
add_action('woocommerce_filter_html', 'woocommerce_filter_thuonghieu_html', 21);

function wcmc_filter_get_taxonomy_url( $get, $key_taxonomy, $option_value ) {
	$ci =& get_instance();
	if( strpos( $key_taxonomy, 'filter-' ) !== false ) {
		if( isset($get[$key_taxonomy]) ) {
			$option = explode(',', $get[$key_taxonomy] );
			if( in_array( $option_value, $option ) !== false ) {
				$key = array_search ( $option_value, $option );
				unset( $option[$key] );
			}
			else $option[] = $option_value;
		} else $option[] = $option_value;
		if( have_posts($option) ) {
			$option = implode(',', $option );
			$get[$key_taxonomy] = $option;
		}
		else {
			unset( $get[$key_taxonomy] );
		}
	}
	return $get;
}
add_filter( 'wcmc_filter_get_url', 'wcmc_filter_get_taxonomy_url', 10, 3 );
function woocommerce_filter_taxonomy_chose( $value, $key_taxonomy ) {
	$ci =& get_instance();
	$get = $ci->input->get();
	$option = '';
	if( isset($get[$key_taxonomy]) ) {
		$option = explode(',', $get[$key_taxonomy] );
		if( in_array( $value, $option ) !== false ) {
			return 'chosen';
		}
	}
	return '';
}
function woocommerce_filter_taxonomy_args( $args ) {
	$ci =& get_instance();
	$get = $ci->input->get();
	$listID = array();
	$list_product_ID = array();
	$_product_id = array();
	$check = false;
	foreach ($get as $key_taxonomy => $value) {
		if( strpos( $key_taxonomy, 'filter-' ) !== false ) {
			$check = true;
			$key_taxonomy = substr( $key_taxonomy , 7 );
			$option = explode(',', $get['filter-'.$key_taxonomy] );
			$model = get_model('post');
			$model->settable('post');
			foreach ($option as $id) {
				$id = (int)$id;
				if( isset($listID[$key_taxonomy][$id]) ) continue;
				$taxonomy = get_post_category( $id );
				if( have_posts( $taxonomy ) ) {
					$temp = $model->gets_category_sub($taxonomy);
					foreach ($temp as $cate_id) {
						$listID[$key_taxonomy][$cate_id] = $cate_id;
					}
				}
			}
			if( have_posts($listID[$key_taxonomy]) ) {
				$model->settable('relationships');
				$data['field'] 	= 'category_id';
				$data['data'] 	= $listID[$key_taxonomy];
				$temp = $model->gets_where_in( $data, array('object_type' => 'products', 'value' => $key_taxonomy), array('select' => 'object_id'));
				$list_product_ID[$key_taxonomy] = array();
				foreach ($temp as $relationships) {
					$list_product_ID[$key_taxonomy][$relationships->object_id] = $relationships->object_id;
				}
			}
		}
	}
	if( $check == true ) {
		foreach ( $list_product_ID as $data ) {
			$_product_id = array_merge( $_product_id, $data );
		}
		if( have_posts( $_product_id ) ) {
			if( isset($args['where_in']['data']) ) {
				$args['where_in']['data'] = array_intersect( $args['where_in']['data'], $_product_id );
			}
			else $args['where_in']['data'] =  $_product_id;
			$args['where_in']['field'] 	=  'id';
		}
		else {
			if( isset($args['where_in']['data']) ) {
				$args['where_in']['data'] = array_intersect( $args['where_in']['data'], array() );
			}
			else $args['where_in']['data'] = array();
		}
	}
	return $args;
}
add_action('woocommerce_controllers_index_args', 'woocommerce_filter_taxonomy_args', 15);