<div class="col-md-12">
	<div class="box">
	    <div class="box-content">
			<form method="post" id="form-input" data-module="wcmc_attribute_item">
				<div class="action-bar">
	                <div class="pull-right">
					    <button name="save" class="btn-icon btn-green"><i class="fa fa-floppy-o"></i>Lưu</button>
					    <a href="<?php echo URL_ADMIN.'/plugins?page=product_attributes&view=options&sub=items&id='.$wcmc_option->id;?>" class="btn-icon btn-blue hvr-sweep-to-right"><i class="fa fa-reply"></i>Quay lại</a>
					</div>
				</div>
				<div class="col-md-12"><?= admin_notices();?></div>
				<div class="clearfix"></div>
				<div id="ajax_loader" class="ajax-load-qa">&nbsp;</div>
				<?= $ci->template->render_include('ajax-page/form');?>
			</form>
		</div>
	</div>
</div>