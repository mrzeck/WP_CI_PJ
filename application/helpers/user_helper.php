<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists('skd_signon') ) {
	
	function skd_signon( $credentials = array() ) {

		$ci =& get_instance();

		if( empty($credentials) ) {

			$credentials = array();

			$post = $ci->input->post();

			if ( ! empty($post['username']) ) $credentials['username'] = removeHtmlTags($post['username']);

	        if ( ! empty($post['password']) ) $credentials['password'] = removeHtmlTags($post['password']);

		}

		do_action_ref_array( 'skd_authenticate', array( &$credentials['username'], &$credentials['password'] ) );

		$user = skd_authenticate($credentials['username'], $credentials['password']);

		if ( is_skd_error($user) ) {

			return $user;
		}

		/**
		 * Update password nếu password của phiên bản củ
		 */
		$hash = generate_password_old( $credentials['password'], $user->username, $user->salt );

		if( $hash == $user->password ) {

			$user->password = generate_password( $credentials['password'], $user->username, $user->salt );

			insert_user((array)$user);

			$user = get_user_by('id', $user->id);
		}

		skd_set_auth_cookie( $user );

		do_action( 'skd_login', $user->username, $user );

		return $user;

	}
}

if( !function_exists('skd_authenticate') ) {
	
	function skd_authenticate( $username, $password) {

		$ci =& get_instance();

		$username = removeHtmlTags($username);

		$password = trim($password);

		$user = apply_filters( 'authenticate', null, $username, $password );

		$user = skd_authenticate_username_email_password( $user, $username, $password );

		if ( $user == null ) {

			$user = new SKD_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Tên đăng nhập, địa chỉ email hoặc mật khẩu không đúng!' ) );

			do_action( 'skd_login_failed', $username );
		}

		return $user;

	}
}

if( !function_exists('skd_authenticate_username_email_password') ) {
	
	function skd_authenticate_username_email_password( $user, $user_login, $password) {

		$ci =& get_instance();

		if ( empty($user_login) || empty($password) ) {

			if ( is_skd_error( $user ) ) return $user;

			$error = new SKD_Error();

			if ( empty($user_login) ) $error->add('empty_user_login', __('<strong>ERROR</strong>: Tên người dùng không được bỏ trống.'));

			if ( empty($password) ) $error->add('empty_password', __('<strong>ERROR</strong>: Mật khẩu không được bỏ trống.'));

			return $error;
		}

		$user = get_user_by( 'username', $user_login );

		if ( !have_posts($user) ) {

			$user = get_user_by( 'email', $user_login );

			if ( !have_posts($user) ) return new SKD_Error( 'invalid_user_login', __( '<strong>ERROR</strong>: Tên người dùng không hợp lệ.' ) );
		}

		$user = apply_filters( 'skd_authenticate_user', $user, $password );

		if ( is_skd_error($user) ) return $user;

		if ( ! skd_check_password( $password, $user ) ) {

			return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: Mật khẩu bạn đã nhập cho tên người dùng hoặc địa chỉ email %s không chính xác.' ), '<strong>' . $user_login . '</strong>'));
		}

		return $user;

	}
}

if( !function_exists('skd_authenticate_username_password') ) {
	
	function skd_authenticate_username_password( $user, $username, $password) {

		$ci =& get_instance();

		if ( empty($username) || empty($password) ) {

			if ( is_skd_error( $user ) ) return $user;

			$error = new SKD_Error();

			if ( empty($username) ) $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

			if ( empty($password) ) $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

			return $error;
		}

		$user = get_user_by( 'username', $username );

		if ( !have_posts($user) ) {

			return new SKD_Error( 'invalid_username', __( '<strong>ERROR</strong>: Invalid username.' ) .' <a href="">' . __( 'Lost your password?' ) . '</a>' );
		}

		$user = apply_filters( 'wp_authenticate_user', $user, $password );

		if ( is_skd_error($user) ) return $user;

		if ( ! skd_check_password( $password, $user ) ) {

			return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: The password you entered for the username %s is incorrect.' ), '<strong>' . $username . '</strong>').' <a href="">' .__( 'Lost your password?' ) .'</a>' );
		}

		return $user;

	}
}

