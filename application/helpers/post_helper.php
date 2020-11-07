<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists('get_post') ) {

    function get_post( $args = array() ) {

        $ci =& get_instance();

        $model = get_model('post');

        $model->settable('post');

        $model->settable_metabox('metabox');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        if(isset($args['post_type'])) $args['where']['post_type'] = $args['post_type'];

        $post = $model->get_data( $args, 'post' );

        return apply_filters('get_post', $post, $args);
    }
}

if( !function_exists('get_post_by') ) {
    /**
     * [get_post_by]
     * @param  [version] 2.3.4
     */
    function get_post_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters('get_post_by', get_post($args), $field, $value );
    }
}

if( !function_exists('gets_post') ) {

    function gets_post( $args = array() ) {

        $ci =& get_instance();

        $model 	= get_model('post');

        $model->settable('post');

        $model->settable_metabox('metabox');

        if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        if(isset($args['post_type'])) $args['where']['post_type'] = $args['post_type'];

        /**
		 * Gets where taxonomy
		 * @since  3.0.5
		 */
		if(isset($args['tax_query'])) {

            $post = [];

            $where  = $args['where'];

		    $params = $args['params'];

            $sql_table  = CLE_PREFIX.'post';

            if(isset($params['select'])) {
                $select = removeHtmlTags($params['select']);
            }
            else {
                $select = '`'.$sql_table.'`.*';
            }

            $sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.`id`  FROM `'.$sql_table.'` ';

            $sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.* FROM `'.$sql_table.'` ';

			if( $model->mutilang == true && $model->language != $model->language_default) {

                $sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.*, lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
                
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}

            $tax_query  = $args['tax_query'];

            $taxonomyID = [];

            $relation 	= 'AND';

            if( !empty($tax_query['relation']) ) {

                $relation = $tax_query['relation']; 

                unset($tax_query['relation']);
            }

            if($relation != 'AND' || $relation != 'OR') $relation = 'AND';

            foreach ($tax_query as $key => $tax) {

                $taxonomy = get_post_category(['where' => array(
                    $tax['field'] => removeHtmlTags($tax['terms']),
                    'cate_type'   => removeHtmlTags($tax['taxonomy']),
                )]);

                $model->settable_category('categories');

                if(have_posts($taxonomy)) {

                    $dataID        = $model->gets_category_sub($taxonomy);

                    if(have_posts($dataID)) {

                        $sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS txnm'.$key.' ON ( `'.$sql_table.'`.id = txnm'.$key.'.object_id ) ';

                        $taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
                    }
                }
            }

            $sql .= 'WHERE 1=1 AND';

            if( $model->mutilang == true && $model->language != $model->language_default) {

                $sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'post\' AND ';
            }

            if(have_posts($taxonomyID)) {

                $sql .= ' AND (';

                foreach ($taxonomyID as $txnmkey => $taxonomyData) {

                    $sql    .= '(';

                    $sql .= $txnmkey.'.`category_id` IN ('.implode(',',$taxonomyData).')';

                    $sql .= ' AND '.$txnmkey.'.`value` = \''.$taxonomy['taxonomy'].'\'';

                    $sql .= ') '.$relation.' ';
                }

                $sql = trim( $sql, ' '.$relation.' ' );

                $sql .= ')';
            }

            if(have_posts($where)) {

                $sql .= ' AND (';

                foreach($where as $key => $value) {

                    $key = trim($key);

                    $compare = '=';

                    if(substr($key,-1) == '>') $compare = '>';

                    if(substr($key,-1) == '<') $compare = '<';

                    if(substr($key,-2) == '>=') $compare = '>=';
                    if(substr($key,-2) == '<=') $compare = '<=';

                    if(substr($key,-2) == '<>') $compare = '<>';

                    if(substr($key,-2) == '!=') $compare = '<>';

                    $key = trim(removeHtmlTags($key));

                    $sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
                }

                $sql = trim( $sql, 'AND ' );

                $sql .= ')';
            }
            else {

                $sql .= ' AND ('.$where.')';
            }

            if(have_posts($params)) {

                if(isset($params['groupby'])) {

                    if(is_array($params['groupby'])) {

                        $groupby = explode(',',$params['groupby']);

                        if(have_posts($groupby)) {

                            foreach ($groupby as &$oby) {

                                $oby_arr = explode('.',$oby);

                                if(count($oby_arr) == 2) {

                                    $oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
                                }
                                else {

                                    $oby = '`'.removeHtmlTags($oby).'`';
                                }
                                
                            }

                            $params['groupby'] = implode(',',$groupby);
                        }
                        else {
                            $params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
                        }
                    }

                    $sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
                }

                if(isset($params['orderby'])) {

                    $orderby = explode(',',$params['orderby']);

                    if(have_posts($orderby)) {

                        foreach ($orderby as &$oby) {

                            $oby_arr = explode('.',$oby);

                            if(count($oby_arr) == 2) {

                                $oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
                            }
                            else {

                                $oby = '`'.removeHtmlTags($oby).'`';
                            }
                            
                        }

                        $params['orderby'] = implode(',',$orderby);
                    }
                    else {
                        $params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
                    }

                    $sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
                }

                if(isset($params['limit'])) {

                    $sql .= ' LIMIT ';

                    if(isset($params['start'])) {

                        $sql .= (int)$params['start'].',';
                    }

                    $sql .= (int)$params['limit'];
                }
            }
            else {

                $sql .= ' GROUP BY `'.$sql_table.'`.`id`';
            }

            if(isset($args['sql']) && $args['sql'] == true) return $sql;

            $query = $model->query($sql);

            foreach ($query->result() as $row ) {

                $post[] = $row;
            }
        }
        else  $post = $model->gets_data($args, 'post');

        $model->settable('post');

        return apply_filters( 'gets_post', $post, $args );
    }
}

