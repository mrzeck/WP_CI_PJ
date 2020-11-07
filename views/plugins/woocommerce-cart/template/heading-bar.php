<?php
    $ci = &get_instance();

    $slug = $ci->data['object']->slug;
?>

<div class="woocommerce-heading-bar">
    <div class="container">
        <div class="woocommerce-heading-bar__content">
            <div class="logo-box" style="display: flex;">
                <a class="active" href="<?php echo base_url();?>">
                    <?php get_img(get_option('logo_header'), get_option('general_label'));?>
                </a>
            </div>

            <div class="woocommerce-heading-bar__rule">
                <div class="step <?php echo ($slug == 'gio-hang')?'active':'';?>">
                    <div class="step-number">1</div>
                    <div class="step-label">Giỏ hàng</div>
                </div>

                <div class="step <?php echo ($slug == 'thanh-toan')?'active':'';?>">
                    <div class="step-number">2</div>
                    <div class="step-label">Thanh toán</div>
                </div>
                
                <div class="step <?php echo ($slug == 'don-hang')?'active':'';?>">
                    <div class="step-number">3</div>
                    <div class="step-label">Hoàn tất</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .woocommerce-heading-bar {
        display:none;
        position: absolute; top:0; left:0; width:100%; box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px 0px, rgba(0, 0, 0, 0.14) 0px 0px 1px 0px, rgba(0, 0, 0, 0.12) 0px 2px 1px -1px; background: rgb(255, 255, 255); z-index: 60;
    }
    .woocommerce-heading-bar .woocommerce-heading-bar__content {
        display: flex; justify-content: space-between; align-items: center;
        height: 70px; min-height: 70px; max-height: 70px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .woocommerce-heading-bar .woocommerce-heading-bar__content .logo-box img {
        max-height: 70px;
    }
    .woocommerce-heading-bar__rule {
        display: flex; justify-content: space-between; height: 100%;
    }
    .woocommerce-heading-bar__rule .step {
        display: flex; justify-content: space-between; position: relative; align-items: center; height: 100%; padding-left: 10px; padding-right: 10px; margin-left: 8px; margin-right: 8px;
    }
    .woocommerce-heading-bar__rule .step.active {
        background: rgb(240, 242, 245);
    }
    .woocommerce-heading-bar__rule .step.active::after {
        content:'';
        position: absolute; width: 12px; height: 100%; background: rgb(240, 242, 245); transform: skewX(-12deg); top: 0px; left: -5px;
    }
    .woocommerce-heading-bar__rule .step.active::before {
        content:'';
        position: absolute; width: 12px; height: 100%; background: rgb(240, 242, 245); transform: skewX(-12deg); top: 0px; right: -5px;
    }
    .woocommerce-heading-bar__rule .step .step-number {
        font-family: avenir-next-regular, arial; color: rgb(77, 78, 79);
    }
    .woocommerce-heading-bar__rule .step .step-label {
        display: block; color: rgb(77, 78, 79); margin-left: 8px; text-transform: uppercase;
    }
    .woocommerce-heading-bar__rule .step.active {
        font-weight:bold;
    }
    @media(min-width:768px) {
        .warper { margin-top:70px; }
        .woocommerce-heading-bar { display:block;}
    }
</style>