if( !function_exists('skd_authenticate_email_password') ) {
	
	function skd_authenticate_email_password( $user, $email, $password) {

		$ci =& get_instance();

		if ( empty($email) || empty($password) ) {

			if ( is_skd_error( $user ) ) return $user;

			$error = new SKD_Error();

			if ( empty($email) ) $error->add('empty_email', __('<strong>ERROR</strong>: The email field is empty.'));

			if ( empty($password) ) $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

			return $error;
		}

		$user = get_user_by( 'email', $email );

		if ( !have_posts($user) ) {

			return new SKD_Error( 'invalid_email', __( '<strong>ERROR</strong>: Invalid email.' ) .' <a href="">' . __( 'Lost your password?' ) . '</a>' );
		}

		$user = apply_filters( 'skd_authenticate_user', $user, $password );

		if ( is_skd_error($user) ) return $user;

		if ( ! skd_check_password( $password, $user ) ) {

			return new SKD_Error( 'incorrect_password', sprintf(__( '<strong>ERROR</strong>: The password you entered for the email %s is incorrect.' ), '<strong>' . $email . '</strong>').' <a href="">' .__( 'Lost your password?' ) .'</a>' );
		}

		return $user;

	}
}

if( !function_exists('skd_check_password') ) {
	
	function skd_check_password($password, $user ) {

		$ci =& get_instance();

		$hash = generate_password( $password, $user->username, $user->salt );

		if( $hash == $user->password ) return true;

		$hash = generate_password_old( $password, $user->username, $user->salt );

		if( $hash == $user->password ) return true;

		return false;
	}
}

if ( !function_exists('skd_set_auth_cookie') ) {

	function skd_set_auth_cookie( $user, $secure = '', $token = '' ) {

		$ci =& get_instance();

		if(is_array($user)) $user = (object)$user;

		$user_cookie = [
			'username' => $user->username,
			'password' => $user->password
		];

		$user_cookie = base64_encode(serialize($user_cookie));

		setcookie("user_login",  $user_cookie, time()+24*60*60, '/');

		$ci->session->set_userdata('user',$ci->skd_security->encode_session($user));
	}
}

if ( !function_exists('is_user_logged_in') ) {

	function is_user_logged_in() {
		
		$ci =& get_instance();

	    return $ci->skd_security->is_login();
	}
}

if ( !function_exists('user_logout') ) {

	function user_logout() {
		
		$ci =& get_instance();

		$ci->session->sess_destroy();

		session_destroy();

		setcookie("user_login", '', time()-10,'/');

		do_action('user_logout');
	}
}

if( !function_exists('username_exists') ) {
	/**
	 * Checks whether the given username exists.
	 *
	 * @since 2.1.4
	 *
	 * @param string $username Username.
	 * @return int|false The user's ID on success, and false on failure.
	 */
	function username_exists( $username ) {

		$ci =& get_instance();

		$user = get_user([
                'where' => [
                    'status <>' => 'null',
                    'username'  => $username
                ]
            ]);

		if( have_posts($user) ) {

			$user_id = $user->id;
		}
		else {

			$user_id = false;
		}

		return apply_filters( 'username_exists', $user_id, $username );

	}
}

if( !function_exists('email_exists') ) {
	/**
	 * Checks whether the given username exists.
	 *
	 * @since 2.1.4
	 *
	 * @param string $username Username.
	 * @return int|false The user's ID on success, and false on failure.
	 */
	function email_exists( $email ) {

		$ci =& get_instance();

		$user = get_user([
			'where' => [
				'status <>' => 'null',
				'email'     => $email
			]
		]);

		if( have_posts($user) ) {

			$user_id = $user->id;
		}
		else {

			$user_id = false;
		}

		return apply_filters( 'email_exists', $user_id, $email );

	}
}

if( !function_exists('get_user_by') ) {
	/**
     * @since  2.1.4
     */
	function get_user_by( $field, $value ) {

		$ci =& get_instance();

		$field = removeHtmlTags( $field );

		$value = removeHtmlTags( $value );

		$args = array( 'where' => array( $field => $value ) );

		return get_user( $args );
	}
}

