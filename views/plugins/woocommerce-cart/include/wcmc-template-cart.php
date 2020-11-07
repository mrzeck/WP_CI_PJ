<?php
/** PRODUCT-CART : CHI TIẾT SẢN PHẨM ******************************************************************/
if ( ! function_exists( 'woocommerce_product_add_cart' ) ) {
	/**
	 * Hiển thị các lựa chọn liên quan add to cart
	 *
	 * @param array Provided arguments
	 */
	function woocommerce_product_add_cart(){
	    wcmc_get_template_cart( 'detail/cart');
	}

	add_action('woocommerce_products_detail_info', 	'woocommerce_product_add_cart', 40);
}

if ( ! function_exists( 'woocommerce_product_data_variations' ) ) {
	/**
	 * Lấy dữ liệu các biến thể
	 *
	 * @param array Provided arguments
	 */
	function woocommerce_product_data_variations( $object = array() ){

	    $ci =& get_instance();

		if( !have_posts($object) ) $object  = $ci->data['object'];

	    return apply_filters( 'woocommerce_product_data_variations', gets_attribute( ['product_id' => $object->id] ) );
	}
}

if ( ! function_exists( 'woocommerce_product_show_variations' ) ) {
	/**
	 * Hiển thị các biến thể
	 *
	 * @param array Provided arguments
	 */
	function woocommerce_product_show_variations($option){

	    if(have_posts($option) && isset($option['items']) && have_posts($option['items'])) {
			
	    	wcmc_get_template_cart( 'detail/cart-variations', array( 'option' => $option ) );

	    }
	}
}
/** 
 * CART : Giỏ hàng ******************************************************************/

if ( ! function_exists( 'woocommerce_cart_heading' ) ) {
	/**
	 * Hiển thị tiêu đề trang giỏ hàng
	 */
	function woocommerce_cart_heading() {
		if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {
			wcmc_get_template_cart( 'cart/cart-heading');
		}
	}

	add_action('woocommerce_before_cart', 'woocommerce_cart_heading', 10);
}