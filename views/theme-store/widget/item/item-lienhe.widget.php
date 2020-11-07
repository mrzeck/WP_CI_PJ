<?php
class widget_item_lienhe extends widget {

    function __construct() {
        parent::__construct('widget_item_lienhe', 'Item liên hệ');
        //add style
        add_action('theme_custom_css', array( $this, 'css'), 10);
        //custom khi save widget
        add_filter('wg_before_widget_item_lienhe_save', array( $this, 'save'));
    }

    function form( $left = array(), $right = array()) {

        $left[] = array('field' => 'item', 'type' => 'stote_wg_lienhe', 'arg' => array('number' => 4));

        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');

        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
        
        $right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');

        $right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');

        parent::form($left, $right);
    }

    function widget($option) {
        
        if( !have_posts($option->item) ) return false;

        $box = $this->container_box('widget_box_lienhe', $option);

        echo $box['before'];
         $css='';
        if(isset($option->box_size['padding'])) {
            $padding = $option->box_size['padding'];
         
            $css .= ($padding['bottom'] > 0)?'padding-bottom:'.(int)$padding['bottom'].'%;':'';
        }

        if($this->name != ''){?><div class="header-title" style="<?=$css ?>"><h2 class="header"><?php echo $this->name;?></h2></div><?php }?>
        <div class="box-content">
        <?php foreach ($option->item as $key => $item): ?>
        <?php
            if(class_exists('skd_multi_language')) {

                if($ci->language['current'] != $ci->language['default']) {

                    if(isset($item['title_'.$ci->language['current']])) $item['title'] = $item['title_'.$ci->language['current']];
                    
                    if(isset($item['description_'.$ci->language['current']])) $item['description'] = $item['description_'.$ci->language['current']];
                }
            }
        ?>
        <div class="item item<?php echo $key;?>">
            <a href="<?php echo $item['url'];?>" title="<?php echo $item['title'];?>">
                <div class="img"><?= $item['image']?></div>
                <div class="title">
                    <h3><?php echo $item['title'];?></h3>
                    <?php if( !empty($item['description']) ) {?>
                    <p class="description"><?php echo $item['description'];?></p>
                    <?php } ?>
                </div>
            </a>
        </div>
        <?php endforeach ?>
        </div>
        <?php echo $box['after'];
    }
    function container_box( $class = '', $option, $id = '' ) {
        //CSS
        $css_inline = '';
        $css2 = '';
        if(!empty($option->bg_color)) {
            $css_inline .= 'background-color:'.$option->bg_color.';';
        }
        //CSS
        if(!empty($option->bg_image)) {
            $css_inline .= 'background:url(\''.get_img_link($option->bg_image).'\');';
            $css_inline .= 'background-size:100% 100%; background-position: center center;';
        }
        //margin
        if(isset($option->box_size['margin'])) {
            $margin = $option->box_size['margin'];
            $css2 .= ($margin['top'] > 0)?'margin-top:'.(int)$margin['top'].'%;':'';
            $css2 .= ($margin['left'] > 0)?'margin-left:'.(int)$margin['left'].'%;':'';
            $css2 .= ($margin['right'] > 0)?'margin-right:'.(int)$margin['right'].'%;':'';
            $css2 .= ($margin['bottom'] > 0)?'margin-bottom:'.(int)$margin['bottom'].'%;':'';
        }
        if(isset($option->box_size['padding'])) {
            $padding = $option->box_size['padding'];
            $css2 .= ($padding['top'] > 0)?'padding-top:'.(int)$padding['top'].'%;':'';
            $css2 .= ($padding['left'] > 0)?'padding-left:'.(int)$padding['left'].'%;':'';
            $css2 .= ($padding['right'] > 0)?'padding-right:'.(int)$padding['right'].'%;':'';
            $css2 .= ($padding['bottom'] > 0)?'padding-bottom:'.(int)$padding['bottom'].'%;':'';
        }
        $class_row = '';
        if(isset($option->col_xs)) $class_row .= ($option->col_xs != 0)?'col-xs-'.$option->col_xs:'';
        if(isset($option->col_sm)) $class_row .= " ".(($option->col_sm != 0)?'col-sm-'.$option->col_sm:'');
        if(isset($option->col_md)) $class_row .= " ".(($option->col_md != 0)?'col-md-'.$option->col_md:'');
        //LAYOUT
        $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css_inline.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
        $before  .= '<div class="inner_content" style="position:relative;'.$css2.'">';
        $after  = '</div>';
        $after  .= '</div>';
        if(isset($option->box)) {
            if($option->box == 'container') {
                $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.'" style="'.$css_inline.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
                $before  .= '<div class="inner_content" style="position:relative;'.$css2.'">';
                $before .= '<div class="container">';
                $after = '</div>';
                $after .= '</div>';
                $after .= '</div>';
            }
            if($option->box == 'in-container') {
                $before = '<div class="container js_widget_builder js_'.$this->key.'_'.$this->id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
                $before .= '<div class="'.$class.'" style="'.$css_inline.'">';
                $before  .= '<div class="inner_content" style="position:relative;'.$css2.'">';
                $after = '</div>';
                $after .= '</div>';
                $after .= '</div>';
            }
        }
        if($class_row != '') {
            $before = '<div class="js_widget_builder js_'.$this->key.'_'.$this->id.' '.$class.' '.$class_row.'" style="'.$css_inline.'" id="'.$id.'" data-id="'.$this->id.'" data-key="'.$this->key.'">';
            $after  = '</div>';
        }
        return array( 'before' => $before, 'after' => $after );
    }
    function css() {
        include_once('css/style-lienhe.css');
    }

