<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists('get_page') ) {
    /**
     * [get_page Lấy dữ liệu trang nội dung]
     * @since 2.5.0
     */
    function get_page( $args = array() ) {

        $ci =& get_instance();

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        $model 	= get_model('page');

        $model->settable('page');

        $model->settable_metabox('metabox');

        $page = $model->get_data($args, 'page');

        $model->settable('page');

        return apply_filters('get_page', $page);
    }
}

if( !function_exists('get_page_by') ) {
    /**
     * [get_page_by]
     * @param  [version] 2.5.0
     */
    function get_page_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters('get_page_by', get_page($args), $field, $value );
    }
}

if( !function_exists('gets_page') ) {
    /**
     * [gets_page]
     * @param  [version] 2.5.0
     */
    function gets_page( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('page');

        $model->settable('page');

        if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        $model 	= get_model('page');

        $model->settable('page');

        $model->settable_metabox('metabox');

        $page = $model->gets_data($args, 'page');

        $model->settable('page');

        return apply_filters( 'gets_page', $page, $args );
    }
}

if( !function_exists('gets_page_by') ) {
    /**
     * [gets_page_by]
     * @since  [version] 2.5.0
     */
    function gets_page_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters( 'gets_page_by', gets_page($args), $field, $value );
    }
}

if( !function_exists('count_page') ) {

    function count_page( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('page');

        $model->settable('page');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        $model 	= get_model('page');

        $model->settable('page');

        $model->settable_metabox('metabox');

        $page = $model->count_data($args, 'page');

        $model->settable('page');
        
        return apply_filters( 'count_page', $page, $args );
    }
}

if( !function_exists('insert_page') ) {
    /**
     * @since  2.5.0
     */
    function insert_page( $page = array() ) {

        $ci =& get_instance();

        $model = get_model('page');

        $model->settable('page');

        $user = get_user_current();

        if ( ! empty( $page['id'] ) ) {

            $id             = (int) $page['id'];

            $update        = true;

            $old_page = get_page($id);

            if ( ! $old_page ) return new SKD_Error( 'invalid_page_id', __( 'ID trang không chính xác.' ) );

            $user_updated = ( have_posts($user) ) ? $user->id : 0;

            $user_created = $old_page->user_created;
        }
        else {

            $update = false;

            $user_updated = 0;

            $user_created = ( have_posts($user) ) ? $user->id : 0;
        }

        if( ! $update ) {

            if ( empty( $page['title'] ) ) return new SKD_Error('empty_page_title', __('Không thể cập nhật trang khi tiêu đề trống.') );

            $slug = $ci->create_slug( removeHtmlTags( $page['title'] ), $model );
        }
        else {

            $slug = empty( $page['slug'] ) ? $old_page->slug : slug($page['slug']);

            if( $slug != $old_page->slug ) $slug = $ci->edit_slug( $slug , $id, $model );

            if ( empty( $page['title'] ) ) $page['title'] = $old_page->title;
        }

        $title      = removeHtmlTags( $page['title'] );

        $pre_title  = apply_filters( 'pre_page_title', $title );

        $title      = trim( $pre_title );

        if( !empty( $page['content'] ) )            $content         =  $page['content'];
        
        if( !empty( $page['excerpt'] ) )            $excerpt         =  $page['excerpt'];
        
        if( !empty( $page['seo_title'] ) )          $seo_title       =  $page['seo_title'];
        
        if( !empty( $page['seo_description'] ) )    $seo_description =  $page['seo_description'];
        
        if( !empty( $page['seo_keywords'] ) )       $seo_keywords    =  $page['seo_keywords'];

        if( !empty( $page['image'] ) ) {

            $image    = removeHtmlTags($page['image']);

            $image    = process_file($image);
        }
        
        $data = compact( 'title', 'slug', 'content', 'excerpt', 'image', 'user_created', 'user_updated', 'seo_title', 'seo_description', 'seo_keywords' );

        $data = apply_filters( 'pre_insert_page_data', $data, $page, $update ? (int) $id : null );

        $language       = !empty( $page['language'] ) ? $page['language'] : array();

        if ( $update ) {

            $model->settable('page');

            $model->update_where( $data, compact( 'id' ) );

            $page_id = (int) $id;

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['object_type']  = 'page';
            $router['object_id']    = $page_id;

            if( $model->update_where( array('slug' => $slug ), $router ) == 0 ) {
                $router['slug']         = $slug;
                $router['directional']  = 'page';
                $router['controller']   = 'frontend_page/page/detail/';
                $model->add($router);
            }

        } else {

            $model->settable('page');

            $page_id = $model->add( $data );

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['slug']         = $slug;
            $router['object_type']  = 'page';
            $router['directional']  = 'page';
            $router['controller']   = 'frontend_page/page/detail/';
            $router['object_id']    = $page_id;

            $model->add($router);

            /*=============================================================
            LANGUAGE
            =============================================================*/
            if( have_posts($language) ) {

                $model->settable('language');

                foreach ($language as $key => $val) {

                    $lang['title']          = removeHtmlTags($val['title']);

                    $lang['excerpt']        = $val['excerpt'];

                    $lang['content']        = $val['content'];
                    
                    $lang['language']       = $key;

                    $lang['object_id']      = $page_id;

                    $lang['object_type']    = 'page';

                    $model->add($lang);
                }
            }

        }

        $model->settable('page');

        $page_id  = apply_filters( 'after_insert_page', $page_id, $page, $data, $update ? (int) $id : null  );

        return $page_id;
    }
}

