<?php if( isset($cart) && have_posts($cart)) {?>
<div class="clearfix"></div>

<div class="woocommerce-cart-heading">
    <div class="cart-heading__button">
        <a class="btn btn-default btn-block" href="<?php echo get_url('gio-hang');?>"><?php echo __('QUAY LẠI', 'wcmc_tieptucmuahang');?></a>
		<button type="submit" value="order" class="btn btn-red btn-block wcmc-checkout-btn-submit" style="margin-left:0"><?php echo __('ĐẶT HÀNG', 'wcmc_dathang');?></button>
    </div>
</div>

<?php
	do_action('woocommerce_checkout_review_order_after');
?>

<div class="woocommerce-box" style="padding:10px">
	<table class="table woocommerce-checkout-review-order-table">
		<tr class="cart-subtotal">
			<th><?php echo __('Thành Tiền', 'wcmc_thanhtien');?></th>
			<td data-title="Thành tiền"><span id="cart-total-price"><?php echo number_format($ci->cart->total()); ?></span><?php echo _price_currency();?></td>
		</tr>
		<?php
			/**
			 * woocommerce_checkout_review_order
			 * 
			 * @hook woocommerce_checkout_review_shipping - 10 - Hiển thị thông tin shipping
			 */
			do_action('woocommerce_checkout_review_order');
		?>
		<tr class="total">
			<td><?php echo __('Tổng Cộng', 'wcmc_tong');?></td>
			<td><strong id="total"><?= number_format( wcmc_order_total() );?>₫</strong></td>
		</tr>
	</table>
</div>

<?php
	do_action('woocommerce_checkout_review_order_before');
?>

<div class="clearfix"></div>

<?php
	/**
	 * checkout_after_submit
	 *
	 * @hook woocommerce_checkout_shipping - 10
	 */
	do_action('checkout_after_submit', $cart );
?>
<?php } ?>
<div class="clearfix"></div>

<br />

