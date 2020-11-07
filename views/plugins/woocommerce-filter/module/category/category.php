<?php
include 'category.widget.php';
function woocommerce_filter_category_html( $id = 0 ) {
	$data['_listCategory'] = wcmc_gets_category_mutilevel( $id, array(), array('orderby' => 'order, created asc', 'select' => 'id, name,image') );
	wcmc_filter_get_template('html-category', $data );
}
add_action('woocommerce_filter_html', 'woocommerce_filter_category_html', 20);
function wcmc_filter_get_category_url( $get, $input, $option_value ) {
	$ci =& get_instance();
	if( $input == 'category' ) {
		if( isset($get['category']) ) {
			$option = explode(',', $get['category'] );
			if( in_array( $option_value, $option ) !== false ) {
				$key = array_search ( $option_value, $option );
				unset( $option[$key] );
			}
			else $option[] = $option_value;
		} else $option[] = $option_value;
		if( isset($ci->data['category']) && have_posts($ci->data['category']) ) {
			if( $option_value != $ci->data['category']->id ) {
				$option[] = $ci->data['category']->id;
			}
			else {
				foreach ($option as $key => $value) {
					if($value == $ci->data['category']->id) unset($option[$key]);
				}
			}
		}
		if( have_posts($option) ) {
			$option = implode(',', $option );
			$get['category'] = $option;
		}
		else {
			unset( $get['category'] );
		}
	}
	if( isset($ci->data['category']) && have_posts($ci->data['category']) && !isset($get['category']) ) {
		$get['category'] = $ci->data['category']->id;
	}
	return $get;
}
add_filter( 'wcmc_filter_get_url', 'wcmc_filter_get_category_url', 10, 3 );
function woocommerce_filter_category_chose( $value ) {
	$ci =& get_instance();
	$get = $ci->input->get();
	$option = '';
	if( isset($ci->data['category']) && have_posts($ci->data['category']) ) {
		if( $ci->data['category']->id == $value ) return 'chosen';
	}
	if( isset($get['category']) ) {
		$option = explode(',', $get['category'] );
		if( in_array( $value, $option ) !== false ) {
			return 'chosen';
		}
	}
	return '';
}
function woocommerce_filter_category_args( $args ) {
	$ci =& get_instance();
	$get = $ci->input->get();
	$listID = array();
	if( isset($get['category']) ) {
		$option = explode(',', $get['category'] );
		$model = get_model('products');
		$model->settable('products');
		foreach ($option as $id) {
			$id = (int)$id;
			if( isset($listID[$id]) ) continue;
			$category = wcmc_get_category( $id );
			if( have_posts($category) ) {
				$temp = $model->gets_category_sub($category);
				foreach ($temp as $cate_id) {
					$listID[$cate_id] = $cate_id;
				}
			}
		}
		if( have_posts($listID) ) {
			$args['where_in']['data'] 	=  $model->gets_relationship_list( $listID, 'object_id', 'products' );
			$args['where_in']['field'] 	=  'id';
		}
	}
	return $args;
}
add_action('woocommerce_controllers_index_args', 'woocommerce_filter_category_args', 10);