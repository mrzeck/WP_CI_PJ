<?php
function get_chekout_fields_billing() {

	$prefix = 'billing_';

	$fields[$prefix.'fullname'] = array(
		'field' => $prefix.'fullname',
		'label' => __('Họ và tên', 'wcmc_fullname'),
		'label_error' => __('Họ và tên', 'wcmc_fullname'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 0,
		'args' => array( 'placeholder' => __('Điền họ tên của bạn.','wcmc_dienhotencuaban') )
	);

	$fields[$prefix.'email'] = array(
		'field' => $prefix.'email',
		'label' 		=> 'Email',
		'label_error' 	=> 'Email',
		'type'  		=> 'email',
		'value' 		=> '',
		'rules' 		=> 'trim|required',
		'priority' 		=> 10,
		'args' 			=> array( 'placeholder' => __('Địa chỉ email của bạn.','wcmc_diachiemailcuaban') )
	);

	$fields[$prefix.'phone'] = array(
		'field' => $prefix.'phone',
		'label' => __('Số điện thoại', 'wcmc_phone'),
		'label_error' => __('Số điện thoại', 'wcmc_phone'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 20,
		'args' => array( 'placeholder' => __('Điện thoại liên lạc với bạn.','wcmc_dienthoailienlacvoiban') )
	);

	$fields[$prefix.'address'] = array(
		'field' => $prefix.'address',
		'label' => __('Địa chỉ', 'wcmc_address'),
		'label_error' => __('Địa chỉ', 'wcmc_address'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 30,
		'args' => array( 'placeholder' => __('Địa chỉ của bạn.','wcmc_diachicuaban') )
	);

	$states[''] 	= 'Chọn tỉnh thành';
		
	$states 	= array_merge( $states, wcmc_shipping_states_provinces());

	$fields[$prefix.'city'] = array(
		'field' => $prefix.'city',
		'label' => 'Tỉnh / Thành Phố',
		'type'  => 'select',
		'rules' => 'trim|required',
		'options' => $states,
		'priority' => 40,
	);

	$fields[$prefix.'districts'] = array(
		'field' => $prefix.'districts',
		'label' => 'Quận Huyện',
		'type'  => 'select',
		'rules' => 'trim|required',
		'options' => array(),
		'priority' => 50,
	);

	// $fields[$prefix.'ward'] = array(
	// 	'field' => $prefix.'ward',
	// 	'label' => 'Phường xã',
	// 	'type'  => 'select',
	// 	'rules' => 'trim',
	// 	'options' => array(),
	// 	'priority' => 60,
	// );

	$user = get_user_current();

	if( have_posts($user) ) {

		$fields[$prefix.'fullname']['value'] = $user->firstname.' '.$user->lastname;

		$fields[$prefix.'email']['value']    = $user->email;

		$fields[$prefix.'phone']['value']    = $user->phone;

		$fields[$prefix.'address']['value']  = get_user_meta( $user->id, 'address', true );
	}

	return apply_filters( 'woocommerce_billing_fields', $fields );
}

function get_chekout_fields_shipping() {
	
	$prefix = 'shipping_';

	$fields[$prefix.'fullname'] = array(
		'field' => $prefix.'fullname',
		'label' => __('Họ và tên', 'wcmc_fullname'),
		'type'  => 'text',
		'value' => '',
		'priority' => 0,
	);

	$fields[$prefix.'email'] = array(
		'field' => $prefix.'email',
		'label' => 'Email',
		'type'  => 'email',
		'value' => '',
		'priority' => 10,
	);

	$fields[$prefix.'phone'] = array(
		'field' => $prefix.'phone',
		'label' => __('Số điện thoại', 'wcmc_phone'),
		'type'  => 'text',
		'value' => '',
		'priority' => 20,
	);

	$fields[$prefix.'address'] = array(
		'field' => $prefix.'address',
		'label' => __('Địa chỉ', 'wcmc_address'),
		'type'  => 'text',
		'value' => '',
		'priority' => 30,
	);

	$states[] 	= 'Chọn tỉnh thành';
		
	$states 	= array_merge( $states, wcmc_shipping_states_provinces());

	$fields[$prefix.'city'] = array(
		'field' => $prefix.'city',
		'label' => 'Tỉnh / Thành Phố',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => $states,
		'priority' => 40,
	);

	$fields[$prefix.'districts'] = array(
		'field' => $prefix.'districts',
		'label' => 'Quận Huyện',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => array(),
		'priority' => 50,
	);

	// $fields[$prefix.'ward'] = array(
	// 	'field' => $prefix.'ward',
	// 	'label' => 'Phường xã',
	// 	'type'  => 'select',
	// 	'rules' => 'trim',
	// 	'options' => array(),
	// 	'priority' => 60,
	// );

	return apply_filters( 'woocommerce_shipping_fields', $fields );
}

function get_chekout_fields_order() {
	
	$prefix = 'order_';

	$fields[$prefix.'note'] = array(
		'field' => $prefix.'note',
		'label' => __('Ghi chú','wcmc_note'),
		'type'  => 'textarea',
		'value' => '',
		'rules' => 'trim',
	);

	return apply_filters( 'woocommerce_order_fields', $fields );
}

function get_checkout_fields() {

	$fields = array();

	$fields['billing']  = get_chekout_fields_billing();

	$fields['shipping'] = get_chekout_fields_shipping();

	$fields['order']    = get_chekout_fields_order();

	$fields = apply_filters( 'woocommerce_checkout_fields', $fields );

	//sort billing

	$priority = array();

	foreach ($fields['billing'] as $key => $value) {
		if(isset($value['priority'])) $priority[$value['priority']][$key] = $value;
		else $priority[1000][$key] = $value;
	}

	ksort($priority);

	$fields['billing'] = array();

	foreach ($priority as $value) {

		foreach ($value as $key => $val) {
			$fields['billing'][$key] = $val;
		}
	}

	//sort shipping
	$priority = array();

	foreach ($fields['shipping'] as $key => $value) {
		if(isset($value['priority'])) $priority[$value['priority']][$key] = $value;
		else $priority[1000][$key] = $value;
	}

	ksort($priority);

	$fields['shipping'] = array();

	foreach ($priority as $value) {

		foreach ($value as $key => $val) {
			$fields['shipping'][$key] = $val;
		}
	}

	return $fields;
}

function checkout_fields_rules() {

	$billings 	= get_chekout_fields_billing();

	$shippings 	= get_chekout_fields_shipping();

	$rules = [
		'billings' 	=> array(),
		'shippings'	=> array(),
	];

	foreach ($billings as $key => $value) {

		if(!empty($value['rules'])) {

			$rules['billings'][$key] = ['field' => $key, 'label' => (isset($value['label_error'])) ? $value['label_error'] : $value['label'], 'rules' => $value['rules']];
		}
	}

	$rules['shippings']['shipping_fullname'] = [
		'field' => 'shipping_fullname',
		'label' => 'họ và tên người nhận',
		'rules' => 'required',
	];

	$rules['shippings']['shipping_email'] = [
		'field' => 'shipping_email',
		'label' => 'email người nhận',
		'rules' => 'required',
	];

	$rules['shippings']['shipping_phone'] = [
		'field' => 'shipping_phone',
		'label' => 'số điện thoại người nhận',
		'rules' => 'required',
	];

	$rules['shippings']['shipping_address'] = [
		'field' => 'shipping_address',
		'label' => 'địa chỉ người nhận',
		'rules' => 'required',
	];

	return apply_filters('checkout_fields_rules', $rules);
}

if(!is_admin() && version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0 ) {

	function wcmc_custom_field_billing($fields) {

		foreach ($fields as $key => &$field) {

			if(empty($field['id'])) $id = $field['field'];

			$id = str_replace('[', '_',$id);

			$id = str_replace(']', '', $id);

			$field['after'] = '<div class="col-md-6 input_checkout"><label>'.$field['label'].'</label>';

			$field['before'] = '<div class="error_message" id="error_'.$id.'"></div></div>';
		}
		
		return $fields;
	}

	add_filter( 'woocommerce_billing_fields', 'wcmc_custom_field_billing', 40 );

	function wcmc_custom_field_shipping($fields) {

		foreach ($fields as $key => &$field) {

			if(empty($field['id'])) $id = $field['field'];

			$id = str_replace('[', '_',$id);

			$id = str_replace(']', '', $id);

			$field['after'] = '<div class="col-md-6 input_checkout"><label>'.$field['label'].'</label>';

			$field['before'] = '<div class="error_message" id="error_'.$id.'"></div></div>';
		}
		
		return $fields;
	}

	add_filter( 'woocommerce_shipping_fields', 'wcmc_custom_field_shipping', 40 );
}