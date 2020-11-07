<?php
if( $ci->input->post() ) {

	$data = $ci->input->post();

	$name = removeHtmlTags($data['name']);

	$phone = removeHtmlTags($data['phone']);

	$email = removeHtmlTags($data['email']);

	$content = removeHtmlTags($data['content']);

	$config = array(

        'from_email' => get_option('contact_mail'),

        'fullname'   => $name,

        'to_email'   => get_option('contact_mail'),

        'subject'    => 'Email liên hệ từ '.$name,

        'content'    => '
			<p>Họ tên: <strong>'.$name.'</strong></p>
			<p>Email: <strong>'.$email.'</strong></p>
			<p>Phone: <strong>'.$phone.'</strong></p>
			<p>Ghi chú: <strong>'.$content.'</strong></p>
        ',
    );

    send_mail( $config );

    $ci->template->set_message(notice('success', 'Gửi email liên hệ thành công'));
}
?>
<div class="layout">
	<div class="col-md-6 contact-map">
		<?php echo get_option('maps_embed');?>
	</div>
	<div class="col-md-6 contact-form-wrapper">
		<?php echo $ci->template->get_message();?>
		<?php the_content();?>
		<h3>Liên hệ</h3>
		<div class="contact-form">
            <form id="contact-form" class="flexiContactForm" role="form" method="post">

            	<?php echo form_open();?>

                <div class="form-wrapper">
                    <div class="form-group">
                        <input type="text" class="text-input" id="name" name="name" placeholder="<?php echo __('Họ tên của bạn');?>">
                    </div>
                    <div class="form-group">
                        <input type="email" class="text-input" id="email" name="email" placeholder="<?php echo __('Email của bạn');?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="text-input" id="phone" name="phone" placeholder="<?php echo __('Điện thoại của bạn');?>">
                    </div>
                    <div class="form-group">
                        <textarea class="text-input" value="" id="content" name="content" placeholder="<?php echo __('Ghi chú');?>" style="overflow: hidden; word-wrap: break-word; height: 150px;"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block" id="contact-form-submit"><span><?php echo __('Gửi');?></span></button>
                    </div>
                </div>
            </form>
        </div>
	</div>
	<div class="clearfix"></div>
</div>
<br />

<style type="text/css">
	iframe { width: 100%!important; height: 100%!important; }

	.contact-map {
		position: -webkit-sticky;
	    position: sticky;
	    top: 0;
	    border-right: 1px solid #ededed;
	    height: 100vh;
	    padding: 0;
	}

	.contact-form-wrapper { padding:50px; }

	.contact-form-wrapper h3 {
	    font-size: 30px;
	    margin-bottom: 20px;
	}

	.contact-form-wrapper h3:after {
	    content: "";
	    display: block;
	    margin-top: 25px;
	    width: 30px;
	    height: 3px;
	    background: #252a2b;
	}
	.contact-form-wrapper .form-wrapper {
	    width: 100%;
	    overflow: hidden;
	}
	.contact-form-wrapper .form-groups {
	    width: 100%;
	    overflow: hidden;
	}

	.contact-form-wrapper .form-group {
		position: relative;
	}

	.contact-form-wrapper .form-groups .form-group {
	    width: 49%;
	    float: left;
	    margin-bottom: 20px;
    	position: relative;
	}
	.contact-form-wrapper .text-input {
	    width: 100%;
	    border: none;
	    border: 1px solid #999999;
	    background-color: #FBFBFB;
	    color: #0f0f0f;
	    font-size: 16px;
	    padding: 10px;
	    transition: all 0.25s ease-in-out;
	    -moz-transition: all 0.25s ease-in-out;
	    -webkit-transition: all 0.25s ease-in-out;
	}
	.contact-form-wrapper .text-input:focus {
	    border-color: #767676;
	    outline: none;
	}
</style>