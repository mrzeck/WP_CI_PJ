<div id="order-print">
    <table>
        <tr>
            <td class="logo" width="30%">
                <img src="<?php echo base_url().'uploads/source/'.get_option('logo_header');?>" style="width: 250px;">
            </td>
            <td class="info-company" width="70%" style="padding-left: 20px; text-align:right">
                <p style="text-align:right"><?php echo get_option('general_label');?></p>
                <p style="text-align:right"><?php echo get_option('contact_address');?></p>
                <p style="text-align:right"> <?php echo get_option('contact_phone');?></p>
            </td>
        </tr>
    </table>

    <h2> <strong>Đơn hàng <span>#<?php echo $order->code;?></span></strong></h2>
    <h4> <strong>Ngày đặt hàng</strong> <span><?php echo date('d-m-Y', strtotime($order->created));?></span></h4>

    <br />

    <table class="customer-info" style="width:100%">
        <tr>
            <td width="50%">
                <h4><b>Thông tin đặt hàng</b></h4>
                <?php $billing = order_detail_billing_info($order);?>
                <?php foreach ($billing as $billing_key => $billing_label) { ?>
                <p class="<?php echo $billing_key;?>"><span><?php echo $billing_label;?></span></p>
                <?php } ?>
            </td>
            <td width="50%">
                <h4><b>Thông tin nhận hàng</b></h4>
                <?php $shipping = order_detail_shipping_info($order);?>
                <?php foreach ($shipping as $shipping_key => $shipping_label) { ?>
                <p class="<?php echo $shipping_key;?>"><span><?php echo $shipping_label;?></span></p>
                <?php } ?>
            </td>
        </tr>
    </table>

    <br />

    <?php 
    $payments = wcmc_gets_payment();
    if( have_posts($payments) && !empty($order->_payment) ) { ?>
    <h3>Hình thức thanh toán</h3>

    <div class="order_cart__section">
        <?php
            foreach ($payments as $key => $payment) {
                if( $order->_payment == $key ) {
                    echo ( !empty($payment['woocommerce_'.$key.'_title']) ) ? $payment['woocommerce_'.$key.'_title'] : $key ;
                }
            }
        ?>
    </div>
    <?php } ?>

    <br />

    <table class="woocommerce_order_items" style="width:100%;border:1px solid #ccc">
        <tr class="item">
            <td style="padding:5px;border:1px solid #ccc">Hình ảnh</td>
            <td style="padding:5px;border:1px solid #ccc">Thông tin</td>
            <td style="padding:5px;border:1px solid #ccc">Đơn giá VND</td>
            <td style="padding:5px;border:1px solid #ccc">Số lượng</td>
            <td style="padding:5px;border:1px solid #ccc">Thành tiền</td>
        </tr>
    <?php foreach ($order->items as $key => $val): ?>
        <tr class="item">
            <td style="padding:5px;border:1px solid #ccc"><img src="<?= base_url().SOURCE.$val->image;?>" style="max-width:100%;height:50px;"></td>
            <td style="padding:5px;border:1px solid #ccc">
                <?= $val->title;?>
                <?php $val->option = @unserialize($val->option) ;?>
                <?php if(isset($val->option) && have_posts($val->option)) {
                    $attributes = '';
                    foreach ($val->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
                    $attributes = trim( trim($attributes), '/' );
                    echo '<p class="variant-title" style="color:#999;">'.$attributes.'</p>';
                } ?>
            </td>
            <td style="padding:5px;border:1px solid #ccc"><?= number_format($val->price);?> đ</td>
            <td style="padding:5px;border:1px solid #ccc"><?= $val->quantity;?></td>
            <td style="padding:5px;border:1px solid #ccc"><?= number_format($val->price*$val->quantity);?> đ</td>
        </tr>
    <?php endforeach ?>
    </table>
    <br />
    <table class="wc-order-totals" style="width:100%">
        <tbody>
            <?php $totals = get_order_item_totals( $order ); ?>

            <?php foreach ($totals as $total): ?>
            <tr style="padding-bottom:10px">
                <td style="padding-bottom:10px" class="label"><b><?php echo $total['label'];?></b></td>
                <td style="padding-bottom:10px; text-align:right" class="total"> <b><?php echo $total['value'];?></b></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>

<style>
    @media print {
        .customer-info { width:100%;}
        .customer-info p { font-size:10px; margin:0;}
        .woocommerce_order_items { border:1px solid #ccc }
        .woocommerce_order_items tr { border:1px solid #ccc }
    }
</style>