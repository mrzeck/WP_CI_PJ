<?= $this->template->render_include('action_bar');?>

<div class="col-md-5">
  	<div class="box">
      	<div class="header">
        	<h2><?php echo $this->category['labels']['add_new_item'];?></h2>
      	</div>
      	<form method="post" id="form-input-category" data-module="<?= $this->template->class;?>" class="table-responsive">
      		<?php admin_loading_icon('ajax_loader');?>
      		<?= $this->template->render_include('ajax-page/form');?>
          	<div class="box-content" style="padding:10px;">
				<button type="submit" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
			</div>
        </form>
  	</div>
</div>


<div class="col-md-7">
	<div class="box">
		<div class="header">
	      <h2><?php echo $this->category['labels']['name'];?></h2>
	    </div>
	    <div class="box-content">
	    	<!-- search box -->
            <?php $table_list->display_search();?>
            <!-- /search box -->

            <form method="post" id="form-action">
                <?php $table_list->display();?>
            </form>

            <!-- paging -->
            <div class="paging">
                <div class="pull-left" style="padding-top:20px;">Hiển thị <?= count($objects);?> trên tổng số <?= $total;?> kết quả</div>
            </div>
            <!-- paging -->
	    </div>
	</div>
</div>