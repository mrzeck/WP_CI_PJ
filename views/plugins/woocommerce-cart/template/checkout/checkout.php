<?php do_action( 'woocommerce_before_checkout' ); ?>
<?php
	$cart 	= $ci->cart->contents();

	$fields = get_checkout_fields();

	wcmc_cart_get_template_version();

	if(have_posts($cart)) {
?>
<div class="">
	<form name="checkout" class="woocommerce-checkout woocommerce-cart-content" method="post">

		<?php echo form_open();?>

		<div class="row">

			<div class="col-md-8">
				<div class="woocommerce-left">

					<?php do_action('wcmc_checkout_content', $cart);?>

				</div>
				<div class="woocommerce-center">
					<div class="wcm-box-more">
						<?php echo wcmc_get_template_cart('checkout/order-more');?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="woocommerce-right">
					<div class="wcm-box-order">
						<?php echo wcmc_get_template_cart('checkout/order-review');?>
					</div>
				</div>
			</div>

		</div>
	</form>
</div>

<?php } else { echo wcmc_get_template_cart('empty'); } ?>

<style type="text/css">
	.object-detail { border:0; background-color: transparent; }
	.box-bg-top { display: none; }
	.table-striped>tbody>tr:nth-of-type(odd) { background-color: transparent; }
	.warper {
		min-height:100vh;
		background-color:#F0F2F5!important;
	}
	h1, header, footer, .btn-breadcrumb { display:none; }
</style>