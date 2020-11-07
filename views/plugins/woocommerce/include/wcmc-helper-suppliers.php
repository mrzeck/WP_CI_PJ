<?php
if( !function_exists('get_suppliers') ) {

    function get_suppliers( $args = array() ) {

        $ci =& get_instance();

        $model = get_model('home');

        $model->settable('suppliers');

        $model->settable_metabox('suppliers_metadata');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $suppliers = $model->get_data( $args, 'suppliers' );

        return apply_filters('get_suppliers', $suppliers, $args);
    }
}

if( !function_exists('get_suppliers_by') ) {
    /**
     * [get_suppliers_by]
     * @param  [version] 1.9.1
     */
    function get_suppliers_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters('get_suppliers_by', get_suppliers($args), $field, $value );
    }
}

if( !function_exists('gets_suppliers') ) {

    function gets_suppliers( $args = array() ) {

        $ci =& get_instance();

        $model 	= get_model('home');

        $model->settable('suppliers');

        $model->settable_metabox('metabox');

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $suppliers = $model->gets_data($args, 'suppliers');

        $model->settable('suppliers');

        return apply_filters( 'gets_suppliers', $suppliers, $args );
    }
}

if( !function_exists('gets_suppliers_by') ) {
    /**
     * [gets_suppliers_by]
     * @param  [version] 2.3.4
     */
    function gets_suppliers_by( $field, $value, $params = array() ) {

        $ci =& get_instance();

        $field = removeHtmlTags( $field );

        $value = removeHtmlTags( $value );

        $args = array( 'where' => array( $field => $value ) );

        if( have_posts($params) ) $arg['params'] = $params;

        return apply_filters( 'gets_suppliers_by', gets_suppliers($args), $field, $value );
    }
}

if( !function_exists('count_suppliers') ) {

    function count_suppliers( $args = array() ) {

        $ci =& get_instance();

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $model = get_model('home');

        $model->settable('suppliers');

		$model->settable_metabox('suppliers_metadata');

		$suppliers = $model->count_data($args, 'suppliers');
		
	    return apply_filters('count_suppliers', $suppliers, $args );
    }
}

