<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_form')) {

    function _form($param, $value = null) {

        $def = array( 
            'group' => '', 
            'field' => '', 
            'label' => '',
            'id'    => '', 
            'class' => '',   
            'note'  => '', 
            'after' => '',  
            'before' => '',
            'args'  => array(),
        );
        
        $param = array_merge($def, $param);

        $param = (object)$param;

        $attributes = '';
        if(have_posts($param->args)) {
            foreach ($param->args as $key => $val) {
                $attributes .= ' '.$key .' ="'.$val.'"';
            }
        }

        if($param->id == '' && $param->field != '') $param->id = $param->field;

        //xử lý file id
        $param->id = str_replace('[', '_', $param->id);
        $param->id = str_replace(']', '', $param->id);
        //note
        $param->note = '<p style="color:#999;margin:5px 0 5px 0;">'.$param->note.'</p>';

        //before and after
        if($param->after == '') {
            $param->after   = '<div class="col-md-12" id="box_'.$param->id.'"><label for="'.$param->field.'" class="control-label">'.$param->label.'</label><div class="group">';
            $param->before  = '</div></div>';
        }

        /* open field*/
        $output = $param->after;

        /* process field */
        $data = array(
            'type'  => $param->type,
            'name'  => $param->field,
            'id'    => $param->id,
            'value' => $value,
            'class' => 'form-control '.$param->class
        );

        if(is_string($value)) $data['value'] = set_value($param->field, $value);

        $data = array_merge($param->args, $data);

        //input popover
        if($param->type == 'popover' ) {
            if(!is_admin()) { $output .= notice('error', 'Input not support in frontend.');}
            else {

                if(empty($param->module))      $param->module   = 'post_categories';

                if(empty($param->key_type))    $param->key_type = 'post_categories';
                
                if(!isset($param->multiple) || $param->multiple == true)    $param->multiple = 'true'; else $param->multiple = 'false';

                ob_start();
                ?>
                <div class="col-md-12" id="box_<?php echo $param->id;?>">
                    <label for="<?php echo $param->id;?>" class="control-label"><?php echo $param->label;?></label>
                    <div class="group input-popover-group" data-name="<?php echo $param->field;?>" id="<?php echo $param->id;?>" data-module="<?php echo $param->module;?>" data-key-type="<?php echo $param->key_type;?>" data-multiple="<?php echo $param->multiple;?>">
                        <input type="text" class="form-control input-popover-search" placeholder="Tìm kiếm <?php echo $param->label;?>" />
                        <div class="popover-content">
                            <div class="popover__tooltip"></div>
                            <div class="popover__scroll">
                                <ul class="popover__ul">
                                    <?php foreach ($param->options as $key => $label) { ?>
                                    <li class="option option-<?php echo $key;?> <?php echo (have_posts($value) && in_array($key, $value) !== false)?'option--is-active':'';?>" data-key="<?php echo $key;?>">
                                        <a href="">
                                            <span class="icon"><i class="fal fa-check"></i></span>
                                            <span class="label-option"><?php echo $label;?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <div class="popover__loading text-center" style="display:none;"> Đang tải… </div>
                            </div>
                        </div>
                        <div class="collections">
                            <ul class="collection-list">
                                <?php foreach ($param->options as $key => $label) {
                                    if($param->multiple == 'true' && (!have_posts($value) || in_array($key, $value) === false)) continue;
                                    if($param->multiple == 'false' && ($key != $value)) continue;
                                ?>
                                <li class="collection-list__li_<?php echo $key;?>">
                                    <input type="checkbox" name="<?php echo $param->field;?><?php echo ($param->multiple == 'true') ?'[]':'';?>" value="<?php echo $key;?>" checked>
                                    <div class="collection-list__grid">
                                        <div class="collection-list__cell"><a href=""><?php echo $label;?></a></div>
                                        <div class="collection-list__cell">
                                            <button class="ui-button collection-list-delete" data-key="<?php echo $key;?>"> <i class="fal fa-times"></i> </button>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <style>
                            .input-popover-group, .page-content .box, .page-content .box .box-content {
                                overflow:inherit;
                            }
                        </style>
                <?php
                $output = ob_get_contents();
                ob_end_clean();
            }
        }

        //input checkbox
        else if($param->type == 'checkbox' ) {

            $output .= form_input_checkbox($param, $value);
        }
        //input checkbox
        else if($param->type == 'switch' ) {
            $output .= form_input_switch($param, $value);
        }
        //input checkbox
        else if($param->type == 'radio' ) {
            $output .= form_input_radio($param, $value);
        }
        //input color
        else if($param->type == 'color' ) {
            $data['type'] = 'text';
            $output .= form_input_color($data, $value);
        }
        else if($param->type == 'code' ) {
            $data['rows'] = 5;
            if($param->language == 'css')        $data['class'] .= ' code-css';
            if($param->language == 'javascript') $data['class'] .= ' code-javascript';

            $output .= '<div class="box-text-code-'.$data['id'].'">';
            $output .= form_textarea($data, $value);
            $output .= '</div>';
            if(isset($param->height)) {
                $output .= '<style> .box-text-code-'.$data['id'].' .CodeMirror { height: '.$param->height.'px }</style>';
            }
        }
        //các định dạng file
        else if($param->type == 'image' || $param->type == 'file' || $param->type == 'video') {
            $output .= form_input_file($data);
        }
        //select
        else if($param->type == 'select' && isset($param->options)) {

            if(count($param->options) == 0){

                // if( is_array($param) )
                    // $param->options = array( '0' => $param['label']);
                // else
                    // $param->options = array( '0' => $param->label);
            }

            $output .= form_dropdown($param->field, $param->options, set_value($param->field, $value), ' class="'.$data['class'].'" id="'.$data['id'].'" ');
        }
        else if($param->type == 'select2-multiple' && isset($param->options)) {

            $output .= form_multiselect($param->field.'[]', $param->options, set_value($param->field, $value), ' multiple class="select2-multiple '.$data['class'].'" id="'.$data['id'].'" ');
        }
        //textarea
        else if($param->type == 'textarea' ) {
            $data['rows'] = 5;
            $output .= form_textarea($data);
        }
        else if($param->type == 'wysiwyg') {
            $data['class'] .= ' tinymce';
            $output        .= form_textarea($data);
        }
        else if($param->type == 'wysiwyg-short') {
            $data['class'] .= ' tinymce-shortcut';
            $output        .= form_textarea($data);
        }
        //date
        else if($param->type == 'date') {
            $data['class'] .= ' datetime';
            $data['type'] = 'text';
            $output        .= form_input($data);
        }
        else if($param->type == 'datetime') {
            $data['class'] .= ' datepicker-here';
            $data['data-time-format'] = 'hh:ii aa';
            $data['data-timepicker'] = 'true';
            $data['data-language'] = 'vi';
            $output        .= form_input($data);
        }
        else if($param->type == 'range') {
            
            $data['class'] .= ' range-slider__range';

            $output .= '<div class="range-slider">';
            $output        .= form_input($data);
            $output .= '<span class="range-slider__value">0</span>';
            $output .= '</div>';
        }
        //post
        else if(substr($param->type,0,5) == 'post_') {
            $post_type = substr($param->type, 5);
            $output .= form_input_post($param,  $post_type, $value);
        }
        //categories
        else if(substr($param->type,0,5) == 'cate_') {
            $cate_type = substr($param->type, 5);
            $output .= form_input_cate($param,  $cate_type, $value);
        }
        //page
        else if($param->type == 'page') {
            $output .= form_input_page($param, $value);
        }
        else if($param->type == 'none') {
            $output .= '';
        }
        //#
        else {
            if(function_exists('_form_'.$param->type)) {
                $function = '_form_'.$param->type;
                $output .= $function($param, $value);
            }
            else {
                $output .= form_input($data);
            }
        }

        /* close field */
        $output .= $param->note.$param->before;

        return $output;
    }
}

if(!function_exists('show_form')) {

    function show_form($input, $options) {

        if(isset($options[$input['field']])) {

            $input['options'] = $options[$input['field']];

        };

        $input['value'] = isset($input['value'])?$input['value']:'';

        return _form($input,$input['value']);
    }
}

if(!function_exists('form_input_checkbox')) {

    function form_input_checkbox($param, $value = '') {

        if(isset($param->options) && is_array($param->options)) {

            $output = '';

            foreach ($param->options as $key => $data) {
                $output .= '<div class="checkbox">';
                $output .= '<label>';
                $output .= '<input type="checkbox" name="'.$param->field.'[]" id="'.$param->id.'" class="icheck '.$param->class.'" value="'.$key.'"';
                if(isset($_POST[$param->field]) && $_POST[$param->field] != null)
                {
                    $output .= set_checkbox($param->field,$key);
                }
                else if(is_array($value))
                    $output .= (in_array($key, $value))?'checked':'';
                else
                    $output .= ($key == $value)?'checked':'';
                $output .= '>';
                $output .= "&nbsp;&nbsp;".$data;
                $output .= '</label>';
                $output .= '</div>';
            }

        } else {

            if(!isset($param->options)) $param->options = $param->id;
            $output = '<div class="checkbox">';
            $output .= '<label>';
            $output .= '<input type="checkbox" name="'.$param->field.'" id="'.$param->id.'" class="icheck '.$param->class.'" value="'.$param->options.'"';
            $output .= ($param->options == $value)?'checked':'';
            $output .= '>';
            $output .= "&nbsp;&nbsp;".$param->label;
            $output .= '</label>';
            $output .= '</div>';

        }

        return $output;
    }
}

if(!function_exists('form_input_switch')) {

    function form_input_switch($param, $value = 0) {

        $output = '';

         ob_start();
        ?>
        <div class="toggleWrapper">
            <input name="<?php echo $param->field;?>" class="hidden switch-value <?php echo $param->class;?>" type="checkbox" value="<?php echo (($value == 1)?'1':'0');?>" checked/>
            <div class="button" id="button-17">
                <input class="switch checkbox" type="checkbox" value="<?php echo (($value == 1)?'1':'0');?>" <?php echo (($value == 1)?'checked':'');?>/>
                <div class="knobs"><span></span></div>
                <div class="layer"></div>
            </div>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}

if(!function_exists('form_input_radio')) {

    function form_input_radio($param, $value = '') {

        $attributes = '';

        if(have_posts($param->args)) {

            foreach ($param->args as $key => $value) {

                $attributes .= ' '.$key .' ="'.$value.'"';

            }
        }

        if(isset($param->options) && is_array($param->options)) {

            $output = '';

            foreach ($param->options as $key => $data) {

                $output .= '<div class="radio">';

                $output .= '<label>';

                $output .= '<input type="radio" name="'.$param->field.'[]" id="'.$param->id.'" class="icheck '.$param->class.'" value="'.$key.'"';

                if(is_array($value))
                    $output .= (in_array($key, $value))?'checked':'';
                else
                    $output .= ($key == $value)?'checked':'';

                $output .= '>';
                $output .= "&nbsp;&nbsp;".$data;

                $output .= '</label>';

                $output .= '</div>';
            }
        } else {
            if(!isset($param->options)) $param->options = $param->id;
            $output = '<div class="radio">';
            $output .= '<label>';
            $output .= '<input type="radio" name="'.$param->field.'" id="'.$param->id.'" class="icheck '.$param->class.'" value="'.$param->options.'"'.$attributes;
            $output .= ($param->options == $value)?'checked':'';
            $output .= '>';
            $output .= "&nbsp;&nbsp;".$param->label;
            $output .= '</label>';
            $output .= '</div>';
        }
        return $output;
    }
}

if(!function_exists('form_input_color')) {
    function form_input_color($data) {

        $class = 'item-color';

        if( isset($data['format']) && $data['format'] == 'hexa' ) $class = 'item-color-hexa';

        $output = '<div class="input-group color-group '.$class.'">';
        $output .= form_input($data);
        $output .= '<span class="input-group-addon"><i></i></span>';
        $output .= '</div>';
        return $output;
    }
}

if(!function_exists('form_input_file')) {
    function form_input_file($data) {
        if($data['type'] == 'image') { $type = 1; $data['type'] = 'images';}
        if($data['type'] == 'file') { $type = 2; $data['type'] = 'files';}
        if($data['type'] == 'video')  $type = 3;

        $url = base_url().PLUGIN.'/rpsfmng/filemanager/dialog.php';
        $url .= '?type='.$type.'&subfolder=&editor=mce_0&field_id='.$data['id'];
        $url .= '&callback=responsive_filemanager_callback';
        
        
        $output = '<div class="input-group image-group">';
        $output .= form_input($data);
        $output .= '<span class="input-group-addon iframe-btn" data-fancybox data-type="iframe" data-src="'.$url.'" data-id="'.$data['id'].'" href="'.$url.'"><i class="fas fa-upload"></i></span>';
        $output .= '</div>';
        return $output;
    }
}

/**
Các trường get dữ liệu từ database
*/
if(!function_exists('form_input_post')) {
    function form_input_post($param, $post_type, $value = '') {
        $output = '';
        if(isset_post_type($post_type)) {
            $ci       = &get_instance();
            $model    = get_model('post_model', 'backend_post');
            $post     = $model->gets_where(array('post_type' => $post_type));
            if(have_posts($post)) {
                $options = array();
                foreach ($post as $key => $val) {
                    $options[$val->id] = $val->title;
                }
                $output .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');
            }
        }
        return $output;
    }
}
if(!function_exists('form_input_cate')) {

    function form_input_cate($param, $cate_type, $value = '') {

        $output = '';

        if(isset_cate_type($cate_type)) {

            $ci       = &get_instance();

            $model    = get_model('post_categories_model', 'backend_post');

            $categories[0] = 'Tất cả';

            $categories  = gets_post_category( array('mutilevel' => $cate_type ) );

            if(have_posts($categories)) {

                $output .= form_dropdown($param->field, $categories, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');
            }
        }
        return $output;
    }
}
if(!function_exists('form_input_page')) {
    function form_input_page($param, $value = '') {
        $output = '';
        $ci       = &get_instance();
        $model    = get_model('page_model', 'backend_page');
        $page  = $model->gets_where(array('trash' => 0));
        if(have_posts($page)) {
            $options = array();
            foreach ($page as $key => $val) { $options[$val->id] = $val->title; }
            $output .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');
        }
        return $output;
    }
}

if(!function_exists('check_file_type')) {

    function get_file_type($path) {

        $extention['image']    = array('bmp', 'rle', 'dib', 'gif', 'jpg', 'jpeg', 'jpe', 'png', 'pns', 'tiff', 'svg' );
        $extention['video']    = array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" );
        $extention['audio']    = array( 'mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' );
        $extention['archives'] = array( 'zip', 'rar', 'gz', 'tar', 'iso', 'dmg' );

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $ext = strtolower($ext);

        if( !empty($ext) ) {

            foreach ( $extention as $type => $data ) {
                if(in_array($ext, $data) !== false ) return $type;
            }

            return 'file';
        }

        return get_url_web_video($path);
    }
}

if(!function_exists('check_url_video')) {

    function get_url_web_video($path) {

        $parsed = parse_url($path);

        $domain = (isset($parsed['scheme']))?$parsed['host']:$parsed['path'];

        if      (strpos($domain, 'youtube.') > 0)   return 'youtube';
        elseif  (strpos($domain, 'vimeo.') > 0)     return 'vimeo';
        else return 'unknown';
    }
}

if(!function_exists('process_file')) {
    function process_file($field) {
        return str_replace(base_url().SOURCE, '', $field);
    }
}

if(!function_exists('process_youtube')) {
    function process_youtube($url) {
        return getYoutubeID($url);
    }
}

if(!function_exists('process_data')) {
    function process_data($data = array(), $rules) {
        foreach ($rules as $val) {
            if(isset($val['type']) && $val['type'] == 'image' && isset($data[$val['field']])) $data[$val['field']] = process_file($data[$val['field']]);
            if(isset($val['type']) && $val['type'] == 'file' && isset($data[$val['field']])) $data[$val['field']] = process_file($data[$val['field']]);
            if(isset($val['type']) && $val['type'] == 'video') $data[$val['field']] = process_youtube($data[$val['field']]);
        }
        return $data;
    }
}


if(!function_exists('form_add_group')) {

    function form_add_group($groups = array(), $key, $name, $position = 0) {

        $form = array();

        if($position === 0) {

            $form = $groups;

            $form[$key] = $name;
        }
        else {
            foreach ($groups as $k => $value) {

                if($k == $position) $form[$key] = $name;

                $form[$k] = $value;
            }

            if(!isset($form[$key])) $form[$key] = $name;
        }

        return $form;
    }
}

if(!function_exists('form_remove_group')) {

    function form_remove_group($groups = array(), $form) {

        $groups = trim($groups,',');

        $groups = explode(',', $groups);

        foreach ($form as $key => $group) {

            if(have_posts($group)) {

                foreach ($group as $k => $value) {

                    if(in_array($k, $groups, true) !== false) {

                        unset($form[$key][$k]);
                    }
                }
            }
        }

        return $form;
    }
}

if(!function_exists('form_add_field')) {

    function form_add_field( $form = array(), $param = array(), $position = null) {

        if( have_posts($param) ) {

            $id = $param['field'];

            if( isset($param['lang']) ) {

                $id = str_replace( $param['lang'].'[', '', $id );

                $id = $param['lang'].'_'.str_replace(']', '', $id );
            }

            if( $position == 'title' || $position == 'name' || $position == 'excerpt' || $position == 'content' ) {

                if( isset($param['lang']) ) $position = $param['lang'].'_'.$position;
                else $position = 'vi_'.$position;
            }

            if( $position === null) {

                $form[ $id ] = $param; 

            }
            else {

                $temp = array();

                foreach ( $form as $k => $value) {

                    if( $k == $position ) {

                        $temp[ $id ] = $param;

                    }

                    $temp[$k] = $value;
                }

                $form = $temp;
            }
        }

        return $form;
    }
}

if(!function_exists('form_remove_field')) {

    function form_remove_field($fields = array(), $form) {

        $fields = trim($fields,',');

        $fields = explode(',', $fields);

        foreach ($form['field'] as $key => $value) {

            if(in_array($value['field'], $fields, true) !== false) unset($form['field'][$key]);
            else if(isset($value['lang'])) {

                $value['field'] = str_replace($value['lang'].'[', '', $value['field']);

                $value['field'] = str_replace(']', '', $value['field']);

                if(in_array($value['field'], $fields, true) !== false) unset($form['field'][$key]);
            }
        }
        return $form;
    }
}


if(!function_exists('form_rename_field')) {

    function form_rename_field($fields = array(), $form) {
        if(have_posts($fields)) {

            foreach ($form['field'] as $key => &$value) {

                if(!isset($fields) || !have_posts($fields)) break;
                foreach ($fields as $field => $label) {

                    if(isset($value['lang'])) {

                        $field = $value['lang'].'['.$field.']';

                    }
                    if($value['field'] == $field) {

                        $value['label'] = $label;

                        unset($fields[$field]);
                        
                        break;
                    }
                }
            }
        }
        return $form;
    }
}