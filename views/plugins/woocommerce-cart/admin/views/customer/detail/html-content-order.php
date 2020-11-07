<div class="box">
	<div class="box-content">

        <section class="ui-layout__section">
            <header class="ui-layout__title">
                <h2>Đơn hàng gần đây</h2>
                <div class="pull-right">
                    <a href="<?php echo admin_url('plugins?page=woocommerce_order&view=shop_order&customer_id='.$customer->id);?>">Xem toàn bộ</a>
                </div>
            </header>
        </section>

        <section class="ui-layout__section">
            <div class="customer-order-list">
                <?php foreach ($orders as $key => $order) {?>
                <div class="order-item">
                    <div class="order-item__left text-left">
                        <p><a href="<?php echo admin_url('plugins?page=woocommerce_order&view=shop_order_detail&id='.$order->id);?>">Đơn hàng #<?php echo $order->code;?></a> - <?php echo number_format($order->total);?> ₫</p>
                        <p><span style="background-color:<?php echo order_status_color($order->status);?>; border-radius:10px; padding:0px 5px; display:inline-block;"><?php echo order_status_label($order->status);?></span></p>
                    </div>
                    <div class="order-item__right text-right">
                        <p><?php echo date('d/m/Y',strtotime($order->created));?></p>
                        <p><?php echo date('h:s',strtotime($order->created));?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

	</div>
</div>