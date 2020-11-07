<div class="box" id="order_action">

	

	<div class="box-content">
		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Hành Động</h2>
			</div>
		</header>

		<div class="order_cart__section">
			<?php
				$actions = woocommerce_order_action(); 
			?>
			<select name="wcmc-action" class="form-control">

				<?php foreach ($actions as $key => $action): ?>
					<?php if( have_posts($action) ) { ?>
					<optgroup value="<?php echo $key;?>" label="<?php echo $action['label'];?>">
						<?php foreach ($action['value'] as $k => $value): ?>
						<option value="<?php echo $k;?>"><?php echo $value;?></option>
						<?php endforeach ?>
					</optgroup>
					<?php } else { ?>
						<option value="<?php echo $key;?>"><?php echo $action;?></option>
					<?php } ?>
				<?php endforeach ?>

			</select>
			<hr />
			<div class="text-right">
				<button type="submit" name="submit" class="btn btn-icon btn-blue">Cập nhật</button>
			</div>

		</div>
	</div>
</div>