<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('register_ajax')){
    /**
     * Ajax : không cần đăng nhập, chạy ở tất cả trường hợp
     * @since  2.0.0
     */
    function register_ajax( $action = null ) {

        if( $action == null ) return;

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['nopriv'] ) === false)  $ci->ajax['nopriv'][] = $action;
    }
}

if(!function_exists('register_ajax_login')){
    /**
     * Ajax : phải đăng nhập
     * @since  2.0.0
     */
    function register_ajax_login( $action = null ) {

        if( $action == null ) return;

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['login'] ) === false) $ci->ajax['login'][] = $action;
    }
}

if(!function_exists('register_ajax_admin')){
    /**
     * Ajax : chỉ chạy ở admin và phải đăng nhập
     * @since  2.0.0
     */
    function register_ajax_admin( $action = null ) {

        if( $action == null ) return;

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['admin'] ) === false) $ci->ajax['admin'][] = $action;

    }
}


if(!function_exists('register_ajax_admin_nov')){
    /**
     * Ajax : chỉ chạy ở admin và không cần đăng nhập
     * @since  2.0.0
     */

    function register_ajax_admin_nov( $action = null ) {

        if( $action == null ) return;

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['admin_nov'] ) === false) $ci->ajax['admin_nov'][] = $action;

    }
}

if(!function_exists('isset_ajax_all')){

    /**
     * Ajax : kiểm tra ajax đã được khai báo chưa
     * @since  2.0.0
     */
    function isset_ajax_all($action = null) {

        if( $action == null ) return;

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['nopriv'] ) !== false) return true;

        if(in_array($action,  $ci->ajax['login'] ) !== false) return true;

        if(in_array($action,  $ci->ajax['admin'] ) !== false) return true;

        if(in_array($action,  $ci->ajax['admin_nov'] ) !== false) return true;

        return false;
    }
}

if(!function_exists('isset_ajax')){

    /**
     * @since  2.0.0
     */
    function isset_ajax($action) {

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['nopriv'] ) !== false) return true;

        return false;
    }
}

if(!function_exists('isset_ajax_login')){

    /**
     * @since  2.0.0
     */
    function isset_ajax_login($action) {

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['login'] ) !== false) return true;

        return false;
    }
}

if(!function_exists('isset_ajax_admin')){

    /**
     * @since  2.0.0
     */
    function isset_ajax_admin($action) {

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['admin'] ) !== false) return true;

        return false;
    }
}

if(!function_exists('isset_ajax_admin_nov')){

    /**
     * @since  2.0.0
     */
    function isset_ajax_admin_nov($action) {

        $ci =& get_instance();

        if(in_array($action,  $ci->ajax['admin_nov'] ) !== false) return true;

        return false;
    }
}


if(!function_exists('remove_ajax')){

    /**
     * Ajax : xóa ajax đã đăng ký
     * @since  2.0.0
     */
    function remove_ajax($action) {

        $ci =& get_instance();

        //kiểm tra function có trong list chưa
        $key = array_search($action,  $ci->ajax['nopriv']);

        if( $key !== false) unset($ci->ajax['nopriv'][$key]);

        $key = array_search($action,  $ci->ajax['login']);

        if( $key !== false) unset($ci->ajax['login'][$key]);

        $key = array_search($action,  $ci->ajax['admin']);

        if( $key !== false) unset($ci->ajax['admin'][$key]);

        $key = array_search($action,  $ci->ajax['admin_nov']);
        
        if( $key !== false) unset($ci->ajax['admin_nov'][$key]);
    }
}