if( !function_exists('gets_user') ) {
	/**
     * @since  2.1.4
     * @since  2.5.2 Thêm điều kiện where_in
     * @since  2.5.9 Điều chỉnh cơ chế get theo meta
     */
	function gets_user( $args = array() ) {

		$ci =& get_instance();

		if( is_numeric($args) ) $args = array( 'where' => array( 'id' => $args ) );

		if( !have_posts($args) ) $args = array();

		if( isset($args['where']) ) {
			if(have_posts($args['where'])) {
				$args['where'] = array_merge( array('status <>' => 'trash' ), $args['where'] );
			}
		}

		$args = array_merge( array('where' => array('status <>' => 'trash' ), 'params' => array() ), $args );

		$cache_id = 'user_'.md5(serialize($args).'_gets');

		if( cache_exists($cache_id) == false ) {

        	$model = get_model( 'plugins', 'backend' );

			$model->settable('users');

			$model->settable_metabox('users_metadata');

			$users = $model->gets_data($args, 'users');

			if(have_posts($users)) {
				save_cache($cache_id, $users);
			}
		}
		else $users = get_cache($cache_id);

		return apply_filters('gets_user', $users, $args );
	}
}

if( !function_exists('get_user') ) {
	/**
     * @since  2.1.4
     */
	function get_user( $args = array() ) {

		$ci =& get_instance();

		if( is_numeric($args) ) $args = array( 'where' => array( 'id' => $args ) );

		if( !have_posts($args) ) $args = array();

		if( isset($args['where']) ) $args['where'] = array_merge( array('status <>' => 'trash' ), $args['where'] );

		$args = array_merge( array('where' => array( 'status <>' => 'trash' ), 'params' => array() ), $args );

		$cache_id = 'user_'.md5(serialize($args).'_get');

		if( cache_exists($cache_id) == false ) {

        	$model = get_model( 'plugins', 'backend' );

			$model->settable('users');

			$user = $model->get_data( $args, 'users');
			
			if(have_posts($user)) {
				save_cache($cache_id, $user);
			}

			return $user;

		}
		else return get_cache($cache_id);
	}
}

if( !function_exists('get_current_user_id') ) {

	function get_current_user_id() {

		$ci =& get_instance();

		$user = get_user_current();

		return ( isset( $user->id ) ? (int) $user->id : 0 );
	}
}

if( !function_exists('get_userdata') ) {

	function get_userdata( $user_id ) {

	    return get_user_by( 'id', $user_id );

	}
}

if( !function_exists('get_user_current') ) {

	function get_user_current() {

		$ci =& get_instance();

		$user = json_decode(base64_decode($ci->session->userdata('user')));

		if(!have_posts($user) && !empty($_COOKIE['user_login'])) {

			$cookie = base64_decode($_COOKIE['user_login']);

			$cookie = (object)@unserialize($cookie);

			if(have_posts($cookie)) {

				$user = get_user_by('username', $cookie->username);

				if(have_posts($user) && $user->password == $cookie->password) {

					$_SESSION['allow_upload'] = true;

					$ci->session->set_userdata('user',base64_encode(json_encode($user)));
				}
			}
		}

		return ( have_posts($user) ) ? $user : array();
	}
}

if( !function_exists('generate_password') ) {

	function generate_password( $password, $username, $salt ) {

		return md5(md5($password).md5($salt));
	}
}

if( !function_exists('generate_password_old') ) {

	function generate_password_old( $password, $username, $salt ) {

		return md5(md5($password).md5($salt).md5($username));
	}
}

if( !function_exists('count_user') ) {
	/**
     * @since  2.5.7
     */
	function count_user( $args = array() ) {

		$ci =& get_instance();

		if( is_numeric($args) ) $args = array( 'where' => array( 'id' => $args ) );

		if( !have_posts($args) ) $args = array();

		if( isset($args['where']) ) {
			if(have_posts($args['where'])) {
				$args['where'] = array_merge( array('status <>' => 'trash' ), $args['where'] );
			}
		}

		$args = array_merge( array('where' => array('status <>' => 'trash' ), 'params' => array() ), $args );

		$model = get_model('plugins','backend');

		$model->settable('users');

		$model->settable_metabox('users_metadata');

		$users = $model->count_data($args, 'users');
		
		$users = apply_filters('count_user', $users, $args );

		return $users;
	}
}

