<?php
	$setting = array(
		'fbm_desktop'    => 1,
		'fbm_tablet'     => 0,
		'fbm_mobile'     => 0,
		
		'fbm_title'      => 'Facebook Chat',
		'fbm_color_bg'   => '',
		'fbm_color_text' => '',
		'fbm_position'   => 'left',
    );

	if( isset($option['fbm-send-message']) ) $setting = $option['fbm-send-message'];
?>

<div class="col-md-6">
	<div class="col-md-12"><h4 class="fbm-header">Facebook Chat Hiển Thị</h4></div>
	
	<?php  $input = array( 'field' => 'fbm_desktop', 'type'	=> 'switch', 'label' => 'Desktop', 'note' => 'Kích thước từ 769px trở lên' ); ?>
	<?php echo _form($input, $setting['fbm_desktop']);?>

	<?php  $input = array( 'field' => 'fbm_tablet', 'type'	=> 'switch', 'label' => 'Tablet', 'note' => 'Kích thước từ 768px đến 500px' ); ?>
	<?php echo _form($input, $setting['fbm_tablet']);?>

	<?php  $input = array( 'field' => 'fbm_mobile', 'type'	=> 'switch', 'label' => 'Mobile', 'note' => 'Kích thước từ 499px trở xuống' ); ?>
	<?php echo _form($input, $setting['fbm_mobile']);?>

</div>
<div class="col-md-6">
	<div class="col-md-12"><h4 class="fbm-header">Facebook Chat Template</h4></div>

	<?php  $input = array(
		'field' => 'fbm_title', 'type'	=> 'text', 'label' => 'Tiêu đề', 'value'  => 'Facebook chat',
	); ?>
	<?php echo _form($input, $setting['fbm_title']);?>

	<?php  $input = array(
		'field' => 'fbm_color_bg', 'type'	=> 'color', 'label' => 'Màu nền', 'note'  => '',
	); ?>
	<?php echo _form($input, $setting['fbm_color_bg']);?>

	<?php  $input = array(
		'field' => 'fbm_color_text', 'type'	=> 'color', 'label' => 'Màu chữ', 'note'  => '',
	); ?>
	<?php echo _form($input, $setting['fbm_color_text']);?>

	<?php  $input = array(
		'field' => 'fbm_position', 'type'	=> 'select', 'options' => array( 'right' => 'Phải', 'left' => 'Trái'),
		'label' => 'Vị trí',
	); ?>
	<?php echo _form($input, $setting['fbm_position']);?>
</div>