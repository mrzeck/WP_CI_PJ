<?php
class widget_partner extends widget {
    function __construct() {
        parent::__construct('widget_partner', 'Đối tác');
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function form( $left = array(), $right = array()) {
         $right[] = array('field' => 'about_img',    'label' =>'icon', 'type' => 'image');
        $right[] = array('field' => 'about_img_bg',    'label' =>'background icon', 'type' => 'image');
        $left[] = array('field' => 'gallery', 'label' =>'Nguồn đối tác', 'type' => 'gallery');
        $left[] = array('field' => 'limit',         'label' =>'Số item sử dụng', 'type' => 'number', 'value' => 20);
        $right[] = array('field' => 'items',       'label' =>'Số bài viết trên 1 hàng',            'type' => 'col', 'value' => 4, 'args' => array('min'=>1, 'max' => 12));
        $right[] = array('field' => 'items_tablet','label' =>'Số bài viết trên 1 hàng - tablet',   'type' => 'col', 'value' => 3, 'args' => array('min'=>1, 'max' => 12));
        $right[] = array('field' => 'items_mobile','label' =>'Số bài viết trên 1 hàng - mobile',   'type' => 'col', 'value' => 2, 'args' => array('min'=>1, 'max' => 12));
        $right[] = array('field' => 'time',         'label' =>'Thời gian chạy (s)', 'type' => 'number', 'value' => 2);
        $right[] = array('field' => 'speed',         'label' =>'Tốc độ chạy (s)', 'type' => 'number', 'value' => 3);
        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
        $right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form($left, $right);
    }

    function widget($option) {
        $args  = [
            'where'  => ['group_id' => $option->gallery],
        ];
        if($option->limit > 0 ) $args['params'] = array('limit' => $option->limit);
        $gItem = gets_gallery($args);
        $box = $this->container_box('widget_box_partner', $option);
        $css='';
        if(isset($option->box_size['padding'])) {
            $padding = $option->box_size['padding'];
         
            $css .= ($padding['bottom'] > 0)?'padding-bottom:'.(int)$padding['bottom'].'%;':'';
        }
        echo $box['before'];?>

        <?php if($this->name != ''){?>
            <div class="header-title" style="<?=$css ?>">
                    <h2 class="header" ><?= $this->name;?></h2>
                </div>
        <?php } ?>

        <div class="partner">
            <div class="partner_arrow" id="partner_arrow_<?= $this->id;?>">
                <div class="arrow prev"><i class="fal fa-chevron-right"></i></div>
                <div class="arrow next"><i class="fal fa-chevron-left"></i></div>
            </div>
            <div id="owl-partner-<?= $this->id;?>" class="owl-carousel">
                <?php foreach ($gItem as $key => $val): ?>
                     <?php 
                $title = get_metadata( 'gallerys', $val->id, 'title', true ); 
                ?>
                    <div class="item" style="width:100%;padding:5px;">
                        <img src="<?php echo get_img_link($val->value);?>" alt="đối tác - thương hiệu" />
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    <style>
            .widget_box_partner{border:1px solid #cdcdcd;}
            .widget_box_partner .item{width:100%;padding:5px;height:100px;position:relative; }
            .widget_box_partner .item img{ position: absolute;top: 50%;transform: translateY(-50%); width:initial;}
        </style>
        <script defer> 
            $(document).ready(function(){
                var ol = $("#owl-partner-<?= $this->id;?>").owlCarousel({
                    items               :<?php echo $option->items;?>,
                    margin              :10,
                    loop                :true,
                    autoplay            :true,
                    autoplayTimeout     :<?php echo $option->time*1000;?>,
                    autoplayHoverPause  :true,
                    smartSpeed          :<?php echo $option->speed*1000;?>,
                    responsive          : {0   :{ items:<?php echo $option->items_mobile;?> }, 400   :{ items:<?php echo $option->items_mobile;?> }, 700   :{ items:<?php echo $option->items_tablet;?> }, 1000:{ items:<?php echo $option->items;?> }}
                });

                $('#partner_arrow_<?= $this->id;?> '+'.next').click(function() {
                    ol.trigger('next.owl.carousel', [1000]);
                })

                $('#partner_arrow_<?= $this->id;?> '+' .prev').click(function() {
                    ol.trigger('prev.owl.carousel', [1000]);
                });
            }); 
        </script>

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
                $before  .= '<div class="before_slider"><div class="inner_before_slider"><img src="'.get_img_link($option->about_img).'" alt=""></div></div>';
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
    function css() { include_once('css/style-partner.css'); }
}



register_widget('widget_partner');

