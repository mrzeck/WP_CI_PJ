<?php
/** PRODUCT-OBJECT ******************************************************************/
if ( ! function_exists( 'woocommerce_product_object_image' ) ) {
	/**
	 * [woocommerce_product_object_image description]
	 */
	function woocommerce_product_object_image( $val ) {
		wcmc_get_template( 'object/image', array('val' => $val ) );
	}
}

if ( ! function_exists( 'woocommerce_product_object_title' ) ) {
	/**
	 * [woocommerce_product_object_image description]
	 */
	function woocommerce_product_object_title( $val ) {

		wcmc_get_template( 'object/title', array('val' => $val ) );
	}
}

if ( ! function_exists( 'woocommerce_product_object_price' ) ) {
	/**
	 * [woocommerce_product_object_image description]
	 */
	function woocommerce_product_object_price( $val ) {

		wcmc_get_template( 'object/price', array('val' => $val ) );
	}
}

if ( ! function_exists( 'woocommerce_product_object_description' ) ) {
	/**
	 * [woocommerce_product_object_image description]
	 */
	function woocommerce_product_object_description( $val ) {

		wcmc_get_template( 'object/description', array('val' => $val ) );
	}
}

if ( ! function_exists( 'woocommerce_product_object' ) ) {

	function woocommerce_product_object() {

		$ci =& get_instance();

		$product_hiden_img      	= get_option('product_hiden_img');

		$product_hiden_title      	= get_option('product_hiden_title');

		$product_hiden_price      	= get_option('product_hiden_price');

		$product_hiden_description  = get_option('product_hiden_description');

		if( $product_hiden_img ) 			add_action( 'product_object_image',	'woocommerce_product_object_image', 10, 1 );

		if( $product_hiden_title ) 			add_action( 'product_object_info',	'woocommerce_product_object_title', 10, 1 );

		if( $product_hiden_price ) 			add_action( 'product_object_info',	'woocommerce_product_object_price', 20, 1 );

		if( $product_hiden_description ) 	add_action( 'product_object_info',	'woocommerce_product_object_description', 30, 1 );

	}

	add_action( 'init','woocommerce_product_object', 10 );

}

if ( ! function_exists( 'woocommerce_product_object_style' ) ) {

	function woocommerce_product_object_style() {

		$ci =& get_instance();

		wcmc_get_template( 'object/css');
	}

	add_action( 'cle_header','woocommerce_product_object_style', 50 );

}