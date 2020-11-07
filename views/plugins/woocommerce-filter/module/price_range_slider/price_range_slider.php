<?php

include 'price_range.widget.php';

function woocommerce_filter_price_range_html() {

	$_listprice_range = get_option('wcmc_filter_price_range_option', serialize(array()));

	$_listprice_range = unserialize( $_listprice_range );


	if( have_posts( $_listprice_range ) ) {

		foreach ($_listprice_range as $value) {

			$data['_listprice_range'][ $value['min_price_range'] .'-'. $value['max_price_range']] = $value['label'];
		}

		wcmc_filter_get_template('html-price_range', $data );
	}
	
}


add_action('woocommerce_filter_html', 'woocommerce_filter_price_range_html', 10);

function woocommerce_filter_price_range_chose( $value ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	$option = '';

	if( isset( $get['min_price_range'] ) && isset( $get['max_price_range'] ) ) {

		$option = $get['min_price_range'].'-'.$get['max_price_range'];

	}
	else if( isset( $get['min_price_range'] ) ) {

		$option = $get['min_price_range'].'-0';

	}
	else if( isset( $get['max_price_range'] ) ) {

		$option = '0-'.$get['max_price_range'];

	}

	if( $option == $value ) return 'chosen'; 

	return '';
}


function wcmc_filter_get_price_range_url( $get, $input, $option_value ) {

	$ci =& get_instance();

	if( $input == 'price_range' ) {

		if( woocommerce_filter_price_range_chose( $option_value ) == 'chosen' ) {

			if( isset($get['min_price_range']) ) unset( $get['min_price_range']);

			if( isset($get['max_price_range']) ) unset( $get['max_price_range']);

		}
		else {

			$option_value = explode('-', $option_value );

			if( $option_value[0] == 0  && isset($get['min_price_range']) ) unset( $get['min_price_range']);

			if( $option_value[0] != 0 ) $get['min_price_range'] = $option_value[0];

			if( $option_value[1] == 0  && isset($get['max_price_range']) ) unset( $get['max_price_range']);

			if( $option_value[1] != 0 ) $get['max_price_range'] = $option_value[1];

		}
		
	}

	return $get;
}

add_filter( 'wcmc_filter_get_url', 'wcmc_filter_get_price_range_url', 10, 3 );

function woocommerce_filter_price_range_where( $where ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	if( isset( $get['min_price_range'] ) ) $where['price_range >='] = $get['min_price_range'];

	if( isset( $get['max_price_range'] ) ) $where['price_range <='] = $get['max_price_range'];

	return $where;
	
}

add_action('wcmc_filter_product_where', 'woocommerce_filter_price_range_where');


