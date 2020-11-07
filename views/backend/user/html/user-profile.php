<form action="" method="post" id="form-action">
	
    <?php echo form_open();?>
    
    <div class="ui-layout">
        <div class="col-md-12">
            <div class="box">
				<div class="box-content">
				<div class="col-md-3">
					<section class="ui-layout__section" style="overflow: hidden; border-right:1px solid #ccc;">
						<div class="form-group">
							<label class="control-label">Họ Tên</label>
							<div class=""> <?= $user->firstname.' '.$user->lastname;?> </div>
						</div>

						<div class="form-group">
							<label class="control-label">Email</label>
							<div class=""><?= $user->email;?></div>
						</div>

						<div class="form-group">
							<label class="control-label">Điện thoại</label>
							<div class=""><?= $user->phone;?></div>
						</div>

						<div class="form-group">
							<label class="control-label">Địa chỉ</label>
							<div class=""><?= get_user_meta($user->id, 'address', true);?></div>
						</div>

						<?php do_action('edit_user_profile_info', $user);?>

					</section>
				</div>
				<div class="col-md-9">
					<section class="ui-layout__section" style="overflow: hidden;">

						<?php $ci->template->get_message();?>

						<div class="row">
							<div class="form-group col-md-6">
								<label class="control-label">Họ</label>
								<div class="">
									<input name="firstname" value="<?= set_value('firstname',$user->firstname);?>" type="text" class="form-control" placeholder="Họ">
								</div>
							</div>

							<div class="form-group col-md-6">
								<label class="control-label">Tên</label>
								<div class="">
									<input name="lastname" value="<?= set_value('lastname',$user->lastname);?>" type="text" class="form-control" placeholder="Tên">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label class="control-label">Email</label>
								<div class="">
									<input name="email" value="<?= set_value('email',$user->email);?>" type="email" class="form-control" placeholder="Email">
								</div>
							</div>

							<div class="form-group col-md-6">
								<label class="control-label">Điện thoại</label>
								<div class="">
									<input name="phone" value="<?= set_value('phone',$user->phone);?>" type="text" class="form-control" placeholder="Điện thoại">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">Địa chỉ</label>
							<div class="">
								<input name="address" value="<?= set_value('address', get_user_meta($user->id, 'address', true) );?>" type="text" class="form-control" placeholder="Địa chỉ">
							</div>
						</div>

						<?php do_action('edit_user_profile', $user);?>

						<div class="form-group">
							<div class="text-right">
								<button type="submit" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
							</div>
						</div>

					</section>
				</div>

				</div>
			</div>
        </div>
    </div>
</form>
