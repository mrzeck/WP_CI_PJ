<?php
function wcmc_gets_payment() {

	$payment['cod'] 	= get_option('_setting_checkout_cod');

	if(empty($payment['cod']['woocommerce_cod_img'])) {
		$payment['cod']['woocommerce_cod_img'] = base_url().WCMC_CART_PATH.'assets/images/cod.png';
	}

	$payment['bacs'] 	= get_option('_setting_checkout_bacs');

	if(empty($payment['bacs']['woocommerce_bacs_img'])) {
		$payment['bacs']['woocommerce_bacs_img'] = base_url().WCMC_CART_PATH.'assets/images/bank.png';
	}

	return apply_filters('wcmc_gets_payment', $payment );
}

/**
 * [woocommerce_checkout_payment_save lưu phương thức thanh toán]
 */
function woocommerce_checkout_payment_save( $id, $data ) {

	if( isset($data['_payment']) ) {
		update_order_meta( $id, '_payment', removeHtmlTags($data['_payment']));
	}
}

add_action( 'woocommerce_checkout_order_after_save', 'woocommerce_checkout_payment_save', 10, 2);