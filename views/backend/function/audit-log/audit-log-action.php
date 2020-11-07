<?php
/**
 * Log khi đăng nhập hệ thống
 */
function audit_log_login( $user ) {

	$ci =& get_instance();

	$ci->load->library('user_agent');

	$log = array(
		'username'     => $user->username,
		'fullname'     => $user->firstname.' '.$user->lastname,
		'ip'           => getRealIpAddr(),
		'action'       => 'login',
		'message'       => 'đăng nhập hệ thống.',
		'time'         => time(),
		'agent_string' => $ci->agent->agent_string(),
	);

	save_audit_log($log);
}

add_action( 'skd_admin_login', 'audit_log_login' );


function audit_log_object_add( $id, $model) {

    $ci =& get_instance();

    $module = $ci->data['module'];

    $ci->load->library('user_agent');

    $user = get_user_current();
    
    $log = array(
		'username'     => $user->username,
		'fullname'     => $user->firstname.' '.$user->lastname,
		'ip'           => getRealIpAddr(),
		'action'       => 'add',
		'time'         => time(),
		'agent_string' => $ci->agent->agent_string(),
	);

    if($module == 'post') {

        $object = get_post($id);

        $log['message'] = 'thêm bài viết <b>'.$object->title.'</b>';
    }

    if($module == 'post_categories') {

        $object = get_post_category($id);

        $log['message'] = 'thêm danh mục <b>'.$object->name.'</b>';
    }

    if($module == 'page') {

        $object = get_page($id);

        $log['message'] = 'thêm trang nội dung <b>'.$object->title.'</b>';
    }

    if(!empty($log['message'])) save_audit_log($log);
}

add_action( 'save_object_add', 'audit_log_object_add', 2, 2 );

function audit_log_object_edit( $id, $model) {

    $ci =& get_instance();

    $module = $ci->data['module'];

    $ci->load->library('user_agent');

    $user = get_user_current();

    $log = array(
		'username'     => $user->username,
		'fullname'     => $user->firstname.' '.$user->lastname,
		'ip'           => getRealIpAddr(),
		'action'       => 'edit',
		'time'         => time(),
		'agent_string' => $ci->agent->agent_string(),
	);

    if($module == 'post') {

        $object = get_post($id);

        $log['message'] = 'cập nhật bài viết <b>'.$object->title.'</b>';
    }

    if($module == 'post_categories') {

        $object = get_post_category($id);

        $log['message'] = 'cập nhật danh mục <b>'.$object->name.'</b>';
    }

    if($module == 'page') {

        $object = get_page($id);

        $log['message'] = 'cập nhật trang nội dung <b>'.$object->title.'</b>';
    }

    if(!empty($log['message'])) save_audit_log($log);
}

add_action( 'save_object_edit', 'audit_log_object_edit', 2, 2 );

function audit_log_object_delete($module, $id) {

    $ci =& get_instance();

    $ci->load->library('user_agent');

    $user = get_user_current();

    $log = array(
		'username'     => $user->username,
		'fullname'     => $user->firstname.' '.$user->lastname,
		'ip'           => getRealIpAddr(),
		'action'       => 'delete',
		'time'         => time(),
		'agent_string' => $ci->agent->agent_string(),
    );

    $listID = $id;
    
    if(is_numeric($id)) $listID = [$id];

    foreach ($listID as $key => $id) {

        $log['message'] = '';

        if($module == 'post') {

            $object = get_post($id);

            $log['message'] = 'xóa bài viết <b>'.$object->title.'</b>';
        }

        if($module == 'post_categories') {

            $object = get_post_category($id);

            $log['message'] = 'xóa danh mục <b>'.$object->name.'</b>';
        }

        if($module == 'page') {

            $object = get_page($id);

            $log['message'] = 'xóa trang nội dung <b>'.$object->title.'</b>';
        }

        if(!empty($log['message'])) save_audit_log($log);
    }

    
}

add_action( 'ajax_delete_after_success', 'audit_log_object_delete', 2, 2 );