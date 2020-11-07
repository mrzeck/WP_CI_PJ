<?php /**
Layout-name: Template Full Width
*/?>
<?php $layout = get_theme_layout();?>
<!DOCTYPE html>
<html lang="<?= $this->language['current'];?>" <?php do_action('in_tag_html');?>>
	<?php $this->template->render_include('head'); ?>
	<body class="" <?php do_action('in_tag_body');?> style="height: auto">
		<?php $this->template->render_include('mobile-search'); ?>
		<div id="td-outer-wrap">
			<?php $this->template->render_include('top'); ?>
			<div class="warper">
				<?php if(isset($layout['banner']) && $layout['banner'] == 'full-width') {
					$this->template->render_include('banner');
				} ?>
				<?php if(is_page('products_detail')) {?>
					<?php echo '<div class="products-breadcrumb"><div class="container">'.Breadcrumb(theme_get_breadcrumb()).'</div></div>';?>
				<?php } ?>
				<div class="container">
				<?php if(isset($layout['banner']) && $layout['banner'] == 'in-container') {
					$this->template->render_include('banner');
				} ?>
				<?php $this->template->render_view(); ?>
				</div>
			</div>
			<?php $this->template->render_include('footer'); ?>
		</div>
	</body>
</html>