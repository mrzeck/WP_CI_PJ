<?php
/**
 * [_form_wg_box thực thi trường dữ liệu chọn các kiểu wg]
 * @param  [type] [wg_box]
 * @param  array  $value [description]
 * @return [type]        [description]
 */
function _form_wg_box($param, $value = array()) {

    $ci =& get_instance();

    $output = '';

    $data = array(
    	array( 'value' => 'full',          'img' => 'box1.png'),
    	array( 'value' => 'container',     'img' => 'box2.png'),
    	array( 'value' => 'in-container',  'img' => 'box3.png'),
    );
    
    $output .= '<div class="row">';

    foreach ( $data as $key => $val ) {
    	$output .= '<div class="wg-box-item col-md-4 '.(($val['value'] == $value)?'active':'').'" data-value="'.$val['value'].'">';
	    $output .= get_img_fontend($val['img'],'',array('class'=>'img-responsive'),true);
	    $output .= '</div>';
    }

    $input 	= array('field' => $param->field,  'label' =>'', 'type' => 'hidden');
    $output .= _form($input, $value);

    $output .= '</div>';

    return $output;
}

/**
 * [_form_MENU get dữ liệu menu]
 * @param  [type] [wg_box]
 * @param  array  $value [description]
 * @return [type]        [description]
 */
function _form_menu($param, $value = array()) {

    $ci =& get_instance();

    $output = '';

    $model = get_model('menu','backend_theme');

    $model->settable('group');

    $menus = $model->gets_where(array( 'object_type' => 'menu' ));

    $options = array();

    foreach ($menus as $key => $val) {
        $options[$val->id] = $val->name;
    }

    $input  = array('field' => $param->field,  'label' => $param->label, 'type' => 'select', 'options' => $options, 'before' => '<div>', 'after' => '</div>' );

    $output .= _form($input, $value);

    return $output;
}


/**
 * [_form_MENU get dữ liệu gallery image]
 * @param  [type] [wg_box]
 * @param  array  $value [description]
 * @return [type]        [description]
 */
function _form_gallery($param, $value = array()) {

    $ci =& get_instance();

    $output = '';

    $model = get_model('gallery','backend');

    $model->settable('group');

    $gallery = $model->gets_where(array( 'object_type' => 'gallery' ));

    if( have_posts($gallery)) {

        $options = array();

        foreach ($gallery as $key => $val) {
            $options[$val->id] = $val->name;
        }

        $input  = array('field' => $param->field,  'label' => $param->label, 'type' => 'select', 'options' => $options, 'before' => '<div>', 'after' => '</div>' );

        $output .= _form($input, $value);
    }

    else $output = notice('error', 'bạn chưa tạo gallery nào.');

    return $output;
}


