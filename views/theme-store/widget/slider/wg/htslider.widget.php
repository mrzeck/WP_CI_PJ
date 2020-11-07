<?php
class widget_ht_slider extends widget {

    function __construct() {

        parent::__construct('widget_ht_slider', 'Slider');

        add_action('cle_enqueue_style', array( $this, 'style'), 10);
        add_action('cle_enqueue_script', array( $this, 'script'), 10);
    }

    function widget($option) {

        $items = gets_gallery($option->gallery);

        $box = $this->container_box('tp-slider-box', $option);

        echo $box['before'];
        ?>
        <div class="tp-banner-container" style="position: relative;">
            <div class="tp-banner" > 
                <ul>                      
                    <?php if(have_posts($items)) { foreach ($items as $key => $item) { ht_slider_creat_item($item); } } ?>
                </ul>
            </div>
        </div>

        <script type="text/javascript">

            var revapi;

            jQuery(document).ready(function() {

                var revapi, revcf, w_width;

                w_width = $(window).width();

                revcf = {
                    delay:<?= $option->speed*1000;?>,
                    startheight:700,
                    hideThumbs:10
                }

                $(document).ready(function() {

                    revcf.startheight = Math.ceil(w_width*<?php echo $option->ratio_height/$option->ratio_width;?>);
                    startheight2 = Math.ceil(2000*<?php echo $option->ratio_height/$option->ratio_width;?>);
                    if( w_width > 1999 ) revcf.startheight = startheight2 + 100;
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

    function form( $left = array(), $right = array()) {

        $left[] = array('field' => 'gallery', 'label' =>'Nguồn slider', 'type' => 'ht_slider');

        $left[] = array("field"=>"ratio_width", "label"=>"Tỉ lệ hiển thị (width)", "type"=>"number", "value"=>3, 'args' => ['step' => 0.1]);

        $left[] = array("field"=>"ratio_height", "label"=>"Tỉ lệ hiển thị (width)", "type"=>"number", "value"=>1, 'args' => ['step' => 0.1]);

        $left[] = array("field"=>"speed", "label"=>"Thời gian chạy slider", "type"=>"number", "value"=>3);

        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');

        // $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>13, 'args' => array('max' => 13));

        // $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));

        // $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));

        $right[] = array('field' =>'box_size', 'label' =>'', 'type' => 'size_box');

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
}

if( class_exists('HTSlider') ) {

    register_widget('widget_ht_slider');

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