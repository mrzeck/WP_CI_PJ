<div class="col-md-12">
	<div class="box">
	    <div class="box-content">
			<form method="post" data-module="wcmc_attribute">
				<?php echo form_open();?>
				<div class="action-bar">
	                <div class="pull-right">
					    <button name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?> Lưu</button>
					    <a href="<?php echo URL_ADMIN.'/plugins?page=woocommerce_attributes';?>" class="btn-icon btn-blue hvr-sweep-to-right"><i class="fa fa-reply"></i>Quay lại</a>
					</div>
				</div>
				<div class="col-md-12"><?php admin_notices();?></div><div class="clearfix"></div>
				<?php admin_loading_icon('ajax_loader');?>
				<?php 
					$form_input = woocommerce_attributes_form();
					foreach ($form_input as $key => $input) {
						echo _form($input, ((!empty($object->{$input['field']})) ? $object->{$input['field']} :''));
					}
				?>
			</form>
		</div>
	</div>
</div>