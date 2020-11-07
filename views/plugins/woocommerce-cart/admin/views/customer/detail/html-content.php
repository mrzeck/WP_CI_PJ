<div class="box" id="customer_content">
	<div class="box-content">
        <div class="customer-profile">
            <div class="customer-profile__avatar">
                <?php get_img('https://yt3.ggpht.com/-tcGz0UiyfkE/AAAAAAAAAAI/AAAAAAAAAAA/XkN5ucCEyBg/w800-h800/photo.jpg');?>
            </div>
            <div class="customer-profile__name">
                <h3><?php echo $customer->firstname.' '.$customer->lastname;?></h3>
                <p><?php echo get_user_meta($customer->id, 'address', true);?></p>
            </div>
        </div>

        <section class="ui-layout__section">
            <div class="customer-order-statistical">

                <div class="type--centered">
                    <p class="type--subdued"> Đơn hàng mới nhất </p>
                    <?php $order_id = (int)get_user_meta($customer->id, 'order_recent', true) - 1000;?>
                    <a class="" href="<?php echo admin_url('plugins?page=woocommerce_order&view=shop_order_detail&id='.$order_id);?>"> #<?php echo get_user_meta($customer->id, 'order_recent', true);?>  </a>
                </div>
                
                <div class="type--centered">
                    <p class="type--subdued"> Tổng chi tiêu </p>
                    <h3 class=""> <?php echo number_format($customer->order_total);?> ₫ </h3>
                </div>

                <div class="type--centered">
                    <p class="type--subdued"> Tổng đơn hàng </p>
                    <h3 class=""> <?php echo $customer->order_count;?> đơn hàng </h3>
                </div>

            </div>
        </section>
	</div>
</div>