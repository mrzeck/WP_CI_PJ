<?php

$product_gallery      	= get_option('product_gallery', 'product_gallery_vertical' );
$product_related      	= get_option('product_related', array(
	'style' => 'slider',
	'columns' => 4,
	'posts_per_page' => 12,
) );


?>
<div class="box">

	<div class="header"> <h2>GALLERY</h2> </div>

	<div class="box-content">
		<div class="row">
			<div class="col-md-6">
					<label class="product-gallery" for="product_gallery_horizontal">
						<?php get_img(base_url().WCMC_PATH.'assets/images/product-gallery.svg');?>
						<input type="radio" name="product_gallery" id="product_gallery_horizontal" value="product_gallery_horizontal" <?php echo ($product_gallery == 'product_gallery_horizontal')?'checked':'';?>>
					</label>
					<label class="product-gallery" for="product_gallery_vertical">
						<?php get_img(base_url().WCMC_PATH.'assets/images/product-gallery-vertical.svg');?>
						<input type="radio" name="product_gallery" id="product_gallery_vertical" value="product_gallery_vertical" <?php echo ($product_gallery == 'product_gallery_vertical')?'checked':'';?>>
					</label>
					<style type="text/css">
						.product-gallery { padding:10px; }
					</style>
					
			</div>
		</div>
	</div>
</div>

<div class="box">

	<div class="header"> <h2>SẢN PHẨM LIÊN QUAN</h2> </div>

	<div class="box-content">
		<div class="row">
			<div class="col-md-6">
				<div class="col-md-12">
					<?php  $input = array('field' => 'product_related[style]', 'type'	=> 'select', 'label' => 'Kiểu sản phẩm liên quan', 'options' => array(
						'slider' => 'Dạng slider',
						'grid' => 'Dạng danh sách',
						'disabled' => 'Tắt',
					)); ?>
					<?php echo _form($input, $product_related['style']);?>
				</div>

				<div class="col-md-12">
					<?php  $input = array('field' => 'product_related[columns]', 'type'	=> 'range', 'label' => 'Số sản phẩm / hàng', 'args' => array('min' => 1, 'max' => 5)); ?>
					<?php echo _form($input, $product_related['columns']);?>
				</div>

				<div class="col-md-12">
					<?php  $input = array('field' => 'product_related[posts_per_page]', 'type'	=> 'range', 'label' => 'Số sản phẩm lớn nhất lấy ra', 'args' => array('min' => 1, 'max' => 30)); ?>
					<?php echo _form($input, $product_related['posts_per_page']);?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function() {
})
</script>