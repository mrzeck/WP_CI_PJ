<?php
    $payments = wcmc_gets_payment();
?>
<div class="box" id="order_save_payments">
    <div class="box-content">
        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Hình thức thanh toán</h2></header>
        </section>
        
        <div class="clearfix"> </div>
        <!-- tìm kiếm sản phẩm -->
        <section class="ui-layout__section">
        <?php if( have_posts($payments) ) {

            foreach ($payments as $key => $payment) {

                if( $payment['woocommerce_'.$key.'_enabled'] == 1 ) {
                    echo '<div class="radio"><label>';
                    echo '<input type="radio" name="_payment" id="_payment_'.$key.'" value="'.$key.'" '.(($key == 'cod')?'checked':'').'>';
                    echo $payment['woocommerce_'.$key.'_title'];
                    echo '</label></div>';

                    do_action('woocommerce_checkout_payment_'.$key.'_view', $payment );
                }
            }
        } ?>
        </section>
        <div class="clearfix"> </div>
        
    </div>
</div>