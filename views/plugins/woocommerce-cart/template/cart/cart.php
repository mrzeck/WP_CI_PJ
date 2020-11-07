<div class="woocommerce-cart woocommerce-cart-content">

	<?php do_action( 'woocommerce_before_cart' ); ?>

	<?php $cart = $ci->cart->contents();?>

	<?php if(have_posts($cart)) { ?>
	<div class="row">
		<div class="col-md-8">
			<div class="woocommerce-cart-form woocommerce-box">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<form method="post" class="wcmc-cart-box" id="wcmc_cart_form">
					<?php echo form_open();?>
					<div class="wcmc-cart-tbody">
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>
						<?php foreach ($cart as $item): $item = (object)$item; ?>
							<?php echo wcmc_get_template_cart('cart/cart-items', array('item' => $item));?>
						<?php endforeach ?>
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</div>
				</form>

				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="cart-collaterals woocommerce-right">
				<?php echo wcmc_get_template_cart('cart/cart-total');?>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

	<?php } else { echo wcmc_get_template_cart('empty'); } ?>

	<?php do_action( 'woocommerce_after_cart' ); ?>
</div>



<style type="text/css">
	.btn-outline-danger {
	    color: #dc3545;
	    background-color: transparent;
	    background-image: none;
	    border-color: #dc3545;
	}
	.object-detail { border:0; background-color: transparent; }
	.box-bg-top { display: none; }
	.table-striped>tbody>tr:nth-of-type(odd) { background-color: transparent; }

	.warper {
		min-height:100vh;
		background-color:#F0F2F5!important;
	}
	h1, header, footer, .btn-breadcrumb { display:none; }
</style>

<script type="text/javascript">
	$(function(){

		$('.plus').click(function(){
			var box   = $(this).closest('.quantity');
			var input = box.find('input.qty');
			var number = box.find('div.qty');
			var qty   = parseInt(input.val());
			qty += 1;
			input.val(qty);
			number.text(qty);
		});

		$('.minus').click(function(){
			var box   = $(this).closest('.quantity');
			var input = box.find('input.qty');
			var number = box.find('div.qty');
			var qty   = parseInt(input.val());
			console.log(qty);
			if(qty > 1 ) {
				qty -= 1;
				input.val(qty);
				number.text(qty);
			}
		});

		var qty_clickTimer;

		var qty_doneClickInterval = 300; //time in ms, 5 second for example

		var $input = $('.plus, .minus');

		//on keyup, start the countdown
		$input.on('click', function() {
			clearTimeout(qty_clickTimer);
			qty_clickTimer = setTimeout(qtyDoneClick($(this)), qty_doneClickInterval);
		});

		//user is "finished typing," do something
		function qtyDoneClick(e) {

			var box   = e.closest('.wcmc-cart__item');
			
			var data = {
				'action' : 'wcmc_ajax_cart_update_quantity',
				'rowid'  : box.find('input[name="rowid"]').val(),
				'qty'    : box.find('input.qty').val(),
			};

			$jqxhr = $.post(base + '/ajax', data, function() {}, 'json');

			$jqxhr.done(function(data) {

				if(data.type == 'success') {
					// box.find('.js_cart_item_price').html(data.price);
					$('#cart-total-price').html(data.total);
					$('#summary-cart-total-price').html(data.summary_total);
				}
			});
		}

		$('.cart_item__trash, .js_cart_item__trash_close').click(function () {
			$(this).closest('.wcmc-cart__item').find('.cart_item__trash_popover').toggleClass('active');
		});

		$('.js_cart_item__trash_agree').click(function () {

			var box   = $(this).closest('.wcmc-cart__item');
			
			var data = {
				'action' : 'wcmc_ajax_cart_update_quantity',
				'rowid'  : box.find('input[name="rowid"]').val(),
				'qty'    : 0
			};

			$jqxhr = $.post(base + '/ajax', data, function() {}, 'json');

			$jqxhr.done(function(data) {

				if(data.type == 'success') {

					box.remove();

					if(data.total == 0) {
						location.reload();
					}
					else {
						$('#cart-total-price').html(data.total);
						$('#summary-cart-total-price').html(data.summary_total);
					}
				}
			});
		});
	})
</script>