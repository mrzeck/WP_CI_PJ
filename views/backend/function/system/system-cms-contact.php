<?php
    $mail = new skd_mail();

    $contact = [
        array( 	
            'label' 	=> 'Email',
            'note'		=> 'Email liên hệ dùng để nhận mail',
            'field' 	=> 'contact_mail',
            'type' 		=> 'email',
            'group'     => 'contact',
        ),
        array( 	
            'label' 	=> 'Điện Thoại',
            'note'		=> 'Số điện thoại chăm sóc khách hàng, hotline tư vấn...',
            'field' 	=> 'contact_phone',
            'type' 		=> 'phone',
            'group'     => 'contact',
        ),
        array( 	
            'label' 	=> 'Điạ chỉ',
            'note'		=> 'Địa chỉ công ty, shop của bạn.',
            'field' 	=> 'contact_address',
            'type' 		=> 'text',
            'group'     => 'contact',
        ),
    ];

    $contact = apply_filters('system_contact_input', $contact );
?>
<?php if(have_posts($contact)) {?>
<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">
            <?php foreach ($contact as $key => $input) {
                echo _form($input, get_option($input['field']));
            } ?>
        </div>
    </div>
</div>
<?php } ?>