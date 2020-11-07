<?php
function theme_layout_list( $layout_key = '' ) {

    $layout = array(
        'layout-full-width' => array(
            'label' => 'Full Width',
            'image' => 'layout/layout-full.png',
            'template' => 'template-full-width',
        ),
        'layout-full-width-banner' => array(
            'label'    => 'Full Width Banner',
            'image'    => 'layout/layout-full-banner.png',
            'template' => 'template-full-width',
            'banner'   => 'full-width'
        ),
        'layout-sidebar-left' => array(
            'label'     => 'Sidebar Left',
            'image'     => 'layout/layout-sidebar-left.png',
            'template'  => 'template-sidebar-left',
            'sidebar'   => 'left'
        ),
        'layout-sidebar-left-banner-1' => array(
            'label'     => 'Sidebar Left #1',
            'image'     => 'layout/layout-sidebar-left-banner-1.png',
            'template'  => 'template-sidebar-left',
            'banner'    => 'in-content',
            'sidebar'   => 'left'
        ),
        'layout-sidebar-left-banner-2' => array(
            'label' => 'Sidebar Left #2',
            'image' => 'layout/layout-sidebar-left-banner-2.png',
            'template' => 'template-sidebar-left',
            'banner'    => 'full-width',
            'sidebar'   => 'left'
        ),
        'layout-sidebar-right' => array(
            'label' => 'Sidebar Right',
            'image' => 'layout/layout-sidebar-right.png',
            'template' => 'template-sidebar-right',
            'sidebar'   => 'right'
        ),
        'layout-sidebar-right-banner-1' => array(
            'label' => 'Sidebar Right #1',
            'image' => 'layout/layout-sidebar-right-banner-1.png',
            'template' => 'template-sidebar-right',
            'banner'    => 'in-content',
            'sidebar'   => 'right'
        ),
        'layout-sidebar-right-banner-2' => array(
            'label' => 'Sidebar Right #2',
            'image' => 'layout/layout-sidebar-right-banner-2.png',
            'template' => 'template-sidebar-right',
            'banner'    => 'full-width',
            'sidebar'   => 'right'
        ),
    );

    if($layout_key != '' && isset($layout[$layout_key])) return $layout[$layout_key];

    return $layout;
}

function get_theme_layout() {

    $layout = '';

    if( is_page('page_detail'))    $layout = get_option('layout_page', 'layout-full-width-banner');

    if( is_page('post_detail'))    $layout = get_option('layout_post', 'layout-sidebar-right-banner-2');

    if( is_page('post_index'))     $layout = get_option('layout_post_category', 'layout-sidebar-right-banner-2');

    if( is_page('products_index')) $layout = get_option('layout_products_category', 'layout-sidebar-right-banner-2');

    $layout_data = theme_layout_list($layout);

    if(!is_admin()) {

        if($layout == 'layout-full-width-banner' || $layout == 'layout-sidebar-left-banner-2' || $layout == 'layout-sidebar-right-banner-2' ) {

            $setting = get_theme_layout_setting();

            if(!empty($setting['banner'])) {

                $layout_data['banner'] = $setting['banner'];
            }
        }
    }

    return $layout_data;
}

