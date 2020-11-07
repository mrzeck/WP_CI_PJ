<?php
if( !function_exists('get_product_meta') ) {

	function get_product_meta( $product_id, $key = '', $single = true) {

		$data = get_metadata('product', $product_id, $key, $single);

		return $data;
	}
}

if( !function_exists('update_product_meta') ) {

	function update_product_meta($product_id, $meta_key, $meta_value) {
		return update_metadata('product', $product_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_product_meta') ) {

	function delete_product_meta($product_id, $meta_key = '', $meta_value = '') {

		return delete_metadata('product', $product_id, $meta_key, $meta_value);

	}
}


if( !function_exists('get_product_category_meta') ) {

	function get_product_category_meta( $cateID, $key = '', $single = true) {
		$data = get_metadata('products_categories', $cateID, $key, $single);
		return $data;
	}
}

if( !function_exists('update_product_category_meta') ) {

	function update_product_category_meta($cateID, $meta_key, $meta_value) {
		return update_metadata('products_categories', $cateID, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_product_category_meta') ) {

	function delete_product_category_meta($cateID, $meta_key = '', $meta_value = '') {
		return delete_metadata('products_categories', $cateID, $meta_key, $meta_value);
	}
}