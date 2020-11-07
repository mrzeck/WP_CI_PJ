<?php
/**
Template Name: Store Theme
Description: Store theme là theme chuyên nghiệp được phát triển dùng cho các website bán hàng.
Version: 2.1.4
Author: Nguyễn Hữu Trọng
*/
function store_theme_support() {
	$layout_page            	= theme_layout_list(get_option('layout_page', 'layout-full-width-banner'));
	$layout_post            	= theme_layout_list(get_option('layout_post', 'layout-sidebar-right-banner-2'));
	$layout_post_category   	= theme_layout_list(get_option('layout_post_category', 'layout-sidebar-right-banner-2'));
	$layout_products_category   = theme_layout_list(get_option('layout_products_category', 'layout-sidebar-right-banner-2'));
	$galelry_support 			= get_option('gallery_template_support');
	//template 
	set_template_default('home','index','template-home');
	set_template_default('home','search','template-sidebar-none');
	//PAGE
	set_template_default('page','detail', $layout_page['template']);
	//POST
	set_template_default('post','index',  $layout_post_category['template']);
	set_template_default('post','detail', $layout_post['template']);
	//PRODUCT
	set_template_default('products','index', $layout_products_category['template']);
	set_template_default('products','detail','template-full-width');
	set_template_default('user','index',	'template-user');
	set_template_default('user','password',	'template-user');
	set_template_default('user','login',	'template-user');
	set_template_default('user','register',	'template-user');
	//template support
	template_support('page', array('theme','gallery'));
	template_support('post');
	template_support('products');
	template_support('post_categories', array('media'), array('content'));
	template_support('products_categories', array('media'));
	//Gallery support
	$gallery_support_page = [];
	if(class_exists('woocommerce')) $gallery_support_page[] = 'products';
	if(class_exists('woocommerce')) $gallery_support_page[] = 'products_categories';
	if(!empty($gallery_support['page'])) $gallery_support_page[] = 'page';
	if(have_posts($gallery_support_page)) gallery_template_support( $gallery_support_page );
	if(isset($gallery_support['category']) && have_posts($gallery_support['category'])) {
		$gallery_support_category = [];
		foreach ($gallery_support['category'] as $cate_key => $cate_value) {
			if($cate_value == 1) $gallery_support_category[] = $cate_key;
		}
		if(have_posts($gallery_support_category)) gallery_template_support_cate_type( $gallery_support_category );
	}
	if(isset($gallery_support['post']) && have_posts($gallery_support['post'])) {
		$gallery_support_post = [];
		foreach ($gallery_support['post'] as $post_key => $post_value) {
			if($post_value == 1) $gallery_support_post[] = $post_key;
		}
		if(have_posts($gallery_support_post)) gallery_template_support_post_type( $gallery_support_post );
	}
}
add_action( 'init', 'store_theme_support' );
/**
 * Các input type mới
 * Cấu hình giao diện menu
 */
include_once 'theme-setting/theme-form.php'; 
/**
 * cài đặt các cấu hình website
 * cấu hình giao diện
 */
include_once 'theme-setting/theme-option.php';
/**
 * chèn các thư viện style, script cần thiêt
 */
include_once 'theme-setting/theme-style.php';
/**
 * chèn các thư viện style, script cần thiêt
 */
include_once 'theme-setting/theme-custom-css.php';
include_once 'theme-setting/theme-custom-script.php';
/**
 * Đăng ký vi trí menu sử dụng
 * Cấu hình giao diện menu
 */
include_once 'theme-setting/menu.php';
/**
 * Đăng ký ajax
 * Cấu hình giao diện menu
 */
include_once 'theme-setting/theme-ajax.php';
/**
 * Đăng ký widget
 * Cấu hình giao diện menu
 */
include_once 'widget/widget.php';
/** 
 * chức năng cấu hình layout theme 
 * */
include_once 'admin/theme-admin.php';
function facebook_comment() {
	?> <div class="fb-comments" data-href="<?php echo fullurl();?>" data-width="100%" data-numposts="5"></div> <?php
}
// add_action('woocommerce_detail_tab_content_after', 		'facebook_comment');
/**
 * HIỆU ỨNG LAZYLOAD IMAGE
 */
function lazyload_get_img( $html ='', $args ) {
	if( !empty($args['params']['class']) && $args['params']['class'] == 'product-image-feature' ) return $html;
	if(is_admin()) return $html;
	$html = '<img data-src="'.$args['url'].'" alt="'.removeHtmlTags($args['alt']).'" src="'.get_img_link('rolling.svg').'"';
	$class = false;
	if(have_posts($args['params'])) {
		foreach ($args['params'] as $key => $value) {
			if( $key == 'class' ) { $value .= ' lazyload'; $class = true; } 
			$html .= $key.'="'.$value.'" ';
		}
	}
	if( $class == false ) $html .= 'class="lazyload"';
	$html .='/>';
	return $html;
}
// add_filter('get_img','lazyload_get_img', 1,2 );
/**
 * CUSTOM BREADCRUMB
 */
