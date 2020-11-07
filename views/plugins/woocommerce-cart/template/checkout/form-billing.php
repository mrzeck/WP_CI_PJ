<div class="woocommerce-box">

	<h2 class="cart__title"><?php echo __('Thông Tin Thanh Toán', 'wcmc_thongtinthanhtoan');?></h2>

	<?php do_action( 'woocommerce_before_checkout_billing_form' ); ?>

	<div class="row">
		<?php foreach ($fields['billing'] as $key => $field): ?>

			<?php echo _form($field, ( !empty($field['value']) ) ? $field['value'] : '');?>

		<?php endforeach ?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form' ); ?>

	
	<div class="checkbox">
		<label>
			<input type="checkbox" name="show-form-shipping">
			<span><?php echo __('Giao Hàng Tới Địa Chỉ Khác', 'wcmc_giaohangdiachikhac');?> ?</span>
		</label>
	</div>
	
</div>