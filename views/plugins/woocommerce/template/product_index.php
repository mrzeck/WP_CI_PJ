<div class="product-slider-horizontal" style="margin-top: 10px;">
	<?php 
	/**
	 * woocommerce_products_index_view hook.
	 *
	 * @hooked woocommerce_products_index_list_product - 10 - hiển thị danh sách sản phẩm
	 * @hooked woocommerce_products_index_pagination - 20 - hiển thị phân trang
	 */
	do_action('woocommerce_products_index_view');
	?>
</div>