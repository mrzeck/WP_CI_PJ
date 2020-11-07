<?php
include 'attribute.widget.php';

function woocommerce_filter_attribute_html( $id = 0 ) {

	$ci =& get_instance();

    $data['_listAttribute'] = woocommerce_options_gets( $id );

	wcmc_filter_get_template('html-attribute', $data );
}

add_action('woocommerce_filter_html', 'woocommerce_filter_attribute_html', 20);


function wcmc_filter_get_attribute_url( $get, $key_attribute, $option_value ) {

	$ci =& get_instance();

	if( strpos( $key_attribute, 'attribute-' ) !== false ) {

		if( isset($get[$key_attribute]) ) {

			$option = explode(',', $get[$key_attribute] );

			if( in_array( $option_value, $option ) !== false ) {

				$key = array_search ( $option_value, $option );

				unset( $option[$key] );
			}
			else $option[] = $option_value;

		} else $option[] = $option_value;

		if( have_posts($option) ) {

			$option = implode(',', $option );

			$get[$key_attribute] = $option;
		}
		else {
			unset( $get[$key_attribute] );
		}

	}

	return $get;
}

add_filter( 'wcmc_filter_get_url', 'wcmc_filter_get_attribute_url', 10, 3 );


function woocommerce_filter_attribute_chose( $value, $key_attribute ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	$option = '';

	if( isset($get[$key_attribute]) ) {

		$option = explode(',', $get[$key_attribute] );

		if( in_array( $value, $option ) !== false ) {

			return 'chosen';
		}
	}

	return '';
}

function woocommerce_filter_attribute_args( $args ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	$lis_opItem_ID = array();

	$list_product_ID = array();

	$check = false;

	$model = get_model('post');

	$model->settable('post');

	foreach ($get as $key_attribute => $value) {

		if( strpos( $key_attribute, 'attribute-' ) !== false ) {

			$check = true;

			$key_attribute = substr( $key_attribute , 10 );

			$option = explode(',', $get['attribute-'.$key_attribute] );

			foreach ($option as $id) {

				$id = (int)$id;

				$lis_opItem_ID[$id] = $id;

			}
		}
		
	}

	if( have_posts($lis_opItem_ID) ) {

		$model->settable('relationships');

		$data['field'] 	= 'value';

		$data['data'] 	= $lis_opItem_ID;

		$temp = $model->gets_where_in( $data, array('object_type' => 'woocommerce_attributes'), array('select' => 'object_id'));

		foreach ($temp as $relationships) {

			$list_product_ID[$relationships->object_id] = $relationships->object_id;
			
		}
	}
	
	if( $check == true ) {

		if( have_posts( $list_product_ID ) ) {

			if( isset($args['where_in']['data']) ) {

				$args['where_in']['data'] = array_intersect( $args['where_in']['data'], $list_product_ID );
			}
			else $args['where_in']['data'] =  $list_product_ID;

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

add_action('woocommerce_controllers_index_args', 'woocommerce_filter_attribute_args', 20);