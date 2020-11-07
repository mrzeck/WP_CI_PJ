<?php 
if(have_posts($objects)) {

	$layout 		= get_theme_layout();

	$layout_setting = get_theme_layout_setting();

	if(isset($layout['banner'])) {
		if($layout['banner'] == 'in-content') $this->template->render_include('banner');
	}
	else {
		$breadcrumb = theme_get_breadcrumb();
		echo Breadcrumb($breadcrumb);?>
		<h1 class="header text-left"><?= $category->name;?></h1>
		<style>
			h1.header { text-align:left;}
			.btn-breadcrumb a.btn.btn-default {
				color: #000; line-height: 37px;
			}
		</style>
		<?php
	}

	$col = '';

	if( $layout_setting['style'] == 'horizontal') {

		$col = array();

		$col['lg'] = ( $layout_setting['horizontal']['category_row_count'] != 5) ? 12/$layout_setting['horizontal']['category_row_count'] : 15;

		$col['md'] = ( $layout_setting['horizontal']['category_row_count'] != 5) ? 12/$layout_setting['horizontal']['category_row_count'] : 15;

		$col['sm'] = ( $layout_setting['horizontal']['category_row_count_tablet'] != 5) ? 12/$layout_setting['horizontal']['category_row_count_tablet'] : 15;

		$col['xs'] = ( $layout_setting['horizontal']['category_row_count_mobile'] != 5) ? 12/$layout_setting['horizontal']['category_row_count_mobile'] : 15;

		$col = 'col-xs-'.$col['xs'].' col-sm-'.$col['sm'].' col-md-'.$col['md'].' col-lg-'.$col['lg'].'';
	}
?>
	<div class="post">
		<?php echo '<div class="row">';
		foreach ($objects as $key => $val):
			if($layout_setting['style'] == 'vertical')
				$this->template->render_include('loop/item_post',array('val' => $val));
			else {
				echo '<div class="col-md-'.$col.'">';
				$this->template->render_include('loop/item_post_horizontal',array('val' => $val));
				echo '</div>';
			}
		endforeach; echo '</div>' ?>
		<nav class="text-center">
			<?= (isset($pagination))?$pagination->html():'';?>
		</nav>
	</div>
<?php } ?>
<style>
	.post .item{padding-top:0;padding-bottom:15px;margin-bottom:15px;}
</style>