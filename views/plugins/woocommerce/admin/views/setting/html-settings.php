<?php 
$tabs = woocommerce_settings_tabs();

reset($tabs);

$current_tab = ($ci->input->get('tab') != '')?$ci->input->get('tab'):key($tabs); ?>

<form id="mainform" method="post">
	<?php echo form_open();?>
	<div class="action-bar">
	    <div class="pull-right">
	        <button type="submit" class="btn-icon btn-green" id="item-data-save"><i class="fas fa-save"></i>Lưu</button>
	    </div>     
	</div>

	<div class="ui-layout">

		<div id="ajax_item_save_loader" class="ajax-load-qa">&nbsp;</div>

		<div class="col-md-12">
			<div class="ui-title-bar__group" style="padding-bottom:5px;">
				<h1 class="ui-title-bar__title">Cấu hình sản phẩm - <?php echo $tabs[$current_tab]['label'];?></h1>
				<div class="ui-title-bar__action">
					<?php foreach ($tabs as $key => $tab): ?>
					<a href="<?php echo URL_ADMIN;?>/plugins?page=woocommerce_settings&view=settings&tab=<?php echo $key;?>" class="<?php echo ($key == $current_tab)?'active':'';?> btn btn-default">
						<?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
						<?php echo $tab['label'];?>
					</a>
					<?php endforeach ?>
				</div>
			</div>
		</div>

		<?= $ci->template->get_message();?>

		<div class="col-md-12">
			<div role="tabpanel">
				<!-- Tab panes -->
				<div class="tab-content" style="padding-top: 10px;">
					<?php call_user_func( $tabs[$current_tab]['callback'], $ci, $current_tab ) ?>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<p style="color:#999">
				Version Template theme use <b><?php echo wcmc_get_template_version();?></b>
				Version Template in woocommerce <b><?php echo WCMC_TEMPLATE;?></b>
			</p>
		</div>

	</div>
</form>