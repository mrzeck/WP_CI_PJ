<?php

$woocommerce_currency 		= get_option('woocommerce_currency');
$woocommerce_price_contact 	= get_option('woocommerce_price_contact');

?>

<div class="box">
	<?php admin_loading_icon();?>
	<div class="box-content">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Tiền tệ</label>
						<input name="woocommerce_currency" type="text" class="form-control" value="<?php echo $woocommerce_currency;?>">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Giá liên hệ</label>
						<p>Thay thế cho giá sản phẩm khi bằng 0</p>
						<input name="woocommerce_price_contact" type="text" class="form-control" value="<?php echo $woocommerce_price_contact;?>">
					</div>
				</div>
			</div>

		</div>

	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {

			$('.loading').show();

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_ajax_setting_product_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {

				$('.loading').hide();

	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>