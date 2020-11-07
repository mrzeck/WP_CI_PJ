<div class="action-bar">
	<div class="pull-left">
        <form action="<?php echo admin_url('plugins');?>" method="get" class="form-inline" role="form">

        	<input type="hidden" name="page" value="woocommerce_order">

        	<input type="hidden" name="view" value="shop_order">
        
            <?php do_action('order_index_search');?>
        
        	<button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
</div>

<div class="col-md-12">
  	<div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Đơn hàng</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_order_action_bar_heading');?>
        </div>
    </div>
</div>

<div class="col-md-12">
	<div class="box">
		<div class="table-responsive">
        <?php $table_list->display();?>
        </div>
        <!-- paging -->
        <div class="paging">
            <div class="pull-right"><?= (isset($pagination)&&is_object($pagination))?$pagination->html():'';?></div>
        </div>
        <!-- paging -->
	</div>
</div>

<style>
    .page-content .action-bar { height:auto;}
    .action-bar .form-group { margin-right:10px; }
    .action-bar .pull-left { width:100%; }
</style>