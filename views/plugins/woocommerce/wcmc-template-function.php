<?php
include 'include/wcmc-template-index.php';

include 'include/wcmc-template-detail.php';

include 'include/wcmc-template-search.php';

include 'include/wcmc-template-object.php';

if ( ! function_exists( '_price_currency' ) ) {
	/**
	 * @Đơn vị tiền tệ
	 */
	function _price_currency() {
		
		$currency = get_option('woocommerce_currency');

		return apply_filters('woocommerce_currency_symbol', $currency);
	}
}

if ( ! function_exists( '_price_none' ) ) {
	/**
	 * @Đơn vị tiền tệ khi giá bằng 0
	 */
	function _price_none() {
		$currency = get_option('woocommerce_price_contact');
		return apply_filters('woocommerce_product_price_none', $currency);
	}
}