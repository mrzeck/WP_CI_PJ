<?php
	$style = $contact_mobile_icon->style1;

	if( isset($option['style-1']) ) $style = $option['style-1'];
?>

<div class="col-md-3">
	<h4>Icon</h4>
</div>

<div class="col-md-3">
	<h4>Desktop</h4>
</div>

<div class="col-md-3">
	<h4>Tablet</h4>
</div>

<div class="col-md-3">
	<h4>Mobile</h4>
</div>

<div class="clearfix"></div>

<div class="col-md-3">

	<?php  $input = array(
		'field' => 'cmi_fb_messenger', 'type'	=> 'text',
		'label' => 'Facebook messenger id', 'note'  => 'ID facebook messenger dùng để chat',
	); ?>

	<?php echo _form($input, $style['cmi_fb_messenger']);?>

	<?php  $input = array(
		'field' => 'cmi_zalo', 'type'	=> 'tel',
		'label' => 'Zalo Phone', 'note'  => 'Số điện thoại liên kết Zalo',
	); ?>

	<?php echo _form($input, $style['cmi_zalo']);?>


	<?php  $input = array(
		'field' => 'cmi_sms', 'type'	=> 'tel',
		'label' => 'SMS Phone', 'note'  => 'Số điện thoại liên kết sms',
	); ?>

	<?php echo _form($input, $style['cmi_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_call', 'type'	=> 'tel',
		'label' => 'CALL Phone', 'note'  => 'Số điện thoại liên kết khi gọi',
	); ?>

	<?php echo _form($input, $style['cmi_call']);?>
</div>

<div class="col-md-3">

	<?php  $input = array(
		'field' => 'cmi_desktop_enable_fb', 'type'	=> 'switch',
		'label' => 'Facebook messenger id', 'note'  => 'Bật / tắt Facebook messenger',
	); ?>

	<?php echo _form($input, $style['cmi_desktop_enable_fb']);?>

	<?php  $input = array(
		'field' => 'cmi_desktop_enable_zalo', 'type'	=> 'switch',
		'label' => 'Zalo Phone', 'note'  => 'Bật / tắt zalo',
	); ?>

	<?php echo _form($input, $style['cmi_desktop_enable_zalo']);?>

	<?php  $input = array(
		'field' => 'cmi_desktop_enable_sms', 'type'	=> 'switch',
		'label' => 'SMS Phone', 'note'  => 'Bật / tắt sms',
	); ?>

	<?php echo _form($input, $style['cmi_desktop_enable_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_desktop_enable_call', 'type'	=> 'switch',
		'label' => 'CALL Phone', 'note'  => 'Bật / tắt gọi điện',
	); ?>

	<?php echo _form($input, $style['cmi_desktop_enable_call']);?>
</div>

<div class="col-md-3">

	<?php  $input = array(
		'field' => 'cmi_tablet_enable_fb', 'type'	=> 'switch',
		'label' => 'Facebook messenger id', 'note'  => 'Bật / tắt Facebook messenger',
	); ?>

	<?php echo _form($input, $style['cmi_tablet_enable_fb']);?>

	<?php  $input = array(
		'field' => 'cmi_tablet_enable_zalo', 'type'	=> 'switch',
		'label' => 'Zalo Phone', 'note'  => 'Bật / tắt zalo',
	); ?>

	<?php echo _form($input, $style['cmi_tablet_enable_zalo']);?>

	<?php  $input = array(
		'field' => 'cmi_tablet_enable_sms', 'type'	=> 'switch',
		'label' => 'SMS Phone', 'note'  => 'Bật / tắt sms',
	); ?>

	<?php echo _form($input, $style['cmi_tablet_enable_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_tablet_enable_call', 'type'	=> 'switch',
		'label' => 'CALL Phone', 'note'  => 'Bật / tắt gọi điện',
	); ?>

	<?php echo _form($input, $style['cmi_tablet_enable_call']);?>
</div>

<div class="col-md-3">

	<?php  $input = array(
		'field' => 'cmi_mobile_enable_fb', 'type'	=> 'switch',
		'label' => 'Facebook messenger id', 'note'  => 'Bật / tắt Facebook messenger',
	); ?>

	<?php echo _form($input, $style['cmi_mobile_enable_fb']);?>

	<?php  $input = array(
		'field' => 'cmi_mobile_enable_zalo', 'type'	=> 'switch',
		'label' => 'Zalo Phone', 'note'  => 'Bật / tắt zalo',
	); ?>

	<?php echo _form($input, $style['cmi_mobile_enable_zalo']);?>

	<?php  $input = array(
		'field' => 'cmi_mobile_enable_sms', 'type'	=> 'switch',
		'label' => 'SMS Phone', 'note'  => 'Bật / tắt sms',
	); ?>

	<?php echo _form($input, $style['cmi_mobile_enable_sms']);?>

	<?php  $input = array(
		'field' => 'cmi_mobile_enable_call', 'type'	=> 'switch',
		'label' => 'CALL Phone', 'note'  => 'Bật / tắt gọi điện',
	); ?>

	<?php echo _form($input, $style['cmi_mobile_enable_call']);?>
</div>