function theme_get_breadcrumb() {
	$ci =& get_instance();
	$model = get_model($ci->template->class);
	$breadcrumb = array();
	if( isset($ci->data['category']) && have_posts($ci->data['category']) )
		$breadcrumb = $model->breadcrumb($ci->data['category']);
	if( isset($ci->data['object']) && have_posts($ci->data['object']) )
		$breadcrumb[] = (object)array('slug' => $ci->data['object']->slug, 'name' => $ci->data['object']->title );
	if(is_page('products_index') || is_page('products_detail')) {
		$temp[] = (object)[
			'name' => __('sản phẩm'),
			'slug' => 'san-pham'
		];
		foreach ($breadcrumb as $key => $value) {
			$temp[] = $value;
		}
		$breadcrumb = $temp;
	}
	return $breadcrumb;
}
remove_action('woocommerce_products_detail_before', 'woocommerce_products_detail_breadcrumb', 10);
/**
 * CUSTOM SẢN PHẨM LIÊN QUAN
 * Thay đổi vị trí
 */
function theme_wc_related_products() {
	remove_action('woocommerce_products_detail_tabs', 'woocommerce_output_related_products', 20);
	add_action('woocommerce_products_detail_after', 'woocommerce_output_related_products', 20);
}
add_action('init', 'theme_wc_related_products');
function khachhang_save($item){
	// debug($item);
	$ci = &get_instance();
	if ($ci->post_type =='post-customer') {
		$item['public']=0;
	}
	return $item;
}
add_filter('save_object_before', 'khachhang_save',10,1);	
////////////////////////////////////////////////////
register_post_type('video', 
	array(
		'labels' => array(
            'name'          => 'Danh sách video',
            'singular_name' => 'Danh sách video ',
        ),
        'public' => true,
        'show_admin_column'  => true,
        'capibilitie' => array(
            'view'      => 'view_video',
            'add'       => 'add_video',
            'edit'      => 'edit_video',
            'delete'    => 'delete_video',
        ),
        'supports' => [
            'group' => ['info']
        ]
	)
);
        // $role = get_role('root');
        // $role->add_cap('view_video');
        // $role->add_cap('add_video');
        // $role->add_cap('edit_video');
        // $role->add_cap('delete_video');
        // // Add caps for Administrator role
        // $role = get_role('administrator');
        // $role->add_cap('view_video');
        // $role->add_cap('add_video');
        // $role->add_cap('edit_video');
        // $role->add_cap('delete_video');
register_cate_type('video_categories', 'video',
	array(
        'labels' => array(
            'name'          => 'Danh mục Video',
            'singular_name' => 'Chuyên mục video',
        ),
        'public' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'parent' => true,
        'capibilitie' => array(
            'edit'      => 'manage_categories',
            'delete'    => 'delete_categories',
        ),
    )
);
function video_custom_input( $form ) {
	$form = form_remove_group('seo', $form);
		$form = form_remove_field('content,excerpt,slug',$form);
	return $form;
}
add_filter('manage_post_video_input', 'video_custom_input');
function add_metabox_vidoe()
{
	add_meta_box(
		'video_metabox',
		'video',
		'video_metabox_callback',
		'post_video'
	);
}
add_action( 'init', 'add_metabox_vidoe');
if( !function_exists('video_metabox_callback') ) {
	function video_metabox_callback( $object ) {
		$value = '';
		if( have_posts($object))
			$value = get_metadata( 'post_video', $object->id, 'video_url', true );
		$input = array( 'field' => 'video_url', 'label'	=> 'Url Video', 'type'  => 'text', 'note'  => 'Liên kết video <b>youtube</b>', );
		echo _form($input, $value);
	}
}
if( !function_exists('wcmc_metabox_video_save') ) {
    function wcmc_metabox_video_save($post_id, $model) {
        $ci =& get_instance();
        if($ci->data['module'] == 'post' && $ci->post_type == 'video') {
            $data = $ci->input->post();
            $currenttable = $model->gettable();
            $url  = get_metadata('post_video', $object->id, 'video_url', true);
            if( $url != removeHtmlTags($data['video_url']) )
                update_metadata( 'post_video', $post_id, 'video_url', removeHtmlTags($data['video_url']) );
        }
    }
    add_action('save_object', 'wcmc_metabox_video_save', '', 2);
}
class skd_post_video_list_table extends skd_post_list_table {
	function column_image($item) {
		if( !empty($item->image) )
        	echo get_img($item->image, $item->title, array('style' => 'width:50px;'), 'medium');
        else {
        	$img = get_metadata( 'post_video', $item->id, 'video_url', true);
        	echo get_img('https://img.youtube.com/vi/'.getYoutubeID($img).'/0.jpg', '', array('style'=>'width:50px;'));
        }
    }
}
if( !function_exists('template_video_save') ) {
    function template_video_save($ins_data, $data_outside) {
        $ci =& get_instance();
        if($ci->data['module'] == 'post' && $ci->post_type == 'video') {
        	$ins_data['theme_view'] = 'post-detail-video';
        }
        return $ins_data;
    }
    add_filter('save_object_before', 'template_video_save', '', 2);
}

// =========

require_once 'include/meta/brands.php';//danh muc san pham
require_once 'include/meta/thuonghieu.php';//danh muc san pham
require_once 'include/excel/excel.php';

