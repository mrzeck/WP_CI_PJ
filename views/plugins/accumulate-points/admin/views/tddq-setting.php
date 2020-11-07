<?php
    $option = tddq_config();
?>
<div class="col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="<?php echo admin_url('plugins?page=accumulate-points&tab=receiving');?>">Hình thức nhận điểm</a>
                </li>
                <li role="presentation">
                    <a href="<?php echo admin_url('plugins?page=accumulate-points&tab=pay');?>">Hình thức thanh toán</a>
                </li>
            </ul>
            
        </div>
    </div>

    <?php if($tab == 'receiving' || $tab == '') include 'tddq-setting-receiving.php'; ?>
    <?php if($tab == 'pay')               include 'tddq-setting-pay.php'; ?>
</div>