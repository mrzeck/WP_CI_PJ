<?php 
$product_pr_page           = get_option('product_pr_page');
$category_row_count        = get_option('category_row_count');
$category_row_count_tablet = get_option('category_row_count_tablet');
$category_row_count_mobile = get_option('category_row_count_mobile');
?>

<div class="box">

	<div class="header"> <h2>TRANG DANH MỤC SẢN PHẨM</h2> </div>

	<div class="box-content">
		<div class="row">

			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12">
					<?php  $input = array('field' => 'product_pr_page', 'type'	=> 'range', 'label' => 'Số sản phẩm trên 1 trang', 'args' => array('min' => 1, 'max' => 50)); ?>
					<?php echo _form($input, $product_pr_page);?>

					<?php  $input = array('field' => 'category_row_count', 'type'	=> 'range', 'label' => 'Số sản phẩm một hàng - DESKTOP', 'args' => array('min'=>1, 'max' => 6)); ?>
					<?php echo _form($input, $category_row_count);?>

					<?php  $input = array('field' => 'category_row_count_tablet', 'type'	=> 'range', 'label' => 'số sản phẩm một hàng - TABLET', 'args' => array('min'=>1, 'max' => 4)); ?>
					<?php echo _form($input, $category_row_count_tablet);?>

					<?php  $input = array('field' => 'category_row_count_mobile', 'type'	=> 'range', 'label' => 'số sản phẩm một hàng - MOBILE', 'args' => array('min'=>1, 'max' => 3)); ?>
					<?php echo _form($input, $category_row_count_mobile);?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
