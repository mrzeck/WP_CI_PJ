<?php
if( !function_exists('woocommerce_order_action') ) {

	function woocommerce_order_action() {

		$action[''] = 'Chọn hành động...';

		$status = order_status();

		$action['wc-status'] = array(
			'label' => 'Cập nhật trạng thái đơn hàng',
			'value' => array(),
		);

		foreach ($status as $key => $value) {

			$action['wc-status']['value'][$key] = $value['label'];
		}

		return apply_filters('woocommerce_order_action', $action);
	}
}

if( !function_exists('wcmc_order_action_status_wait_confim') ) {
	/**
	 * [wcmc_order_action_status_wait_confim chờ xác nhận]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_wait_confim( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-wait-confim', 'wcmc_order_action_status_wait_confim' );
}

if( !function_exists('wcmc_order_action_status_confim') ) {
	/**
	 * [wcmc_order_action_status_confim đã xác nhận]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_confim( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-confim', 'wcmc_order_action_status_confim' );
}

if( !function_exists('wcmc_order_action_status_processing') ) {
	/**
	 * [wcmc_order_action_status_processing Đang xử lý]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_processing( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-processing', 'wcmc_order_action_status_processing' );
}

if( !function_exists('wcmc_order_action_status_pending') ) {
	/**
	 * [wcmc_order_action_status_pending Chờ thanh toán]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_pending( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-pending', 'wcmc_order_action_status_pending' );
}

if( !function_exists('wcmc_order_action_status_completed') ) {
	/**
	 * [wcmc_order_action_status_completed Đã hoàn thành]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_completed( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-completed', 'wcmc_order_action_status_completed' );
}

if( !function_exists('wcmc_order_action_status_cancelled') ) {
	/**
	 * [wcmc_order_action_status_cancelled Đã hủy]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_cancelled( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-cancelled', 'wcmc_order_action_status_cancelled' );
}

if( !function_exists('wcmc_order_action_status_refunded') ) {
	/**
	 * [wcmc_order_action_status_refunded Đã hoàn tiền]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_refunded( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-refunded', 'wcmc_order_action_status_refunded' );
}

if( !function_exists('wcmc_order_action_status_failed') ) {
	/**
	 * [wcmc_order_action_status_failed Đã thất bại]
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	function wcmc_order_action_status_failed( $order ) {

		$ci =& get_instance();

		$action = $ci->input->post('wcmc-action');

		$action = removeHtmlTags($action);

		if( $order->status != $action ) insert_order(array('id' => $order->id, 'status' => $action));
	}

	add_action( 'woocommerce_order_action_wc-failed', 'wcmc_order_action_status_failed' );
}