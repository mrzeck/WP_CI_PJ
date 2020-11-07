<?php
class widget_adv_image extends widget {
    function __construct() {
        parent::__construct('widget_adv_image', 'Banner Quảng cáo');
        add_filter('wg_before_widget_adv_image_save', array( $this, 'save'));
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function form($left = array() , $right = array()) {
        $left[] = array('field' => 'adv_img_item', 'type' => 'stote_wg_adv_img_item', 'arg' => array('number' => 2));
        $right[] = array('field' => 'tile', 'label' =>'Tỉ lệ chiều cao / chiều rộng', 'type' => 'text');
        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#fff');
        $right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
        $right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form( $left, $right );
    }
    function widget($option) {
        ob_start();
        ?>
        
        <div class="row2">
        <?php $i=0 ?>
        <?php foreach ($option->adv_img_item as $item): ?>
            <?php if(!empty($item['image'])) {?>
                <div class="item  effect-hover-guong effect-hover-zoom ">
                    <a href="<?php echo $item['url'];?>" title="<?php echo $item['title'];?>">
                        <div class="img">
                            <?php if (get_option('general_public_km')!=1){ ?> 
                                <?php get_img($item['image'], $item['title']);?>
                            <?php }else{ ?>
                                <?php if ($item['image'] !=null){?>
                                    <?php get_img($item['image_sales'], $item['title']);?>
                                <?php }else{ ?>
                                    <?php get_img($item['image'], $item['title']);?>
                                <?php } ?>

                            <?php } ?>
                                
                        </div>
                    </a>
                </div>
                <?php $i+=1 ?>
            <?php } ?>
        <?php endforeach;?>
        </div>
        <style>
            .widget_adv_image.widget_adv_image_<?=$this->id?>  a{padding-top: <?=$option->tile * 100?>%;}
            .widget_adv_image.widget_adv_image_<?=$this->id?> .item{width:<?=100/$i?>%;}
           
        </style>
        <?php if ($i%2 !=0 && $i > 1): ?>
            <style>
                 @media(max-width:767px){
                .widget_adv_image.widget_adv_image_<?=$this->id?> .item{width:50%;}
                .widget_adv_image.widget_adv_image_<?=$this->id?> .item:last-child{margin-left:25%;}
            }
            </style>
        <?php endif ?>
        <?php $content = ob_get_contents();
        ob_end_clean();
        $box = $this->container_box('widget_adv_image widget_adv_image_'.$this->id, $option);
        echo $box['before'];
        echo $content;
        echo $box['after'];
    }
    function save( $data ) {
        if( isset($data['options']['item'])) {
            foreach ($data['options']['item'] as $key => &$item) {
                $item['image'] = process_file($item['image']);
            }
        }
        return $data;
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
            $css_inline .= 'background-size:cover;background-attachment: fixed;background-repeat: no-repeat; background-position: center center;';
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
    function css() { include_once('css/adv-style.css'); }
}
register_widget('widget_adv_image');
function _form_stote_wg_adv_img_item($param, $value = array()) {
    $ci =& get_instance();
    if( !have_posts($value) ) $value = array();
    $value_default = array( 'image' => '', 'title' => '', 'url' => '','image_sales'=>'' );
    //Số Lượng item
    $number = (isset($param->arg['number'])) ? (int)$param->arg['number'] : 1;
    $output = '';
    for ( $i = 0; $i <= $number; $i++ ) {
        if(!isset($value[$i]) || !is_array($value[$i])) $value[$i] = array();
        $value[$i] = array_merge($value_default, $value[$i]);
        $output .= '<div class="stote_wg_item" style="margin-bottom:10px;" id="adv_image_item_'.$i.'">';
        $output .= '<div class="col-md-6">';
        $input = array('field' => $param->field.'['.$i.'][image]',      'label' =>'Ảnh Trái', 'type' => 'image');
        $output .= _form($input, $value[$i]['image']);
        $output .= '</div>';

        $output .= '<div class="col-md-6 clearfix">';
        $input = array('field' => $param->field.'['.$i.'][image_sales]',      'label' =>'Ảnh Trái (sales)', 'type' => 'image');
        $output .= _form($input, $value[$i]['image_sales']);
        $output .= '</div>';

        $output .= '<div class="col-md-6">';
        $input  = array('field' => $param->field.'['.$i.'][title]',    'label' =>'Tiêu đề', 'type' => 'text');
        $output .= _form($input, $value[$i]['title']);
        $output .= '</div>';
        $output .= '<div class="col-md-6">';
        $input  = array('field' => $param->field.'['.$i.'][url]',      'label' =>'Liên kết', 'type' => 'url');
        $output .= _form($input, $value[$i]['url']);
        $output .= '</div>';
        $output .= '</div>';
    }
    return $output;
}