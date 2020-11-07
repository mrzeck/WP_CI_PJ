<?php
$object_type = removeHtmlTags($ci->input->get('type'));

if(empty($object_type)) $object_type = 'product';

$tag = removeHtmlTags($ci->uri->segment('2'));

if($tag == 'tag') {

	$tag = removeHtmlTags($ci->uri->segment('3'));
}

if($object_type == 'product') {

	$ci->data['objects'] = gets_product( array(
		'meta_query' => array(
			array( 'key' => 'tag-slug'),
			array( 'compare' => 'LIKE', 'value' => $tag ),
		)
	));
	?>
	<div class="product-slider-horizontal" style="margin-top: 10px;">
		<?php 
		/**
		 * woocommerce_products_index_view hook.
		 *
		 * @hooked woocommerce_products_index_list_product - 10 - hiển thị danh sách sản phẩm
		 * @hooked woocommerce_products_index_pagination - 20 - hiển thị phân trang
		 */
		do_action('woocommerce_products_index_view');
		?>
	</div>
<?php } else {
	$ci->data['objects'] = gets_post( array(
		'where' => array('post_type' => 'post'),
		'meta_query' => array(
			array( 'key' => 'tag-slug'),
			array( 'compare' => 'LIKE', 'value' => $tag ),
		)
	));

	$objects = $ci->data['objects'];

	if(have_posts($objects)) {

		$layout 		= get_theme_layout();

		$layout_setting = get_theme_layout_setting('post_category');

		if(isset($layout['banner'])) {
			if($layout['banner'] == 'in-content') $this->template->render_include('banner');
		}
		else {
			$breadcrumb = theme_get_breadcrumb();
			echo Breadcrumb($breadcrumb);?>
			<h1 class="header text-left">Tags</h1>
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
<?php } ?>