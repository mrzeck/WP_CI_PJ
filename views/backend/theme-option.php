<?php echo $this->template->render_include('action_bar');?>
<div class="col-md-12 system">
		<?= $this->template->get_message();?>
		<div id="ajax_item_save_loader" class="ajax-load-qa">&nbsp;</div>
	  	<!-- Nav tabs -->
		<div class="system-tab">
			<ul class="nav nav-tabs" role="tablist">
				<?php $i = key($this->theme_option['group']) ;?>
				<?php if(isset($_COOKIE["of_current_opt"])) $i = $_COOKIE["of_current_opt"];?>
				<?php foreach ($this->theme_option['group'] as $key => $value) { if(isset($value['root']) && $value['root'] == true && !is_super_admin()) continue; ?>
				<li class="<?= ($i == $key)?'active':'';?>"><a href="#<?= $key;?>" aria-controls="<?= $key;?>" role="tab" data-toggle="tab"><?= $value['icon'];?><span><?= $value['label'];?></span></a></li>
				<?php } ?>
			</ul>
		</div>

	  	<!-- Tab panes -->
		<div class="system-tab-content tab-content">
			<?php foreach ($this->theme_option['group'] as $key => $value) { ?>
			<div role="tabpanel" class="tab-pane <?= ($i == $key)?'active':'';?>" id="<?= $key;?>">
				<div class="header">
					<h2><?= $value['label'];?></h2>
				</div>
				<?php foreach ($this->theme_option['option'] as $k => $field) {
					if($field['group'] == $key) {
						echo _form($field, $system->{$field['field']});
						unset($this->theme_option['option'][$k]);
					}
				} ?>
			</div>
			<?php } ?>
	  	</div>
</div>