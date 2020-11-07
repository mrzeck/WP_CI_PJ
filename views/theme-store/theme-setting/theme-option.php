<?php
function store_theme_option() {
	$fonts = gets_theme_font();
	register_theme_option_group( array(
		'general'    => array( 'label' => 'Cấu hình chung', 	'icon' 	=> '<i class="fa fa-cogs"></i>'),
		'header'     => array( 'label' => 'Header', 			'icon' 	=> '<i class="fab fa-hire-a-helper"></i>', ),
		'nav'        => array( 'label' => 'Navigation', 		'icon' 	=> '<i class="fa fa-bars"></i>', ),
		'map'        => array( 'label' => 'Map', 				'icon' 	=> '<i class="fa fa-map-marker" aria-hidden="true"></i>', ),
		'footer'     => array( 'label' => 'Footer', 			'icon' 	=> '<i class="fa fa-window-maximize"></i>',),
		'fonts'    	 => array( 'label' => 'Font style', 		'icon' 	=> '<i class="fas fa-font"></i>', 'root' => true),
	) );
	//general
	register_theme_option_field(
		array(
			array( 'field'  => 'general_start_group', 'group' => 'general', 'after' => ' <div class="box col-md-12"><div class="box-content">', 'before'=> '', 'type' => 'hidden'),
			array(
				'label' => 'Tên website', 'field' =>  'general_label', 'type'  => 'text', 'group' => 'general',
				'after' => '<div class="col-md-6"><label>Tên website</label>', 'before'=> '</div>',
			),
			array(
				'label'  => 'Tiêu đề trang chủ', 'field'=>'general_title',
				'type'  => 'text', 'group' => 'general',
				'after' => '<div class="col-md-6"><label>Tiêu đề trang chủ</label>', 'before'=> '</div>',
				'note'=> 'Dùng làm thẻ h1 ở trang chủ',
			),
			array( 
				'label'  => 'Mô tả trang chủ', 'field'    => 'general_description',
				'type'  => 'textarea', 'group' => 'general',
			),
			array( 'label'  => 'Từ khóa trang chủ', 'field'    => 'general_keyword', 'type'  => 'textarea', 'group' => 'general',),
			array(
				'label'     => 'Banner các chuyên mục', 'field'  => 'banner_img',
				'type'      => 'image', 'group' => 'general',
				'note'      => '',
			),
			array( 'field'  => 'general_end_group', 'type' => 'hidden', 'group' => 'general', 'after' => '</div></div>', 'before'=> ''),

			array( 'field'  => 'general_start_group', 'group' => 'general', 'after' => ' <div class="box col-md-12"><div class="box-content">', 'before'=> '', 'type' => 'hidden'),
			array(
				'label'     => 'Bật tắt chế độ khuyến mãi', 	'note' 		=> '',
				'field'     => 'general_public_km', 'value'     => '0',
				'type'      => 'switch', 'group' 	=> 'general',
				'options'   => 1,
				'after' => '<div class="col-md-12"><label>Bật tắt chế độ khuyến mãi</label><div class="group">', 'before'=> '</div></div><br />',
			),
			array(
				'label'     => 'Ngày bắt đầu khuyến mãi', 	'note' 		=> '',
				'field'     => 'general_day_sta', 'value'     => '',
				'type'      => 'date', 'group' 	=> 'general',
				'options'   => 1,
				'after' => '<div class="col-md-4"><label>Ngày bắt đầu khuyến mãi</label><div class="group">', 'before'=> '</div></div>',
			),array(
				'label'     => 'Ngày kết thúc khuyến mãi', 	'note' 		=> '',
				'field'     => 'general_day_end', 'value'     => '',
				'type'      => 'date', 'group' 	=> 'general',
				'options'   => 1,
				'after' => '<div class="col-md-4"><label>Ngày kết thúc khuyến mãi</label><div class="group">', 'before'=> '</div></div>',
			),
			array(
				'label'     => 'Màu nền toàn trang', 	'note' 		=> '',
				'field'     => 'general_background_color', 'value'     => '',
				'type'      => 'color', 'group' 	=> 'general',
				'options'   => 1,
				'after' => '<div class="col-md-4"><label>Màu nền toàn trang</label><div class="group">', 'before'=> '</div></div><br />',
			),
			array( 'field'  => 'general_end_group', 'type' => 'hidden', 'group' => 'general', 'after' => '</div></div>', 'before'=> ''),
		)
	);
	register_theme_option_field(
		array(
			/****************** HEADER MOBILE ******************/
			array( 'field'  => 'header_start_group', 'group' => 'header', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">HEADER MOBILE</h2></div><div class="box-content">', 'before'=> '', 'type' => 'hidden'),
			array( 	
				'field'     => 'header_mobile_bg_color', 			'type' 		=> 'color',
				'group'     => 'header',
				'after' => '<div class="col-md-6"><label>Màu nền header mobile </label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'header_mobile_color_search', 			'type' 		=> 'color',
				'group'     => 'header',
				'after' => '<div class="col-md-6"><label>Màu icon tìm kiếm mobile </label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'header_mobile_icon_menu', 			'type' 		=> 'color',
				'group'     => 'header',
				'after' => '<div class="col-md-6"><label>Màu icon menu </label><div class="group">', 'before'=> '</div></div>',
			),
			array(
				'field'    	=> 'header_mobile_icon_cart',
				'type'      => 'image', 		'group' 	=> 'header',
				'after' => '<div class="col-md-6"><label>Icon giỏ hàng</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 'field'  => 'header_end_group', 'type' => 'hidden', 'group' => 'header', 'after' => '</div></div>', 'before'=> ''),
		)
	);
	//map
	register_theme_option_field(
		array(
			array( 'field'  => 'contact_start_group', 'group' => 'map', 'after' => '<div class="box col-md-12"><div class="box-content">', 'before'=> '', 'type' => 'hidden'),
			array( 	
				"label" 		=> "Embed Map",
				"field" 		=> "maps_embed",
				"type" 		=> "textarea",
				"group"     => 'map',
			),
			array( 'field'  => 'contact_end_group', 'group' => 'map', 	'after' => '</div></div>', 'before'=> '', 'type' => 'hidden'),
		)
	);
	//footer
	register_theme_option_field(
		array(
			array( 'field'  => 'footer_start_group', 'type' => 'hidden', 'group' => 'footer', 'after' => ' <div class="box col-md-12"><div class="box-content">', 'before'=> ''),
			array( 	
				'field'     => 'footer_bg_color', 'type' => 'color',
				'group'     => 'footer',
				'after' => '<div class="col-md-3"><label>Màu nền footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'footer_bg_image', 			'type' 		=> 'image',
				'group'     => 'footer',
				'after' => '<div class="col-md-3"><label>Hình nền footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'footer_text_color', 			'type' 		=> 'color',
				'group'     => 'footer',
				'after' => '<div class="col-md-3"><label>Màu chữ footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'footer_header_color', 		'type' 		=> 'color',
				'group'     => 'footer',
				'after' => '<div class="col-md-3"><label>Màu tiêu đề Footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 'field'  => 'footer_end_group', 'type' => 'hidden', 'group' => 'footer', 'after' => '</div></div>', 'before'=> ''),
			array( 'field'  => 'footer_start_group', 'type' => 'hidden', 'group' => 'footer', 'after' => ' <div class="box col-md-12"><div class="header"><h4 class="title-header">Footer bottom</h4></div><div class="box-content">', 'before'=> ''),
			array(
				'field'     => 'footer_bottom_public', 'value'     => '1',
				'type'      => 'switch', 'group' 	=> 'footer',
				'options'   => 1,
				'after' => '<div class="col-md-12"><label>Hiển thị footer bottom</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'footer_bottom_bg_color', 'type' => 'color',
				'group'     => 'footer',
				'after' => '<div class="col-md-4"><label>Màu nền footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 	
				'field'     => 'footer_bottom_text_color', 			'type' 		=> 'color',
				'group'     => 'footer',
				'after' => '<div class="col-md-4"><label>Màu chữ footer</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 'field'  => 'footer_end_group', 'type' => 'hidden', 'group' => 'footer', 'after' => '</div></div>', 'before'=> ''),
		)
	);
	//Font Style
	$fonts2[] 	= 'Font mặc định';
	$fonts2 	= array_merge($fonts2, gets_theme_font());
	$font_style_option = [
		['field'  => 'font_start_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => ' <div class="box col-md-12"><div class="box-content">', 'before'=> ''],
		[
			'field'     => 'text_font', 'value' => 'left', 'type' => 'select',	'group' => 'fonts', 'options'   => $fonts,
			'note'		=> 'Fonts mặc định chữ cho các thẻ p, span,...',
			'after' 	=> '<div class="col-md-6"><label>Font Chữ</label>', 'before'=> '</div>',
		],
		[
			'field'     => 'header_font', 'type' => 'select', 'group' => 'fonts', 'options'   => $fonts,
			'note'		=> 'Fonts mặc định chữ cho các thẻ h1,h2,h3...',
			'after' => '<div class="col-md-6"><label>Font Chữ Tiêu Đề</label>', 'before'=> '</div>',
		],
		['field'  => 'font_end_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => '</div></div>', 'before'=> ''],
		['field'  => 'fonts_end_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => ' <div class="box col-md-12"><div class="header"><h4 class="title-header">FONT STYLE FOOTER</h4></div><div class="box-content">', 'before'=> ''],
		[
			'field'     => 'footer_text_font', 'value' => 'left', 'type' => 'select',	'group' => 'fonts', 'options'   => $fonts2,
			'note'		=> 'Fonts chữ cho các thẻ p, span,...ở footer',
			'after' 	=> '<div class="col-md-6"><label>Font Chữ</label>', 'before'=> '</div>',
		],
		[
			'field'     => 'footer_header_font', 'type' => 'select', 'group' => 'fonts', 'options'   => $fonts2,
			'note'		=> 'Fonts chữ cho các thẻ h1,h2,h3...ở footer',
			'after' => '<div class="col-md-6"><label>Font Chữ Tiêu Đề</label>', 'before'=> '</div>',
		],
		['field'  => 'fonts_end_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => '</div></div>', 'before'=> ''],
	];
	if(class_exists('woocommerce')) {
		$font_style_option_woocommerce = [
			['field'  => 'fonts_end_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => ' <div class="box col-md-12"><div class="header"><h4 class="title-header">FONT STYLE SẢN PHẨM</h4></div><div class="box-content">', 'before'=> ''],
			[
				'field'     => 'product_text_font', 'value' => 'left', 'type' => 'select',	'group' => 'fonts', 'options'   => $fonts2,
				'note'		=> 'Fonts chữ cho mô tả sản phẩm',
				'after' 	=> '<div class="col-md-6"><label>Font Chữ</label>', 'before'=> '</div>',
			],
			[
				'field'     => 'product_header_font', 'type' => 'select', 'group' => 'fonts', 'options'   => $fonts2,
				'note'		=> 'Fonts chữ cho tiêu đề sản phẩm',
				'after' => '<div class="col-md-6"><label>Font Chữ Tiêu Đề</label>', 'before'=> '</div>',
			],
			['field'  => 'fonts_end_group', 'type' => 'hidden', 'group' => 'fonts', 'after' => '</div></div>', 'before'=> ''],
		];
		$font_style_option 	= array_merge($font_style_option, $font_style_option_woocommerce);
	}
	register_theme_option_field($font_style_option);
}
add_action('theme_option_setup', 'store_theme_option', 20);