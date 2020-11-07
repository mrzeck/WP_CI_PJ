<?php $tabs = woocommerce_metabox_tabs();?>

<div role="tabpanel" class="wcmc_metabox" id="wcmc_metabox" data-object-id="<?php echo (isset($object->id))?$object->id:0;?>" data-session-id="0">
	<input type="hidden" name="wcmc_metabox_session_id" value="0">
	<input type="hidden" name="wcmc_metabox_product_id" value="<?php echo (isset($object->id))?$object->id:0;?>">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs col-md-3" role="tablist">
		<?php foreach ($tabs as $key => $tab): ?>
		<li role="presentation" class="<?php echo ($key == 'wcmc_metabox_tab_attributes')?'active':'';?>">
			<a href="#<?php echo $key;?>" aria-controls="<?php echo $key;?>" role="tab" data-toggle="tab"><?php echo $tab['label'];?></a>
		</li>
		<?php endforeach ?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content col-md-9">
		<?php foreach ($tabs as $key => $tab): ?>
		<div role="tabpanel" class="tab-pane <?php echo ($key == 'wcmc_metabox_tab_attributes')?'active':'';?>" id="<?php echo $key;?>">
			<?php call_user_func( $tab['callback'], $key, $tab ) ?>
		</div>
		<?php endforeach ?>
	</div>
</div>
