<div class="woocommerce-box">
	<h2 class="cart__title">GIAO HÀNG</h2>
	<select name="shipping" id="shipping" class="form-control" required>

		<option value="0">Chọn khu vực</option>

		<?php foreach ($shipping['shipping'] as $key => $account): ?>

			<option value="<?php echo $account['shipping_key'];?>"><?php echo $account['shipping_name'];?></option>

		<?php endforeach;?>

	</select>
</div>

<script type="text/javascript">
	$(document).on('change', '#shipping', function() {

		wcmc_update_order_review();

		return false;
	});
</script>