<?php
function tddq_config() {
    $option = [
        'receiving_form'    => 2,
        'receiving_range'   => [],
        'receiving_conver'  => [],
        'point_type'        => 1,
        'point_conver'      => 1,
        'pay_type'          => [1],
    ];
    $option_save = get_option('tddq_congfig', []);
    if(empty($option_save['receiving_form'])) $option_save['receiving_form'] = $option['receiving_form'];
    if(empty($option_save['point_type'])) $option_save['point_type'] = $option['point_type'];
    if(empty($option_save['pay_type'])) $option_save['pay_type'] = $option['pay_type'];
    if(empty($option_save['point_conver'])) $option_save['point_conver'] = $option['point_conver'];
    if(!isset($option_save['receiving_range']) || !have_posts($option_save['receiving_range'])) $option_save['receiving_range'] = $option['receiving_range'];
    if(!isset($option_save['receiving_conver']) || !have_posts($option_save['receiving_conver'])) $option_save['receiving_conver'] = $option['receiving_conver'];
    return $option_save;
}
function tddq_calculate_point($total, $user_id) {
    $config  = tddq_config();
    $return = 0;
    $total_return = $total;
    if($config['receiving_form'] == 1) {
        $total = get_user($user_id)->order_total;
    }
    if($config['point_type'] == 1) {
        $percent = tddq_get_percent($total, $config);
        $return = $total*$percent/100;
    }
    else {
        $conver = [];
        foreach ($config['receiving_conver'] as $key => $rand) {
            if($total >= $rand['form'] && $total <= $rand['to'] ) {
                $conver = $rand;
                break;
            }
        }
        if(have_posts($conver)) $return = (int)($total_return/$conver['value']*$conver['point']);
    }
    return $return;
}
function tddq_get_percent($total, $config = []) {
    if(!have_posts($config)) $config = tddq_config();
    foreach ($config['receiving_range'] as $key => $rand) {
        if($total >= $rand['form'] && $total <= $rand['to'] ) {
            return $rand['percent'];
        }
    }
    return 0;
}
if( !function_exists('get_tddq_history') ) {
    function get_tddq_history( $args = array() ) {
        $ci =& get_instance();
        $model  = get_model('home');
        $model->settable('tddq_history');
        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
        if( !have_posts($args) ) return array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where  = $args['where'];
        $params = $args['params'];
        $tddq_history = $model->get_where( $where, $params );
        return apply_filters('get_tddq_history', $tddq_history);
    }
}
if( !function_exists('gets_tddq_history') ) {
    function gets_tddq_history( $args = array() ) {
        $ci =& get_instance();
        $model      = get_model('home');
        $model->settable('tddq_history');
        if( !have_posts($args) ) $args = array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where  = $args['where'];
        $params = $args['params'];
        if( isset($args['where_in'])) {
            $data = $args['where_in'];
            $tddq_historys = $model->gets_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {
            $data['like'] = $args['where_like'];
            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];
            $tddq_historys = $model->gets_where_like($data, $where, $params);
        }
        else $tddq_historys = $model->gets_where( $where, $params );
        return apply_filters('gets_tddq_history', $tddq_historys);
    }
}
if(!function_exists('insert_tddq_history')) {
    function insert_tddq_history( $tddq_history = array() ) {
        $ci = &get_instance();
        $model = get_model('home');
        $model->settable('tddq_history');
        $user = get_user_current();
        if ( ! empty( $tddq_history['id'] ) ) {
            $id             = (int) $tddq_history['id'];
            $update        = true;
            $old_tddq_history = get_tddq_history($id);
            if ( ! $old_tddq_history ) return new SKD_Error( 'invalid_tddq_history_id', __( 'ID tddq_history không chính xác.' ) );
        } else {
            $update = false;
        }
        if(!empty($tddq_history['point']))   $point     =  removeHtmlTags($tddq_history['point']);
        if(!empty($tddq_history['user_id'])) $user_id   =  removeHtmlTags($tddq_history['user_id']);
        if(!empty($tddq_history['content'])) $content   =  removeHtmlTags($tddq_history['content']);
        if(!empty($tddq_history['type']))    $type      =  removeHtmlTags($tddq_history['type']);
        if(!$update)  {
            if(empty($tddq_history['type'])) $type = 'history';
        }
        if(!empty($tddq_history['status']))  $status    =  removeHtmlTags($tddq_history['status']);
        if(!$update)  {
            if(empty($tddq_history['status'])) $status = 'success';
        }
        if(!empty($tddq_history['bank']))    $bank      =  removeHtmlTags($tddq_history['bank']);
        if(!empty($tddq_history['point_conver']))    $point_conver      =  removeHtmlTags($tddq_history['point_conver']);
        $data = compact( 'point', 'user_id', 'content', 'type', 'status', 'bank', 'point_conver' );
        if ( $update ) {
            $model->settable('tddq_history');
            $model->update_where( $data, compact( 'id' ) );
            $tddq_history_id = (int) $id;
        }
        else {
            $model->settable('tddq_history');
            $tddq_history_id = $model->add( $data );
        }
        return $tddq_history_id;
    }
}
if( !function_exists('count_tddq_history') ) {
    function count_tddq_history( $args = array() ) {
        $ci =& get_instance();
        $model      = get_model('home');
        $model->settable('tddq_history');
        if( !have_posts($args) ) $args = array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where  = $args['where'];
        $params = $args['params'];
        if( isset($args['where_in'])) {
            $data = $args['where_in'];
            $tddq_historys = $model->count_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {
            $data['like'] = $args['where_like'];
            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];
            $tddq_historys = $model->count_where_like($data, $where, $params);
        }
        else $tddq_historys = $model->count_where( $where, $params );
        return apply_filters('count_tddq_history', $tddq_historys);
    }
}
function tddq_checkout_check($point, $user_id, $total) {
    $account_point = (int)get_user_meta($user_id, 'tddq_point', true);
    if($account_point < $point) {
        return new SKD_error('invalid_point', __( 'Điểm thưởng của bạn không đủ.' ));
    }
    return true;
}
function tddq_pay_type_check($pay_type_id) {
    $tddq_pay_type = tddq_config()['pay_type'];
    if(!is_array($tddq_pay_type)) $tddq_pay_type = [1];
    if(in_array($pay_type_id, $tddq_pay_type) !== false ) return true;
    return false;
}
function tddq_history_status($status_id = '') {
    $status = [
        'wait'      => ['label' => 'Đang xử lý'],
        'success'   => ['label' => 'Đã hoàn thành'],
        'cancel'   => ['label' => 'Đã hủy'],
    ];
    if(isset($status[$status_id])) return $status[$status_id];
    return $status;
}
/////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('insert_affiliate_history')) {
    function insert_affiliate_history( $affiliate_history = array() ) {
        $ci = &get_instance();
        $model = get_model('home');
        $model->settable('affiliate_history');
        $user = get_user_current();
        if ( ! empty( $affiliate_history['id'] ) ) {
            $id             = (int) $affiliate_history['id'];
            $update        = true;
            $old_affiliate_history = get_affiliate_history($id);
            if ( ! $old_affiliate_history ) return new SKD_Error( 'invalid_affiliate_history_id', __( 'ID affiliate_history không chính xác.' ) );
        } else {
            $update = false;
        }
        if(!empty($affiliate_history['cookie']))   $cookie     =  removeHtmlTags($affiliate_history['cookie']);
        if(!empty($affiliate_history['user_id'])) $user_id   =  removeHtmlTags($affiliate_history['user_id']);
        if(!empty($affiliate_history['url'])) $url   =  removeHtmlTags($affiliate_history['url']);
        if(!empty($affiliate_history['type']))    $type      =  removeHtmlTags($affiliate_history['type']);
        if(!empty($affiliate_history['title']))    $title      =  removeHtmlTags($affiliate_history['title']);
        if(!empty($affiliate_history['image']))    $image      =  removeHtmlTags($affiliate_history['image']);
        
        $data = compact( 'cookie', 'user_id', 'url', 'type','title','image' );
        if ( $update ) {
            $model->settable('affiliate_history');
            $model->update_where( $data, compact( 'id' ) );
            $affiliate_history_id = (int) $id;
        }
        else {
            $model->settable('affiliate_history');
            $affiliate_history_id = $model->add( $data );
        }
        return $affiliate_history_id;
    }
}

