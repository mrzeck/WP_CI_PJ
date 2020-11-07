<div class="box" id="order_note">
	<div class="box-content">
		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Ghi chú</h2>
			</div>
		</header>

		<div class="order_cart__section">
			<?php echo get_order_meta($order->id, 'order_note', true);?>
		</div>
	</div>
</div>

<?php 
$payments = wcmc_gets_payment();
if( have_posts($payments) && !empty($order->_payment) ) {?>
<div class="box" id="order_payment">
	<div class="box-content">
		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Hình thức thanh toán</h2>
			</div>
		</header>

		<div class="order_cart__section">
			<?php
				foreach ($payments as $key => $payment) {
					if( $order->_payment == $key ) {
						echo ( !empty($payment['woocommerce_'.$key.'_title']) ) ? $payment['woocommerce_'.$key.'_title'] : $key ;
					}
				}
			?>
		</div>
	</div>
</div>
<?php } ?>