<?php $tabs = cmi_settings_tabs();

$current_tab = ($ci->input->get('tab') != '')?$ci->input->get('tab'):'general';

$option = cmi_fix_version(get_option('cmi_active'));

if( !have_posts($option) ) {
		$option = array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0);
}
?>

<form id="mainform" method="post" class="cmi-main" name="<?php echo $current_tab;?>">
	<?php echo form_open();?>

	<div class="action-bar">
	    <div class="pull-right">
	        <button type="submit" class="btn-icon btn-green" id="item-data-save"><i class="fa fa-floppy-o"></i>Lưu</button>
	    </div>     
	</div>

	<div class="col-md-12">
		<?php $ci->template->get_message();?>

		<?php admin_loading_icon('ajax_item_save_loader');?>

		<div class="ui-title-bar__group" style="padding-bottom:5px;">
			<h1 class="ui-title-bar__title">ICON CONTACT - <?php echo $tabs[$current_tab]['label'];?></h1>
			<div class="ui-title-bar__action">
				<?php foreach ($tabs as $key => $tab): ?>
					<?php if( isset($option[$key]) && $option[$key] == 0) continue; ?>
					<a href="<?php echo admin_url('/plugins?page=cmi&view=settings&tab='.$key);?>" class="<?php echo ($key == $current_tab)?'active':'';?> btn btn-default">
						<?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-grip-horizontal"></i>';?>
						<span><?php echo $tab['label'];?></span>
					</a>
				<?php endforeach ?>
			</div>
		</div>

		<div role="tabpanel" class="cmi-<?php echo $current_tab;?>">
			<!-- Tab panes -->
			<div class="tab-content" style="padding-top: 10px;">
				<?php call_user_func( $tabs[$current_tab]['callback'], $ci, $current_tab, $contact_mobile_icon ) ?>
			</div>
		</div>
	</div>
	<?php do_action('cmi_tab_review');?>
</form>