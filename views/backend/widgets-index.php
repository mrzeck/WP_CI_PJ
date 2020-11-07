<?= $this->template->render_include('action_bar');?>

<!-- widget -->
<div class="col-md-6" id="widget_main">
  	<div class="rows">
	    <div class="col-md-12">
	      	<div class="box">
		        <div class="box-content">
		          	
		            <div class="rows" id="widget">
	            		<div class="col-md-12" style="padding:5px;">
	            			<input type="text" name="js_widget_search" id="widget_main_search" class="form-control" value="" placeholder="Điền Tên hoặc key để tìm...">
	            		</div>

	            		<div class="clearfix"></div>
						
		            	<ul id="js_widget_main_list">
		            		<?php if(isset($objects) && have_posts($objects)) { ?>
				              	<?php foreach ($objects as $key => $val): ?>
				              		<?php include 'include/loop/widget_item.php'; ?>
				            	 <?php endforeach ?>
			            	<?php } else { echo notice('danger','Template hiện hành không có widget'); }  ?>
		            	</ul>
		            </div>
		        </div>
	      	</div>
	    </div>
  	</div>
</div>

<!-- sidebar -->
<div class="col-md-6" id="widget_sidebar">
<?php if(have_posts($this->sidebar)) { ?>
<?php foreach ($this->sidebar as $key => $val): ?>
	<div class="col-md-6">
		<div class="box" id="box_<?= $key;?>">
			<div class="header">
				<h3 class="pull-left"><?= $val['name'];?></h3>
				<a class="pull-right btn-collapse" id="btn-<?=$key;?>" data-toggle="collapse" data-target="#widget-sidebar-content_<?=$key;?>"><?php echo (get_cookie("btn-".$key)== null)?'<i class="fal fa-minus-square"></i>':'<i class="fal fa-plus-square"></i>';?></a>
			</div>
			<div class="box-content widget-sidebar-content collapse <?= (get_cookie("btn-".$key)== null)?'in':'';?>" id="widget-sidebar-content_<?= $key;?>">
				<ul class="js_sidebar" id="<?= $key;?>">
				<?php foreach ($val['widget'] as $wig):
					if(have_posts($wig) && isset($wig->widget_name)) echo $this->template->render_include('loop/widget_item', array('val' =>$wig));
				endforeach ?>
				</ul>
			</div>
		</div>
	</div>
<?php endforeach ?>
<?php } else { echo notice('danger','Không có sidebar nào');}  ?>
</div>

<div class="overflow"></div>

<div id="modal-edit-widget">
	<form id="form-edit-widget">
	  	<div id="ajax_edit_widget_loader" class="ajax-load-qa">&nbsp;</div>
	  	<div class="box-edit-widget">
	  	</div>
	  	<hr />
	  	<div class="box-content text-right">
	    	<button type="submit" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
	    	<button type="button" class="btn btn-default btn-close">Đóng</button>
	  	</div>
	</form>
</div>


<style type="text/css">
	#modal-edit-widget {
		position: absolute;
		background-color: #fff;
		width: 100%;
		z-index: 999;
	}
</style>

<div class="modal fade" id="modal-service-widget" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header" style="overflow: hidden;">
      			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        		

        		<div class="pull-left">
					<h4 class="modal-title">Danh Sách Widget</h4>
        		</div>
        		<div class="pull-right">
					<input type="text" name="widget_server_search" id="widget_server_search" class="form-control" placeholder="Điền Tên để tìm...">
        		</div>	
      		</div>
      		<div class="ajax-load-qa">&nbsp;</div>
      		<div class="modal-body" id="widget-view-content">
      		</div>
    	</div>
  	</div>
</div>


<style type="text/css" media="screen">
	.col-md-6 { padding:5px; }
	.location { display: none; }
	.header { cursor: pointer; }
	.overflow { position: fixed; display: none; z-index: 999; top:0; left:0; width:100%; height: 100%; background-color: rgba(0,0,0,0.5); }
	.highlights { position:relative; z-index: 999999; background-color: #fff; padding:10px; }

	.loading {
	    border-radius: 50%;
	    width: 17px;
	    height: 17px;
	    margin-top: 0px;
	    margin-left: 5px;
	    display: none;
	    border: .25rem solid rgba(255, 255, 255, 0.2);
	    border-top-color: #fff;
	    -webkit-animation: spin 1s infinite linear;
	    animation: spin 1s infinite linear;
	}

	.wrapper .content .page-content .box {
		border: 1px solid #e7ecf1;
	    overflow: hidden;
		margin-bottom: 1px;
	}
	.fancybox-container {
		z-index:5000;
	}

	.modal.in .modal-dialog { margin: 0; width: 100%!important; }
	.modal-content { width: 100%; height:100vh; border-radius:0px; border:0; }
</style>