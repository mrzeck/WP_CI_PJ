<div class="col-md-4">
  	<div class="box">
      	<div class="header"> <h2>Thêm option</h2> </div>
      	<form method="post" id="form-input" data-module="wcmc_attribute_item">
		  	<div class="box-content">
      		<?php
				echo form_open();
				  
				$form_input = woocommerce_attributes_item_form($attribute);

				foreach ($form_input as $key => $input) echo _form($input);
			?>
			</div>
          	<div class="box-content">
				<div class="col-md-12"><button type="button" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('add');?> Lưu</button></div>
			</div>
        </form>
  	</div>
</div>


<div class="col-md-8">
	<div class="box">
		<div class="header">
	    	<h2>Danh Sách Thuộc Tính</h2>
	    </div>
	    <div class="box-content">
            <div class="col-md-12"><?php admin_notices();?></div>
			<?php $table_list->display();?>
	    </div>
	</div>
</div>
