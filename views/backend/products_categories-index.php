<?php $this->template->render_include('action_bar');?>
<?php if( current_user_can('wcmc_product_cate_edit') ) { ?>
<div class="col-md-5">
  	<div class="box">
      	<div class="header">
        	<h2>Thêm Nhanh Danh Mục</h2>
      	</div>
      	<form method="post" id="form-input-category" data-module="<?= $this->template->class;?>" class="form-categories" class="table-responsive">
      		<div id="ajax_loader" class="ajax-load-qa">&nbsp;</div>
      		<?= $this->template->render_include('ajax-page/form');?>
        	<div class="box-content"><button type="submit" name="save" class="btn-icon btn-green"><i class="fas fa-save" aria-hidden="true"></i>Lưu</button></div>
      	</form>
  	</div>
</div>
<?php } ?>


<div class="col-md-7">
	<div class="box">
		<div class="header">
	      <h2>Danh Sách Danh Mục Sản Phẩm</h2>
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
                <div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
            </div>
            <!-- paging -->
	    </div>
	</div>
</div>