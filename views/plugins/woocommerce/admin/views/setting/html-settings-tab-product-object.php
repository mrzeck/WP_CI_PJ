<?php
$product_hiden_img      	= get_option('product_hiden_img');
$product_hiden_title      	= get_option('product_hiden_title');
$product_hiden_price      	= get_option('product_hiden_price');
$product_hiden_description  = get_option('product_hiden_description');

$product_shadow      		= get_option('product_shadow');
$product_shadow_hover      	= get_option('product_shadow_hover');

$product_border_size      	= get_option('product_border_size');
$product_border_style      	= get_option('product_border_style');
$product_border_color      	= get_option('product_border_color');


$product_hover_effect      = get_option('product_hover_effect');
$product_title_color       = get_option('product_title_color');
$product_price_color       = get_option('product_price_color');

?>

<div class="box">
	<div class="header"> <h2>Box Sản Phẩm</h2> </div>
	<div class="box-content">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3">
					<label for="">Ảnh sản phẩm</label>
					<p style="color:#999;margin:5px 0 5px 0;">Cấu hình hình ảnh sản phẩm</p>
				</div>
				<div class="col-md-9">
					<?php  $input = array('field' => 'product_hiden_img', 'type' => 'switch', 'label' => 'Ẩn / Hiện'); ?>
					<?php echo _form($input, $product_hiden_img);?>

					<?php 
						$effect = array(
							'effect3 bottom_to_top'       => 'effect3 bottom_to_top',
							'effect3 top_to_bottom'       => 'effect3 top_to_bottom',
							'effect4'                     => 'effect4',
							'effect5 left_to_right'       => 'effect5 left_to_right',
							'effect5 right_to_left'       => 'effect5 right_to_left',
							'effect6 from_top_and_bottom' => 'effect6 from_top_and_bottom',
							'effect6 from_left_and_right' => 'effect6 from_left_and_right',
							'effect6 top_to_bottom'       => 'effect6 top_to_bottom',
							'effect6 bottom_to_top'       => 'effect6 bottom_to_top',
							'effect7'                     => 'effect7',
							'effect8 scale_up'            => 'effect8 scale_up',
							'effect8 scale_down'          => 'effect8 scale_down',
						);
						$input = array('field' => 'product_hover_effect', 'type'	=> 'select', 'label' => 'Hiệu ứng hover', 'options' => $effect);
						echo _form($input, $product_hover_effect);
					?>
				</div>
			</div>
			<hr/>

			<div class="row">
				<div class="col-md-3">
					<label for="">Tên sản phẩm</label>
					<p style="color:#999;margin:5px 0 5px 0;">Cấu hình tên hiển thị sản phẩm</p>
				</div>
				<div class="col-md-9">
					<?php  $input = array('field' => 'product_hiden_title', 'type'	=> 'switch', 'label' => 'Ẩn / Hiện'); echo _form($input, $product_hiden_title);?>
					<?php  $input = array('field' => 'product_title_color', 'type'	=> 'color', 'label' => 'Màu Tên',); ?>
					<?php echo _form($input, $product_title_color);?>
				</div>
			</div>
			<hr/>

			<div class="row">
				<div class="col-md-3">
					<label for="">Giá sản phẩm</label>
					<p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị giá sản phẩm</p>
				</div>
				<div class="col-md-9">
					<?php  $input = array('field' => 'product_hiden_price', 'type'	=> 'switch', 'label' => 'Ẩn / Hiện'); ?>
					<?php echo _form($input, $product_hiden_price);?>

					<?php  $input = array('field' => 'product_price_color', 'type'	=> 'color', 'label' => 'Màu Giá',); ?>
					<?php echo _form($input, $product_price_color);?>
				</div>
			</div>
			<hr/>

			<div class="row">
				<div class="col-md-3">
					<label for="">Mô tả sản phẩm</label>
					<p style="color:#999;margin:5px 0 5px 0;">Cấu hình tên hiển thị sản phẩm</p>
				</div>
				<div class="col-md-9">
					<?php  $input = array('field' => 'product_hiden_description', 'type'	=> 'switch', 'label' => 'Ẩn / Hiện'); ?>
					<?php echo _form($input, $product_hiden_description);?>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="box">
	<div class="header"> <h2>Khung Sản Phẩm</h2> </div>
	<div class="box-content">
		<div class="row">

			<div class="col-md-12">
				<div class="row">
					<div class="col-md-4">
						<?php  $input = array('field' => 'product_shadow', 'type'	=> 'range', 'label' => 'Đổ Bóng', 'args' => array('min' => 0, 'max' => 5)); ?>
						<?php echo _form($input, $product_shadow);?>
					</div>
					<div class="col-md-4">
						<?php  $input = array('field' => 'product_shadow_hover', 'type'	=> 'range', 'label' => 'Đổ Bóng Hover', 'args' => array('min' => 0, 'max' => 5)); ?>
						<?php echo _form($input, $product_shadow_hover);?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<?php  $input = array('field' => 'product_border_size', 'type'	=> 'range', 'label' => 'Border Size'); ?>
						<?php echo _form($input, $product_border_size);?>
					</div>
					<div class="col-md-4">
						<?php  $input = array('field' => 'product_border_style', 'type'	=> 'select', 'label' => 'Border Style', 'options' => array(
							'none'    =>'none',
							'hidden'  =>'hidden',
							'dotted'  =>'dotted',
							'dashed'  =>'dashed',
							'solid'   =>'solid',
							'double'  =>'double',
							'groove'  =>'groove',
							'ridge'   =>'ridge',
							'inset'   =>'inset',
							'outset'  =>'outset',
							'initial' =>'initial',
							'inherit' =>'inherit'
						)); ?>
						<?php echo _form($input, $product_border_style);?>
					</div>

					<div class="col-md-4">
						<?php  $input = array('field' => 'product_border_color', 'type'	=> 'color', 'label' => 'Border Color'); ?>
						<?php echo _form($input, $product_border_color);?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<style type="text/css">
	.product { margin-bottom: 10px; }
	.product .img img { width: 100%; }
	.product .title { text-transform: uppercase; font-size: 10px; margin-bottom: 5px; }
	.product.ih-item.square { width: 100%; height: auto; }
</style>

<script type="text/javascript">
$(function() {

	var item = $('.woocommecre-review-object');

	var form = $('#mainform');

	var productObjectHandler = function() {
		this.onLoad();
	}

	productObjectHandler.prototype.onLoad = function (e) {

		var data = $( ':input', form).serializeJSON();
	}

	new productObjectHandler();

})
</script>