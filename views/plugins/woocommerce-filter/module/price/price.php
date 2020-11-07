<?php
include 'admin/setting-price.php';

include 'price.widget.php';

function woocommerce_filter_price_html() {

	$_listPrice = get_option('wcmc_filter_price_option', serialize(array()));
	$_listPrice = serialize( $_listPrice );
	$_listPrice = unserialize( $_listPrice );


	if( have_posts( $_listPrice ) ) {

		foreach ($_listPrice as $value) {

			$data['_listPrice'][ $value['min_price'] .'-'. $value['max_price']] = $value['label'];
		}

		wcmc_filter_get_template('html-price', $data );
	}
	
}


add_action('woocommerce_filter_html', 'woocommerce_filter_price_html', 10);

function woocommerce_filter_price_chose( $value ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	$option = '';

	if( isset( $get['min_price'] ) && isset( $get['max_price'] ) ) {

		$option = $get['min_price'].'-'.$get['max_price'];

	}
	else if( isset( $get['min_price'] ) ) {

		$option = $get['min_price'].'-0';

	}
	else if( isset( $get['max_price'] ) ) {

		$option = '0-'.$get['max_price'];

	}

	if( $option == $value ) return 'chosen'; 

	return '';
}


function wcmc_filter_get_price_url( $get, $input, $option_value ) {

	$ci =& get_instance();

	if( $input == 'price' ) {

		if( woocommerce_filter_price_chose( $option_value ) == 'chosen' ) {

			if( isset($get['min_price']) ) unset( $get['min_price']);

			if( isset($get['max_price']) ) unset( $get['max_price']);

		}
		else {

			$option_value = explode('-', $option_value );

			if( $option_value[0] == 0  && isset($get['min_price']) ) unset( $get['min_price']);

			if( $option_value[0] != 0 ) $get['min_price'] = $option_value[0];

			if( $option_value[1] == 0  && isset($get['max_price']) ) unset( $get['max_price']);

			if( $option_value[1] != 0 ) $get['max_price'] = $option_value[1];

		}
		
	}

	return $get;
}

add_filter( 'wcmc_filter_get_url', 'wcmc_filter_get_price_url', 10, 3 );

function woocommerce_filter_price_where( $where ) {

	$ci =& get_instance();

	$get = $ci->input->get();

	if( isset( $get['min_price'] ) ) $where['price >='] = $get['min_price'];

	if( isset( $get['max_price'] ) ) $where['price <='] = $get['max_price'];

	return $where;
	
}

add_action('wcmc_filter_product_where', 'woocommerce_filter_price_where');