if( !function_exists('get_user_meta') ) {

	function get_user_meta( $user_id, $key = '', $single = true) {

		$data = get_metadata('users', $user_id, $key, $single);

		return $data;

	}
}

if( !function_exists('update_user_meta') ) {

	function update_user_meta($user_id, $meta_key, $meta_value) {
		return update_metadata('users', $user_id, $meta_key, $meta_value);
	}
}

if( !function_exists('delete_user_meta') ) {

	function delete_user_meta($user_id, $meta_key, $meta_value = '') {

		return delete_metadata('users', $user_id, $meta_key, $meta_value);

	}
}

if( !function_exists('insert_user') ) {

	function insert_user( $userdata ) {

		if ( ! empty( $userdata['id'] ) ) {

			$id 			= (int) $userdata['id'];

			$update 	   = true;

			$old_user_data = get_user([
                'where' => [
                    'status <>' => 'null',
                    'id'     => $id
                ]
            ]);

			if ( ! $old_user_data ) return new SKD_Error( 'invalid_user_id', __( 'ID user không chính xác.' ) );

			$password 	= ! empty( $userdata['password'] ) ? $userdata['password'] : $old_user_data->password;

			$salt 		= $old_user_data->salt;
		}
		else {

			$update = false;

			$salt = random(32, TRUE);

			$password = generate_password( $userdata['password'], $userdata['username'], $salt );
		}

		$username 		= removeHtmlTags($userdata['username'] );

		$pre_username 	= apply_filters( 'pre_user_login', $username );

		$username 		= trim( $pre_username );

		if(empty($username)) {

	        return new SKD_Error('empty_username', __('Không thể tạo user khi tên đăng nhập trống.') );

	    }elseif(mb_strlen( $username ) > 60 ) {

	        return new SKD_Error( 'username_too_long', __( 'Tên đăng nhập không thể lớn hơn 60 ký tự.' ) );
	    }

	    if ( ! $update && username_exists( $username ) ) {

	    	return new SKD_Error( 'existing_username', __( 'Xin lỗi, Tên đăng nhập đã tồn tại!' ) );
	    }

	    $illegal_logins = (array) apply_filters( 'illegal_username', array() );

	    if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_logins ) ) ) {
	    	return new WP_Error( 'invalid_username', __( 'Xin lỗi, Tên đăng nhập này không được phép sử dụng.' ) );
	    }

	    $meta = array();

	    $raw_user_email = empty( $userdata['email'] ) ? '' : $userdata['email'];

	    $email = apply_filters( 'pre_user_email', $raw_user_email );

	    if ((!$update || (!empty($old_user_data) && 0 !== strcasecmp($email, $old_user_data->email))) && email_exists($email)) {

	    	return new SKD_Error( 'existing_user_email', __( 'Xin lỗi, Email này đã được sử dụng!' ) );
	    }

	    $firstname = empty( $userdata['firstname'] ) ? '' : $userdata['firstname'];

	    $lastname 	= empty( $userdata['lastname'] ) ? '' : $userdata['lastname'];

	    $phone 		= empty( $userdata['phone'] ) ? '' : $userdata['phone'];

	    $activation_key = empty( $userdata['activation_key'] ) ? '' : $userdata['activation_key'];

	    $time 			= empty( $userdata['time'] ) ? 0 : $userdata['time'];

	    if ($update) {

	    	$activation_key 	= !isset( $userdata['activation_key'] ) ? $old_user_data->activation_key : $userdata['activation_key'];

	    	$time 				= !isset( $userdata['time'] ) ? $old_user_data->time : $userdata['time'];
	    }

	    if ( !$update ) {
	    	$status 	= empty( $userdata['status'] ) ? 'public' : $userdata['status'];
	    }
	    else {
	    	$status 	= empty( $userdata['status'] ) ? $old_user_data->status : $userdata['status'];
	    }

	    $data = compact( 'username', 'email', 'salt', 'firstname', 'lastname', 'password', 'phone', 'status', '	activation_key', 'time' );

		$data = apply_filters( 'pre_insert_user_data', $data, $userdata, $update ? (int) $id : null );
		
	    $model = get_model( 'plugins', 'backend' );

		$model->settable('users');

	    if ( $update ) {

	    	$model->update_where( $data, compact( 'id' ) );

	    	$user_id = (int) $id;
	    }
	    else {

	    	$user_id = $model->add( $data );
	    }

	    if( isset( $userdata['role'] ) ) {

	    	user_set_role( $user_id, $userdata['role'] );

	    }
		elseif ( ! $update ) {

	        user_set_role( $user_id, get_option('default_role', 'subscriber'));
	    }

	    delete_cache( 'user_', true );

	    if ( $update ) {

	    	do_action( 'profile_update', $user_id, $old_user_data );

	    } else {

	    	do_action( 'user_register', $user_id );
	    }

	    return $user_id;
	}
}

