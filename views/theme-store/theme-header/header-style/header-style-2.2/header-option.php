<?php
$fonts  = ['Font mặc định'];
$fonts  = array_merge($fonts, gets_theme_font());
//HEADER
register_theme_option_field(
    [
        ['field'  => 'header_start_group', 'group' => 'header', 'after' => ' <div class="box col-md-12"><div class="box-content">', 'before'=> '', 'type' => 'none'],
        array(
            'field'  => 'logo_header', 'type'      => 'image', 'group' => 'header', 'note'      => 'Upload logo ở đây. Upload logo lớn hơn 2x kích cở để hiển thị rỏ nét.',
            'after' => '<div class="col-md-4"><label>Logo website</label><div class="group">', 'before'=> '</div></div>',
        ),
        array(
            'field'     => 'logo_position', 'value' => 'left', 'note'=> 'Chọn vị trí của logo.', 'type' => 'select',        'group'     => 'header',
            'after'     => '<div class="col-md-4"><label>Vị trí logo</label>', 'before'=> '</div>',
            'options'   => ['left' => 'Canh Trái', 'right' => 'Canh Phải', 'center' => 'Canh Giửa'],
        ),
        array(
            'field' => 'logo_height', 'value' => 'left', 'note' => 'Chiều cao logo (px)', 'type' => 'number', 'group' => 'header',
            'after' => '<div class="col-md-4"><label>Chiều cao logo</label>', 'before'=> '</div>',
        ),
        array(  
            'label'     => 'Header background color',   'note'      => 'Chọn màu nền header.',
            'field'     => 'header_bg_color',           'type'      => 'color',
            'group'     => 'header',
            'after' => '<div class="clearfix"></div><div class="col-md-4"><label>Header background color</label><div class="group">', 'before'=> '</div></div>',
        ),
        array(  
            'label'     => 'Header background image',   'note'      => 'Chọn ảnh nền header.',
            'field'     => 'header_bg_image',           'type'      => 'image',
            'group'     => 'header',
            'after' => '<div class="col-md-4"><label>Header background image</label><div class="group">', 'before'=> '</div></div>',
        ),
        array(  
            'label'     => 'Lặp ảnh nền background image',  'note'      => 'Khi ảnh nền không phù hợp kích thước header.',
            'field'     => 'header_position_image',         'value'     => 'cover',
            'type'      => 'select',                        'group'     => 'header',
            'after' => '<div class="col-md-4"><label>Lặp ảnh nền background image</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>',
            'options'   => array(
                'repeat-x'  => 'Lặp lại - chiều ngang',
                'repeat-y'  => 'Lặp lại - chiều dọc',
                'no-repeat' => 'Không lặp lại',
            ),
        ),
        ['field'  => 'header_end_group', 'group' => 'header', 'after' => '</div></div>', 'before'=> '', 'type' => 'none'],
    ]
);
//HEADER SLOGAN
register_theme_option_field(
    array(
        ['field'  => 'header_start_group', 'group' => 'header', 'after' => ' <div class="box col-md-12"><div class="header"><h2 class="title-header">HEADER SLOGAN - HOTLINE</h2></div><div class="box-content">', 'before'=> '', 'type' => 'none'],
        array(     
            'label'     => 'Icon cart',   'note'      => 'Chọn ảnh giỏ hàng .',
            'field'     => 'header_icon_cart',           'type'      => 'image',
            'group'     => 'header',
            'after' => '<div class="col-md-6"><label>Icon giỏ hàng</label><div class="group">', 'before'=> '</div></div>',
        ),
        array(     
            'label'     => 'Icon Hotline',   'note'      => 'Chọn ảnh nền header.',
            'field'     => 'header_icon_hotline',           'type'      => 'image',
            'group'     => 'header',
            'after' => '<div class="col-md-6"><label>Icon Hotline</label><div class="group">', 'before'=> '</div></div>',
        ),
         array(     
            'label'     => 'Link menu chia sẻ kinh nghiệm',  
            'field'     => 'header_menu_chiase_link',           'type'      => 'text',
            'group'     => 'header',
            'after' => '<div class="col-md-12"><label>Link menu chia sẻ kinh nghiệm</label><div class="group">', 'before'=> '</div></div>',
        ),
       

        ['field'  => 'header_end_group', 'type' => 'none', 'group' => 'header', 'after' => '</div></div>', 'before'=> ''],
    )
);