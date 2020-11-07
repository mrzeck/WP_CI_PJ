<div class="box" id="order_items">

	<div class="box-content">

		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Chi Tiết đơn hàng</h2>
				<span style="background-color:<?php echo woocommerce_order_status_color($order->status);?>;border-radius:10px; padding:0px 15px; height:25px; line-height: 25px; display:inline-block;"><?php echo woocommerce_order_status_label($order->status);?></span>
			</div>
		</header>

		<div class="order_cart__section">
			
			<table class="woocommerce_order_items" style="width:100%">
		    <?php foreach ($order->items as $key => $val): ?>
		      	<tr class="item">
		        	<td><img src="<?= SOURCE.$val->image;?>" class="img-thumbnail img-responsive" style="max-width:100%;height:50px;"></td>
		        	<td>
		        		<?php echo $val->title;?>
		        		<?php $val->option = (is_serialized($val->option))?@unserialize($val->option):$val->option ;?>
		        		<?php if(isset($val->option) && have_posts($val->option)) {
							$attributes = '';
							foreach ($val->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
							$attributes = trim( trim($attributes), '/' );
							echo '<p class="variant-title" style="color:#999;">'.$attributes.'</p>';
						} ?>
		        	</td>
		        	<td><?= number_format($val->price);?> VNĐ x <b><?= $val->quantity;?></b></td>
		        	<td><?= number_format($val->price*$val->quantity);?> VNĐ</td>
		      	</tr>
		    <?php endforeach ?>
		    </table>
		</div>
	</div>
	<div class="box-content wc-order-data-row">
		<table class="wc-order-totals">
			<tbody>
				<?php $totals = get_order_item_totals( $order ); ?>

				<?php foreach ($totals as $total): ?>
				<tr>
					<td class="label"><?php echo $total['label'];?></td>
					<td width="1%"></td>
					<td class="total">
						<span class="woocommerce-Price-amount amount">
							<?php echo $total['value'];?>
						</span>				
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<div class="clear"></div>
	</div>

</div>