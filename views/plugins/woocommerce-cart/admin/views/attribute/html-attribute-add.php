<?php if(current_user_can('wcmc_attributes_add')) {?>
<div class="col-md-4">
  	<div class="box">
      	<div class="header"> <h2>Thêm option</h2> </div>
      	<form method="post" id="form-input" data-module="wcmc_attribute">
			<div class="box-content">
				<?php 
					echo form_open();

					$form_input = woocommerce_attributes_form();
					
					foreach ($form_input as $key => $input) echo _form($input);
				?>
			</div>
			  
          	<div class="box-content">
				<div class="col-md-12"><button type="button" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('add');?>Lưu</button></div>
			</div>
        </form>
  	</div>
</div>
<?php } ?>

<div class="col-md-8">
	<div class="box">
		<div class="header">
	    	<h2>Danh Sách option</h2>
	    </div>
	    <div class="box-content">
            <?php $table_list->display();?>
	    </div>
	</div>
</div>
