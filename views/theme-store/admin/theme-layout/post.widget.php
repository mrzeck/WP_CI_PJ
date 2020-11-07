<?php
class widget_post extends widget {

    function __construct() {
        
        parent::__construct('widget_post', 'Bài viết');

        add_action('theme_custom_css', array( $this, 'css'), 10);
    }

    function form( $left = array(), $right = array()) {

        $left[] = array('field' => 'post_cate_id',      'label' =>'Nguồn bài viết ', 'type' => 'cate_post_categories');

        $left[] = array('field' => 'post_display_type',  'label' =>'Kiểu Hiển Thị', 'type' => 'select', 'options' => array('Slider', 'Danh sách'));

        $right[] = array('field' => 'post_per_row',       'label' =>'Số bài viết trên 1 hàng - desktop',            'type' => 'col', 'value' => 4, 'args' => array('min'=>1, 'max' => 5));
       
        $right[] = array('field' => 'post_per_row_tablet','label' =>'Số bài viết trên 1 hàng - tablet',   'type' => 'col', 'value' => 3, 'args' => array('min'=>1, 'max' => 5));
        
        $right[] = array('field' => 'post_per_row_mobile','label' =>'Số bài viết trên 1 hàng - mobile',   'type' => 'col', 'value' => 2, 'args' => array('min'=>1, 'max' => 5));

        $right[] = array('field' => 'limit',             'label' =>'Số bài viết lấy ra',                 'type' => 'number', 'value' => 10, 'note'=>'Để 0 để lấy tất cả (không khuyên dùng)');

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

        if($option->post_cate_id != 0) {

            $args['where_category'] = get_post_category( $option->post_cate_id );
        }

        $post = gets_post($args);

        $box = $this->container_box('widget_post', $option);

        //SHOW
        echo $box['before'];
        if($this->name != ''){?><div class="header-title"><h3 class="header"><?= $this->name;?></h3></div><?php }

        if($option->post_display_type == 0) $this->display_slider($post, $option, $args);
        
        if($option->post_display_type == 1) $this->display_list($post, $option, $args);

        echo $box['after'];
    }

    function display_slider($post, $option, $args) {
        ?>
        <div class="row widget_box_post">
            <div class="widget_post__arrow" id="widget_post_arrow_<?= $this->id;?>">
                <div class="arrow prev"><i class="fal fa-chevron-right"></i></div>
                <div class="arrow next"><i class="fal fa-chevron-left"></i></div>
            </div>
            <div id="widget_post_<?= $this->id;?>" class="col-md-12 owl-carousel">
                <?php foreach ($post as $key => $val): ?>
                <div class="item">
                    <div class="img">     
                        <a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= get_img($val->image,$val->title, array());?></a>
                    </div>
                    <div class="title">
                        <h3><a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= $val->title;?></a></h3>
                        <div class="excerpt"><?= str_word_cut(removeHtmlTags($val->excerpt), 20);?></div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
        <script defer>
            $(document).ready(function(){
                var config = {
                    items               :<?= $option->post_per_row;?>,
                    margin              :10,
                    autoplayTimeout     :2000,
                    smartSpeed          :3000,
                    loop                :true, autoplay:true, autoplayHoverPause:true,
                    responsive          :{ 0    :{ items:<?= $option->post_per_row_mobile;?> },  600  :{ items:<?= $option->post_per_row_tablet;?> },  1000:{ items:<?= $option->post_per_row;?> } }
                }

                var ol = $("#widget_post_<?= $this->id;?>").owlCarousel(config);

                $('#widget_post_arrow_<?= $this->id;?> '+'.prev').click(function() {
                    ol.trigger('next.owl.carousel', [1000]);
                })
                $('#widget_post_arrow_<?= $this->id;?> '+' .next').click(function() {
                    ol.trigger('prev.owl.carousel', [1000]);
                });
            });
        </script>
        <?php
    }

    function display_list($post, $option, $args) {

        $option->post_per_row_mobile = ($option->post_per_row_mobile == 5)?15:(12/$option->post_per_row_mobile);

        $option->post_per_row_tablet = ($option->post_per_row_tablet == 5)?15:(12/$option->post_per_row_tablet);
        
        $option->post_per_row        = ($option->post_per_row == 5)?15:(12/$option->post_per_row);
        
        ?>
        <div class="row widget_box_post">
            <div id="widget_post_<?= $this->id;?>" class="col-md-12">
                <?php foreach ($post as $key => $val): ?>
                <div class="col-xs-<?php echo $option->post_per_row_mobile;?> col-sm-<?php echo $option->post_per_row_tablet;?> col-md-<?php echo $option->post_per_row;?> col-lg-<?php echo $option->post_per_row;?>">
                    <div class="item">
                        <div class="img">     
                            <a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= get_img($val->image,$val->title, array());?></a>
                        </div>
                        <div class="title">
                            <h3><a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= $val->title;?></a></h3>
                            <div class="excerpt"><?= str_word_cut(removeHtmlTags($val->excerpt), 10);?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
            <?php if(isset($args['where_category']) && have_posts($args['where_category'])) {?>
            <div class="text-center post-more">
                <a href="<?php echo get_url($args['where_category']->slug);?>"><?php echo __('XEM THÊM');?></a>
            </div>
            <?php }?>
        </div>
        <?php
    }

    function css() { include_once('css/style-post-1.css'); }
}

register_widget('widget_post');