if( !function_exists('delete_page') ) {
    /**
     * @since  2.5.0
     */
    function delete_page( $pageID = 0, $trash = false ) {

        $ci =& get_instance();

        $pageID = (int)removeHtmlTags($pageID);

        if( $pageID == 0 ) return false;

        $model      = get_model('page');

        $model->settable('page');

        $ci->data['module']   = 'page';

        $count  = count_page( $pageID );

        if( $count == 1 ) {

            //nếu bỏ vào thùng rác
            if( $trash == true ) {

                do_action('delete_page_trash', $pageID );

                if($model->update_where(array('trash' => 1), array('id' => $pageID))) {

                    return [$pageID];
                }

                return false;
            }

            do_action('delete_page', $pageID );

            if($model->delete_where(['id'=> $pageID])) {

                do_action('delete_page_success', $pageID );

                //delete language
                $model->settable('language');

                $model->delete_where(['id'=> $pageID, 'object_type' => 'page']);

                //delete router
                $model->settable('routes');

                $model->delete_where(['id'=> $pageID, 'object_type' => 'page']);

                //delete gallerys
                delete_gallery_by_object($pageID, 'page');

                delete_metadata_by_mid('page', $pageID);

                //delete menu
                $model->settable('menu');

                $model->delete_where(['id'=> $pageID, 'object_type' => 'page']);

                return [$pageID];
            }
        }

        return false;
    }
}

if( !function_exists('delete_list_page') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_page( $pageID = array(), $trash = false ) {

        $ci =& get_instance();

        if(have_posts($pageID)) {

            $model      = get_model('page');

            $model->settable('page');

            if( $trash == true ) {

                do_action('delete_page_list_trash', $pageID );

                if($model->update_where_in(['field' => 'id', 'data' => $pageID], ['trash' => 1])) {

                    return $pageID;
                }

                return false;
            }

            if($model->delete_where_in(['field' => 'id', 'data' => $pageID])) {

                do_action('delete_page_list_trash_success', $pageID );

                //delete language
                $model->settable('language');

                $model->delete_where_in(['field' => 'object_id', 'data' => $pageID], ['object_type' => 'page']);

                //delete router
                $model->settable('routes');

                $model->delete_where_in(['field' => 'object_id', 'data' => $pageID], ['object_type' => 'page']);

                //delete gallerys
                delete_gallery_by_object($pageID, 'page');

                foreach ($pageID as $key => $id) {
                    delete_metadata_by_mid('page', $id);
                }

                //delete menu
                $model->settable('menu');

                $model->delete_where_in(['field' => 'object_id', 'data' => $pageID], ['object_type' => 'page']);

                return $pageID;
            }
        }

        return false;
    }
}

if( !function_exists('get_page_meta') ) {

	function get_page_meta( $page_id, $key = '', $single = true) {

		$data = get_metadata('page', $page_id, $key, $single);

		return $data;
	}
}

if( !function_exists('update_page_meta') ) {

	function update_page_meta($page_id, $meta_key, $meta_value) {

		return update_metadata('page', $page_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_page_meta') ) {

	function delete_page_meta($page_id, $meta_key = '', $meta_value = '') {

		return delete_metadata('page', $page_id, $meta_key, $meta_value);
	}
}