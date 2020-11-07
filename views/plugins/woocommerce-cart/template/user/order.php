<?php
$user = get_user_current();

$orders = wcmc_gets_order( array('where' => array('user_created' => $user->id ) ) );

?>
<div class="col-md-12">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo __('Mã đơn hàng'); ?></th>
				<th><?php echo __('Số sản phẩm'); ?></th>
				<th><?php echo __('Tổng tiền'); ?></th>
				<th><?php echo __('Ngày mua'); ?></th>
				<th><?php echo __('Tình trạng'); ?></th>
				<th><?php echo __('Chi tiết'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order): ?>
			<tr>
				<td class="order_code"><a href="<?php echo my_account_url().'/order/detail?code='.$order->id;?>">#<?php echo $order->code;?></a></td>
				<td class="order_quantity"><?php echo $order->quantity;?></td>
				<td class="order_total"><?php echo number_format($order->total)._price_currency();?></td>
				<td class="order_created"><?php echo date('d/m/Y', strtotime($order->created));?></td>
				<td class="order_status"><?php echo  '<span style="background-color:'.woocommerce_order_status_color($order->status).'; border-radius:10px; padding:0px 5px; display:inline-block;">'.woocommerce_order_status_label($order->status).'</span>';?></td>
				<td class="order_detail"><a href="<?php echo my_account_url().'/order/detail?code='.$order->id;?>"><?php echo __('Chi Tiết') ?></a></td>
			</tr>
			<?php endforeach ?>
			
		</tbody>
	</table>
</div>

<style type="text/css">
	.table>thead>tr>th {
		font-size: 16px;
		padding: 10px 0;
    	font-weight: bold;
	}
	.table>tbody>tr>td { border:0; padding:20px 0; }
	.table>tbody>tr>td.order_code { font-weight: bold; }
</style>