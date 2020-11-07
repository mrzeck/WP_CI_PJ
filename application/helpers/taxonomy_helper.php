<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//kiểm tra cate đã được đăng ký chưa
if(!function_exists('isset_cate_type')){

    function isset_cate_type($cate_type = null) {
        $ci =& get_instance();

        if(!isset($ci->taxonomy['list_cat']) || !have_posts($ci->taxonomy['list_cat'])) return false;

        if($cate_type !== null) {
            if(in_array($cate_type, $ci->taxonomy['list_cat'] ) === false) return false;
        }
        return true;
    }

}
//dăng ký cate
if(!function_exists('register_cate_type')){

    function register_cate_type($cate_type, $post_type, $arg) {

        $ci =& get_instance();

        if( $post_type == '' ) return false;
        //kiểm tra taxonomy có trong list chưa
        if(!isset($ci->taxonomy['list_cat']) || in_array($cate_type, $ci->taxonomy['list_cat'] ) === false) {

            $ci->taxonomy['list_cat'][] = $cate_type;

            $arg = array_merge(array(
                'labels' => array(),
                //hiển thị trên menu
                'public' => true,
                //menu_position: Vị trí hiển thị menu trên danh sách menu của admin, giá trị truyền vào là số nguyên bắt đầu từ 1.
                'menu_position' => 3,
                //menu_icon: URL đến Icon của menu
                'menu_icon' => '',
                //danh mục cha
                'parent' => true,

                'show_admin_column' => false,

                'post_type' => '',

                'supports' => array(),
            ), $arg);

            $arg['labels'] = array_merge(
                array(
                    'name'          => 'Danh sách chuyên mục',
                    'singular_name' => '',
                    'add_new_item'  => 'Thêm chuyên mục',
                ),
                $arg['labels']
            );

            $arg['supports'] = array_merge(

                array(
                    'group'     => array( 'info', 'media', 'seo', 'theme', 'category' ),

                    'field'     => array( 'name', 'excerpt', 'content', 'image', 'public', 'slug', 'seo_title', 'seo_keywords', 'seo_description', 'theme_layout', 'theme_view', 'parent_id'),
                ),
                
                $arg['supports']
            );

            if( isset($arg['admin_nav_header']) ) { $arg['labels']['name'] = $arg['admin_nav_header']; unset($arg['admin_nav_header']); }

            if( isset($arg['name']) ) { $arg['labels']['singular_name'] = $arg['name']; unset($arg['name']); }

            //exclude_from_search : loại bỏ khỏi kết quả tìm kiếm khi search bên ngoài frondend
            if(!isset($arg['exclude_from_search'])) $arg['exclude_from_search'] = $arg['public'];
            //show_in_nav_menus : Nếu có giá trị TRUE thì nó sẽ hiển thị bên trang quản lý menu.
            if(!isset($arg['show_in_nav_menus']))   $arg['show_in_nav_menus']   = 0;
            //show_in_admin_bar : Nếu có giá trị TRUE thì sẽ hiển thị một đường link trên thanh Admin Menu Bar
            if(!isset($arg['show_in_nav_admin']))   $arg['show_in_nav_admin']   = $arg['public'];

            $ci->taxonomy['list_cat_detail'][$cate_type] = $arg;

            $ci->taxonomy['list_cat_detail'][$cate_type]['post_type'] = $post_type;


            if( isset($ci->taxonomy['list_post_detail'][$post_type])) {

                $ci->taxonomy['list_post_detail'][$post_type]['cate_type']      = $cate_type;

                $ci->taxonomy['list_post_detail'][$post_type]['taxonomies'][]   = $cate_type;
            }
        }
    }
}

if(!function_exists('remove_cate_type')){

    function remove_cate_type($cate_type, $post_type) {

        $ci =& get_instance();

        if( $cate_type == '' ) return false;

        foreach ($ci->taxonomy['list_cat'] as $key => $value) {
            if( $value == $cate_type ) unset($ci->taxonomy['list_cat'][$key]); break;
        }

        unset($ci->taxonomy['list_cat_detail'][$cate_type]);

        unset($ci->taxonomy['list_post_detail'][$post_type]['cate_type']);
    }
}

//lấy dữ liệu đăng ký
if(!function_exists('get_cate_type')){

    function get_cate_type($cate_type = null) {

        if(isset_cate_type($cate_type)) {

            $ci =& get_instance();

            if($cate_type === null) return $ci->taxonomy['list_cat'];

            else return $ci->taxonomy['list_cat_detail'][$cate_type];
        }

        return array(); 
    }
}

if(!function_exists('get_object_taxonomies')){
    /**
     * [get_object_taxonomies lấy danh sách taxonomy của post_type]
     * @param  [type] $cate_type [description]
     * @return [type]            [description]
     */
    function get_object_taxonomies( $object, $output = 'names' ) {

        $ci =& get_instance();

        if( is_object($object) ) $object = $object->post_type;

        $post = get_post_type( $object );

        if( have_posts($post) ) {

            $taxonomies = array();

            if( $output == 'names' ) return $post['taxonomies'];

            $taxonomy = array();

            foreach ($post['taxonomies'] as $val) {

                if( isset($ci->taxonomy['list_cat_detail'][$val]))
                    $taxonomy[$val] = (object)$ci->taxonomy['list_cat_detail'][$val];
            }

            return (object) $taxonomy;

        }

        return array(); 
    }
}

