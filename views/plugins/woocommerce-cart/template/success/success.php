<?php do_action('woocommerce_before_success');?>
<div class="woocommerce-cart-content woocommerce-cart-success">

    <div class="col-md-8 col-md-offset-2">
        <div class="woocommerce-box" style="padding:15px;">
            <div class="wcmc-cart-success__heading text-center" style="overflow:hidden">

                <div class="thankyou-message-icon">
                    <div class="icon icon--order-success svg">
                        <?php get_img('https://win.acb.com.vn/static/media/success-icon.30c98631.gif');?>
                    </div>
                </div>

                <h2 class="header" style="text-align:center;font-size:30px;background-color:transparent;">ĐẶT HÀNG THÀNH CÔNG</h2>
                
                <div class="thankyou-message-text">

                    <h3>Cảm ơn bạn đã đặt hàng</h3>

                    <p>Một email xác nhận đã được gửi tới <?php echo $order->billing_email;?>. Xin vui lòng kiểm tra email của bạn</p>

                    <div style="font-style: italic;"></div>

                </div>

                <div class="clearfix"></div>
            </div>

            <div class="wcmc-cart-success__order" style="overflow:hidden">
                <h5>Mã đơn hàng của bạn: <strong>#<?php echo $order->code;?></strong></h5>
                <p><?php echo date('d/m/Y', strtotime($order->created));?> <?php echo date('h:s a', strtotime($order->created));?></p>
            </div>

            <div class="wcmc-cart-success__customer" style="overflow:hidden">
                <h3 class="header">Thông Tin Đặt Hàng</b></h3>
                <table class="table table-bordered table-user-info">
                    <tbody>
                        <tr>
                            <th>Họ Tên</th>
                            <td><?php echo $order->billing_fullname;?></td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td><?php echo $order->billing_email;?></td>
                        </tr>

                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?php echo $order->billing_phone;?></td>
                        </tr>

                        <tr>
                            <th>Địa chỉ</th>
                            <td><?php echo $order->billing_address;?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if( !empty($order->shipping_fullname) ) {?>
                <h3 class="header">Thông Tin Giao Hàng</b></h3>

                <table class="table table-bordered table-user-info">
                    <tbody>
                        <tr>
                            <th>Họ Tên</th>
                            <td><?php echo $order->shipping_fullname;?></td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td><?php echo $order->shipping_email;?></td>
                        </tr>

                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?php echo $order->shipping_phone;?></td>
                        </tr>

                        <tr>
                            <th>Địa chỉ</th>
                            <td><?php echo $order->shipping_address;?></td>
                        </tr>
                    </tbody>
                </table>
                <?php } ?>
            </div>

            <div class="woocommerce-cart wcmc-cart-success__product">
                <div class="wcmc-cart-box">
                    <div class="wcmc-cart-tbody">
                        <?php $total = 0; ?>
                        <?php foreach ($order->items as $key => $item): ?>
                            <div class="wcmc-cart__item">

                                <div class="cart_item__img">
                                    <?php get_img($item->image,'',array('style'=>'height:70px;'));?>
                                </div>

                                <div class="cart_item__info">
                                    <div class="pr-name">
                                        <h3><?= $item->title;?></h3>
                                        <?php $item->option = @unserialize($item->option) ;?>
                                        <?php if(isset($item->option) && have_posts($item->option)) {
                                            $attributes = '';
                                            foreach ($item->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
                                            $attributes = trim( trim($attributes), '/' );
                                            echo '<p class="variant-title" style="color:#999;">'.$attributes.'</p>';
                                        } ?>
                                    </div>
                                    <div class="pr-price" style="padding-top: 5px">
                                        <span class="js_cart_item_price"><?= number_format($item->price);?></span><?php echo _price_currency();?> x <span class="number qty"><?= $item->quantity;?></span>
                                    </div>
                                </div>
                            </div>
                            <?php $total += $item->subtotal; ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <br />
            <p><b>Ghi chú:</b> <?php echo $order->order_note;?></p>
            <br />

            <?php $totals = get_order_item_totals( $order ); ?>

                    
            <div class="wcmc-cart-success__total">
                <div class="col-md-6 th-cart text-right">
                    <div class="title-total"> Tổng Tạm Tính </div>
                </div>
                <div class="col-md-6 th-cart text-right">
                    <p class=""><?php echo number_format($total);?>đ</p>
                </div>

                <?php do_action('woocommerce_success_review_order', $order);?>

                <?php foreach ($totals as $total): ?>
                <div class="col-md-6 th-cart text-right">
                    <div class="title-total"> <?php echo $total['label'];?> </div>
                </div>
                <div class="col-md-6 th-cart text-right">
                    <p class="totalPrice"><?php echo $total['value'];?></p>
                </div>
                <?php endforeach ?>
                
            </div>
        </div>
    </div>
</div>
<?php do_action('woocommerce_after_success');?>

<style type="text/css">
    .warper {
		min-height:100vh;
		background-color:#F0F2F5!important;
	}
	h1, header, footer, .btn-breadcrumb { display:none; }
    .object-detail {background-color:transparent}
    .wcmc-cart-success__heading h2 { margin:20px 0;color:#77B43F }
    .wcmc-cart-success__heading .thankyou-message-icon img { width:40px;  }
    .wcmc-cart-success__heading .thankyou-message-text {  }
    .wcmc-cart-success__heading .thankyou-message-text h3 { margin-top:0; }
    .wcmc-cart-success__heading .thankyou-message-text p { font-size:12px; }
    .wcmc-cart-success__customer h3.header { text-align:left; margin:20px 0; font-size:15px; }
    .wcmc-cart-success__order h5 { text-align:left; margin:20px 0; }
    .wcmc-cart-success__order h5 strong { color:red; }
    .wcmc-cart-success__product .wcmc-cart-box .wcmc-cart-tbody .wcmc-cart__item .qty { color:#000; }
    .woocommerce-cart .wcmc-cart-box .wcmc-cart-tbody .wcmc-cart__item:first-child { padding:0;}

    .wcmc-cart-success__total .th-cart {
        padding-top:15px;
        padding-bottom:15px;
        font-size:15px;
    }
    .wcmc-cart-success__total .th-cart:last-child .totalPrice {
        font-weight:bold; font-size:20px; color:#F15A5F;
    }
</style>