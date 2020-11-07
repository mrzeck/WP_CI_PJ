<div class="col-md-10 col-md-offset-1" style="overflow: hidden;">
	<div class="user-signin-content">
		<div class="col-md-6 signin-content-left text-center">
			<?php get_img('https://colorlib.com/etc/regform/colorlib-regform-7/images/signin-image.jpg');?>
			<p>Bạn chưa có tài khoản ? <a href="<?php echo register_url();?>">Đăng ký</a></p>
		</div>
		<div class="col-md-6 signin-content-right">
			<form action="" method="POST" role="form">
				<h1 class="text-left"><?php echo __('ĐĂNG NHẬP', 't_login');?></h1>
				<hr />

				<?php echo form_open(); ?>

				<?php $this->template->get_message();?>
			
				<div class="form-group">
					<label for="">Tên đăng nhập</label>
					<input name="username" type="text" class="form-control" placeholder="Tên đăng nhập">
				</div>

				<div class="form-group">
					<label for="">Mật khẩu</label>
					<input name="password" type="password" class="form-control" placeholder="Tên đăng nhập">
				</div>
			
				<button type="submit" class="btn btn-primary">Đăng Nhập</button>
			</form>
		</div>
	</div>
</div>