if( !function_exists('gets_post_by') ) {
    /**
     * [gets_post_by]
     * @param  [version] 2.3.4
     */
    function gets_post_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters( 'gets_post_by', gets_post($args), $field, $value );
    }
}

if( !function_exists('count_post') ) {

    function count_post( $args = array() ) {

        $ci =& get_instance();

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
        }

        if(isset($args['post_type'])) $args['where']['post_type'] = $args['post_type'];

        $model = get_model('post');

        $model->settable('post');

		$model->settable_metabox('metabox');

		/**
		 * Gets where taxonomy
		 * @since  3.0.5
		 */
		if(isset($args['tax_query'])) {

            $post = 0;

            $where  = $args['where'];

		    $params = $args['params'];

            $sql_table  = CLE_PREFIX.'post';

            if(isset($params['select'])) {
                $select = removeHtmlTags($params['select']);
            }
            else {
                $select = '`'.$sql_table.'`.*';
            }

            $sql        = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.`id`  FROM `'.$sql_table.'` ';

            $sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.* FROM `'.$sql_table.'` ';

			if( $model->mutilang == true && $model->language != $model->language_default) {

                $sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.'.*, lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
                
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}

            $tax_query  = $args['tax_query'];

            $taxonomyID = [];

            $relation 	= 'AND';

            if( !empty($tax_query['relation']) ) {

                $relation = $tax_query['relation']; 

                unset($tax_query['relation']);
            }

            if($relation != 'AND' || $relation != 'OR') $relation = 'AND';

            foreach ($tax_query as $key => $tax) {

                $taxonomy = get_post_category(['where' => array(
                    $tax['field'] => removeHtmlTags($tax['terms']),
                    'cate_type'   => removeHtmlTags($tax['taxonomy']),
                )]);

                $model->settable_category('categories');

                if(have_posts($taxonomy)) {

                    $dataID        = $model->gets_category_sub($taxonomy);

                    if(have_posts($dataID)) {

                        $sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS txnm'.$key.' ON ( `'.$sql_table.'`.id = txnm'.$key.'.object_id ) ';
                        
                        $taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
                    }
                }
            }

            $sql .= 'WHERE 1=1 AND';

            if( $model->mutilang == true && $model->language != $model->language_default) {

                $sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'post\' AND ';
            }

            if(have_posts($taxonomyID)) {

                $sql .= ' AND (';

                foreach ($taxonomyID as $txnmkey => $taxonomyData) {

                    $sql    .= '(';

                    $sql .= $txnmkey.'.`category_id` IN ('.implode(',',$taxonomyData).')';

                    $sql .= ' AND '.$txnmkey.'.`value` = \''.$taxonomy['taxonomy'].'\'';

                    $sql .= ') '.$relation.' ';
                }

                $sql = trim( $sql, ' '.$relation.' ' );

                $sql .= ')';
            }

            if(have_posts($where)) {

                $sql .= ' AND (';

                foreach($where as $key => $value) {

                    $key = trim($key);

                    $compare = '=';

                    if(substr($key,-1) == '>') $compare = '>';

                    if(substr($key,-1) == '<') $compare = '<';

                    if(substr($key,-2) == '>=') $compare = '>=';
                    if(substr($key,-2) == '<=') $compare = '<=';

                    if(substr($key,-2) == '<>') $compare = '<>';

                    if(substr($key,-2) == '!=') $compare = '<>';

                    $key = trim(removeHtmlTags($key));

                    $sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
                }

                $sql = trim( $sql, 'AND ' );

                $sql .= ')';
            }
            else {

                $sql .= ' AND ('.$where.')';
            }

            if(have_posts($params)) {

                if(isset($params['groupby'])) {

                    if(is_array($params['groupby'])) {

                        $groupby = explode(',',$params['groupby']);

                        if(have_posts($groupby)) {

                            foreach ($groupby as &$oby) {

                                $oby_arr = explode('.',$oby);

                                if(count($oby_arr) == 2) {

                                    $oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
                                }
                                else {

                                    $oby = '`'.removeHtmlTags($oby).'`';
                                }
                                
                            }

                            $params['groupby'] = implode(',',$groupby);
                        }
                        else {
                            $params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
                        }
                    }

                    $sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
                }
                
                if(isset($params['orderby'])) {

                    $orderby = explode(',',$params['orderby']);

                    if(have_posts($orderby)) {

                        foreach ($orderby as &$oby) {

                            $oby_arr = explode('.',$oby);

                            if(count($oby_arr) == 2) {

                                $oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
                            }
                            else {

                                $oby = '`'.removeHtmlTags($oby).'`';
                            }
                            
                        }

                        $params['orderby'] = implode(',',$orderby);
                    }
                    else {
                        $params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
                    }

                    $sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
                }

                if(isset($params['limit'])) {

                    $sql .= ' LIMIT ';

                    if(isset($params['start'])) {

                        $sql .= (int)$params['start'].',';
                    }

                    $sql .= (int)$params['limit'];
                }
            }
            else {

                $sql .= ' GROUP BY `'.$sql_table.'`.`id`';
            }

            if(isset($args['sql']) && $args['sql'] == true) return $sql;

            $query = $model->query($sql);

            $query = $model->query('SELECT FOUND_ROWS() as count');

            foreach ($query->result() as $row ) {

                $post = $row;
            }

            $post = $post->count;
        }
        else $post = $model->count_data($args, 'post');
		
	    return apply_filters('count_post', $post, $args );
    }
}