function get_theme_layout_setting( $key = '' ) {

    if($key == '') {

        if( is_page('post_index'))      $key = 'post_category';

        if( is_page('post_detail'))     $key = 'post';

        if( is_page('products_index'))  $key = 'products_category';
    }

    $setting['post_category'] = array(
        'style' => 'vertical',
        'sidebar' => array(
            'new' => array(
                'toggle' => 1,
                'title'  => 'Tin tức mới',
                'data'   => 'post-category-current',
                'limit'  => 5,
            ),
            'hot' => array(
                'toggle' => 1,
                'title'  => 'Tin tức nổi bật',
                'data'   => 'post-category-current',
                'limit'  => 5,
            ),
            'sidebar' => array(
                'toggle' => 0,
                'data'   => '',
            ),
            'sub' => array(
                'toggle' => 1,
                'data'   => 'post-category-current',
                'limit'  => 5,
                'status' => 'new',
            ),
        ),
        'horizontal' => array(
            'category_row_count'        => 2,
            'category_row_count_tablet' => 2,
            'category_row_count_mobile' => 1,
        )
    );

    $setting['post'] = array(
        'sidebar' => array(
            'new' => array(
                'toggle' => 1,
                'title'  => 'Tin tức mới',
                'data'   => 'post-category-current',
                'limit'  => 5,
            ),
            'hot' => array(
                'toggle' => 1,
                'title'  => 'Tin tức nổi bật',
                'data'   => 'post-category-current',
                'limit'  => 5,
            ),
            'related' => array(
                'toggle' => 1,
                'title'  => 'Tin tức liên quan',
                'limit'  => 5,
            ),
            'sidebar' => array(
                'toggle' => 0,
                'data'   => '',
            ),
            'sub' => array(
                'toggle' => 1,
                'data'   => 'post-category-current',
                'limit'  => 5,
                'status' => 'new',
            ),
        ),
    );

    $setting['products_category'] = array(
    );

    $setting['banner'] = array(
        'height'            => 200,
        'page'              => 'full-width',
        'post'              => 'full-width',
        'post_category'     => 'full-width',
        'products_category' => 'full-width',
    );

    if( $key == '' ) return array();

    $setting_layout = array_merge($setting[$key], get_option('layout_'.$key.'_setting', $setting[$key]));

    if($key != 'banner') {

        $banner = array_merge($setting['banner'], get_option('layout_banner_setting', $setting['banner']));

        $setting_layout['banner'] = $banner[$key];
    }
    
    return $setting_layout;
}

if( is_admin() && is_super_admin() ) {

    include 'theme-layout-ajax.php';

    register_admin_subnav('theme', 'Theme Layout', 'theme-layout','plugins?page=theme-layout&type=layout', array('callback' => 'theme_layout'));

    function theme_layout() {

        $ci =& get_instance();

		$ci->load->helper('directory');

        $type = removeHtmlTags($ci->input->get('type'));

        $type_object = removeHtmlTags($ci->input->get('object'));

        $type_object = (!empty($type_object))?$type_object:'layout';

        if( $type == 'header' || $type == 'navigation' || $type == 'top-bar' ) {

            $path 		= FCPATH.VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style';

            $url        = base_url(VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style');

		    $header_style_active 	= get_option('header_style_active', array());

            if( $type == 'header' ) {
                $service 	= $ci->service_api->gets_header();
            }

            if( $type == 'top-bar' ) {
                $service 	= $ci->service_api->gets_top_bar();
            }

            if( $type == 'navigation' ) {
                $service 	= $ci->service_api->gets_navigation();
            }

            $dir = directory_map( $path, true );

            if( isset($service->status) &&  $service->status == 'success' ) {

                $service = $service->data;

                $temp = array();

                foreach ($service as $value) {

                    $temp[$value->folder] = array( 'id' => $value->id, 'title' => $value->title, 'image' => $value->image, 'folder' => $value->folder );
                }

                $service = $temp;
            }
            else $service = [];

            if( have_posts($dir) ) {

                $temp = array();

                foreach ($dir as $value) {

                    $temp[$value] = array( 'id' => 0, 'title' => $value, 'image' => $url.'/'.$value.'/'.$value.'.png', 'folder' => $value );
                }

                $dir = $temp;
            }

            $header_data = array_merge( $dir, $service );

            ksort($header_data);
        }
        
		include_once 'html/theme_layout_html.php';
    }
}

$header_style_active = get_option('header_style_active', array());

if( have_posts($header_style_active) ) {

	foreach ($header_style_active as $type => $list_dir) {

		if( !have_posts($list_dir) ) continue;

		$path = FCPATH. VIEWPATH . get_instance()->data['template']->name.'/theme-header/';

		foreach ($list_dir as $key_dir => $dir) {
			
			if( $type == 'header' 		&& file_exists($path.'header-style/'.$key_dir.'/header-function.php') ) 			include_once $path.'header-style/'.$key_dir.'/header-function.php';
			
			if( $type == 'navigation' 	&& file_exists($path.'navigation-style/'.$key_dir.'/navigation-function.php')) 		include_once $path.'navigation-style/'.$key_dir.'/navigation-function.php';
			
			if( $type == 'top-bar' 		&& file_exists($path.'top-bar-style/'.$key_dir.'/top-bar-function.php')) 			include_once $path.'top-bar-style/'.$key_dir.'/top-bar-function.php';
		}
	}
}