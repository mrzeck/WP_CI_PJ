<?php
$layout 		= get_theme_layout();

if(isset($layout['banner'])) {
	if($layout['banner'] == 'in-content') $this->template->render_include('banner');
}
else {
	$breadcrumb = theme_get_breadcrumb();
	?>
	<?php echo Breadcrumb($breadcrumb);?>
	<h1 class="header text-left"><?= $object->title;?></h1>
	<style>
		h1.header { text-align:left;}
		.btn-breadcrumb a.btn.btn-default {
			color: #000;    line-height: 37px;
		}
	</style>
	<?php
}
?>
<div class="object-detail">
	<?php if(have_posts($object)) {?>
		<!-- content -->
		<div class="object-detail-content">
			<?php the_content();?>
		</div>
	<?php } ?>
</div>