if( !function_exists('insert_post') ) {
    /**
     * @since  2.0.0
     * @since  2.3.5 custom new
     */
    function insert_post( $post = array() ) {

        $ci =& get_instance();

        $model = get_model('post');

        $model->settable('post');

        $user = get_user_current();

        if ( ! empty( $post['id'] ) ) {

            $id             = (int) $post['id'];

            $update        = true;

            $old_post = get_post($id);

            if ( ! $old_post ) return new SKD_Error( 'invalid_post_id', __( 'ID bài viết không chính xác.' ) );

            $user_updated = ( have_posts($user) ) ? $user->id : 0;

            $user_created = $old_post->user_created;
        }
        else {

            $update = false;

            $user_updated = 0;

            $user_created = ( have_posts($user) ) ? $user->id : 0;
        }

        if( ! $update ) {

            if ( empty( $post['title'] ) ) return new SKD_Error('empty_post_title', __('Không thể cập nhật bài viết khi tiêu đề để trống.') );

            $slug = $ci->create_slug( removeHtmlTags( $post['title'] ), $model );
        }
        else {

            $slug = empty( $post['slug'] ) ? $old_post->slug : slug($post['slug']);

            if( $slug != $old_post->slug ) $slug = $ci->edit_slug( $slug , $id, $model );

            if ( empty( $post['title'] ) ) $post['title'] = $old_post->title;
        }

        $title      = removeHtmlTags( $post['title'] );

        $pre_title  = apply_filters( 'pre_post_title', $title );

        $title      = trim( $pre_title );

        if( !empty( $post['content'] ) )            $content         =  $post['content'];
        
        if( !empty( $post['excerpt'] ) )            $excerpt         =  $post['excerpt'];
        
        if( !empty( $post['seo_title'] ) )          $seo_title       =  $post['seo_title'];
        
        if( !empty( $post['seo_description'] ) )    $seo_description =  $post['seo_description'];
        
        if( !empty( $post['seo_keywords'] ) )       $seo_keywords    =  $post['seo_keywords'];

        if( !empty( $post['image'] ) ) {

            $image    = removeHtmlTags($post['image']);

            $image    = process_file($image);
        }

        if( $update ) {

            $post_type = ( !empty( $post['post_type'] ) ) ? removeHtmlTags($post['post_type']) : $old_post->post_type;

            $public = (isset($post['public']) && is_numeric($post['public']) ) ? removeHtmlTags($post['public']) : $old_post->public;
        }
        else {
            $post_type = ( !empty( $post['post_type'] ) ) ? removeHtmlTags($post['post_type']) : 'post';

            $public = (isset($post['public']) && is_numeric($post['public']) ) ? removeHtmlTags($post['public']) : 1;
        }
        
        $data = compact( 'title', 'slug', 'content', 'excerpt', 'image', 'user_created', 'user_updated', 'post_type', 'seo_title', 'seo_description', 'seo_keywords', 'public' );

        $data = apply_filters( 'pre_insert_post_data', $data, $post, $update ? (int) $id : null );

        $taxonomies     = !empty( $post['taxonomies'] ) ? $post['taxonomies'] : array();

        $language       = !empty( $post['language'] ) ? $post['language'] : array();

        if ( $update ) {

            $model->settable('post');

            $model->update_where( $data, compact( 'id' ) );

            $post_id = (int) $id;

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['object_type']  = 'post';
            $router['object_id']    = $post_id;

            if( $model->update_where( array('slug' => $slug ), $router ) == 0 ) {
                $router['slug']         = $slug;
                $router['directional']  = 'post';
                $router['controller']   = 'frontend_post/post/detail/';
                $model->add($router);
            }

            /*=============================================================
            TAXONOMY
            =============================================================*/
            if( have_posts($taxonomies) ) {

                $model->settable('relationships');

                $taxonomy['object_id']      = $post_id;

                $taxonomy['object_type']    = 'post';

                foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {

                    $taxonomy['value']      = $taxonomy_key;

                    foreach ($taxonomy_value as $taxonomy_id) {

                        $taxonomy['category_id'] = $taxonomy_id;

                        $model->add($taxonomy);
                    }
                    
                }
            }

        } else {

            $model->settable('post');

            $post_id = $model->add( $data );

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['slug']         = $slug;
            $router['object_type']  = 'post';
            $router['directional']  = 'post';
            $router['controller']   = 'frontend_post/post/detail/';
            $router['object_id']    = $post_id;

            $model->add($router);

            /*=============================================================
            TAXONOMY
            =============================================================*/
            if( have_posts($taxonomies) ) {

                $model->settable('relationships');

                $taxonomy['object_id']      = $post_id;

                $taxonomy['object_type']    = 'post';

                foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {

                    $taxonomy['value']      = $taxonomy_key;

                    foreach ($taxonomy_value as $taxonomy_id) {

                        $taxonomy['category_id'] = $taxonomy_id;

                        $model->add($taxonomy);
                    }
                    
                }
            }

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

                    $lang['object_id']      = $post_id;

                    $lang['object_type']    = 'post';

                    $model->add($lang);
                }
            }

        }

        $model->settable('post');

        $post_id  = apply_filters( 'after_insert_post', $post_id, $post, $data, $update ? (int) $id : null  );

        return $post_id;
    }
}

