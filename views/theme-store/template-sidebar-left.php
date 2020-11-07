<?php /**
Layout-name: Template slider left
*/?>
<?php $layout = get_theme_layout(); ?>
<!DOCTYPE html>
<html lang="<?= $this->language['current'];?>" <?php do_action('in_tag_html');?>>
	<?php $this->template->render_include('head'); ?>
	<body <?php do_action('in_tag_body');?>>
		<?php $this->template->render_include('mobile-search'); ?>
		<div id="td-outer-wrap">
			<?php $this->template->render_include('top'); ?>
			<div class="warper warper-<?php echo $this->template->class;?>">
				<?php if(isset($layout['banner']) && $layout['banner'] == 'full-width') {
					$this->template->render_include('banner');
				} ?>
				<div class="container">
					<?php if(isset($layout['banner']) && $layout['banner'] == 'in-container') {
						$this->template->render_include('banner');
					} ?>
					<div class="row">
						<div class="col-md-push-3 col-md-9">
							<?php $this->template->render_view(); ?>
						</div>
						<div class="col-md-pull-9 col-md-3 sidebar">
							<?php dynamic_sidebar('sidebar-main');?> 
						</div>
					</div>
				</div>
			</div>
			<?php $this->template->render_include('footer'); ?>
		</div>
	</body>
</html>