<?php
if( !function_exists('get_tags') ) {

    function get_tags( $args = array() ) {

        $ci =& get_instance();

        $model  = get_model('home');

        $model->settable('tags');

        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

        if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where  = $args['where'];

        $params = $args['params'];

        $tags = $model->get_where( $where, $params );

        return apply_filters('get_tags', $tags);
    }
}

if( !function_exists('gets_tags') ) {

    function gets_tags( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('home');

        $model->settable('tags');

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where  = $args['where'];

        $params = $args['params'];

        if( isset($args['where_in'])) {

            $data = $args['where_in'];

            $tagss = $model->gets_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $tagss = $model->gets_where_like($data, $where, $params);
        }
        else $tagss = $model->gets_where( $where, $params );

        return apply_filters('gets_tags', $tagss);
    }
}

if(!function_exists('insert_tags')) {

    function insert_tags( $tags = array() ) {

        $ci = &get_instance();

        $model = get_model('home');

        $model->settable('tags');

        $user = get_user_current();

        if ( ! empty( $tags['id'] ) ) {

            $id             = (int) $tags['id'];

            $update        = true;

            $old_tags = get_tags($id);

            if ( ! $old_tags ) return new SKD_Error( 'invalid_tags_id', __( 'ID tags không chính xác.' ) );
        } else {
            $update = false;
        }
        
        $name            =  removeHtmlTags($tags['name']);

        $name_format     =  strtolower($name);

        $slug            =  'tag/'.slug($name);

        $data = compact( 'name', 'name_format', 'slug' );
    
        if ( $update ) {

            $model->settable('tags');

            $model->update_where( $data, compact( 'id' ) );

            $tags_id = (int) $id;
        }
        else {

            $model->settable('tags');

            $tags_id = $model->add( $data );
        }

        return $tags_id;
    }
}

if( !function_exists('count_tags') ) {

    function count_tags( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('home');

        $model->settable('tags');

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where  = $args['where'];

        $params = $args['params'];

        if( isset($args['where_in'])) {

            $data = $args['where_in'];

            $tagss = $model->count_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $tagss = $model->count_where_like($data, $where, $params);
        }
        else $tagss = $model->count_where( $where, $params );

        return apply_filters('count_tags', $tagss);
    }
}