<div class="form-inline" id="add-group-attributes">
	<div class="form-group">
		<select id="wcmc_option_id" class="form-control" required="required" style="width: 250px;"> <?php
			if ( $options = gets_attribute() ) :
				foreach ( $options as $val ) : ?>
					<option value="<?php echo $val->id;?>"><?php echo $val->title;?></option>
					<?php
				endforeach;
			else : ?>
			<?php
			endif; ?>
		</select>
	</div>
	<button type="button" class="btn btn-default save-attributes">Thêm</button>
	<button type="button" class="btn btn-default save-attributes-all">Thêm Tất Cả</button>
</div>

<div class="panel-group" id="result-attributes-items" role="tablist" aria-multiselectable="true" style="margin-bottom: 10px;">
	
</div>
<div style="padding:10px;text-align:right">
	<!-- <button type="button" class="btn-icon btn-blue save-group-attributes" style="margin:0px;"><?php echo admin_button_icon('save');?> Lưu</button> -->
</div>