if( !function_exists('delete_post') ) {
    /**
     * @since  2.0.0
     */
    function delete_post( $postID = 0, $trash = false ) {

        $ci =& get_instance();

        $postID = (int)removeHtmlTags($postID);

        if( $postID == 0 ) return false;

        $model      = get_model('post');

        $model->settable('post');

        $post  = get_post( $postID );

        if( have_posts($post) ) {

            $ci->data['module']   = 'post';

            //nếu bỏ vào thùng rác
            if( $trash == true ) {

                /**
                 * @since 2.5.0 add action delete_post_trash
                 */
                do_action('delete_post_trash', $postID );

                if($model->update_where(array('trash' => 1), array('id' => $postID))) {

                    return [$postID];
                }

                return false;
            }
            /**
             * @since 2.5.0 add action delete_post
             */
            do_action('delete_post', $postID );

            if($model->delete_where(['id'=> $postID])) {

                do_action('delete_post_success', $postID );

                //delete language
                $model->settable('language');

                $model->delete_where(['id'=> $postID, 'object_type' => 'post']);

                //delete router
                $model->settable('routes');

                $model->delete_where(['id'=> $postID, 'object_type' => 'post']);

                //delete gallerys
                delete_gallery_by_object($postID, 'post_'.$post->post_type);

                delete_metadata_by_mid('post', $postID);

                //delete menu
                $model->settable('menu');

                $model->delete_where(['id'=> $postID, 'object_type' => 'post']);

                //xóa liên kết
                $model->settable('relationships');

                $model->delete_where(['object_id'=> $postID, 'object_type' => 'post']);

                return [$postID];
            }
        }

        return false;
    }
}

