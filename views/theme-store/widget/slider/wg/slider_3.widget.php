<?php
class widget_slider_3 extends widget {
    function __construct() {
        parent::__construct('widget_slider_3', 'Slider style 3');
        add_action('cle_enqueue_style', array( $this, 'style'), 10);
        add_action('cle_enqueue_script', array( $this, 'script'), 10);
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function widget($option) {
        if(class_exists('skd_multi_language')) {
            $ci =& get_instance();
            if($ci->language['current'] != $ci->language['default']) {
                if(isset($item['banner_url1_'.$ci->language['current']])) $item['banner_url1'] = $item['banner_url1_'.$ci->language['current']];
                if(isset($item['banner_url2_'.$ci->language['current']])) $item['banner_url2'] = $item['banner_url2_'.$ci->language['current']];
            }
        }
        $items = gets_gallery($option->gallery);
        $args = ['where'  => ['post_type' => 'post'], 'params' => ['orderby' => 'order, created desc', 'limit' => 1]];
        if($option->post_cate_id != 0) {
            $args['where_category'] = $option->post_cate_id;
        }
        $post = gets_post($args);
        $box = $this->container_box('tp-slider-box', $option);
        if(have_posts($items)) {
            echo $box['before'];
            ?>
            <div class="row">
                <div class="col-md-3 slider-vetical-menu hidden-xs hidden-sm">
                    <div class="vetical-menu__content">
                        <div class="vertical-menu-category">
                            <ul class="vertical-menu-category__nav">
                                <?php echo cle_nav_menu(array( 'theme_location' => 'main-vertical', 'walker' => 'store_nav_menu_vertical'));?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="padding:0;">
                    <div class="tp-banner-container" style="position: relative;">
                        <div class="tp-banner" > 
                            <ul>                      
                                <?php foreach ($items as $key => $item) { ht_slider_creat_item($item); } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 slider-left">
                    <div class="post-sidebar">
                        <?php foreach ($post as $key => $item) { ?>
                        <div class="item">
                            <div class="img">     
                                <a href="<?= get_url($item->slug);?>" title="<?= $item->title;?>"><?= get_img($item->image,$item->title, array());?></a>
                            </div>
                            <div class="title">
                                <h3><a href="<?= get_url($item->slug);?>" title="<?= $item->title;?>"><?= $item->title;?></a></h3>
                                <div class="excerpt"><?= date('d-m-Y', strtotime($item->created));?></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="slider-banner">
                        <a href="<?php echo $option->banner_url1;?>"><?php get_img($option->banner_img1);?></a>
                        <a href="<?php echo $option->banner_url2;?>"><?php get_img($option->banner_img2);?></a>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                var revapi;
                jQuery(document).ready(function() {
                    var revapi, revcf, w_width;
                    w_width = $('.tp-slider-box .tp-banner-container').width();
                    revcf = {
                        delay:<?= $option->speed*1000;?>,
                        startheight:700,
                        hideThumbs:10
                    }
                    $(document).ready(function() {
                        revcf.startheight = Math.ceil(w_width*<?php echo $option->ratio_height/$option->ratio_width;?>);
                        if( w_width < 1600 ) revcf.startheight += 100;
                        if( w_width < 850 ) revcf.startheight += 100;
                        if( w_width < 650 ) revcf.startheight += 50;
                        if( w_width < 450 ) revcf.startheight += 60;
                        revapi = jQuery('.tp-banner').revolution(revcf);
                    }); //ready
                }); //ready
            </script>
            <?php
            echo $box['after'];
        }
    }
    function form( $left = array(), $right = array()) {
        $left[] = array('field' => 'gallery', 'label' =>'Nguồn slider', 'type' => 'ht_slider');
        $left[] = array("field"=>"ratio_width", "label"=>"Tỉ lệ hiển thị (width)", "type"=>"number", "value"=>3, 'args' => ['step' => 0.1]);
        $left[] = array("field"=>"ratio_height", "label"=>"Tỉ lệ hiển thị (width)", "type"=>"number", "value"=>1, 'args' => ['step' => 0.1]);
        $left[] = array("field"=>"speed", "label"=>"Thời gian chạy slider", "type"=>"number", "value"=>3);
        $right[] = array('field' => 'post_cate_id', 'label' =>'Nguồn bài viết', 'type' => 'cate_post_categories');
        $right[] = array('field' => 'banner_img1', 'label' =>'Banner ảnh 1', 'type' => 'image');
        $right[] = array('field' => 'banner_url1', 'label' =>'Banner url 1', 'type' => 'text');
        $right[] = array('field' => 'banner_img2', 'label' =>'Banner ảnh 2', 'type' => 'image');
        $right[] = array('field' => 'banner_url2', 'label' =>'Banner url 2', 'type' => 'text');
        $left[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
        $left[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form($left, $right);
    }
    function script() {
        $ci =& get_instance();
        $path = $ci->plugin->get_path('ht-slider');
        cle_register_script('ht-slider',    $path."assets/src/js/jquery.themepunch.plugins.min.js");
        cle_register_script('ht-slider',    $path."assets/src/js/jquery.themepunch.revolution.min.js");
    }
    function style() {
        $ci =& get_instance();
        $path = $ci->plugin->get_path('ht-slider');
        cle_register_style('ht-slider',    $path."assets/src/css/settings.css", null, ['images' => base_url().$path.'assets/src']);
    }
    function css() {
        include_once('css/style-slider-3.css');
    }
}
if( class_exists('HTSlider') ) {
    register_widget('widget_slider_3');
    function _form_ht_slider($param, $value = array()) {
        $ci =& get_instance();
        $output = '';
        $model = get_model('gallery','backend');
        $model->settable('group');
        $gallery = $model->gets_where(array( 'object_type' => 'ht-slider' ));
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
}
else {
    add_action('admin_notices', function() {
        echo notice('error', 'Để sử dụng widget <b>widget_ht_slider</b> vui lòng cài đặt plugin HT Slider');
    });
}