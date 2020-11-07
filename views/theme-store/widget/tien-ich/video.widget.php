<?php
class Widget_post_video extends widget {
    function __construct() {
        parent::__construct('Widget_post_video', 'Danh sách video');
    }
    function form( $left = array(), $right = array()) {
        $left[] = array('field' => 'post_cate_id',      'label' =>'Nguồn video', 'type' => 'cate_video_categories');
        $right[] = array('field' => 'limit',             'label' =>'Số bài viết lấy ra',                 'type' => 'number', 'value' => 10, 'note'=>'Để 0 để lấy tất cả (không khuyên dùng)');
        $right[] = array('field' => 'post_per_row',       'label' =>'Số bài viết trên 1 hàng',            'type' => 'col', 'value' => 4, 'args' => array('min'=>1, 'max' => 5));
        $right[] = array('field' => 'post_per_row_tablet','label' =>'Số bài viết trên 1 hàng - tablet',   'type' => 'col', 'value' => 3, 'args' => array('min'=>1, 'max' => 5));
        $right[] = array('field' => 'post_per_row_mobile','label' =>'Số bài viết trên 1 hàng - mobile',   'type' => 'col', 'value' => 2, 'args' => array('min'=>1, 'max' => 5));
        $right[] = array('field' => 'time',             'label' =>'Thời gian tự động chạy', 'type' => 'number', 'value' => 2);
        $right[] = array('field' => 'speed',            'label' =>'Thời gian hoàn thành chạy', 'type' => 'number', 'value' => 3);
        $left[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
        $left[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
        $left[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
        $right[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form($left, $right);
    }
    function widget($option) {
        $model                = get_model('post');
        $where                = array('public' => 1, 'trash' => 0, 'post_type' => 'video');
        $param              = array('orderby' => 'order, created desc');
        if($option->limit > 0) $param['limit'] = $option->limit;
        //get dữ liệu
        if($option->post_cate_id == 0) {
            $post = $model->gets_where($where, $param);
            $slug='videos';
        }
        else {
            $category = $model->fget_categories_where('post_categories', array('id' => $option->post_cate_id));
            $slug=$category->slug;
            $post     = $model->fgets_object_category('post', $category, $where, $param);
        }
        //CSS
        $css_inline = '';
        $css2 = '';
        if($option->bg_color != '') {
            $css_inline .= 'background-color:'.$option->bg_color.';';
        }
        if($option->bg_image != '') {
            $css_inline .= 'background:url(\''.get_img_link($option->bg_image).'\');';
            $css_inline .= 'background-size:cover;';
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
        //LAYOUT
        $before = '<div class="widget_box_post_video " style="'.$css_inline.$css2.'">';
        $after  = '</div>';
        if($option->box == 'container') {
            $before = '<div class="widget_box_post_video" style="'.$css_inline.'">';
            $before .= '<div class="container" style="'.$css2.'">';
            $before .= '<div class="row">';
            $after = '</div>';
            $after .= '</div>';
            $after .= '</div>';
        }
        if($option->box == 'in-container') {
            $before = '<div class="container">';
            $before .= '<div class="widget_box_post_video" style="'.$css_inline.$css2.'">';
            $after = '</div>';
            $after .= '</div>';
        }
        //SHOW
        echo $before; 
        $css="";
        if(isset($option->box_size['padding'])) {
            $padding = $option->box_size['padding'];
            $css .= ($padding['bottom'] > 0)?'padding-bottom:'.(int)$padding['bottom'].'%;':'';
        }
        
        if($this->name != ''){?>
            <div class="header-title text-center" style="<?=$css?>">
                <h2 class="header"><a href="<?=$slug?>" ><span><?= $this->name;?></span></a></h2>
                <div class="text-center"></div><div class="hr" ></div>
            </div>
        <?php }?>
        <div class="box-content">
            
            <div id="Widget_post_video_<?= $this->id;?>" class="col-md-12 owl-carousel">
                <?php foreach ($post as $key => $val): ?>
                        <div class="item">
                            <div class="img">   
                                <?php $val->video  = get_metadata('post_video', $val->id, 'video_url', true); ?>
                                <a href="<?=$val->video?>" data-fancybox="video" class="item-video">
                                    <?php if ($val->image != null){ ?>
                                        <?php get_img($val->image,$val->title,array('class'=>'img_maint')) ?>
                                    <?php }else{ ?>
                                        <img src="https://img.youtube.com/vi/<?= getYoutubeID($val->video);?>/0.jpg" class="img_maint">
                                    <?php } ?>
                                    <?= get_img_template('video_icon.png','VIDEO ICON',array('style'=>'width:10%;','class'=>'button_play')) ?>
                                </a>
                            </div>
                            <div class="title"> 
                                <?php echo $val->title ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php endforeach ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <style>
        .widget_box_post_video h2.header {font-size:34px;text-transform: uppercase;font-weight:bold;color:#000;    position: relative;display: inline-table;padding:0 15px;}
        .widget_box_post_video  .header-title h2.header::before{    content: '';width: 70px;height: 3px;background-color: #252525;position: absolute;z-index: 9;left: -70px;bottom: 10px;border: 0;}
        .widget_box_post_video  .header-title h2.header::after{content: '';width: 70px;height: 3px;background-color: #252525;position: absolute;z-index: 9;right: -70px;bottom: 10px;border: 0;}
        .widget_box_post_video h2.header a{color:#000;}
            .widget_box_post_video .item .img img.img_maint{margin:-9% 0;overflow:hidden;}
            .widget_box_post_video{text-align:center;}
            .widget_box_post_video .box-content{padding-top:0px;}
            .widget_box_post_video .item{position: relative;}
            .widget_box_post_video .item .img{position:relative;overflow:hidden;border:1px solid #cdcdcd;}
            .widget_box_post_video .item .img a{position:relative;float:left;width:100%;height:100%;}
            .widget_box_post_video .item .title{font-weight:bold;width: 100%;color:#000;font-size: 16px;line-height:18px;padding:10px ;text-align: left;min-height:56px;overflow: hidden;-webkit-transition: all .5s ease;
               -moz-transition: all .5s ease;
                -ms-transition: all .5s ease;
                 -o-transition: all .5s ease;
                    transition: all .5s ease;background-color:#fff;position:relative;display:block;text-align:center;}
            .widget_box_post_video .item-video{position: relative;}
            .widget_box_post_video .item-video .button_play{position: absolute;z-index: 99;top:calc(50% - 15px);left:calc(50% - 20px);display:inherit;}
            .owl-carousel .owl-item img.button_play{display:inherit;}
           
            @media(max-width:991px){
            .widget_box_post_video{padding: 0 !important;}
            .widget_box_post_video .item .title{height:50px;}
            .widget_box_post_video{margin:20px 0 !important;}
                     
            }
            @media(max-width:767px){
                .widget_box_post_video h2.header{font-size:25px;}
                .widget_box_post_video .item .title {font-size:14px}
                .widget_box_post_video .item-video .button_play {top:calc(50px - 10px)}
            }
           
            
        </style>
        <script defer>
            $(document).ready(function(){
                var config = {
                    items               :<?= $option->post_per_row;?>,
                    margin              :20,
                    autoplayTimeout     :<?= $option->time*1000;?>,
                    smartSpeed          :<?= $option->speed*1000;?>,
                    loop                :true, autoplay:true, autoplayHoverPause:true,
                    responsive          :{ 0    :{ items:<?= $option->post_per_row_mobile;?> },  600  :{ items:<?= $option->post_per_row_tablet;?> },  1000:{ items:<?= $option->post_per_row;?> } }
                }
                $("#Widget_post_video_<?= $this->id;?>").owlCarousel(config);
            });
        </script>
        <?php ?>
        <?php
        echo $after;
    }
}
register_widget('Widget_post_video');