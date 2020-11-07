<?php
foreach ($attribute as $key => $value):

	$options[$key] = get_attribute($key);

	foreach ($value['value'] as $k => $val) {
		
		$options[$key]->{'attribute'}[$val] = get_attribute_item($val);
	}
	
endforeach;

$meta = get_metadata( 'product', $variations_id );

$review_image        = (!empty($variation->image))? $variation->image : base_url().WCMC_CART_PATH.'assets/images/Placeholder.jpg';

$upload_image        = (isset($variation->image) && $variation->image != base_url().WCMC_CART_PATH.'assets/images/Placeholder.jpg') ? $variation->image : '';

$variable_price      = (isset($variation->price))?$variation->price:0;

$variable_price_sale = (isset($variation->price_sale))?$variation->price_sale:0;

?>
<div class="panel panel-default" data-variations-id="<?php echo $variations_id;?>">
	<div class="panel-heading" role="tab" id="variations_heading<?php echo $variations_id;?>">
		<input type="hidden" name="wcmc_variations_id[]" value="<?php echo $variations_id;?>">


		<?php foreach ($options as $key => $option): ?>
			<?php if( have_posts($option) ) : ?>
				<?php $attribute_op = (isset($meta->{'attribute_op_'.$option->id}))?$meta->{'attribute_op_'.$option->id}:0; ?>
				<select name="attribute_op_<?php echo $option->id;?>[<?php echo $variations_id;?>]" class="form-control attribute_value" data-option-id="<?php echo $option->id;?>">
					<option value="0">Chọn <?php echo $option->title;?></option>
					<?php foreach ($option->attribute as $attribute): ?>
						<option value="<?php echo $attribute->id;?>" <?php echo ($attribute_op == $attribute->id)?'selected=selected':'';?>><?php echo $attribute->title;?></option>
					<?php endforeach ?>
				</select>
			<?php else: ?>
				<select class="form-control attribute_value">
					<option value="0">Không biết</option>
				</select>
			<?php endif ?>
		<?php endforeach;?>
		<div class="pull-right" style="display: inline-block;">
			<a class="btn btn-white text-right" data-toggle="collapse" data-parent="#result-attributes-items" href="#variations_collapse<?php echo $variations_id;?>">Chi Tiết</a>
	    	<a class="btn btn-red variations-del text-right" href="" data-id="<?php echo $variations_id;?>">Xóa</a>
		</div>
	</div>
	<div id="variations_collapse<?php echo $variations_id;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="variations_heading<?php echo $variations_id;?>">
	  	<div class="panel-body">
			<div class="form-group">
            	<button class="iframe-btn" data-fancybox data-type="iframe" data-id="<?php echo $variations_id;?>" data-src="<?= base_url();?>scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=field_image_<?php echo $variations_id;?>&amp;callback=wcm_responsive_filemanager_callback" href="<?= base_url();?>scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=field_image_<?php echo $variations_id;?>&amp;callback=wcm_responsive_filemanager_callback" type="button" style="padding:0;background-color: transparent;border: 0;">
              		<img src="<?= get_img_link($review_image);?>" class="field-btn-img" style="width:50px;">
           		</button>
            	<input name="upload_image[<?php echo $variations_id;?>]" data-name="variable_image" value="<?php echo $upload_image;?>" type="hidden" id="field_image_<?php echo $variations_id;?>" class=" form-control">
          	</div>

			<div class="row">
	
			</div>
			<div class="row">
				<div class="col-md-4 form-group">
	          		<label>Mã sản phẩm biến thể</label>
					<input name="variable_code[<?php echo $variations_id;?>]" data-name="variable_code" type="text" class=" form-control" value="<?php echo (!empty($variations_code)) ? $variations_code : '';?>">
					<p style="color:#999;margin:5px 0 5px 0;">Nhập mã sản phẩm biến thể (SKU) nếu có.</p>
				</div>
				<div class="col-md-4 form-group">
	          		<label>Giá bán</label>
					<input name="variable_price[<?php echo $variations_id;?>]" data-name="variable_price" type="text" class=" form-control" value="<?php echo $variable_price;?>" required="required">
				</div>
				<div class="col-md-4 form-group">
	          		<label>Giá khuyến mãi</label>
					<input name="variable_price_sale[<?php echo $variations_id;?>]" data-name="variable_price_sale" type="text" class=" form-control" value="<?php echo $variable_price_sale;?>" required="required">
				</div>
			</div>

			<div class="row">
				<?php $field = array(); ?>
				<?php do_action( 'woocommerce_product_after_variable_attributes', $variations_id, $meta );?>
			</div>
          	

	  	</div>
	</div>
</div>