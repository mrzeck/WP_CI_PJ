<!DOCTYPE html>
<html lang="<?= $this->language['current'];?>" <?php do_action('in_tag_html');?>>
	<?php $this->template->render_include('head'); ?>
	<body <?php do_action('in_tag_body');?> style="height: auto">
		<?php $this->template->render_include('mobile-search'); ?>
		<div id="td-outer-wrap">
			<?php $this->template->render_include('top'); ?>
			<div class="warper">
				<?php $this->template->render_include('banner'); ?>
				<?php $this->template->render_view(); ?>
			</div>
			<?php $this->template->render_include('footer'); ?>
		</div>
	</body>
</html>