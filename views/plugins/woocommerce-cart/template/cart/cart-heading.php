<div class="woocommerce-cart-heading row">
    <h2 class="cart-heading__title col-md-8" style="margin:0;">Giỏ Hàng Của Bạn</h2>
    <?php $cart = $ci->cart->contents();?>

	<?php if(have_posts($cart)) { ?>
    <div class="cart-heading__button col-md-4">
        <a class="btn btn-default btn-block" href="<?php echo get_url('san-pham');?>"><?php echo __('MUA THÊM', 'wcmc_tieptucmuahang');?></a>
        <a class="btn btn-red btn-block" href="<?php echo get_url('thanh-toan');?>"><?php echo __('THANH TOÁN', 'wcmc_thanhtoan');?></a>
    </div>
    <?php } ?>
</div>