if( !function_exists('insert_suppliers') ) {
    /**
     * @since  1.9.1
     */
    function insert_suppliers( $suppliers = array() ) {

        $ci =& get_instance();

        $model = get_model('home');

        $model->settable('suppliers');

        $user = get_user_current();

        if ( ! empty( $suppliers['id'] ) ) {

            $id             = (int) $suppliers['id'];

            $update        = true;

            $old_suppliers = get_suppliers($id);

            if ( ! $old_suppliers ) return new SKD_Error( 'invalid_suppliers_id', __( 'ID bài viết không chính xác.' ) );

            $user_updated = ( have_posts($user) ) ? $user->id : 0;

            $user_created = $old_suppliers->user_created;
        }
        else {

            $update = false;

            $user_updated = 0;

            $user_created = ( have_posts($user) ) ? $user->id : 0;
        }

        if(empty($suppliers['name']) ) {

            $language = $ci->language;

            if(!empty($suppliers[$language['default']]['name'])) {
                $suppliers['name'] = $suppliers[$language['default']]['name'];
            }
        }

        if(empty($suppliers['language']) ) {

            $language = $ci->language;

            foreach ($language['language_list'] as $key => $label) {

                if( $key != $language['default'] ) {

                    if(!empty($suppliers[$key]['name'])) {

                        $suppliers['language'][$key] = $suppliers[$key];
                    }
                }
            }
        }

        if( ! $update ) {

            if ( empty( $suppliers['name'] ) ) return new SKD_Error('empty_suppliers_name', __('Không thể cập nhật nhà sản xuất khi tên để trống.') );

            $slug = $ci->create_slug( removeHtmlTags( $suppliers['name'] ), $model );
        }
        else {

            if ( empty( $suppliers['name'] ) ) return new SKD_Error('empty_suppliers_name', __('Không thể thêm nhà sản xuất khi tên để trống.') );

            $slug = empty( $suppliers['slug'] ) ? $old_suppliers->slug : slug($suppliers['slug']);

            if( $slug != $old_suppliers->slug ) $slug = $ci->edit_slug( $slug , $id, $model );
        }

        $name      = removeHtmlTags( $suppliers['name'] );

        $pre_name  = apply_filters( 'pre_suppliers_name', $name );

        $name      = trim( $pre_name );

        if( !empty( $suppliers['firstname'] ) )         $firstname         =  removeHtmlTags($suppliers['firstname']);
        
        if( !empty( $suppliers['lastname'] ) )           $lastname         =  removeHtmlTags($suppliers['lastname']);

        if( !empty( $suppliers['email'] ) )              $email         =  removeHtmlTags($suppliers['email']);

        if( !empty( $suppliers['phone'] ) )              $phone         =  removeHtmlTags($suppliers['phone']);

        if( !empty( $suppliers['address'] ) )            $address         =  removeHtmlTags($suppliers['address']);
        
        if( !empty( $suppliers['seo_title'] ) )          $seo_title       =  removeHtmlTags($suppliers['seo_title']);
        
        if( !empty( $suppliers['seo_description'] ) )    $seo_description =  removeHtmlTags($suppliers['seo_description']);
        
        if( !empty( $suppliers['seo_keywords'] ) )       $seo_keywords    =  removeHtmlTags($suppliers['seo_keywords']);

        if( !empty( $suppliers['image'] ) ) {

            $image    = removeHtmlTags($suppliers['image']);

            $image    = process_file($image);
        }

        if(empty($seo_title)) {

            $seo_title = $name;
        }
        
        $data = compact( 'name', 'slug', 'firstname', 'lastname', 'email', 'phone', 'address', 'seo_title', 'seo_description', 'seo_keywords', 'image' );

        $data = apply_filters( 'pre_insert_suppliers_data', $data, $suppliers, $update ? (int) $id : null );

        $language       = !empty( $suppliers['language'] ) ? $suppliers['language'] : array();

        if ( $update ) {

            $model->settable('suppliers');

            $model->update_where( $data, compact( 'id' ) );

            $suppliers_id = (int) $id;

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['object_type']  = 'suppliers';

            $router['object_id']    = $suppliers_id;

            if( $model->update_where( array('slug' => $slug ), $router ) == 0 ) {

                $router['slug']         = $slug;

                $router['directional']  = 'suppliers';

                $router['controller']   = 'frontend_home/home/page/';

                $router['callback']     = 'wcmc_suppliers_frontend';

                $model->add($router);
            }

            if( have_posts($language) ) {

                $model->settable('language');

                foreach ($language as $key => $val) {
                    
                    $lang['language']       = $key;

                    $lang['object_id']      = $suppliers_id;

                    $lang['object_type']    = 'suppliers';

                    if($model->count_where($lang)) {

                        $model->update_where(['name' => removeHtmlTags($val['name'])], $lang);
                    }
                    else {

                        $lang['name']          = removeHtmlTags($val['name']);

                        $model->add($lang);
                    }
                }
            }

        } else {

            $model->settable('suppliers');

            $suppliers_id = $model->add( $data );

            /*=============================================================
            ROUTER
            =============================================================*/
            $model->settable('routes');

            $router['slug']         = $slug;

            $router['directional']  = 'suppliers';

            $router['controller']   = 'frontend_home/home/page/';

            $router['callback']     = 'wcmc_suppliers_frontend';

            $router['object_id']    = $suppliers_id;

            $model->add($router);

            /*=============================================================
            LANGUAGE
            =============================================================*/
            if( have_posts($language) ) {

                $model->settable('language');

                foreach ($language as $key => $val) {

                    $lang['name']          = removeHtmlTags($val['name']);
                    
                    $lang['language']       = $key;

                    $lang['object_id']      = $suppliers_id;

                    $lang['object_type']    = 'suppliers';

                    $model->add($lang);
                }
            }

        }

        $model->settable('suppliers');

        $suppliers_id  = apply_filters( 'after_insert_suppliers', $suppliers_id, $suppliers, $data, $update ? (int) $id : null  );

        return $suppliers_id;
    }
}