if( !function_exists('delete_list_post') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_post( $postID = array(), $trash = false ) {

        $ci =& get_instance();

        if(have_posts($postID)) {

            $model      = get_model('post');

            $model->settable('post');

            if( $trash == true ) {

                do_action('delete_post_list_trash', $postID );

                if($model->update_where_in(['field' => 'id', 'data' => $postID], ['trash' => 1])) {

                    return $postID;
                }

                return false;
            }


            $posts = gets_post(['where_in' => ['field' => 'id', 'data' => $postID]]);

            if($model->delete_where_in(['field' => 'id', 'data' => $postID])) {

                $where_in = ['field' => 'object_id', 'data' => $postID];

                do_action('delete_post_list_trash_success', $postID );

                //delete language
                $model->settable('language');

                $model->delete_where_in($where_in, ['object_type' => 'post']);

                //delete router
                $model->settable('routes');

                $model->delete_where_in($where_in, ['object_type' => 'post']);

                //delete router
                foreach ($posts as $key => $post) {
                    
                    delete_gallery_by_object($post->id, 'post_'.$post->post_type);

                    delete_metadata_by_mid('post', $post->id);
                }

                //delete menu
                $model->settable('menu');

                $model->delete_where_in($where_in, ['object_type' => 'post']);

                //xóa liên kết
                $model->settable('relationships');

                $model->delete_where_in($where_in, ['object_type' => 'post']);

                return $postID;
            }
        }

        return false;
    }
}

if( !function_exists('get_post_meta') ) {

	function get_post_meta( $post_id, $key = '', $single = true) {

		$data = get_metadata('post', $post_id, $key, $single);

		return $data;
	}
}

