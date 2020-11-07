<?php
	$style = $contact_mobile_icon->style3;

	if( isset($option['style-3']) ) $style = $option['style-3'];
?>

<div class="col-md-6">
	<?php  $input = array(
		'field' => 'cmi_call', 'type'	=> 'tel',
		'label' => 'Call Phone', 'note'  => 'Số điện thoại liên kết khi gọi',
	); ?>
	<?php echo _form($input, $style['cmi_call']);?>

	<?php  $input = array(
		'field' => 'cmi_position', 'type'	=> 'select',
		'label' => 'Vị trí', 'note'  => 'Vị trí icon xuất hiện',
		'options' => array('left' => 'Trái', 'right' => 'Phải'),
	); ?>
	<?php echo _form($input, $style['cmi_position']);?>

	<?php  $input = array(
		'field' => 'cmi_bottom', 'type'	=> 'number',
		'label' => 'Cách bên dưới', 'note'  => 'Vị trí icon xuất hiện',
	); ?>
	<?php echo _form($input, $style['cmi_bottom']);?>
</div>

<div class="col-md-4">

	<?php  $input = array(
		'field' => 'cmi_color_icon', 'type'	=> 'color',
		'label' => 'Màu icon', 'note'  => 'Màu nền icon',
	); ?>
	<?php echo _form($input, $style['cmi_color_icon']);?>

	<?php  $input = array(
		'field' => 'cmi_color_border1', 'type'	=> 'color',
		'label' => 'Màu viền 1', 'note'  => 'Màu nền viền',
	); ?>
	<?php echo _form($input, $style['cmi_color_border1']);?>

	<?php  $input = array(
		'field' => 'cmi_color_border2', 'type'	=> 'color',
		'label' => 'Màu viền 2', 'note'  => 'Màu nền viền',
	); ?>
	<?php echo _form($input, $style['cmi_color_border2']);?>
</div>