function animate_css_option() {

    $option['fadeIn']             = 'fadeIn';
    $option['fadeInDown']         = 'fadeInDown';
    $option['fadeInDownBig']      = 'fadeInDownBig';
    $option['fadeInLeft']         = 'fadeInLeft';
    $option['fadeInLeftBig']      = 'fadeInLeftBig';
    $option['fadeInRight']        = 'fadeInRight';
    $option['fadeInRightBig']     = 'fadeInRightBig';
    $option['fadeInUp']           = 'fadeInUp';
    $option['fadeInUpBig']        = 'fadeInUpBig';
    $option['fadeOut']            = 'fadeOut';
    $option['fadeOutDown']        = 'fadeOutDown';
    $option['fadeOutDownBig']     = 'fadeOutDownBig';
    $option['fadeOutLeft']        = 'fadeOutLeft';
    $option['fadeOutLeftBig']     = 'fadeOutLeftBig';
    $option['fadeOutRight']       = 'fadeOutRight';
    $option['fadeOutRightBig']    = 'fadeOutRightBig';
    $option['fadeOutUp']          = 'fadeOutUp';
    $option['fadeOutUpBig']       = 'fadeOutUpBig';

    $option['bounce']             = 'bounce';
    $option['flash']              = 'flash';
    $option['pulse']              = 'pulse';
    $option['rubberBand']         = 'rubberBand';
    $option['shake']              = 'shake';
    $option['headShake']          = 'headShake';
    $option['swing']              = 'swing';
    $option['tada']               = 'tada';
    $option['jello']              = 'jello';
    $option['bounceIn']           = 'bounceIn';
    $option['bounceInDown']       = 'bounceInDown';
    $option['bounceInLeft']       = 'bounceInLeft';
    $option['bounceInRight']      = 'bounceInRight';
    $option['bounceInUp']         = 'bounceInUp';
    $option['bounceOut']          = 'bounceOut';
    $option['bounceOutDown']      = 'bounceOutDown';
    $option['bounceOutLeft']      = 'bounceOutLeft';
    $option['bounceOutRight']     = 'bounceOutRight';
    $option['bounceOutUp']        = 'bounceOutUp';
   
    $option['flipInX']            = 'flipInX';
    $option['flipInY']            = 'flipInY';
    $option['flipOutX']           = 'flipOutX';
    $option['flipOutY']           = 'flipOutY';
    $option['lightSpeedIn']       = 'lightSpeedIn';
    $option['lightSpeedOut']      = 'lightSpeedOut';
    $option['rotateIn']           = 'rotateIn';
    $option['rotateInDownLeft']   = 'rotateInDownLeft';
    $option['rotateInDownRight']  = 'rotateInDownRight';
    $option['rotateInUpLeft']     = 'rotateInUpLeft';
    $option['rotateInUpRight']    = 'rotateInUpRight';
    $option['rotateOut']          = 'rotateOut';
    $option['rotateOutDownLeft']  = 'rotateOutDownLeft';
    $option['rotateOutDownRight'] = 'rotateOutDownRight';
    $option['rotateOutUpLeft']    = 'rotateOutUpLeft';
    $option['rotateOutUpRight']   = 'rotateOutUpRight';
    $option['hinge']              = 'hinge';
    $option['jackInTheBox']       = 'jackInTheBox';
    $option['rollIn']             = 'rollIn';
    $option['rollOut']            = 'rollOut';
    $option['zoomIn']             = 'zoomIn';
    $option['zoomInDown']         = 'zoomInDown';
    $option['zoomInLeft']         = 'zoomInLeft';
    $option['zoomInRight']        = 'zoomInRight';
    $option['zoomInUp']           = 'zoomInUp';
    $option['zoomOut']            = 'zoomOut';
    $option['zoomOutDown']        = 'zoomOutDown';
    $option['zoomOutLeft']        = 'zoomOutLeft';
    $option['zoomOutRight']       = 'zoomOutRight';
    $option['zoomOutUp']          = 'zoomOutUp';
    $option['slideInDown']        = 'slideInDown';
    $option['slideInLeft']        = 'slideInLeft';
    $option['slideInRight']       = 'slideInRight';
    $option['slideInUp']          = 'slideInUp';
    $option['slideOutDown']       = 'slideOutDown';
    $option['slideOutLeft']       = 'slideOutLeft';
    $option['slideOutRight']      = 'slideOutRight';
    $option['slideOutUp']         = 'slideOutUp';

    return $option;
}


/**
 * [_form_MENU tạo trình quản lý margin, padding, border]
 * @param  [type] [wg_box]
 * @param  array  $value [description]
 * @return [type]        [description]
 */
