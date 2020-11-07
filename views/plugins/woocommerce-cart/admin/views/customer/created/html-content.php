<div class="box">
	<div class="box-content">
        <?php $ci =&get_instance(); echo $ci->template->get_message();?>

        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Thông tin đăng nhập</h2></header>
        </section>
        
        <section class="ui-layout__section" style="overflow: hidden;">

            <div class="form-group col-md-12">
				<label for="">Tên đăng nhập</label>
				<input name="username" type="text" value="<?php echo $ci->input->post('username');?>" class="form-control" placeholder="Tên đăng nhập" required>
			</div>

            <div class="form-group col-md-6">
				<label for="">Mật khẩu</label>
				<input name="password" type="password" value="<?php echo $ci->input->post('password');?>" class="form-control" placeholder="Nhập mật khẩu" required>
			</div>

            <div class="form-group col-md-6">
				<label for="">Nhập lại mật khẩu</label>
				<input name="re_password" type="password" value="<?php echo $ci->input->post('re_password');?>" class="form-control" placeholder="Nhập lại mật khẩu" required>
			</div>

        </section>
	</div>
</div>

<div class="box">
	<div class="box-content">
        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Thông tin cơ bản</h2></header>
        </section>
        
        <section class="ui-layout__section" style="overflow: hidden;">

            <?php foreach ($fields as $key => $field) {

                $value = $ci->input->post($key);

                echo _form($field, $value);
            } ?>

        </section>

	</div>
</div>