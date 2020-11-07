<?php $tabs = woocommerce_settings_tabs();?>

<?php $current_tab = ($ci->input->get('tab') != '')?$ci->input->get('tab'):'general'; ?>
<form id="mainform" method="post">
	<?php echo form_open();?>
	<div class="action-bar">
	    <div class="pull-right">
	        <button type="submit" class="btn-icon btn-green" id="item-data-save"><i class="fas fa-save"></i>Lưu</button>
	    </div>     
	</div>
	<div class="col-md-12">
		<?= $ci->template->get_message();?>
		<div id="ajax_item_save_loader" class="ajax-load-qa">&nbsp;</div>

		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<?php foreach ($tabs as $key => $tab): ?>
				<li role="presentation" class="<?php echo ($key == $current_tab)?'active':'';?>">
					<a href="<?php echo URL_ADMIN;?>/plugins?page=woocommerce_settings&view=settings&tab=<?php echo $key;?>"><?php echo $tab['label'];?></a>
				</li>
				<?php endforeach ?>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content" style="padding-top: 10px;">
				<?php call_user_func( $tabs[$current_tab]['callback'], $ci, $current_tab ) ?>
			</div>
		</div>
	</div>
</form>