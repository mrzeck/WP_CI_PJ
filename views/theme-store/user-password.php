<form class="form-horizontal col-md-7" method="post">
	<h1 class="user-header-title">Đổi mật khẩu</h1>
	<?php echo form_open();?>

	<div class="form-group">
		<label class="col-sm-3 control-label">Mật khẩu cũ</label>
		<div class="col-sm-9">
			<input name="old_password" type="password" class="form-control" placeholder="Nhập mật khẩu cũ">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Mật khẩu mới</label>
		<div class="col-sm-9">
			<input name="new_password" type="password" class="form-control" placeholder="Mật khẩu từ 6 đến 32 ký tự">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Nhập lại</label>
		<div class="col-sm-9">
			<input name="re_new_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu mới">
		</div>
	</div>

  	<div class="form-group">
    	<div class="col-sm-offset-2 col-sm-10">
      		<button type="submit" class="btn btn-yellow">Cập Nhật</button>
    	</div>
  	</div>
</form>


