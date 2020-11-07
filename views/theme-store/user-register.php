<div class="col-md-10 col-md-offset-1" style="overflow: hidden;">
	<div class="user-signin-content">
		<div class="col-md-6 signin-content-left text-center">
			<h1 class="text-left"><?php echo __('ĐĂNG KÝ', 't_register');?></h1>
			<hr />	
			<?php get_img('https://colorlib.com/etc/regform/colorlib-regform-7/images/signup-image.jpg');?>
			<p>Bạn đã có tài khoản ? <a href="<?php echo login_url();?>">Đăng nhập</a></p>
		</div>
		<div class="col-md-6 signin-content-right">
			<form action="" method="POST" role="form">
				<?php echo form_open(); ?>

				<?php $this->template->get_message();?>
			
				<div class="form-group col-md-12">
					<label for="">Tên đăng nhập</label>
					<input name="username" type="text" value="<?php echo $this->input->post('username');?>" class="form-control" placeholder="Tên đăng nhập">
				</div>

				<div class="form-group col-md-6">
					<label for="">Mật khẩu</label>
					<input name="password" type="password" class="form-control" placeholder="Nhập mật khẩu">
				</div>

				<div class="form-group col-md-6">
					<label for="">Nhập lại mật khẩu</label>
					<input name="re_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
				</div>

				<div class="form-group col-md-6">
					<label for="">Họ</label>
					<input name="lastname" value="<?php echo $this->input->post('lastname');?>" type="text" class="form-control" placeholder="Nhập họ tên của bạn">
				</div>

				<div class="form-group col-md-6">
					<label for="">Tên</label>
					<input name="firstname" value="<?php echo $this->input->post('firstname');?>" type="text" class="form-control" placeholder="Nhập họ tên của bạn">
				</div>

				<div class="form-group col-md-6">
					<label for="">Email</label>
					<input name="email" type="email" value="<?php echo $this->input->post('email');?>"  class="form-control" placeholder="Nhập email của bạn">
				</div>

				<div class="form-group col-md-6">
					<label for="">Số điện Thoại</label>
					<input name="phone" type="text" value="<?php echo $this->input->post('phone');?>" class="form-control" placeholder="Nhập số điện thoại của bạn">
				</div>

				<div class="form-group col-md-12">
					<label for="">Địa chỉ</label>
					<input name="address" type="text" value="<?php echo $this->input->post('address');?>" class="form-control" placeholder="Địa chỉ của bạn">
				</div>
			
				<div class="form-group col-md-12"><button type="submit" class="btn btn-primary">Đăng Ký</button></div>
			</form>
		</div>
	</div>
</div>