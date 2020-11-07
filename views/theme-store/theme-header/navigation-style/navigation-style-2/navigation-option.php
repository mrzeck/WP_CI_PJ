<?php
function store_theme_navigation_option() {
    $fonts 	= ['Font mặc định'];
    $fonts 	= array_merge($fonts, gets_theme_font());
    register_theme_option_field(
        array(
            ['field' => 'navigation_start_group', 'group' => 'nav', 'after' => '<div class="box col-md-12"><div class="box-content">', 'before'=> '', 'type' => 'none'],
            array(
                'field' 	=> 'nav_position', 'value' 	=> 'left', 'type' => 'select',
                'options' 	=> array( 'navbar-left'      	=> 'Canh Trái', 'navbar-center'    	=> 'Canh Giửa', 'navbar-right'    	=> 'Canh Phải' ),
                'group' => 'nav', 'after' => '<div class="col-md-6"><label>Vị Trí Menu</label>', 'before'=> '</div>',
            ),
            [ 	
                'group' => 'nav', 'field' => 'nav_padding', 'value' 	=> '10px', 'type' => 'text',
                'after' => '<div class="col-md-6"><label>Navigation pagdding</label>', 'before'=> '</div>',
            ],
            array( 	
                'field' => 'nav_bg_color', 'type'  => 'color', 'group' => 'nav', 
                'after' => '<div class="col-md-6"><label>Màu nền Navigation</label>', 'before'=> '</div>',
            ),
            array( 	
                'field' => 'nav_bg_color_hover', 'group' => 'nav', 'type' => 'color',
                'after' => '<div class="col-md-6"><label>Màu nền Navigation khi trỏ chuột</label>', 'before'=> '</div>',
            ),
            array( 	
                'note'  => 'Chọn màu chữ thanh menu.', 'field' => 'nav_text_color', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu chử Navigation</label>', 'before'=> '</div>',
            ),
            array( 	
                'note'  => 'Chọn màu chữ khi đưa con trỏ chuột vào.', 'field' => 'nav_text_color_hover', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu chử Navigation khi trỏ chuột</label>', 'before'=> '</div>',
            ),
            array( 'field'  => 'navigation_end_group', 'type' => 'none', 'group' => 'nav', 'after' => '</div></div>', 'before'=> ''),
            array( 'field'  => 'navigation_start_group', 'group' => 'nav', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">Navigation con</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'),
            array( 	
                'field' => 'navsub_bg_color', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu nền Navigation con</label>', 'before'=> '</div>',
            ),
            array( 	
                'note'  => 'Chọn màu nền thanh menu khi đưa con trỏ chuột vào.',
                'field' => 'navsub_bg_color_hover', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu nền Navigation con khi trỏ chuột</label>', 'before'=> '</div>',
            ),
            array( 	
                'field' => 'navsub_text_color', 'note'  => 'Chọn màu chữ thanh menu.', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu chử Navigation con</label>', 'before'=> '</div>',
            ),
            array( 	
                'field' => 'navsub_text_color_hover', 'note'  => 'Chọn màu chữ khi đưa con trỏ chuột vào.', 'type'  => 'color', 'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu chử Navigation con khi trỏ chuột</label>', 'before'=> '</div>',
            ),
            array( 'field'  => 'navigation_end_group', 'type' => 'none', 'group' => 'nav', 'after' => '</div></div>', 'before'=> ''),
        )
	);
	register_theme_option_field(
		array(
			array( 'field'  => 'navigation_start_group', 'group' => 'nav', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">NAVIGATION DỌC - TIÊU ĐỀ</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'),
			array(
				'field'  => 'nav_vh_text', 'type'  => 'text', 'group' => 'nav', 
				'after' => '<div class="col-md-4"><label>Tiêu đề</label><div class="group">', 'before'=> '</div></div>',
			),
			array(
				'field' => 'nav_vh_text_color', 'type'  => 'color', 'group' => 'nav',
				'after' => '<div class="col-md-4"><label>Màu chử tiêu đề</label>', 'before'=> '</div>',
			),
			array(
				'field'  => 'nav_vh_bg', 'type'  => 'color', 'group' => 'nav', 
				'after' => '<div class="col-md-4"><label>Màu nền tiêu đề</label><div class="group">', 'before'=> '</div></div>',
			),
			array( 'field'  => 'navigation_end_group', 'type' => 'none', 'group' => 'nav', 'after' => '</div></div>', 'before'=> ''),
		)
	);
	register_theme_option_field(
		array(
			array( 'field'  => 'navigation_start_group', 'group' => 'nav', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">NAVIGATION DỌC</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'),
			array( 	
				'field' => 'nav_v_bg', 'type'  => 'color', 'group' => 'nav',
				'after' => '<div class="col-md-6"><label>Màu nền</label>', 'before'=> '</div>',
		    ),
		    array( 	
				'field' => 'nav_v_bg_hover', 'type'  => 'color', 'group' => 'nav',
				'after' => '<div class="col-md-6"><label>Màu nền khi trỏ chuột</label>', 'before'=> '</div>',
		    ),
		    array( 	
				'field' => 'nav_v_text_color', 'type'  => 'color', 'group' => 'nav',
				'after' => '<div class="col-md-6"><label>Màu chữ</label>', 'before'=> '</div>',
		    ),
		    array( 	
				'field' => 'nav_v_text_color_hover','type'  => 'color', 'group' => 'nav',
				'after' => '<div class="col-md-6"><label>Màu chử khi trỏ chuột</label>', 'before'=> '</div>',
		    ),
			array( 'field'  => 'navigation_end_group', 'type' => 'none', 'group' => 'nav', 'after' => '</div></div>', 'before'=> ''),
		)
	);
    register_theme_option_field(
        array(
            ['group' => 'fonts', 'field' => 'navigation_start_group', 'after' => '<div class="box col-md-12"><div class="header"><h4 class="title-header">NAVIGATION</h4></div><div class="box-content">', 'before'=> '', 'type' => 'none'],
            [ 	'group' => 'fonts', 'field' => 'nav_font', 'type' => 'select', 'options' => $fonts, 
                'after' => '<div class="col-md-6"><label>Font Chữ</label>', 'before'=> '</div>',
            ],
            [	'group' => 'fonts', 'field' 	=> 'nav_font_weight', 'type' => 'select',
                'options' 	=> array('100' => 100, '300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'),
                'after' => '<div class="col-md-6"><label>Font Weight (in đậm)</label>', 'before'=> '</div>',
            ],
            [	'group' => 'fonts', 'field' => 'nav_font_size', 'type' => 'select', 'value' 	=> '18px',
                'options' 	=> array('10' => '10px', '11' => '11px', '12' => '12px', '13' => '13px', '14' => '14px', '15' => '15px', '16' => '16px', '17' => '17px', '18' => '18px', '19' => '19px', '20' => '20px', '21' => '21px', '22' => '22px', '23' => '23px', '24' => '24px', '25' => '25px', '26' => '26px', '27' => '27px', '28' => '28px'),
                'after' => '<div class="col-md-12"><label>Font Size</label>', 'before'=> '</div>',
            ],
            ['group' => 'fonts', 'field'  => 'navigation_end_group', 'type' => 'none', 'after' => '</div></div>', 'before'=> ''],
        )
    );

    //NAVIGATION SEARCH
    register_theme_option_field(
        array(
            ['field'  => 'header_start_group', 'group' => 'nav', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">NAVIGATION TÌM KIẾM</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'],
            
            array(
                'field'     => 'nav_search_border_color', 'value'   => '#000',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-3"><label>Màu viền</label>', 'before'=> '</div>',
            ),
            array(
                'field'     => 'nav_search_bg_color', 'value'   => '#fff',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-3"><label>Màu nền</label>', 'before'=> '</div>',
            ),
            array(
                'field'     => 'nav_search_btn_bg_color', 'value'   => '#000',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-3"><label>Màu nền nút search</label>', 'before'=> '</div>',
            ),
            array(
                'field'     => 'nav_search_btn_txt_color', 'value'  => '#fff',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-3"><label>Màu icon nút search</label>', 'before'=> '</div>',
            ),
            array(
                'field'     => 'nav_search_bg_color_select', 'value'   => '',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu nền chọn danh mục</label>', 'before'=> '</div>',
            ),
            array(
                'field'     => 'nav_search_text_color_select', 'value'  => '',  'type' => 'color',  'group' => 'nav',
                'after' => '<div class="col-md-6"><label>Màu chữ chọn danh mục</label>', 'before'=> '</div>',
            ),

            ['field'  => 'nav_end_group', 'type' => 'none', 'group' => 'nav', 'after' => '</div></div>', 'before'=> ''],
        )
    );
}
add_action('theme_option_setup', 'store_theme_navigation_option', 100);