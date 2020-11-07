<?php
	$style = $contact_mobile_icon->style2;

	if( isset($option['style-2']) ) $style = $option['style-2'];
?>

<div class="col-md-8">
	<?php  $input = array(
		'field' => 'cmi_call', 'type'	=> 'tel',
		'label' => 'CALL Phone', 'note'  => 'Số điện thoại liên kết khi gọi',
	); ?>
	<?php echo _form($input, $style['cmi_call']);?>


	<?php  $input = array(
		'field' => 'cmi_sms', 'type'	=> 'tel',
		'label' => 'SMS Phone', 'note'  => 'Số điện thoại liên kết sms',
	); ?>
	<?php echo _form($input, $style['cmi_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_contact', 'type'	=> 'text',
		'label' => 'Liên hệ', 'note'  => 'Liên kết url liên hệ',
	); ?>
	<?php echo _form($input, $style['cmi_contact']);?>
</div>

<div class="col-md-4">
	<?php  $input = array(
		'field' => 'cmi_title_call', 'type'	=> 'tel',
		'label' => 'Tiêu đề call Phone', 'note'  => 'Số điện thoại liên kết khi gọi',
	); ?>
	<?php echo _form($input, $style['cmi_title_call']);?>


	<?php  $input = array(
		'field' => 'cmi_title_sms', 'type'	=> 'tel',
		'label' => 'Tiêu đề SMS Phone', 'note'  => 'Số điện thoại liên kết sms',
	); ?>
	<?php echo _form($input, $style['cmi_title_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_title_contact', 'type'	=> 'text',
		'label' => 'Tiêu đề Liên hệ', 'note'  => 'Liên kết url liên hệ',
	); ?>
	<?php echo _form($input, $style['cmi_title_contact']);?>
</div>

<div class="col-md-12">
	<?php  $input = array(
		'field' => 'cmi_bg', 'type'	=> 'color',
		'label' => 'Màu nền takbar', 'note'  => '',
	); ?>
	<?php echo _form($input, $style['cmi_bg']);?>
</div>

