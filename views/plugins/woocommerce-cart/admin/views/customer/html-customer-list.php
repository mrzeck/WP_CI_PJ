<div class="col-md-12">
  	<div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Khách hàng</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_customer_action_bar_heading');?>
        </div>
    </div>
</div>

<div class="col-md-12">
	<div class="box">
		<?php $table_list->display();?>
	</div>
</div>