if( !function_exists('delete_suppliers') ) {
    /**
     * @since  2.0.0
     */
    function delete_suppliers( $suppliersID = 0, $trash = false ) {

        $ci =& get_instance();

        $suppliersID = (int)removeHtmlTags($suppliersID);

        if( $suppliersID == 0 ) return false;

        $model      = get_model('home');

        $model->settable('suppliers');

        $suppliers  = get_suppliers( $suppliersID );

        if( have_posts($suppliers) ) {

            $ci->data['module']   = 'suppliers';

            //nếu bỏ vào thùng rác
            if( $trash == true ) {

                do_action('delete_suppliers_trash', $suppliersID );

                if($model->update_where(array('trash' => 1), array('id' => $suppliersID))) {

                    return [$suppliersID];
                }

                return false;
            }
            do_action('delete_suppliers', $suppliersID );

            if($model->delete_where(['id'=> $suppliersID])) {

                do_action('delete_suppliers_success', $suppliersID );

                //delete language
                $model->settable('language');

                $model->delete_where(['id'=> $suppliersID, 'object_type' => 'suppliers']);

                //delete router
                $model->settable('routes');

                $model->delete_where(['id'=> $suppliersID, 'object_type' => 'suppliers']);

                //delete gallerys
                delete_gallery_by_object($suppliersID, 'suppliers');

                delete_metadata_by_mid('suppliers', $suppliersID);

                //delete menu
                $model->settable('menu');

                $model->delete_where(['id'=> $suppliersID, 'object_type' => 'suppliers']);

                //xóa liên kết
                $model->settable('relationships');

                $model->delete_where(['object_id'=> $suppliersID, 'object_type' => 'suppliers']);

                return [$suppliersID];
            }
        }

        return false;
    }
}

if( !function_exists('delete_list_suppliers') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_suppliers( $suppliersID = array(), $trash = false ) {

        $ci =& get_instance();

        if(have_posts($suppliersID)) {

            $model      = get_model('home');

            $model->settable('suppliers');

            if( $trash == true ) {

                do_action('delete_suppliers_list_trash', $suppliersID );

                if($model->update_where_in(['field' => 'id', 'data' => $suppliersID], ['trash' => 1])) {

                    return $suppliersID;
                }

                return false;
            }


            $supplierss = gets_suppliers(['where_in' => ['field' => 'id', 'data' => $suppliersID]]);

            if($model->delete_where_in(['field' => 'id', 'data' => $suppliersID])) {

                $where_in = ['field' => 'object_id', 'data' => $suppliersID];

                do_action('delete_suppliers_list_trash_success', $suppliersID );

                //delete language
                $model->settable('language');

                $model->delete_where_in($where_in, ['object_type' => 'suppliers']);

                //delete router
                $model->settable('routes');

                $model->delete_where_in($where_in, ['object_type' => 'suppliers']);

                //delete router
                foreach ($supplierss as $key => $suppliers) {
                    
                    delete_gallery_by_object($suppliers->id, 'suppliers');

                    delete_metadata_by_mid('suppliers', $suppliers->id);
                }

                //delete menu
                $model->settable('menu');

                $model->delete_where_in($where_in, ['object_type' => 'suppliers']);

                //xóa liên kết
                $model->settable('relationships');

                $model->delete_where_in($where_in, ['object_type' => 'suppliers']);

                return $suppliersID;
            }
        }

        return false;
    }
}

if( !function_exists('get_suppliers_meta') ) {

	function get_suppliers_meta( $suppliers_id, $key = '', $single = true) {

		$data = get_metadata('suppliers', $suppliers_id, $key, $single);

		return $data;
	}
}

if( !function_exists('update_suppliers_meta') ) {

	function update_suppliers_meta($suppliers_id, $meta_key, $meta_value) {

		return update_metadata('suppliers', $suppliers_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_suppliers_meta') ) {

	function delete_suppliers_meta($suppliers_id, $meta_key = '', $meta_value = '') {

		return delete_metadata('suppliers', $suppliers_id, $meta_key, $meta_value);
	}
}

if( !function_exists('gets_suppliers_option') ) {

    function gets_suppliers_option() {

        $ci =& get_instance();

        $suppliers = gets_suppliers();

        $options = ['Chọn nhà sản xuất'];

        foreach ($suppliers as $key => $supplier) {

            $options[$supplier->id] = $supplier->name;
        }

        return apply_filters( 'gets_suppliers_option', $options );
    }
}