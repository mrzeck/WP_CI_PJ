<?php
	$setting = array(
		'fbm_desktop'    => 1,
        'fbm_tablet'     => 0,
        'fbm_mobile'     => 0,
        'fbm_position'   => 'left',
        'fbm_height'   => 300,
        'fbm_bottom'   => 300,
    );

	if( isset($option['fbm-tab']) ) $setting = $option['fbm-tab'];
?>
<div class="col-md-4">
	<div class="col-md-12"><h4 class="fbm-header">Facebook Tab Hiển Thị</h4></div>
	
	<?php  $input = array( 'field' => 'fbm_desktop', 'type'	=> 'switch', 'label' => 'Desktop', 'note' => 'Kích thước từ 769px trở lên' ); ?>
	<?php echo _form($input, $setting['fbm_desktop']);?>

	<?php  $input = array( 'field' => 'fbm_tablet', 'type'	=> 'switch', 'label' => 'Tablet', 'note' => 'Kích thước từ 768px đến 500px' ); ?>
	<?php echo _form($input, $setting['fbm_tablet']);?>

	<?php  $input = array( 'field' => 'fbm_mobile', 'type'	=> 'switch', 'label' => 'Mobile', 'note' => 'Kích thước từ 499px trở xuống' ); ?>
	<?php echo _form($input, $setting['fbm_mobile']);?>

</div>
<div class="col-md-4">
	<div class="col-md-12"><h4 class="fbm-header">Facebook Tab Template</h4></div>

	<?php  $input = array(
		'field' => 'fbm_position', 'type'	=> 'select', 'options' => array( 'right' => 'Phải', 'left' => 'Trái'),
		'label' => 'Vị trí',
	); ?>
	<?php echo _form($input, $setting['fbm_position']);?>

	<?php  $input = array( 'field' => 'fbm_height', 'type'	=> 'number', 'label' => 'Chiều cao', 'value' => 300); ?>
	<?php echo _form($input, $setting['fbm_height']);?>

	<?php  $input = array( 'field' => 'fbm_bottom', 'type'	=> 'number', 'label' => 'Bottom', 'value' => 300); ?>
	<?php echo _form($input, $setting['fbm_bottom']);?>
</div>