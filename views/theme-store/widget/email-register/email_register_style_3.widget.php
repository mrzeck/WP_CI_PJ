<?php
class widget_email_register_style_3 extends widget {

    function __construct() {

        parent::__construct('widget_email_register_style_3', 'ĐK Email Style 3');

        add_action('theme_custom_css', array( $this, 'css'), 10);
        
        add_action('theme_custom_script', array( $this, 'script'), 10);
    }

    function form( $left = array(), $right = array() ) {

        $left[] = array('field' => 'description', 'label' =>'Mô tả', 'type' => 'text');

        $right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');

        // $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>13, 'args' => array('max' => 13));

        // $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));

        // $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));

        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');

        $right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');

        $right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');

        parent::form( $left, $right );
    }

    function widget($option) {
        if(class_exists('skd_multi_language')) {

            $ci =& get_instance();

            if($ci->language['current'] != $ci->language['default']) {

                if(isset($option->{'description_'.$ci->language['current']})) $option->description = $option->{'description_'.$ci->language['current']};
            }
        }
        
        $box = $this->container_box('widget_email_register_style_3', $option);

        echo $box['before']; ?>
        <div class="box-content">
            <div class="email-register-left">
                <form action="" method="post" class="form email-register-form" id="email-register-<?php echo $this->id;?>">
                    <div class="col-md-12 box-email">
                        <h3>
                            <?php echo $this->name;?>
                            <span><?php echo $option->description;?></span>
                        </h3>
                        <div class="form-group">
                            <input name="email" type="email" class="form-control" placeholder="Nhập địa chỉ email" required="">
                            <input name="action" type="hidden" value="ajax_email_register">
                            <input name="form_key" type="hidden" value="email_register">
                            <button type="submit" class="btn">ĐĂNG KÝ</button>
                        </div>
                    </div>
                </form>
            </div>
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
    function css() { include_once('assets/style-3.css'); }

    function script() { include_once('assets/script-style-3.js'); }
}

register_widget('widget_email_register_style_3');