<?php
    $config = excel_config();
?>
<div class="col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?php echo ($tab == 'order' || $tab == '')?'active':'';?>">
                    <a href="<?php echo admin_url('plugins?page=excel-editor&tab=order');?>">Đơn hàng</a>
                </li>
                <!-- <li role="presentation" class="<?php echo ($tab == 'customer')?'active':'';?>">
                    <a href="<?php echo admin_url('plugins?page=excel-editor&tab=customer');?>">Khách hàng</a>
                </li>
                <li role="presentation" class="<?php echo ($tab == 'product')?'active':'';?>">
                    <a href="<?php echo admin_url('plugins?page=excel-editor&tab=product');?>">Sản phẩm</a>
                </li> -->
            </ul>
            
        </div>
    </div>

    <?php if($tab == 'order' || $tab == '') include 'excel-setting-order.php'; ?>
    <?php if($tab == 'customer')            include 'excel-setting-customer.php'; ?>
    <?php if($tab == 'product')             include 'excel-setting-product.php'; ?>
</div>