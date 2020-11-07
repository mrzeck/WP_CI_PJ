<form method="post" id="form-input" data-module="<?php echo $this->template->class;?>">

	<?php $this->template->render_include('action_bar');?>

	<?php admin_loading_icon('ajax_loader');?>

	<?php if(isset($object->title)) {?>
	<div class="col-md-12">
		<div class="ui-title-bar__group">
			<h1 class="ui-title-bar__title"><?php echo $object->title;?></h1>
			<div class="ui-title-bar__action">
				<?php do_action('admin_post_save_'.$object->post_type.'_action_bar_heading');?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php $this->template->render_include('ajax-page/form');?>
</form>