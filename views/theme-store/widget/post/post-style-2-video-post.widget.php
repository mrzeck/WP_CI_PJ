<?php
class widget_post_style_2 extends widget {
    function __construct() {
        parent::__construct('widget_post_style_2', 'Bài viết video - tin tức');
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }
    function form( $left = array(), $right = array()) {
        $left[] = array('field' => 'title_left', 'label' =>'Tiêu đề tin tức', 'type' => 'text');
        $right[] =  array('field' => 'title_right', 'label' =>'Tiêu đề video', 'type' => 'text');
        $left[] = array('field' => 'post_cate_id',      'label' =>'Nguồn bài viết ', 'type' => 'cate_post_categories');
        $right[] = array('field' => 'post_cate_id2',      'label' =>'Nguồn video ', 'type' => 'cate_video_categories');
        $right[] = array('field' => 'limit',             'label' =>'Số bài viết lấy ra',                 'type' => 'number', 'value' => 5, 'note'=>'Để 0 để lấy tất cả (không khuyên dùng)');
        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
// $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>13, 'args' => array('max' => 13));
// $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));
// $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));
        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
        $right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
        $right[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');
        parent::form($left, $right);
    }
    function widget($option) {
        $args = [
            'where'  => ['post_type' => 'post'],
            'params' => ['orderby' => 'order, created desc']
        ];
        if($option->limit > 0) $args['params']['limit'] = $option->limit;
        $slug='tin-tuc';
        if($option->post_cate_id != 0){
            $args['where_category'] = get_post_category( $option->post_cate_id );
            $slug=$args['where_category']->slug;
        } 
        $post = gets_post($args);

        $args2 = [
            'where'  => ['post_type' => 'video'],
            'params' => ['orderby' => 'order, created desc']
        ];
        if($option->limit > 0) $args2['params']['limit'] = 6;
        $slug2='videos';
        if($option->post_cate_id2 != 0) {
            $args2['where_category'] = get_post_category( $option->post_cate_id2 );
             $slug2= $args2['where_category']->slug;
        }
        $post2 = gets_post($args2);


        $box = $this->container_box('widget_box_post_style_2', $option);
        if (count((array)$post) !=0) {
            $post_main = $post[0]; unset($post[0]);
        }else{
            $post_main=array();
        }
//SHOW
        echo $box['before'];?>

        <div class="box-content">
            <div class="">
                <div class="post-video-left widget_box_post_video">
                    <?php if($option->title_right != ''){?>
                            <div class="header-title">
                                <h2 class="header"><?= $option->title_right;?></h2>
                                <div class="xemthem"> <a href="<?=$slug2 ?>">Xem tất cả >></a></div>
                            </div>
                     <?php } ?>
                    <div class="box-content">
                        <div class="video_top">
                            <div class="item">
                                <div class="img">   
                                    <?php $post2[0]->video  = get_metadata('post_video', $post2[0]->id, 'video_url', true); ?>
                                    <a href="<?=$post2[0]->video?>" data-fancybox="video" class="item-video">
                                        <?php if ($post2[0]->image != null){ ?>
                                            <?php get_img($post2[0]->image,$post2[0]->title,array('class'=>'img_maint')) ?>
                                        <?php }else{ ?>

                                            <img src="http://i3.ytimg.com/vi/<?= getYoutubeID($post2[0]->video);?>/maxresdefault.jpg" class="img_maint">
                                        <?php } ?>
                                        <?= get_img_template('video_icon.png','VIDEO ICON',array('style'=>'width:10%;','class'=>'button_play')) ?>
                                    </a>
                                </div>
                                <div class="title"> 
                                    <?php echo $post2[0]->title ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div id="Widget_post_video_<?= $this->id;?>" class=" owl-carousel">
                            <?php foreach ($post2 as $key => $val): ?>
                                <div class="item">
                                    <div class="img">   
                                        <?php $val->video  = get_metadata('post_video', $val->id, 'video_url', true); ?>
                                        <a href="<?=$val->video?>" data-fancybox="video" class="item-video">
                                            <?php if ($val->image != null){ ?>
                                                <?php get_img($val->image,$val->title,array('class'=>'img_maint')) ?>
                                            <?php }else{ ?>
                                                <img src="http://img.youtube.com/vi/<?= getYoutubeID($val->video);?>/mqdefault.jpg" class="img_maint">
                                            <?php } ?>
                                            <?= get_img_template('video_icon.png','VIDEO ICON',array('style'=>'width:10%;','class'=>'button_play')) ?>
                                        </a>
                                    </div>
                                    <div class="title" style="font-size:12px;"> 
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
                       
                        .widget_box_post_video  .video_top .item{position:relative;border-radius:10px;overflow:hidden;margin-bottom:10px;}

                        .widget_box_post_style_2 h2.header {    text-align: left;
                            font-size: 22px;
                            color: #cf1717;
                            line-height: 30px;
                            margin-bottom: 10px;
                            font-weight: 600;}
                        .widget_box_post_video .item .img img.img_maint{overflow:hidden;}
                        .widget_box_post_video{text-align:center;}
                        .widget_box_post_video .box-content{padding-top:0px;}
                        .widget_box_post_video .item{position: relative;}
                        .widget_box_post_video .item .img{position:relative;overflow:hidden;border:1px solid #cdcdcd;}
                        .widget_box_post_video .item .img a{position:relative;float:left;width:100%;height:100%;}
                        .widget_box_post_video .item .title{position:absolute;font-size: 14px;line-height:18px;padding:5px 10px ;text-align: left;overflow: hidden;background-color:rgba(0,0,0,0.7);text-align:center;color:#fff;text-align:center;bottom:0;width:100%;-webkit-transition: all .5s ease-in-out;
                            -moz-transition: all .5s ease-in-out;
                            -ms-transition: all .5s ease-in-out;
                            -o-transition: all .5s ease-in-out;
                            transition: all .5s ease-in-out;height:0;opacity:0;display:none;}
                            .widget_box_post_video .video_top .item .title{padding:10px;}
                            .widget_box_post_video .item:hover .title{height:auto;opacity:1;display:block}
                            .widget_box_post_video .item-video{position: relative;}
                            .widget_box_post_video .video_top  .item-video .button_play{position: absolute;z-index: 99;top:calc(50% - 15px);left:calc(50% - 20px);display:inherit;}
                            .widget_box_post_video  .item-video .button_play{position: absolute;z-index: 99;top:calc(50% - 5px);left:calc(50% - 5px);display:inherit;}
                            .owl-carousel .owl-item img.button_play{display:inherit;}

                            /* @media(max-width:991px){
                                .widget_box_post_video{padding: 0 !important;}
                                .widget_box_post_video .item .title{height:50px;}
                                .widget_box_post_video{margin:20px 0 !important;}

                            }*/
                            @media(max-width:767px){
                                .widget_box_post_style_2 .post-video-left,.widget_box_post_style_2 .post-video-right{width:100%;}
                                /* .widget_box_post_video h2.header{font-size:25px;}
                                .widget_box_post_video .item .title {font-size:14px}
                                .widget_box_post_video .item-video .button_play {top:calc(50px - 10px)} */
                            } 
                            @media(max-width:599px){
                                .widget_box_post_style_2 .post-video-right .box-content-right{display: block;}
                                .widget_box_post_style_2 .post-list,.widget_box_post_style_2 .post-main{width:100%;padding:0;}
                            } 


                        </style>
                        <script defer>
                            $(document).ready(function(){
                                var config = {
                                    items               :3,
                                    margin              :10,
                                    autoplayTimeout     :6000,
                                    smartSpeed          :3000,
                                    loop                :true, autoplay:true, autoplayHoverPause:true,
                                    responsive          :{ 0    :{ items:2 },  600  :{ items:3 },  992:{ items:3 } }
                                }
                                $("#Widget_post_video_<?= $this->id;?>").owlCarousel(config);
                            });
                        </script>
                </div>
                <div class="post-video-right">
                        <?php if($option->title_left != ''){?>
                            <div class="header-title">
                                <h2 class="header"><?= $option->title_left;?></h2>
                                <div class="xemthem"> <a href="<?=$slug2 ?>">Xem tất cả >></a></div>
                            </div>
                        <?php } ?>
                        <div class="box-content-right">
                            <div class="col-xs-6 post-main">

                                <?php if (!empty($post_main)): ?>
                                    <div class="item">
                                        <div class="img effect-hover-guong">  
                                            <div class="inner_img2">   
                                                <a href="<?= get_url($post_main->slug);?>" title="<?= $post_main->title;?>"><?= get_img($post_main->image,$post_main->title);?></a>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <h3><a href="<?= get_url($post_main->slug);?>" title="<?= $post_main->title;?>"><?= str_word_cut($post_main->title, 15 );?></a></h3>

                                            <div class="excerpt"><?= str_word_cut(removeHtmlTags($post_main->excerpt), 40);?></div>
                                            <div class="xemthem">
                                                 <a href="<?= get_url($post_main->slug);?>" >Đọc tiếp >></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="col-xs-6 post-list">
                                <?php foreach ($post as $key => $val): ?>
                                    <div class="item ">
                                        <div class="img">    
                                            <div class="inner_img2"> 
                                                <a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= get_img($val->image,$val->title, array());?></a>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <h3><a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= str_word_cut($val->title, 11 );?></a></h3>

                                            <div class="excerpt"><?= str_word_cut(removeHtmlTags($val->excerpt), 20);?></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <?php echo $box['after'];
        }
        function css() {
            include_once('css/style-post-video-2.css');
        }
    }
    register_widget('widget_post_style_2');