if(!function_exists('register_post_type')) {

    function register_post_type($post_type, $arg) {

        $ci =& get_instance();

        if( $post_type == '' ) return false;

        if( !is_array($ci->taxonomy) ) $ci->taxonomy = array();
        
        //kiểm tra taxonomy có trong list chưa
        if(!isset($ci->taxonomy['list_post']) || in_array($post_type, $ci->taxonomy['list_post'] ) === false) {

            $ci->taxonomy['list_post'][] = $post_type;

            $arg = array_merge(array(

                'labels' => array(

                    'name'          => '',

                    'singular_name' => ''
                ),
                //hiển thị trên menu
                'public'        => true,
                //menu_position: Vị trí hiển thị menu trên danh sách menu của admin, giá trị truyền vào là số nguyên bắt đầu từ 1.
                'menu_position' => 3,
                //menu_icon: URL đến Icon của menu
                'menu_icon'     => '<img src="views/backend/assets/images/icon-post.png" />',
                //cat_type
                'cate_type'     => false,

                'taxonomies'    => array(),

                'supports'      => array(),

            ), $arg);

            $arg['labels'] = array_merge(
                array(
                    'name'          => 'Bài viết',

                    'singular_name' => 'Bài viết',

                    'add_new_item'  => 'Thêm bài viết',

                    'edit_item'     => 'Thêm bài viết',
                ),

                $arg['labels']
            );

            $arg['supports'] = array_merge(

                array(
                    'group'     => array( 'info', 'media', 'seo', 'theme' ),

                    'field'     => array( 'title', 'excerpt', 'content', 'image', 'public', 'slug', 'seo_title', 'seo_keywords', 'seo_description', 'theme_layout', 'theme_view'),
                ),

                $arg['supports']
            );


            if( isset($arg['admin_nav_header']) ) { $arg['labels']['name'] = $arg['admin_nav_header']; unset($arg['admin_nav_header']); }

            if( isset($arg['name']) ) { $arg['labels']['singular_name'] = $arg['name']; unset($arg['name']); }

            


            //exclude_from_search : loại bỏ khỏi kết quả tìm kiếm khi search bên ngoài frondend
            if(!isset($arg['exclude_from_search'])) $arg['exclude_from_search'] = $arg['public'];
            //show_in_nav_menus : Nếu có giá trị TRUE thì nó sẽ hiển thị bên trang quản lý menu.
            if(!isset($arg['show_in_nav_menus']))   $arg['show_in_nav_menus']   = 0;
            //show_in_nav_admin : Nếu có giá trị TRUE thì sẽ hiển thị một đường link trên thanh Admin Menu Bar
            if(!isset($arg['show_in_nav_admin']))   $arg['show_in_nav_admin']   = $arg['public'];

            $ci->taxonomy['list_post_detail'][$post_type] = $arg;
        }

    }
}


if(!function_exists('remove_post_type')){

    function remove_post_type($post_type) {

        $ci =& get_instance();

        if( $post_type == '' ) return false;

        foreach ($ci->taxonomy['list_post'] as $key => $value) {
            if( $value == $post_type ) unset($ci->taxonomy['list_post'][$key]); break;
        }

        unset($ci->taxonomy['list_post_detail'][$post_type]);
    }
}

if(!function_exists('isset_post_type')) {

    function isset_post_type($post_type = null) {

        $ci =& get_instance();

        if(!isset($ci->taxonomy['list_post']) || !have_posts($ci->taxonomy['list_post'])) return false;
        
        if($post_type !== null) {
            if(in_array($post_type, $ci->taxonomy['list_post'] ) === false) return false;
        }
        return true;
    }
}

if(!function_exists('get_post_type')){

    function get_post_type($post_type = null) {

        $ci =& get_instance();

        if(isset_post_type($post_type)) {

            $ci =& get_instance();

            if($post_type === null) return $ci->taxonomy['list_post'];

            else return $ci->taxonomy['list_post_detail'][$post_type];

        }
        return array(); 
    }
}


if(!function_exists('get_post_type_detail')){
    function get_post_type_detail() {
        $ci =& get_instance();
        return $ci->taxonomy['list_post_detail'];
    }
}

if(!function_exists('get_cate_type_detail')){
    function get_cate_type_detail() {
        $ci =& get_instance();
        return $ci->taxonomy['list_cat_detail'];
    }
}


if(!function_exists('get_the_terms')){
    /** lấy danh sách categories */
    function get_the_terms( $object, $object_type, $taxonomy = null ) {

        $object_id = 0;

        if( $object_type == 'post' ) {

            if( have_posts($object) ) $object_id = $object->id;

            if( is_numeric($object) ) $object_id = $object;

            $model = get_model('post');

            return $model->gets_relationship_join_categories( $object_id, $object_type, $taxonomy );
        }
    }
}