function _form_size_box($param, $value = array()) {

    $value_default = array( 
        'margin' => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
        'border' => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
        'padding' => array('top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0),
    );

    if(!is_array($value)) $value = array();

    $value = array_merge($value_default, $value);

    ?>
    <div class="col-md-12">
    <div class="inp_size_box">
        <div class="inp_size_box_margin box-property">
            <div class="title">margin</div>
            <input type="text" name="<?php echo $param->field.'[margin][top]';?>" value="<?php echo $value['margin']['top'];?>" class="margin-top">
            <input type="text" name="<?php echo $param->field.'[margin][left]';?>" value="<?php echo $value['margin']['left'];?>" class="margin-left">
            <input type="text" name="<?php echo $param->field.'[margin][right]';?>" value="<?php echo $value['margin']['right'];?>" class="margin-right">
            <input type="text" name="<?php echo $param->field.'[margin][bottom]';?>" value="<?php echo $value['margin']['bottom'];?>" class="margin-bottom">
        </div>
        <div class="inp_size_box_padding box-property">
            <div class="title">padding</div>
            <input type="text" name="<?php echo $param->field.'[padding][top]';?>" value="<?php echo $value['padding']['top'];?>" class="padding-top">
            <input type="text" name="<?php echo $param->field.'[padding][left]';?>" value="<?php echo $value['padding']['left'];?>" class="padding-left">
            <input type="text" name="<?php echo $param->field.'[padding][right]';?>" value="<?php echo $value['padding']['right'];?>" class="padding-right">
            <input type="text" name="<?php echo $param->field.'[padding][bottom]';?>" value="<?php echo $value['padding']['bottom'];?>" class="padding-bottom">
        </div>
        <div class="inp_size_box_content box-property"><div class="title">content</div></div>
    </div>
    </div>
    <div class="clearfix"> </div>

    <style type="text/css">
        .inp_size_box {
            position: relative;
            width: 100%; height: 200px;
        }

        [class*='inp_size_box_'] {
            position: absolute;
        }
        .inp_size_box_margin {
            width:100%; height:200px;
            border:1px dashed #000;
            background:#f8cb9c;
        }

        .inp_size_box_padding {
            left:35px; top:35px;
            width:calc(100% - 70px); height:130px;
            border:1px dashed #ccc;
            background-color:#c2ddb6;
        }
        .inp_size_box_content {
            left:70px; top:70px;
            width:calc(100% - 136px);
            height:60px; line-height:60px;
            border:1px solid #000;
            background-color:#9fc4e7;
            text-align:center;
        }
        .inp_size_box_content .title {
            float:none;width:100%; text-align:center!important;
        }
        [class*='inp_size_box_'] > input { position: absolute; width:25px; font-size:10px; text-align: center; }
        [class*='inp_size_box_'] > .title { position: absolute; text-align: left; }
        [class*='inp_size_box_'] > input[class*='-top'] { top:5px; left:50%; margin-left:-13px; }
        [class*='inp_size_box_'] > input[class*='-left'] { left:2px; top:50%; margin-top:-13px;}
        [class*='inp_size_box_'] > input[class*='-right'] { right:2px; top:50%; margin-top:-13px;}
        [class*='inp_size_box_'] > input[class*='-bottom'] { bottom:5px; left:50%; margin-left:-13px;}

        .inp_size_box:hover .box-property { background-color: #fff; }
        .inp_size_box_margin:hover { background:#f8cb9c!important; }
        .inp_size_box_border:hover { background:#feedbb!important; }
        .inp_size_box_padding:hover { background:#c2ddb6!important; }
        .inp_size_box_content:hover { background:#9fc4e7!important; }
    </style>
    <?php
}


/**
 * [_form_MENU tạo trình quản lý margin, padding, border]
 * @param  [type] [wg_box]
 * @param  array  $value [description]
 * @return [type]        [description]
 */
function _form_col($param, $value = array())
{
    $args = array('min'=> 1, 'max' => 12);

    if( isset($param->args)) $args = array_merge($args, $param->args);

    $output = '';
    $output .='<div class="input-cols">';
    $output .='    <div class="input-col-wrap input-col-'.$value.'">';
    for ( $i = $args['min'];  $i <= $args['max'] ; $i++ ) { 
        $output .='<div class="col-item" data-col="'.$i.'">'.$i.'</div>';
    }
    $output .='    </div>';
    $output .='    <input type="range" name="'.$param->field.'" value="'.$value.'" min="'.$args['min'].'" max="'.$args['max'].'" id="'.$param->field.'" class="form-control ">';
    $output .='</div>';
    $output .='<div class="clearfix"></div>';
    return $output;
}


/**
 * [gets_theme_font lấy danh sách font cần sử dụng]
 * @singe  3.0.0
 */
function gets_theme_font()
{
    $font_family = get_font_family();

    $fonts = [];

    foreach ($font_family as $key => $font) {
        $fonts[$font['key']] = $font['label'];
    }
    
    return $fonts;
}
/**
 * [get_theme_social lấy danh sách mạng xã hội cần sử dụng]
 * @singe  3.0.0
 */
if( !function_exists('get_theme_social') ) {

    function get_theme_social() {

        $socials = [
            array(	
                'label' 	=> 'Facebook Fanpage',
                'note'		=> 'Đường dẫn facebook fanpage',
                'field' 	=> 'social_facebook',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Twitter',
                'note'		=> 'Đường dẫn Twitter',
                'field' 	=> 'social_twitter',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Youtube',
                'note'		=> 'Đường dẫn kênh youtube',
                'field' 	=> 'social_youtube',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Instagram',
                'note'		=> 'Đường dẫn Instagram',
                'field' 	=> 'social_instagram',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Pinterest',
                'note'		=> 'Đường dẫn Pinterest',
                'field' 	=> 'social_pinterest',
                'type' 		=> 'url',
                'group'     => 'social',
            ),
            array( 	
                'label' 	=> 'Zalo',
                'note'		=> 'Số điện thoại liên kết Zalo',
                'field' 	=> 'social_zalo',
                'type' 		=> 'text',
                'group'     => 'social',
            )
        ];

        return $socials;
    }
}
/**
 * [get_theme_social lấy danh sách input seo cần sử dụng]
 * @singe  3.0.0
 */
if( !function_exists('get_theme_seo_input') ) {

    function get_theme_seo_input() {

        $seo_input = array(
			array(
                'label' => 'Favicon', 'field' => 'seo_favicon', 'type' => 'image',
            ),
		    array( 	
		        'label' => 'Google Master key', 'field' => 'seo_google_masterkey', 'type' => 'text',
		    ),
			array( 	
		        'label' => 'Script Header', 'field' => 'header_script', 'type' => 'code', 'language'  => 'javascript', 'note' => 'Chèn script vào header (google analytic code, google master code..)',
		    ),
			array(
		        'label' => 'Script Footer', 'field' => 'footer_script', 'type' => 'code', 'language' => 'javascript', 'note' => 'Chèn script vào footer (chat code, thống kê code..)',
		    ),
		);

        return apply_filters('get_theme_seo_input', $seo_input);
    }
}