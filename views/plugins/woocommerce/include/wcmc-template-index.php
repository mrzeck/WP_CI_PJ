<?php
/** PRODUCT-INDEX ******************************************************************/
if ( ! function_exists( 'woocommerce_products_index' ) ) {
	/**
	 * @Hiển thị trang danh mục sản phẩm
	 */
	function woocommerce_products_index() {

		$ci =& get_instance();

		wcmc_get_template( 'product_index' );
	}

	add_action('content_products_index', 'woocommerce_products_index', 10);
}

if ( ! function_exists( 'woocommerce_products_index_list_product' ) ) {
	/**
	 * @Hiển thị danh sách sản phẩm
	 */
	function woocommerce_products_index_list_product() {

		$ci =& get_instance();

		wcmc_get_template( 'index/products' );
	}

	add_action('woocommerce_products_index_view', 'woocommerce_products_index_list_product', 10);
}

if ( ! function_exists( 'woocommerce_products_index_pagination' ) ) {
	/**
	 * @Hiển thị phân trang
	 */
	function woocommerce_products_index_pagination() {

		$ci =& get_instance();

		wcmc_get_template( 'index/pagination' );
	}

	add_action('woocommerce_products_index_view', 'woocommerce_products_index_pagination', 20);
}