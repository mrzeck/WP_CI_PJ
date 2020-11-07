<?php
	do_action('woocommerce_cart_review_order_after');
?>
<table cellspacing="0" class="woocommerce-box table shop_table_responsive">

	<tr class="cart-subtotal">
		<th><?php echo __('Thành Tiền', 'wcmc_thanhtien');?></th>
		<td data-title="Thành tiền"><span id="cart-total-price"><?php echo number_format($ci->cart->total()); ?></span><?php echo _price_currency();?></td>
	</tr>

	<?php
		do_action('woocommerce_cart_review_order');
	?>
	

	<tr class="cart-subtotal">
		<th><?php echo __('Tổng Tiền', 'wcmc_tong');?></th>
		<td data-title="Thành tiền"><span id="summary-cart-total-price"><?php echo number_format( wcmc_order_total() ); ?><span><?php echo _price_currency();?></td>
	</tr>

</table>
<?php
	do_action('woocommerce_cart_review_order_before');
?>