<form action="" method="post" id="user_form__created">

	<?php echo $this->template->render_include('action_bar');?>

    <?php echo form_open();?>
    
    <div class="ui-layout customer-sections">
        <div class="col-md-12">
            <div class="ui-title-bar__group">
                <h1 class="ui-title-bar__title">Thêm mới nhân viên</h1>
                <div class="ui-title-bar__action">
                    <?php do_action('user_created_header_action');?>
                </div>
            </div>
        </div>
	</div>

    <div class="ui-layout customer-sections">
        <div class="col-md-8">
			<div class="box">
				<div class="box-content">

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
						<div class="row">
							<div class="form-group col-md-6">
								<label for="">Họ và tên đệm</label>
								<input name="firstname" type="text" value="<?php echo html_escape($this->input->post('firstname'));?>" class="form-control" placeholder="Họ và tên đệm">
							</div>

							<div class="form-group col-md-6">
								<label for="">Tên</label>
								<input name="lastname" type="text" value="<?php echo html_escape($this->input->post('lastname'));?>" class="form-control" placeholder="Nhập tên của tài khoản">
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="">Email</label>
								<input name="email" type="email" value="<?php echo html_escape($this->input->post('email'));?>" class="form-control" placeholder="Nhập email của tài khoản" required>
							</div>

							<div class="form-group col-md-6">
								<label for="">Số điện Thoại</label>
								<input name="phone" type="text" value="<?php echo html_escape($this->input->post('phone'));?>" class="form-control" placeholder="Nhập số điện thoại của tài khoản">
							</div>
						</div>

			        </section>

				</div>
			</div>
            <?php 
            /**
             * customer_created_sections_primary
             */
            echo do_action('user_created_sections_primary');
            ?>
        </div>
        <div class="col-md-4">
			<div class="box">
				<div class="box-content">
					<section class="ui-layout__section">
						<header class="ui-layout__title"><h2>Ghi chú</h2></header>
					</section>
					<section class="ui-layout__section">
						<textarea name="note" class="form-control" rows="3"></textarea>
					</div>
				</div>
			</div>
            <?php 
            /**
             * customer_created_sections_secondary
             */
            echo do_action('user_created_sections_secondary');
            ?>
        </div>
    </div>
</form>
<style type="text/css">
	.ui-layout {
		box-sizing: border-box;
	    max-width: 1100px;
	    margin-right: auto;
	    margin-left: auto;
	    font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
	}

	.ui-layout .box {
		border-radius: 3px;
		-webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    	box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
	}

    section.ui-layout__section {
        padding:20px;
    }

    section.ui-layout__section+section.ui-layout__section {
	    border-top: 1px solid #dfe4e8;
    }
    section.ui-layout__section~section.ui-layout__section {
        border-top: 1px solid #ebeef0;
    }
    
    section.ui-layout__section header.ui-layout__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
        display:inline-block;
	}
</style>