if( !function_exists('update_user') ) {

	function update_user($userdata) {

		$ID = isset( $userdata['id'] ) ? (int) $userdata['id'] : 0;

		if ( ! $ID ) {
	        return new SKD_Error( 'invalid_user_id', __( 'ID Thành viên không chính xác.' ) );
	    }

		$user_obj = get_userdata( $ID );
		
		$user     = (array)$user_obj;
		
		$user     = add_magic_quotes( $user );

		if ( ! empty( $userdata['password'] ) && $userdata['password'] !== $user_obj->password ) {

			$plaintext_pass = $userdata['password'];

			$userdata['password'] = generate_password( $userdata['password'], $user_obj->username, $user_obj->salt );

			$send_password_change_email = apply_filters( 'send_password_change_email', true, $user, $userdata );

		}

		if ( isset( $userdata['email'] ) && $user['email'] !== $userdata['email'] ) {

			$send_email_change_email = apply_filters( 'send_email_change_email', true, $user, $userdata );

		}

		$userdata = array_merge( $user, $userdata );

		$user_id = insert_user( $userdata );

		if ( ! is_skd_error( $user_id ) ) {

			delete_cache('user', true);
		}

		$current_user = get_user_current();

		if ( have_posts($current_user) && $current_user->id == $ID ) {
			skd_set_auth_cookie( $userdata );
		}

		return $user_id;
	}
}

if( !function_exists('delete_user') ) {

	function delete_user( $id, $reassign = null ) {

		if ( ! is_numeric( $id ) ) return false;

		$id = (int) $id;

		$user = get_user_by( 'id', $id );

		if( !have_posts($user) ) return false;

		do_action( 'delete_user', $id, $reassign );

		//delete metabox
		delete_metadata_by_mid( 'users', $id );

		$model = get_model( 'plugins', 'backend' );

		$model->settable('users');

		$model->delate_where( array( 'id' => $id ) );

		delete_cache( 'user_', true );

		do_action( 'deleted_user', $id, $reassign );

		return true;
	}

}
//Roles
if( !function_exists('get_caps_data') ) {

	function get_caps_data( $user_id, $cap ) {

		$caps = get_user_meta( $user_id, $cap, true );

		$caps = (is_serialized($caps))?unserialize($caps):$caps;

		if ( ! is_array( $caps ) ) return array();

		return $caps;
	}
}

if( !function_exists('get_role_caps') ) {

	function get_role_caps( $user_id, $cap = 'capabilities' ) {

		$user = get_userdata( $user_id );

		if( !have_posts($user) ) return false;

	    $skd_roles = skd_roles();

	    $caps = get_caps_data( $user_id, 'capabilities' );

	    $user_roles = array();

	    if ( is_array( $caps ) )
	    	$user_roles = array_filter( array_keys( $caps ), array( $skd_roles, 'is_role' ) );

	    $allcaps = array();

	    foreach ( (array) $user_roles as $role ) {

	    	$the_role = $skd_roles->get_role( $role );

	    	$allcaps = array_merge( (array) $allcaps, (array) $the_role->capabilities );
	    }

	    $allcaps = array_merge( (array) $allcaps, (array) $caps );

	    return $allcaps;
	}
}

if( !function_exists('get_user_current_roles') ) {

	function get_user_current_roles() {

		$user = get_user_current();

		if( !have_posts($user) ) return false;

		$user_id = $user->id;

	    $skd_roles = skd_roles();

	    $caps = get_caps_data( $user_id, 'capabilities' );

	    $user_roles = array();

	    if ( is_array( $caps ) )
	    	$user_roles = array_filter( array_keys( $caps ), array( $skd_roles, 'is_role' ) );

	    $allcaps = array();

	    foreach ( (array) $user_roles as $role ) {

	    	$the_role = $skd_roles->get_role( $role );

	    	$allcaps = array_merge( (array) $allcaps, (array) $the_role->capabilities );
	    }

	    $allcaps = array_merge( (array) $allcaps, (array) $caps );

	    return $allcaps;
	}
}

