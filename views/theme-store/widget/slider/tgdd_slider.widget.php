<?php
class widget_tgdd_slider extends widget {
    function __construct() {
        parent::__construct('widget_tgdd_slider', 'Slider Style 2');
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function form( $left = array(), $right = array()) {
        $left[] = array('field' => 'gallery', 'label' =>'Nguồn slider', 'type' => 'ht_slider','after' => '<div class="col-md-6"><label>Nguồn slider</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'gallery2', 'label' =>'Nguồn slider (sales)', 'type' => 'ht_slider','after' => '<div class="col-md-6"><label>Nguồn slider (sales)</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        // $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
        $right[] = array('field' => 'title',      'label' =>'Tiêu đề bài viết  ', 'type' => 'text');
        $right[] = array('field' => 'post_cate_id',      'label' =>'Nguồn bài viết  ', 'type' => 'cate_post_categories');
        $left[] = array("field"=>"height_desktop", "label"=>"Chiều Cao (Desktop)",  "type"=>"number", "value"=>400,'after' => '<div class="col-md-3"><label>Chiều Cao (Desktop)</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array("field"=>"height_tablet", "label"=>"Chiều Cao (Tablet)",    "type"=>"number", "value"=>300,'after' => '<div class="col-md-3"><label>Chiều Cao (Tablet)</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array("field"=>"height_mobile", "label"=>"Chiều Cao (Mobile)",    "type"=>"number", "value"=>200,'after' => '<div class="col-md-3"><label>Chiều Cao (Mobile)</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array("field"=>"speed", "label"=>"Thời gian chạy slider", "type"=>"number", "value"=>3,'after' => '<div class="col-md-3"><label>Thời gian chạy slider</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_img1', 'label' =>'Banner ảnh 1', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner ảnh 1</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_img_sales_1', 'label' =>'Banner ảnh sales 1', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner sales ảnh 1</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_url1', 'label' =>'Banner url 1', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner url 1</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_url_sales_1', 'label' =>'Banner sales url 1', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner sales url 1</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_img2', 'label' =>'Banner ảnh 2', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner ảnh 2</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_img_sales_2', 'label' =>'Banner ảnh sales 2', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner sales ảnh 2</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_url2', 'label' =>'Banner url 2', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner url 2</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_url_sales_2', 'label' =>'Banner sales url 2', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner sales url 2</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_img3', 'label' =>'Banner ảnh 3', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner ảnh 3</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_img_sales_3', 'label' =>'Banner ảnh sales 3', 'type' => 'image','after' => '<div class="col-md-6"><label>Banner sales ảnh 3</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $left[] = array('field' => 'banner_url3', 'label' =>'Banner url 3', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner url 3</label><div class="group">', 'before'=> '</div></div>');
        $left[] = array('field' => 'banner_url_sales_3', 'label' =>'Banner sales url 3', 'type' => 'text','after' => '<div class="col-md-6"><label>Banner sales url 3</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');
        $right[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form($left, $right);
    }
    function widget($option) {
     $args = ['where'  => ['post_type' => 'post'], 'params' => ['orderby' => 'order, created desc', 'limit' => 5]];
     if($option->post_cate_id != 0) {
        $args['where_category'] = $option->post_cate_id;
    }
    $post = gets_post($args);
    if (get_option('general_public_km')!=1){ ?>
        <?php $items = get_gallery(array(), $option->gallery);?>
    <?php }else{ ?>
        <?php $items = get_gallery(array(), $option->gallery2)?>
        <?php if (count((array)$items) == 0): ?>
            <?php $items = get_gallery(array(), $option->gallery);?>
        <?php endif ?>
    <?php }
    $box = $this->container_box('widget_tgdd_slider', $option);
    echo $box['before'];
    if(have_posts($items)) {
        ?>
        <div style="position:relative">
            <div class="wg_slider_btn" id="wiget_slider_btn_<?= $this->id;?>">
                <div class="prev"><i class="fal fa-chevron-left"></i></div>
                <div class="next"><i class="fal fa-chevron-right"></i></div>
            </div>
            <div id="sync1" class="owl-carousel">
                <?php foreach ($items as $key => $item) {
                    $item->name = (isset($item->options['name']))?$item->options['name']:'';
                    $item->url  = (isset($item->options['url']))?$item->options['url']:'';
                    ?>
                    <div class="item">
                        <a aria-label='slide' href="<?php echo $item->url;?>">
                            <?php get_img($item->value, $item->name, array('style' => 'cursor:pointer'));?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="sync2" class="owl-carousel">
            <?php $total_item = count($items) - 1;?>
            <?php foreach ($items as $key => $item) {
                $item->name = (isset($item->options['name']))?$item->options['name']:'';
                $item->url  = (isset($item->options['url']))?$item->options['url']:'';
                ?>
                <div class="item">
                    <?php if($key != 0) {?><i class="arrowbarleft"></i><?php } ?>
                    <h3><?php echo $item->name;?></h3>
                    <?php if($key != $total_item) {?> <i class="arrowbar"></i><?php } ?>
                </div>
            <?php } ?>
        </div>
        <style>
            #sync1 .item { height: <?php echo $option->height_desktop;?>px; }
            @media(max-width:800px) {
                #sync1 .item { height: <?php echo $option->height_tablet;?>px; }
            }
            @media(max-width:500px) {
                #sync1 .item { height: <?php echo $option->height_mobile;?>px; }
            }
        </style>
        <script>
            $(function () {
                var sync1 = $("#sync1");
                var sync2 = $("#sync2");
                sync1 = $("#sync1").owlCarousel({
                    items:1,
                    loop :true,
                    autoplay:true,
                    autoplayTimeout:5000,
                    smartSpeed:1000,
                    autoplayHoverPause:true,
                    lazyLoad: true
                });
                sync1.on('changed.owl.carousel', function(event) {
                    var current = event.item.index - 2;
                    var index = event.item.index - 2;
                    var count = event.item.count;
                    if(index >= count) {
                        current = index - count;
                    }
                    $("#sync2")
                    .find(".owl-item")
                    .removeClass("synced")
                    .eq(current)
                    .addClass("synced");
                    if($("#sync2").data("owlCarousel") !== undefined) {
                        center(current);
                    }
                });
                $('#wiget_slider_btn_<?= $this->id;?> '+'.next').click(function() {
                    sync1.trigger('next.owl.carousel', [1000]);
                })
                $('#wiget_slider_btn_<?= $this->id;?> '+'.prev').click(function() {
                    sync1.trigger('prev.owl.carousel', [1000]);
                });
                sync2 = $("#sync2").owlCarousel({
                    items: 5,
                    responsive :{ 
                        0   :{ items:2 }, 
                        500 :{ items:3 },
                        1000:{ items:4 },
                        1200:{ items:5 },
                    },
                    loop :false,
                });
                $("#sync2")
                .find(".owl-item")
                .removeClass("synced")
                .eq(0)
                .addClass("synced");
                $("#sync2").on("click", ".owl-item", function (e) {
                    e.preventDefault();
                    var number = $(this).index();
                    sync1.trigger("to.owl.carousel", number);
                });
                function center(number) {
                    if(number == 0) {
                        sync2.trigger("to.owl.carousel", 0);
                    }
                    else if(number == 1) {
                        sync2.trigger("to.owl.carousel", 0);
                    }
                    else {
                        var sync2visible = $("#sync2 .owl-item").get();
                        if (number < sync2visible.length - 1) {
                            sync2.trigger("to.owl.carousel", number + 1);
                        }
                    }
                }
            });
        </script>
        <?php
    }
    echo $box['after'];
    $ci =& get_instance();
    $add_on_url = $ci->template->get_assets().'add-on/';
    ?>
    <link rel="stylesheet" type="text/css" href="<?=$add_on_url?>/slick-master/slick/slick.css">
    <div class="slider-right hidden-md hidden-sm">
        <div class="post-sidebar">
            <h2 class="header"><?= $option->title ?></h2>
            <div class="box-content">
                <?php foreach ($post as $key => $item) { ?>
                    <div class="item">
                        <h3><a href="<?= get_url($item->slug);?>" title="<?= $item->title;?>"><?= $item->title;?></a></h3>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="slider-banner">
            <?php if (get_option('general_public_km')!=1){ ?>
                <a href="<?php echo $option->banner_url1;?>"><?php get_img($option->banner_img1);?></a>
                <a href="<?php echo $option->banner_url2;?>"><?php get_img($option->banner_img2);?></a>
                <a href="<?php echo $option->banner_url3;?>"><?php get_img($option->banner_img3);?></a>
            <?php }else{ ?>
                <?php if ($option->banner_img1 !=null){?>
                    <a href="<?php echo $option->banner_url_sales_1;?>"><?php get_img($option->banner_img_sales_1);?></a>
                <?php }else{ ?>
                    <a href="<?php echo $option->banner_url1;?>"><?php get_img($option->banner_img1);?></a>
                <?php } ?>
                <?php if ($option->banner_img1 !=null){?>
                    <a href="<?php echo $option->banner_url_sales_2;?>"><?php get_img($option->banner_img_sales_2);?></a>
                <?php }else{ ?>
                    <a href="<?php echo $option->banner_url2;?>"><?php get_img($option->banner_img2);?></a>
                <?php } ?>
                <?php if ($option->banner_img1 !=null){?>
                    <a href="<?php echo $option->banner_url_sales_3;?>"><?php get_img($option->banner_img_sales_3);?></a>
                <?php }else{ ?>
                    <a href="<?php echo $option->banner_url3;?>"><?php get_img($option->banner_img3);?></a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <script src="<?=$add_on_url?>/slick-master/slick/slick.js" type="text/javascript" charset="utf-8"></script>
    <script>
        $(document).on('ready', function() {
            $(".slider-right .post-sidebar .box-content").slick({
               vertical: true,
               slidesToShow: 2,
               slidesToScroll: 1,
               autoplay: true,
               autoplaySpeed: 2000
           });
        });
    </script>
    <style>
        .widget_tgdd_slider{position:relative;float:left;width:70%;padding:10px;padding-bottom:0;}
        .slider-right{position:relative;float:left;width:30%;padding-top:10px;}
        .slider-right .post-sidebar{background-color:#fff;padding-bottom:0 ;}
        .slider-right h3{margin:0;font-size:14px;padding:10px ;border-bottom:1px solid #cdcdcd;}
        .slider-right h3 a{color:#333;}
        .slider-right h3:hover a{color:#4a90e2;}
        .slider-right h2.header{    padding:  0;font-size: 14px;color: #4a90e2;text-transform: uppercase;font-weight: 600;line-height: 40px;margin:0;border-bottom:1px solid #cdcdcd;padding:0 10px;text-align:left;}
        .slick-arrow{display: none !important;}
        @media(max-width:1199px){
            .widget_tgdd_slider{width:100%;}
        }
    </style>
    <?php
}
function css() {
    include_once('css/style-tgdd-slider.css');
}
}
if( class_exists('HTSlider') ) {
    register_widget('widget_tgdd_slider');
    if(!function_exists('_form_ht_slider')){
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
}
else {
    add_action('admin_notices', function() {
        echo notice('error', 'Để sử dụng widget <b>widget_tgdd_slider</b> vui lòng cài đặt plugin HT Slider');
    });
}