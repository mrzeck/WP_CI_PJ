<?php
include 'wcmc-order-action-bar.php';
include 'wcmc-order-table.php';
include 'wcmc-order-action.php';
include 'wcmc-print.php';
include 'wcmc-order-save.php';
function woocommerce_order($ci, $model) {
	$ci =& get_instance();
	$views 	= removeHtmlTags( $ci->input->get('view') );
	if( $views == 'shop_order') {
		$args = array();
		$pagination = array();
		$customer_id = (int)$ci->input->get('customer_id');
		if($customer_id != 0) {
			$args = [
				'where' => [
					'user_created' => $customer_id
				]
			];
		}
		$keyword = removeHtmlTags($ci->input->get('name'));
		if( !empty($keyword) ) $args['meta_query'][] = array('key' => 'billing_fullname', 'value' => $keyword, 'compare' => 'LIKE');
		$phone   = removeHtmlTags($ci->input->get('phone'));
		if( !empty($phone) ) $args['meta_query'][] = array('key' => 'billing_phone', 'value' => $phone, 'compare' => 'LIKE');
		$action   = removeHtmlTags($ci->input->get('action'));
		if( !empty($action) ) $args['where']['status'] = $action;
		$time_start = removeHtmlTags($ci->input->get('time_start'));
		if( !empty($time_start) ) {
			$time_start = date('Y-m-d', strtotime($time_start)).' 00:00:00';
			$args['where']['created >='] = $time_start;
		}
		$time_end = removeHtmlTags($ci->input->get('time_end'));
		if( !empty($time_end) ) {
			$time_end = date('Y-m-d', strtotime($time_end)).' 23:59:59';
			$args['where']['created <='] = $time_end;
		}
		if(empty($keyword) && empty($phone) && empty($action) && (empty($time_start) || empty($time_end))) {
			$order_limit = 20;
			$total = count_order($args);
			//Phân trang
			$url        = admin_url('plugins?page=woocommerce_order&view=shop_order&paging={paging}');
			$pagination = pagination($total, $url, $order_limit);
			$params = array(
				'limit'  => $order_limit,
				'start'  => $pagination->getoffset(),
				'orderby'=> 'order, created desc',
			);
			$args['params'] = $params;
		}
		$args = apply_filters( 'woocommerce_order_index_args', $args );
		$orders = gets_order($args);
		$args = array(
	        'items' => $orders,
	        'table' => 'wcmc_order',
	        'model' => $model,
	        'module'=> 'woocommerce_cart',
	    );
		$table_list = new skd_woocomerce_order_list_table($args);
		wcmc_get_template_cart('admin/order/html-order-index', array('table_list' => $table_list, 'pagination' => $pagination));
	}
	if( $views == 'shop_order_detail') {
		$id 	= (int)$ci->input->get('id');
		$order 	= get_order( $id );
		if( $ci->input->post() ) {
			$action = $ci->input->post('wcmc-action');
			$action = removeHtmlTags($action);
			if( !empty($action) ) {
				do_action( 'woocommerce_order_action_'.$action,  $order );
			}
			do_action( 'woocommerce_order_action_post', $order );
			redirect( URL_ADMIN.'/plugins?page=woocommerce_order&view=shop_order_detail&id='.$id,'refresh');
		}
		wcmc_get_template_cart('admin/order/html-order-detail', array( 'order' => $order ));
	}
	if( $views == 'create' ) {
		if(current_user_can('wcmc_order_add'))  {
			include WCMC_CART_PATH.'template/admin/order/html-order-save.php';
		}
		else {
			echo notice('error', 'Bạn không có đủ quyền để sử dụng chức năng này.');
		}
	}
	if( $views == 'edit' ) {
		if(current_user_can('wcmc_order_copy'))  {
			$id 	= (int)$ci->input->get('id');
			$order 	= get_order( $id );
			include WCMC_CART_PATH.'template/admin/order/html-order-save.php';
		}
		else {
			echo notice('error', 'Bạn không có đủ quyền để sử dụng chức năng này.');
		}
	}
}
//============================ PAGE DANH SÁCH ĐƠN HÀNG ========================//
if( ! function_exists('order_index_search_customer_name') ) {
	function order_index_search_customer_name() {
		wcmc_get_template_cart('admin/order/index/search-customer-name');
	}
	add_action('order_index_search', 'order_index_search_customer_name', 10);
}
if( ! function_exists('order_index_search_customer_phone') ) {
	function order_index_search_customer_phone() {
		wcmc_get_template_cart('admin/order/index/search-customer-phone');
	}
	add_action('order_index_search', 'order_index_search_customer_phone', 20);
}
if( ! function_exists('order_index_search_order_action') ) {
	function order_index_search_order_action() {
		wcmc_get_template_cart('admin/order/index/search-order-action');
	}
	add_action('order_index_search', 'order_index_search_order_action', 30);
}
if( ! function_exists('order_index_search_order_time') ) {
	function order_index_search_order_time() {
		wcmc_get_template_cart('admin/order/index/search-order-time');
	}
	add_action('order_index_search', 'order_index_search_order_time', 40);
}
//============================ PAGE CHI TIẾT ĐƠN HÀNG ========================//
if( ! function_exists('order_detail_primary_content') ) {
	function order_detail_primary_content( $order ) {
		wcmc_get_template_cart('admin/order/detail/content', array('order' => $order));
	}
	add_action('order_detail_sections_primary', 'order_detail_primary_content', 10, 1);
}
if( ! function_exists('order_detail_primary_note') ) {
	function order_detail_primary_note( $order ) {
		wcmc_get_template_cart('admin/order/detail/note', array('order' => $order));
	}
	add_action('order_detail_sections_primary', 'order_detail_primary_note', 20, 1);
}
if( ! function_exists('order_detail_secondary_action') ) {
	/**
	 * [order_detail_secondary_action hiển thị hành động cập nhật status]
	 */
	function order_detail_secondary_action( $order ) {
		wcmc_get_template_cart('admin/order/detail/sidebar-action', array('order' => $order));
	}
	add_action('order_detail_sections_secondary', 'order_detail_secondary_action', 10, 1);
}
if( ! function_exists('order_detail_secondary_customer') ) {
	/**
	 * [order_detail_secondary_customer hiển thị thông tin khách hàng]
	 */
	function order_detail_secondary_customer( $order ) {
		wcmc_get_template_cart('admin/order/detail/sidebar-customer', array('order' => $order));
	}
	add_action('order_detail_sections_secondary', 'order_detail_secondary_customer', 20, 1);
}
if( ! function_exists('order_detail_billing_info') ) {
	function order_detail_billing_info($order) {
		$billing = get_chekout_fields_billing();
		$temp = array();
		foreach ($billing as $key => $field) {
			if(isset($order->{$field['field']})) $temp[$field['field']] = $order->{$field['field']};
		}
		return apply_filters( 'order_detail_billing_info', $temp );
	}
}
if( ! function_exists('order_detail_shipping_info') ) {
	function order_detail_shipping_info($order) {
		$shipping = get_chekout_fields_shipping();
		$temp = array();
		foreach ($shipping as $key => $field) {
			if(isset($order->{$field['field']})) $temp[$field['field']] = $order->{$field['field']};
		}
		return apply_filters( 'order_detail_shipping_info', $temp );
	}
}
function wcmc_order_detail_edit_button( $order ) {
	if(!current_user_can('wcmc_order_copy')) return false;
	?>
	<a href="<?php echo admin_url('plugins?page=woocommerce_order&view=edit&id='.$order->id);?>" class="btn btn-default"><i class="fal fa-clone"></i> Đặt lại</a>
	<?php
}
add_action( 'order_detail_header_action', 'wcmc_order_detail_edit_button' );