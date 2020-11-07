<?php
include 'order-save/wcmc-order-save-search-product.php';
include 'order-save/wcmc-order-save-search-customer.php';
function wcmc_admin_order_button_add() {
	if(!current_user_can('wcmc_order_add')) return false;
	?>
	<a href="<?php echo admin_url('plugins?page=woocommerce_order&view=create');?>" class="btn btn-default"><i class="fal fa-layer-plus"></i> Thêm đơn hàng</a>
	<?php
}
add_action( 'admin_order_action_bar_heading', 'wcmc_admin_order_button_add' );
//================================ order save function ===========================================//
function wcmc_order_save_total( $total = 0 ) {
	$total = apply_filters( 'wcmc_order_save_total', $total );
	return $total;
}
//================================ order save primary ===========================================//
if( ! function_exists('order_save_primary_product_items') ) {
	function order_save_primary_product_items( $order ) {
		wcmc_get_template_cart('admin/order/save/product-items', array('order' => $order));
	}
	add_action('order_save_sections_primary', 'order_save_primary_product_items', 10, 1);
}
if( ! function_exists('order_save_primary_payments') ) {
	function order_save_primary_payments( $order ) {
		wcmc_get_template_cart('admin/order/save/payments', array('order' => $order));
	}
	add_action('order_save_sections_primary', 'order_save_primary_payments', 10, 1);
}
//================================ order save secondary ========================================//
if( ! function_exists('order_save_secondary_customer') ) {
	function order_save_secondary_customer( $order ) {
		wcmc_get_template_cart('admin/order/save/customer', array('order' => $order));
	}
	add_action('order_save_sections_secondary', 'order_save_secondary_customer', 10, 1);
}