    function save( $data ) {
        if( isset($data['options']['item'])) {
            foreach ($data['options']['item'] as $key => &$item) {
                $item['image'] = process_file($item['image']);
            }
        }
        return $data;
    }
}

register_widget('widget_item_lienhe');

function _form_stote_wg_lienhe($param, $value = array()) {

    $ci =& get_instance();

    if( !have_posts($value) ) $value = array();

    $value_default = array( 'image' => '', 'title' => '', 'url' => '', 'animate' => '', 'description' => '' );

    //Số Lượng item
    $number = (isset($param->arg['number'])) ? (int)$param->arg['number'] : 1;

    $output = '';

    for ( $i = 0; $i <= $number; $i++ ) {

        if(!isset($value[$i]) || !is_array($value[$i])) $value[$i] = array();

        $value[$i] = array_merge($value_default, $value[$i]);

        $output .= '<label for="name" class="control-label">Item '.($i+1).'</label>';

        $output .= '<div class="stote_wg_item">';

        $output .= '<div class="col-md-4">';
        $input = array('field' => $param->field.'['.$i.'][image]',      'label' =>'icon', 'type' => 'text');
        $output .= _form($input, $value[$i]['image']);
        $output .= '</div>';

        $output .= '<div class="col-md-4">';
        $input  = array('field' => $param->field.'['.$i.'][title]',    'label' =>'Tiêu đề', 'type' => 'text');
        $output .= _form($input, $value[$i]['title']);
        $output .= '</div>';


        $output .= '<div class="col-md-4">';
        $input  = array('field' => $param->field.'['.$i.'][url]',      'label' =>'Liên kết', 'type' => 'url');
        $output .= _form($input, $value[$i]['url']);
        $output .= '</div>';


        $output .= '<div class="col-md-8">';
        $input  = array('field' => $param->field.'['.$i.'][description]',    'label' =>'Mô tả', 'type' => 'text');
        $output .= _form($input, $value[$i]['description']);
        $output .= '</div>';

        if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            foreach ($ci->language['language_list'] as $lang_key => $lang_val) {

                if($lang_key == $ci->language['default']) continue;

                $output .= '<div class="col-md-4">';
                $input  = array('field' => $param->field.'['.$i.'][title_'.$lang_key.']',    'label' =>'Tiêu đề ('.$lang_val['label'].')', 'type' => 'text');
                $output .= _form($input, (isset($value[$i]['title_'.$lang_key]))?$value[$i]['title_'.$lang_key]:'');
                $output .= '</div>';

                $output .= '<div class="col-md-8>';
                $input  = array('field' => $param->field.'['.$i.'][description_'.$lang_key.']',    'label' =>'Mô tả ('.$lang_val['label'].')', 'type' => 'wysiwyg-short');
                $output .= _form($input, (isset($value[$i]['description_'.$lang_key]))?$value[$i]['description_'.$lang_key]:'');
                $output .= '</div>';
            }
        }
        $output .= '</div>';
    }

    return $output;
}