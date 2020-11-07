<div class="woocommerce-box" id="checkout_shipping_form" style="display: none;">

	<h2 class="cart__title"><?php echo __('Thông Tin Giao Hàng', 'wcmc_thongtinthanhtoan');?></h2>
	
	<?php do_action( 'woocommerce_before_checkout_shipping_form' ); ?>

	<?php foreach ($fields['shipping'] as $key => $field): ?>
		<?php echo _form($field, ( !empty($field['value']) ) ? $field['value'] : '');?>
	<?php endforeach ?>

	<?php do_action( 'woocommerce_after_checkout_shipping_form' ); ?>
</div>


<script type="text/javascript">
	$(function(){
		$('input[name="show-form-shipping"]').click(function(){ $('#checkout_shipping_form').toggle(); })
	})
</script>