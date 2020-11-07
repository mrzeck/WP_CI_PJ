<?php echo $this->template->render_include('action_bar');?>

<div class="col-md-12">

	<!-- <div class="col-md-12"> -->
		<div class="ui-title-bar__group">
			<h1 class="ui-title-bar__title">Danh sách thành viên</h1>
			<div class="ui-title-bar__action">
				<?php do_action('admin_user_action_bar_heading');?>
			</div>
		</div>
	<!-- </div> -->

	<div class="box" style="padding-top:5px;">
		<!-- <div class="header"> <h2>Danh sách thành viên</h2> </div> -->
		<?php $table_list->display_search();?>
		<form method="post" id="form-action" class="table-responsive">
            <?php $table_list->display();?>
        </form>

		<!-- paging -->
		<div class="paging">
			<div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
		</div>
		<!-- paging -->
	</div>
</div>

<?php if(is_super_admin()) {?>
<!-- Modal -->
<div class="modal fade" id="modalreset" tabindex="-1" role="dialog">
  	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title" id="myModalLabel">Đổi Mật Khẩu</h4>
	      	</div>
	      	<form id="form-check-pass">
		        <div class="modal-body">
		          	<div class="form-group">
		            	<label for="">Mật Khẩu <b style="color:red">root</b></label>
		            	<input name="password" type="password" class="form-control" placeholder="password" required>
		          	</div>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
		          	<button type="submit" class="btn btn-primary">Lưu</button>
		        </div>
	      	</form>
	    </div>
  	</div>
</div>
<?php } ?>