if( !function_exists('update_post_meta') ) {

	function update_post_meta($post_id, $meta_key, $meta_value) {

		return update_metadata('post', $post_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_post_meta') ) {

	function delete_post_meta($post_id, $meta_key = '', $meta_value = '') {

		return delete_metadata('post', $post_id, $meta_key, $meta_value);
	}
}

if( !function_exists('get_post_category') ) {

    function get_post_category( $args = array() ) {

        $ci =& get_instance();

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array( 'public' => 1 ) , $args['where'] );
        }

        $model = get_model('post');

        $model->settable('categories');

        $model->settable_metabox('metabox');

        $categories =  $model->get_data($args, 'post_categories');

        $model->settable('post');

        return apply_filters( 'get_post_category', $categories, $args );
    }
}

if( !function_exists('gets_post_category') ) {

    function gets_post_category( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('post');

        $model->settable('categories');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1 ) , $args['where'] );
        }

        $cache_id = 'post_category_'.md5( serialize($args) );

        if( $ci->language['default'] != $ci->language['current'] ) {
            $cache_id = 'post_category_'.$ci->language['current'].md5( serialize($args) );
        }

        if( cache_exists($cache_id) == false ) {

            $where  = $args['where'];

            $params = $args['params'];

            if( isset($args['tree']) ) {

                if( !isset($args['tree']['data']) || !have_posts($args['tree']['data']) ) $args['tree']['data'] = array();

                $args['tree'] = array_merge( array( 'parent_id' => 0 ), $args['tree'] );

                $where['parent_id'] = $args['tree']['parent_id'];

                $model->settable('post');

                $categories =  gets_category_recurs( $args['tree']['data'], $where, $params );

            }
            else if( isset($args['mutilevel']) ){

                if( is_numeric($args['mutilevel']) ) {

                    if( $args['mutilevel'] != 0)
                        $where_level = array_merge(array('id' => $args['mutilevel']), $where);
                    else
                        $where_level = array_merge(array('parent_id' => $args['mutilevel']), $where);

                    $category = $model->fgets_categories_where('post_categories', $where_level, $params);

                    $model->settable('categories');

                    $categories = $ci->multilevel_categories($category, $where, $model, $params);
                }
                else {

                    $where = array_merge( $where, array('cate_type' => $args['mutilevel']) );

                    if( !class_exists('nestedset')) $ci->load->library('nestedset');

                    $nestedset = new nestedset(array('model' => 'post_categories_model', 'table' => 'categories', 'where' => $where));

                    $categories    = $nestedset->get_dropdown_backend();

                }
            }
            else {

                $model = get_model('post');

                $model->settable('categories');

                $model->settable_metabox('metabox');

                $categories =  $model->gets_data($args, 'post_categories');
            }

            $model->settable('post');

            save_cache($cache_id, $categories);
        }
        else {
            $categories = get_cache($cache_id);
        }

        $model->settable('post');

        return apply_filters('gets_post_category', $categories, $args);

    }
}

if( !function_exists('count_post_category') ) {

    function count_post_category( $args = array() ) {

        $ci =& get_instance();

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1 ) , $args['where'] );
        }

        $where  = $args['where'];

        $params = $args['params'];

        $model = get_model('post');

        $model->settable('categories');

        $model->settable_metabox('metabox');

        $categories =  $model->count_data($args, 'post_categories');

        $model->settable('post');

        return apply_filters('count_post_category', $categories, $args);
    }
}

function gets_category_recurs( $trees = NULL, $where = array(), $params = array()) {

    $model = get_model('post');

    $model->settable('categories');

    $params = array_merge( $params, array( 'orderby' =>'order' ) );

    if(is_admin()) {

        $root = $model->gets_where( $where , $params );
    }
    else {

        $root = $model->fgets_where( 'post_categories', $where , $params );
    }

    if( isset( $root ) &&  have_posts($root) ) {

        foreach ($root as $val) {

            $trees[] = $val;

            $where['parent_id'] = $val->id;

            $trees   = gets_category_recurs($trees, $where, $params);

        }
    }

    return $trees;
}