if( !function_exists('user_has_cap') ) {

	function user_has_cap( $user_id, $cap ) {

		$user = get_userdata( $user_id );

		if( !have_posts($user) ) return false;

	    if ( is_numeric( $cap ) ) $cap = 'level_' . $cap;

	    $args = array_slice( func_get_args(), 1 );

	    $args = array_merge( array( $cap, $user_id ), $args );

	    $caps = call_user_func_array( 'map_meta_cap', $args );

	    $capabilities = apply_filters( 'user_has_cap', get_role_caps( $user_id, $cap ), $caps, $args, $user );

	    $capabilities['exist'] = true;

	    unset( $capabilities['do_not_allow'] );    

	    foreach ( (array) $caps as $cap ) {
            if ( empty( $capabilities[ $cap ] ) ) return false;
        }

        return true;

	}
}

if( !function_exists('user_role') ) {

	function user_role( $user_id ) {

		$user = get_userdata( $user_id );

		if( !have_posts($user) ) return false;

	    $skd_roles = skd_roles();

	    $caps = get_caps_data( $user_id, 'capabilities' );

	    $user_roles = array();

	    if ( is_array( $caps ) ) $user_roles = array_filter( array_keys( $caps ), array( $skd_roles, 'is_role' ) );

	    return $user_roles;
	}
}

if( !function_exists('user_add_role') ) {

	function user_add_role( $user_id, $role ) {

		$user_role = current( user_role( $user_id ) );

		if( $user_role == $role ) return false;

		if ( !empty( $role ) ) {

			$caps = get_user_meta( $user_id, 'capabilities', true );

			if(empty($caps)) $caps = array();

			$caps[$role] = true;

			update_user_meta( $user_id, 'capabilities', $caps );

			do_action( 'set_user_role', $user_id, $role, $user_role );

			return $role;
		}

		return false;

	}
}

if( !function_exists('user_set_role') ) {

	function user_set_role( $user_id, $role ) {

		$caps = array();

		$user_role = current( user_role( $user_id ) );

		if( $user_role == $role ) return false;

		if ( !empty( $role ) ) {

			$caps[$role] = true;

			update_user_meta( $user_id, 'capabilities', $caps );

			do_action( 'set_user_role', $user_id, $role, $user_role );

			return $role;
		}

		return false;

	}
}

function my_account_url( $full = false ) {

    $my_account_url = ( $full ) ? base_url('tai-khoan') : 'tai-khoan' ;

    return apply_filters( 'my_account_url', $my_account_url );
}

function register_url( $redirect = '') {

    $register_url = base_url( my_account_url().'/register');

    if ( !empty($redirect) ) {
    	
        $redirect = urlencode( $redirect );

        $register_url .= '?redirect='.$redirect;
    }

    return apply_filters( 'register_url', $register_url, $redirect );
}

function login_url( $redirect = '') {

    $login_url = base_url( my_account_url().'/login');

    if ( !empty($redirect) ) {
    	
        $redirect = urlencode( $redirect );

        $login_url .= '?redirect='.$redirect;
    }

    return apply_filters( 'login_url', $login_url, $redirect );
}

function logout_url( $redirect = '') {

    $logout_url = base_url( my_account_url().'/logout');

    if ( !empty($redirect) ) {

        $redirect = urlencode( $redirect );

        $logout_url .= '?redirect='.$redirect;
    }

    return apply_filters( 'logout_url', $logout_url, $redirect );
}

function admin_my_action_links() {

    $args = array(
		'profile' => array(
			'label' 	=> __('Thông tin tài khoản'),
			'icon'		=> '<i class="fal fa-address-card"></i>',
			'callback'	=> 'admin_user_profile',
		),
		'password' => array(
			'label' => __('Đổi mật khẩu'),
			'icon'		=> '<i class="fal fa-lock"></i>',
			'callback'   => 'admin_user_password',
		),
	);

	return apply_filters('admin_my_action_links', $args );
}