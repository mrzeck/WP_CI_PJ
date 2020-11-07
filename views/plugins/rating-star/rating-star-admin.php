<?php
if ( ! function_exists( 'rating_star_admin_navigation' ) ) {

    function rating_star_admin_navigation() {

        $ci =&get_instance();

        //sản phẩm
        if( current_user_can('rating_star') ) {

            register_admin_nav('Đánh giá sao', 'rating-star', 'plugins?page=rating-star', 'theme', array('icon' => '<img src="http://icons.iconarchive.com/icons/inipagi/job-seeker/1024/rating-icon.png">', 'callback' => 'rating_star_admin'));

            register_admin_subnav('rating-star', 'Đánh giá sản phẩm', 'rating_star_product', 'plugins?page=rating-star&object=product', array('callback' => 'rating_star_admin'));
            
            register_admin_subnav('rating-star', 'Đánh giá bài viết', 'rating_star_post', 'plugins?page=rating-star&object=post_post', array('callback' => 'rating_star_admin'));
        }
    }

    add_action('init', 'rating_star_admin_navigation', 10);
}

if ( ! function_exists( 'rating_star_admin' ) ) {

    function rating_star_admin() {

        $ci =&get_instance();

        $views 	    = removeHtmlTags( $ci->input->get('view') );

        $object_type 	= removeHtmlTags( $ci->input->get('object') );

        if( $views == '' || $views == 'index' ) {

            if($object_type != null) {

                $rating_stars = gets_rating_star([
                    'where' => [
                        'object_type' => $object_type
                    ]
                ]);

                include 'admin/html-index.php';
            }
            else {
                include 'admin/html-empty.php';
            }

            
        }
        
        if( $views == 'setting' ) {

            $rating_stars = gets_rating_star();

            include 'admin/html-setting.php';
	    }
    }
}

function action_bar_plugin_rating_star_button ( $module ) {

	$ci =& get_instance();

	if($ci->template->class == 'plugins' && $ci->input->get('page') == 'rating-star') {
            
        echo '<div class="pull-right">'; do_action('action_bar_plugin_rating_star_right', $module); echo '</div>';
    }
}

add_action( 'action_bar_before', 'action_bar_plugin_rating_star_button', 10 );

function action_bar_plugin_rating_star_right ( $module ) {

    $ci =& get_instance();
    
    if($ci->input->get('view') == '' || $ci->input->get('view') == 'list') {
	    ?><a href="<?php echo admin_url('plugins?page=rating-star&view=setting');?>" class="btn btn-blue">Cấu Hình</a><?php
    }

    if($ci->input->get('view') == 'setting') {
        ?>
        <a href="<?php echo admin_url('plugins?page=rating-star');?>" class="btn btn-white">Danh sách đánh giá</a>
        <button form="rating_star_setting_form" type="submit" class="btn btn-blue">Lưu</a><?php
    }
}

add_action( 'action_bar_plugin_rating_star_right', 'action_bar_plugin_rating_star_right', 10 );