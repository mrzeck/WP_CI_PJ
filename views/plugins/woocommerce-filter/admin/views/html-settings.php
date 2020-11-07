<?php $tabs = woocommerce_filter_setting_tabs();?>

<?php $current_tab = ($ci->input->get('tab') != '')?$ci->input->get('tab'):'general'; ?>

<form id="mainform" method="post">

	<?php echo form_open();?>	

	<div class="col-md-12 woocommerce_filter_setting">

		

		<div id="ajax_item_save_loader" class="ajax-load-qa">&nbsp;</div>

		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="col-md-3 nav nav-tabs" role="tablist">
				<?php foreach ($tabs as $key => $tab): ?>
				<li role="presentation" class="<?php echo ($key == $current_tab)?'active':'';?>">
					<a href="<?php echo URL_ADMIN;?>/plugins?page=woocommerce_filter_setting&tab=<?php echo $key;?>"><?php echo $tab['label'];?></a>
				</li>
				<?php endforeach ?>
			</ul>

			<!-- Tab panes -->
			<div class="col-md-9 tab-content" style="padding-top: 10px;">

				<?php echo $ci->template->get_message();?>

				<?php call_user_func( $tabs[$current_tab]['callback'], $ci, $current_tab ) ?>

			</div>
		</div>

	</div>
</form>


<style type="text/css">
	.woocommerce_filter_setting .nav-tabs { padding: 0; }
	.woocommerce_filter_setting .nav-tabs li { display: block;width: 100%; }
</style>