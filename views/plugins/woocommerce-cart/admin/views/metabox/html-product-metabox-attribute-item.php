<?php $option = get_attribute($id); ?>
<?php if( have_posts($option) ) {?>
<div class="panel panel-default">
	<div class="panel-heading" role="tab" id="heading<?php echo $option->id;?>">
	  	<h4 class="panel-title" style="overflow: hidden;">
	    	<a data-toggle="collapse" href="#collapse<?php echo $option->id;?>"><?php echo $option->title;?></a>
	    	<a class="attribute-del btn btn-red pull-right" data-id="<?php echo $option->id;?>" style="color:#fff;"><?php echo admin_button_icon('delete');?></a>
	  	</h4>
	</div>
	<div id="collapse<?php echo $option->id;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $option->id;?>">
	  	<div class="panel-body">
			<?php $attributes = gets_attribute_item(['attribute' => $id]);?>
			<select name="attribute_values[<?php echo $option->id;?>][]" multiple data-placeholder="Chọn tên chủng loại của thuộc tính" class="form-control attribute_values" tabindex="-1" aria-hidden="true">
				<?php foreach ($attributes as $key => $attribute): ?>
					<option value="<?php echo $attribute->id;?>" <?php echo (in_array( $attribute->id , $attributes_item ) !== false)?'selected':'';?>><?php echo $attribute->title;?></option>
				<?php endforeach ?>
			</select>

			<input class="attribute_names" type="hidden" name="attribute_names[<?php echo $option->id;?>]" value="<?php echo $option->id;?>">
	  	</div>
	</div>
</div>
<?php }  else { ?>
<div class="panel panel-default">
	<div class="panel-heading" role="tab" id="heading">
	  	<h4 class="panel-title" style="overflow: hidden;">
	    	<a style="color:red;"><del>(không tồn tại)</del></a>
	    	<a class="attribute-del btn btn-red pull-right" data-id="">Xóa</a>
	  	</h4>
	</div>
</div>
<?php }
