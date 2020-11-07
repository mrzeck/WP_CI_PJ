<?php
function wcmc_customer_fields() {

	$fields['firstname'] = array(
		'field' => 'firstname',
		'label' => __('Họ', 'wcmc_firstname'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 0,
        'args' => array( 'placeholder' => __('Nhập họ.','wcmc_dienhotencuaban') ),
        'after' => '<div class="col-md-6"><label for="lastname" class="control-label">'.__('Họ', 'wcmc_firstname').'</label><div class="group">',
        'before' => '</div></div>',
    );
    
    $fields['lastname'] = array(
		'field' => 'lastname',
		'label' => __('Tên', 'wcmc_lastname'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 0,
        'args' => array( 'placeholder' => __('Nhập tên.','wcmc_dienhotencuaban') ),
        'after' => '<div class="col-md-6"><label for="lastname" class="control-label">'.__('Tên', 'wcmc_lastname').'</label><div class="group">',
        'before' => '</div></div>',
	);

	$fields['email'] = array(
		'field' => 'email',
		'label' => 'Email',
		'type'  => 'email',
		'value' => '',
		'rules' => 'trim|required|email',
		'priority' => 10,
		'args' => array( 'placeholder' => __('Địa chỉ email của bạn.','wcmc_diachiemailcuaban') )
	);

	$fields['phone'] = array(
		'field' => 'phone',
		'label' => __('Số điện thoại', 'wcmc_phone'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 20,
		'args' => array( 'placeholder' => __('Điện thoại liên lạc với bạn.','wcmc_dienthoailienlacvoiban') )
	);

	$fields['address'] = array(
		'field' => 'address',
		'label' => __('Địa chỉ', 'wcmc_address'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 30,
		'metadata' => true,
		'args' => array( 'placeholder' => __('Địa chỉ của bạn.','wcmc_diachicuaban') )
	);

	$states[] 	= 'Chọn tỉnh thành';

	$states 	= array_merge( $states, wcmc_shipping_states_provinces());

	$fields['city'] = array(
		'field' => 'city',
		'label' => 'Tỉnh / Thành Phố',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => $states,
		'priority' => 40,
		'metadata' => true,
	);

	$fields['districts'] = array(
		'field' => 'districts',
		'label' => 'Quận Huyện',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => array(),
		'priority' => 50,
		'metadata' => true,
	);

	return apply_filters( 'wcmc_customer_fields', $fields );
}