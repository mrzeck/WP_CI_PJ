<?php
class widget_item_style_1 extends widget {

    function __construct() {
        parent::__construct('widget_item_style_1', 'Item');
        //add style
        add_action('theme_custom_css', array( $this, 'css'), 10);
        //custom khi save widget
        add_filter('wg_before_widget_item_style_1_save', array( $this, 'save'));
    }

    function form( $left = array(), $right = array()) {

        $left[] = array('field' => 'item', 'type' => 'stote_wg_item', 'arg' => array('number' => 2));

        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');

        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
        
        $right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');

        $right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');

        parent::form($left, $right);
    }

    function widget($option) {
        
        if( !have_posts($option->item) ) return false;

        $box = $this->container_box('widget_box_item', $option);

        echo $box['before'];
        if($this->name != ''){?><div class="header-title"><h3 class="header"><?= $this->name;?></h3></div><?php } ?>

        <?php foreach ($option->item as $key => $item): ?>
        <?php
            if(class_exists('skd_multi_language')) {

                if($ci->language['current'] != $ci->language['default']) {

                    if(isset($item['title_'.$ci->language['current']])) $item['title'] = $item['title_'.$ci->language['current']];
                    
                    if(isset($item['description_'.$ci->language['current']])) $item['description'] = $item['description_'.$ci->language['current']];
                }
            }
        ?>
        <div class="item item<?php echo $key;?> wow <?php echo $item['animate'];?>">
            <a href="<?php echo $item['url'];?>" title="<?php echo $item['title'];?>">
                <div class="img"><?php get_img($item['image'], $item['title']);?></div>
                <div class="title">
                    <h3><?php echo $item['title'];?></h3>
                    <?php if( !empty($item['description']) ) {?>
                    <p class="description"><?php echo $item['description'];?></p>
                    <?php } ?>
                </div>
            </a>
        </div>
        <?php endforeach ?>

        <?php echo $box['after'];
    }

    function css() {
        include_once('css/style-item-1.css');
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

register_widget('widget_item_style_1');

function _form_stote_wg_item($param, $value = array()) {

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
        $input = array('field' => $param->field.'['.$i.'][image]',      'label' =>'Ảnh Trái', 'type' => 'image');
        $output .= _form($input, $value[$i]['image']);
        $output .= '</div>';

        $output .= '<div class="col-md-4">';
        $input  = array('field' => $param->field.'['.$i.'][title]',    'label' =>'Tiêu đề', 'type' => 'text');
        $output .= _form($input, $value[$i]['title']);
        $output .= '</div>';

        $output .= '<div class="col-md-4">';
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

                $output .= '<div class="col-md-4">';
                $input  = array('field' => $param->field.'['.$i.'][description_'.$lang_key.']',    'label' =>'Mô tả ('.$lang_val['label'].')', 'type' => 'text');
                $output .= _form($input, (isset($value[$i]['description_'.$lang_key]))?$value[$i]['description_'.$lang_key]:'');
                $output .= '</div>';
            }
        }

        $output .= '<div class="col-md-4">';
        $input  = array('field' => $param->field.'['.$i.'][url]',      'label' =>'Liên kết', 'type' => 'url');
        $output .= _form($input, $value[$i]['url']);
        $output .= '</div>';

        $output .= '<div class="col-md-4">';
        $input  = array('field' => $param->field.'['.$i.'][animate]',    'label' =>'Hiệu ứng hiển thị', 'type' => 'select', 'options' => animate_css_option());
        $output .= _form($input, $value[$i]['animate']);
        $output .= '</div>';

        $output .= '</div>';
    }

    return $output;
}