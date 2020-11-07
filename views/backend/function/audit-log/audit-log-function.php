<?php
function getRealIpAddr() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

      $ip = $_SERVER['HTTP_CLIENT_IP'];

    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

    }
    else {

      $ip = $_SERVER['REMOTE_ADDR'];

    }

    return $ip;
}

function get_audit_log( $audit_log_file = null ) {

    if( $audit_log_file == null ) $audit_log_file = 'audit_log_'.date('m-y');

    $path = FCPATH . VIEWPATH . 'log/'.$audit_log_file;

    $data = array();

    if ( file_exists( $path ) ) {

        $data = read_file( $path );

        $data = unserialize($data);
    }

    return $data;
}

function save_audit_log( $audit_log_data = array(), $audit_log_file = null ) {

	$ci =& get_instance();

	if( $audit_log_file == null ) $audit_log_file = 'audit_log_'.date('m-y');

	$audit_log 		= get_audit_log( $audit_log_file );

	$audit_log[] 	= $audit_log_data;

	$path = FCPATH . VIEWPATH . 'log/'.$audit_log_file;

    if (write_file( $path , serialize($audit_log))) {

        @chmod( $path, 0777);

        return true;
    }

    return false;
}

function get_audit_icon( $action = '' ) {

  $icon = '';

  if($action == 'login') $icon = '<i class="fal fa-sign-in"></i>';

  if($action == 'add') $icon = '<i class="fal fa-plus"></i>';

  if($action == 'edit') $icon = '<i class="fas fa-pencil"></i>';

  if($action == 'delete') $icon = '<i class="fas fa-trash"></i>';

  return apply_filters('get_audit_icon', $icon, $action);
}