<?php
$user 		= get_user_current();

$order_id 	= (int)$ci->input->get('code');

$order 		= wcmc_get_order( array('where' => array('user_created' => $user->id, 'id' => $order_id ) ) );

?>
<div class="col-md-12">
	<div class="order-ui order-header">
		<h1>Đơn hàng #<?php echo $order->code;?>, <span class="order-time-created"><?php echo __('Đặt lúc');?> — <?php echo date('d/m/Y, H:s', strtotime($order->created));?></span></h1>
		<span style="background-color:<?php echo woocommerce_order_status_color($order->status);?>;border-radius:10px; padding:0px 15px; height:25px; line-height: 25px; display:inline-block;"><?php echo woocommerce_order_status_label($order->status);?></span>
	</div>
</div>

<div class="col-md-12">
	<div class="row">
		<div class="col-md-6 order_cart__section">
			<h3>Thanh Toán</h3>
			<div class="order-customer-info">
				<p><span><?php echo $order->billing_fullname;?></span></p>
				<p><span><?php echo $order->billing_address;?></span></p>
				<p><span><?php echo $order->billing_phone;?></span></p>
				<p><span><?php echo $order->billing_email;?></span></p>
			</div>

		</div>
		<div class="col-md-6 order_cart__section">
			<h3>Giao nhận</h3>
			
			<div class="order-customer-info">
				<?php if( 
					$order->shipping_fullname == $order->billing_fullname && 
					$order->shipping_address  == $order->billing_address && 
					$order->shipping_phone    == $order->billing_phone && 
					$order->shipping_email    == $order->billing_email
				) { echo '<p>Giống địa chỉ giao hàng.</p>'; } else { ?>
				<p> <span><?php echo $order->shipping_fullname;?></span></p>
				<p> <span><?php echo $order->shipping_address;?></span></p>
				<p> <span><?php echo $order->shipping_phone;?></span></p>
				<p> <span><?php echo $order->shipping_email;?></span></p>
				<?php } ?>
			</div>

		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="box" id="order_items">

		<div class="box-content">

			<header class="order__title">
				<div class="order__title_wrap">
					<h2>Chi Tiết Đơn Hàng</h2>
				</div>
			</header>

			<div class="order_cart__section">
				
				<table class="woocommerce_order_items" style="width:100%">
			    <?php foreach ($order->items as $key => $val): ?>
			      	<tr class="item">
			        	<td><img src="<?= SOURCE.$val->image;?>" class="img-thumbnail img-responsive" style="max-width:100%;height:50px;"></td>
			        	<td>
			        		<?= $val->title;?>
			        		<?php $val->option = @unserialize($val->option) ;?>
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
			<div class="clearfix"></div>
		</div>

	</div>

	<div class="row">
		<div class="col-md-6 box" id="order_note">
			<div class="box-content">
				<header class="order__title">
					<div class="order__title_wrap">
						<h2>Ghi chú</h2>
					</div>
				</header>

				<div class="order_cart__section">
					<?php echo $order->order_note;?>
				</div>
			</div>
		</div>

		<div class="col-md-6 box" id="order_payment">
			<div class="box-content">
				<header class="order__title">
					<div class="order__title_wrap">
						<h2>Hình thức thanh toán</h2>
					</div>
				</header>

				<div class="order_cart__section">
					<?php

						$bacs 	= get_option('_setting_checkout_bacs');

						$cod 	= get_option('_setting_checkout_cod');
					?>
					<?php echo ( $order->_payment == 'cod' ) ? $cod['woocommerce_cod_title'] : $bacs['woocommerce_bacs_title'] ;?>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	header.order__title {
		display: block;
		padding: 20px 20px 20px 0;
	}
	header.order__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
	}

	.order_cart__section h3 {
		font-size: 13px;
		font-weight: 600;
	    line-height: 1.6rem;
	    text-transform: uppercase;
	    margin-top: 0;
	}
	.order-customer-info {
		border:1px solid #FBFBFB; background-color: #FBFBFB; height: 150px;
		padding:5px;
		color: #637381;
		word-break: break-all;
	    word-wrap: break-word;
	    white-space: normal;
	}

	.order-ui {
		box-sizing: border-box;
	    max-width: 1100px;
	    margin-right: auto;
	    margin-left: auto;
	    font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
	}
	.order-header { margin-bottom: 10px; }

	.order-header h1 {
		font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
		font-weight: 600;
	    margin-right: .8rem;
	    overflow: hidden;
	    overflow-wrap: break-word;
	    word-wrap: break-word;
	    white-space: normal;
		font-size: 2.8rem;
    	line-height: 3.4rem;
	}
	.order-header h1 span.order-time-created {
		font-size: 1.4rem;
		color: #798c9c;
		font-weight: 400;
		padding-left: 0;
	    -webkit-box-flex: 0;
	    -webkit-flex: 0 1 auto;
	    -ms-flex: 0 1 auto;
	    flex: 0 1 auto;
	    -webkit-align-self: flex-end;
	    -ms-flex-item-align: end;
	    align-self: flex-end;
	    line-height: 2.5rem;
	}

	.order-sections .box {
		border-radius: 3px;
		-webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    	box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
	}

	#order_data h3, #order_data h4 {
	    color: #333;
	    margin: 1.33em 0 0;
	}
	#order_data h3 { font-size: 14px; margin-bottom: 20px; font-weight: bold; }

	#order_data p {
	    color: #777;
	}
	#order_data .order_data_column .address strong {
	    display: block;
	}
	

	#order_items .wc-order-data-row {
	    /*border-bottom: 1px solid #dfdfdf;*/
	    padding: 1.5em 2em;
	    background: #f8f8f8;
	    line-height: 2em;
	    text-align: right;
	}
	#order_items .wc-order-data-row::after, #order_items .wc-order-data-row::before {
	    content: ' ';
	    display: table;
	}

	#order_items .wc-order-data-row  .wc-order-totals {
	    float: right;
	    width: 50%;
	    margin: 0;
	    padding: 0;
	    text-align: right;
	}
	
	#order_items .wc-order-data-row  .wc-order-totals .label{
	    color:#333;
	    font-size: 15px;
	}

	table.customer tr {
		border-bottom: 1px dotted #ccc;
	}
	table.customer tr td {
		padding:15px 5px;
		/*padding-left: 10px;*/
	}
	table.customer tr td:first-child {
		padding-left:10px;
		padding-right:5px;
		/*padding-left: 10px;*/
	}
	table.customer tr td i.icon {
		padding: 5px;
		margin-right:5px;
		display: inline-block;
		color:#fff;
		background-color: #000;
		text-align: center;
	}

	table.order_detail tr {
		border-bottom: 1px dotted #ccc;
	}
	table.order_detail td {
		padding:10px;
	}
	.loading, .success, .error, #order_cancel_alert {
		display: none;
	}
</style>