if( !function_exists('insert_category') ) {
    /**
     * @since  2.0.5
     */
    function insert_category( $postarr = array(), $outsite = array() ) {

        $ci =& get_instance();

        $model      = get_model('post_categories', 'backend_post');

        $model->settable('categories');

        $defaults = array(
            'name'      => '',
            'content'   => '',
            'excerpt'   => '',
            'image'     => '',
            'cate_type' => 'post_categories',
        );

        $postarr = array_merge($defaults, $postarr);

        $object = array();

        if( isset($postarr['id']) ) {

            $postarr['id'] = (int)removeHtmlTags($postarr['id']);

            $object = get_post_category( $postarr['id'] );
        }

        $ci->data['module']   = 'post_categories';

        $ci->data['param']    = array( 'slug' => 'name', 'parent' => true );

        $postarr[$ci->language['default']]['name']      = removeHtmlTags($postarr['name']); unset($postarr['name']);
        $postarr[$ci->language['default']]['content']   = $postarr['content']; unset($postarr['content']);
        $postarr[$ci->language['default']]['excerpt']   = $postarr['excerpt']; unset($postarr['excerpt']);

        $ci->cate_type = $postarr['cate_type'];

        $rules  = $ci->form_gets_field( array('class' => 'post_categories') );

        if( !class_exists('nestedset') ) $ci->load->library('nestedset', array('model' => 'post_categories_model', 'table' => 'categories', 'where' => array('cate_type' => $ci->cate_type)));

        $result = false;

        if( !have_posts($object) ) {
            $resutl =  $ci->_form_add($rules, $postarr, $outsite);
        }
        else {
            $result =  $ci->_form_edit($rules, $postarr, $object->id, $outsite);
        }

        if( isset( $result['id'] ) )
            return $result['id'];
        else
            return $result;
    }
}

if( !function_exists('delete_category') ) {
    /**
     * @since  2.0.5
     */
    function delete_category( $cate_ID = 0 ) {

        $ci =& get_instance();

        $cate_ID = (int)removeHtmlTags($cate_ID);

        if( $cate_ID == 0 ) return false;

        $model      = get_model('post_categories', 'backend_post');

        $model->settable('categories');

        $model->settable_category('categories');

        $category  = get_post_category( $cate_ID );

        if( have_posts($category) ) {

            $ci->data['module']   = 'post_categories';

            $listID = $model->gets_category_sub($category);

            if(!have_posts($listID)) $listID = [$cate_ID];
            
            $model->settable('relationships');

            $model->delete_where( array( 'category_id' => (string)$cate_ID, 'value' => $category->cate_type ) );
            
            if(have_posts($listID)) {

                $where['object_type'] = 'post_categories';

                $data['field'] = 'object_id';

                $data['data']  = $listID;

                //xóa router
                $model->settable('routes');

                $model->delete_where_in( $data, $where );

                //xóa ngôn ngữ
                $model->settable('language');

                $model->delete_where_in( $data, $where );

                //xóa gallery
                foreach ($listID as $key => $id) {
                    
                    delete_gallery_by_object($id, 'post_categories_'.$category->cate_type);

                    delete_metadata_by_mid('categories', $id);
                }

                $model->settable('categories');

                $data['field'] = 'id';

                $data['data']  = $listID;

                if($model->delete_where_in($data)) return $listID;
            }
        }

        return false;
    }
}

if( !function_exists('delete_list_category') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_category( $cate_ID = array() ) {

        $ci =& get_instance();

        $result = array();

        if(!have_posts($cate_ID)) return false;

        foreach ($cate_ID as $key => $id) {

            if( delete_category($id) != false ) $result[] = $id;
        }

        if(have_posts($result)) return $result;

        return false;
    }
}

if( !function_exists('get_post_category_meta') ) {

	function get_post_category_meta( $cateID, $key = '', $single = true) {

		$data = get_metadata('categories', $cateID, $key, $single);

		return $data;
	}
}

if( !function_exists('update_post_category_meta') ) {

	function update_post_category_meta($cateID, $meta_key, $meta_value) {

		return update_metadata('categories', $cateID, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_post_category_meta') ) {

	function delete_post_category_meta($cateID, $meta_key = '', $meta_value = '') {

		return delete_metadata('categories', $cateID, $meta_key, $meta_value);
	}
}