if( !function_exists('count_affiliate_history') ) {

    function count_affiliate_history( $args = array() ) {

        $ci =& get_instance();

        $model      = get_model('home');

        $model->settable('affiliate_history');

        if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where  = $args['where'];

        $params = $args['params'];

        if( isset($args['where_in'])) {

            $data = $args['where_in'];

            $affiliate_historys = $model->count_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $affiliate_historys = $model->count_where_like($data, $where, $params);
        }
        else $affiliate_historys = $model->count_where( $where, $params );

        return apply_filters('count_affiliate_history', $affiliate_historys);
    }
}


if( !function_exists('affiliate_history') ) {
    function affiliate_history( $args = array() ) {
        $ci =& get_instance();
        $model      = get_model('home');
        $model->settable('affiliate_history');
        if( !have_posts($args) ) $args = array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where  = $args['where'];
        $params = $args['params'];
        if( isset($args['where_in'])) {
            $data = $args['where_in'];
            $affiliate_historys = $model->gets_where_in( $data, $where, $params );
        }
        else if( isset($args['where_like'])) {
            $data['like'] = $args['where_like'];
            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];
            $affiliate_historys = $model->gets_where_like($data, $where, $params);
        }
        else $affiliate_historys = $model->gets_where( $where, $params );
        return apply_filters('affiliate_history', $affiliate_historys);
    }
}

