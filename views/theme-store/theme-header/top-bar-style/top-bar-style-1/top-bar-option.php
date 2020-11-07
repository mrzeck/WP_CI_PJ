<?php
function store_theme_top_bar_option() {

	$fonts = gets_theme_font();

	//header
	register_theme_option_field(
		array(
			/****************** HEADER TOP BAR ******************/
			array( 'field'  => 'header_start_group', 'group' => 'header', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">HEADER TOP BAR</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'),

			array(
				'label'     => 'Hiển Thị Top Bar', 	'note' 		=> '',
				'field'     => 'top_bar_public', 'value'     => '1',
				'type'      => 'switch', 'group' 	=> 'header',
				'options'   => 1,
				'after' => '<div class="col-md-12"><label>Hiển Thị Top Bar</label><div class="group">', 'before'=> '</div></div><br />',
			),

			array( 	
				'label'     => 'Top bar banner image', 	'note' 		=> 'Chọn ảnh nền top bar.',
				'field'     => 'top_bar_bg_image', 			'type' 		=> 'image',
				'group'     => 'header',
				'after' => '<div class="col-md-4"><label>Top bar banner image</label><div class="group">', 'before'=> '</div></div>',
			),

			array( 	
				'label'     => 'Top bar banner image khuyễn mãi', 	'note' 		=> 'Chọn ảnh nền top bar.',
				'field'     => 'top_bar_bg_image_km', 			'type' 		=> 'image',
				'group'     => 'header',
				'after' => '<div class="col-md-4"><label>Top bar banner image khuyễn mãi</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'label'     => 'Top bar background color', 	
				'field'     => 'top_bar_bg_color', 			'type' 		=> 'color',
				'group'     => 'header',
				'after' => '<div class="col-md-4"><label>Top bar background color</label><div class="group">', 'before'=> '</div></div>',
			),


			array( 'field'  => 'header_end_group', 'type' => 'none', 'group' => 'header', 'after' => '</div></div>', 'before'=> ''),
		)
    );
    
    //HEADER SOCIAL
    $social[] = ['field'  => 'header_start_group', 'group' => 'header', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">TOP BAR ICON MẠNG XÃ HỘI</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'];
    $list_social = get_theme_social();
    foreach ($list_social as $key => $field) {
        $social[] = ['field' => 'top_bar_'.$field['field'].'_icon', 'label' => 'Icon '.$field['label'], 'type' => 'image', 'note' => 'Liên kết được lấy từ cấu hình hệ thống.', 'group' => 'header',];
    }
    $social[] = ['field'  => 'header_end_group', 'type' => 'none', 'group' => 'header', 'after' => '</div></div>', 'before'=> ''];

    register_theme_option_field($social);
}

add_action('theme_option_setup', 'store_theme_top_bar_option', 100);