if( !function_exists('get_affiliate_history') ) {
    function get_affiliate_history( $args = array() ) {
        $ci =& get_instance();
        $model  = get_model('home');
        $model->settable('affiliate_history');
        if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
        if( !have_posts($args) ) return array();
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
        $where  = $args['where'];
        $params = $args['params'];
        $affiliate_history = $model->get_where( $where, $params );
        return apply_filters('get_affiliate_history', $affiliate_history);
    }
}
function tddq_ajax_affiliate($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {

       $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $size = strlen( $chars );
       $str='';
       $str2='';
       for( $i = 0; $i < 5; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];
        $str2 .= $chars[ rand( 0, $size - 1 ) ];
        }


        $data      = $ci->input->post();
        $user       = get_user_current();
        $link=$data['link'];
        $code1=$data['code'];
        unset($data['action']);
        $mang=explode(base_url(), $link);
        $slug=$mang[1];
        $slug=explode('/', $slug);
        $slug=$slug[0];
        
        $where  = array('slug'=>$slug);
        $params =array();
        $args = array_merge( array('where' => $where, 'params' => array() ) );
        $post = get_post($args);
        $product = get_product($args);
        if (!empty($post) && $post->slug==$slug) {
            $post2=$post;
            $type='post';
        }
        if (!empty($product) && $product->slug==$slug) {
            $post2=$product;
            $type='product';
        }

        $url=base_url().$slug;
        $code2=removeHtmlTags($code1.$str);
        if(have_posts($user)) {
            $where=array('url'=>$url,'cookie'=>$code2);
            $tontai=get_affiliate_history(array('where' => $where, 'params' => array()));
            if (!empty($tontai)) {
                $code2=removeHtmlTags($code1.$str2);
            }
            insert_affiliate_history([
                'user_id' => $user->id,
                'type'    => $type,
                'url'    => $url,
                'cookie' => $code2,
                'title' => $post2->title,
                'image' => $post2->image,

            ]);
            $result['status']   = 'success';
            $result['message']  = 'Tạo link chia sẻ thành công.';

        }
        unset($data);
    }
    echo json_encode($result);
}
register_ajax_login('tddq_ajax_affiliate');

