<?php defined('BASEPATH') OR exit('No direct script access allowed');

class user extends MY_Controller {

	function __construct() {

		parent::__construct('frontend');

		$this->load->model($this->data['module'].'_model');
	}


	/*==================== DISPLAY ================*/
	public function index( $param1 =  '', $param2 = '' ) {

		$param1 = removeHtmlTags($param1);

		$param2 = removeHtmlTags($param2);

		$view = apply_filters( 'my_account_view_'.$param1, 'user-'.$param1 );

		$layout = apply_filters( 'my_account_layout_'.$param1, 'template-user' );

		if( empty($param1) ) $param1 = 'index';

		if( $param1 ==  'login' ) {

			$this->login( $param2 );

		}
		else if( $param1 ==  'register' ) {

			$this->register( $param2 );

		}
		else if( $param1 ==  'index' ) {

			$this->profile( $param2 );

		}
		else if( $param1 ==  'password' ) {

			$this->password( $param2 );

		}
		else if( $param1 ==  'logout' ) {

			$this->logout( $param2 );

		} else {

			do_action('my_account_template_'.$param1, $param2 );
		}

		if( !empty($layout) ) $this->template->set_layout( $layout );

		if( !empty($view) ) $this->template->render( $view );
	}

	public function login() {

		if( $this->input->post() ) {

			$args['username'] = removeHtmlTags($this->input->post('username'));

			$args['password'] = removeHtmlTags($this->input->post('password'));

			$result = skd_signon( $args );

			if( is_skd_error($result) ) {

				foreach ($result->errors as $error_key => $error_value) {
					$this->template->set_message( notice('error', $error_value[0]), $error_key );
				}
			}
			else {

				$login_redirect = $this->input->get('redirect');

				if ( !empty($login_redirect) ) {

					$login_redirect = urldecode($login_redirect);
		    	
			        $login_redirect = removeHtmlTags($login_redirect);

			    }
			    else $login_redirect = my_account_url(true);

				$login_redirect = apply_filters( 'login_redirect', my_account_url() );

				redirect( $login_redirect );
			}
		}
	}

	public function register() {

		if( $this->input->post() ) {

			$user_meta = array();

			$error = array();

			if ( !empty($this->input->post('username')) ) {

				$user_array['username'] = removeHtmlTags( $this->input->post('username') );
			}

			if ( !empty($this->input->post('firstname')) ) {

				$user_array['firstname'] = removeHtmlTags( $this->input->post('firstname') );
			}

			if ( !empty($this->input->post('lastname')) ) {

				$user_array['lastname'] = removeHtmlTags( $this->input->post('lastname') );
			}

			if ( !empty($this->input->post('fullname')) ) {

				$user_meta['fullname'] = removeHtmlTags( $this->input->post('fullname') );
			}

			if ( !empty($this->input->post('phone')) ) {

				$user_array['phone'] = removeHtmlTags( $this->input->post('phone') );
			}

			if ( !empty($this->input->post('address')) ) {

				$user_meta['address'] = removeHtmlTags( $this->input->post('address') );
			}

			if ( !empty($this->input->post('email')) ) {

				if( email_exists(removeHtmlTags( $this->input->post('email') )) != false ) {

					$error = new SKD_Error( 'email_exists', __('Email này đã được sử dụng.'));
				}

				$user_array['email'] = removeHtmlTags( $this->input->post('email') );
			}


			if ( !empty($this->input->post('password')) ) {

				$user_array['password'] = removeHtmlTags( $this->input->post('password') );
			}
			else {

				if( is_skd_error($error) ) $error->add( 'empty_password', __('Mật khẩu không được bỏ trống.') );
				else $error = new SKD_Error( 'empty_password', __('Mật khẩu không được bỏ trống.'));
			}

			if ( empty($this->input->post('re_password')) ||  $this->input->post('re_password') != $this->input->post('password') ) {

				if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.') );
				else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.'));
			}

			$error = apply_filters('registration_errors', $error, $user_array, $user_meta );

			if( !is_skd_error($error) ) {

				$user_array = apply_filters( 'pre_user_register', $user_array );

				$user_meta 	= apply_filters( 'pre_user_register_meta', $user_meta );

				$error = insert_user( $user_array );

				if( !is_skd_error($error) && have_posts($user_meta) ) {

					foreach ($user_meta as $user_meta_key => $user_meta_value) {

						if ( !empty( $user_meta_value ) ) update_user_meta( $error, $user_meta_key, $user_meta_value );
					}

				}
			}

			if( is_skd_error($error) ) {

				foreach ($error->errors as $error_key => $error_value) {

					$this->template->set_message( notice('error', $error_value[0]), $error_key );
				}

			}
			else {
				$this->template->set_message( notice('success', __('Đăng ký tài khoản thành công.')), 'register_success' );
			}

		}
	}

	public function profile() {

		if( $this->input->post() ) {

			$user_obj = get_user_current();

			$error = array();

			if( have_posts( $user_obj ) ) {

				$user_array = (array)$user_obj;

				$user_meta = array();

				$error = array();

				if ( !empty($this->input->post('firstname')) ) {

					$user_array['firstname'] = removeHtmlTags( $this->input->post('firstname') );
				}

				if ( !empty($this->input->post('lastname')) ) {

					$user_array['lastname'] = removeHtmlTags( $this->input->post('lastname') );
				}

				if ( !empty($this->input->post('fullname')) ) {

					$user_meta['fullname'] = removeHtmlTags( $this->input->post('fullname') );
				}

				if ( !empty($this->input->post('phone')) ) {

					$user_array['phone'] = removeHtmlTags( $this->input->post('phone') );
				}

				if ( !empty($this->input->post('address')) ) {

					$user_array['address'] = removeHtmlTags( $this->input->post('address') );
				}

				if ( !empty($this->input->post('birthday')) ) {

					$user_meta['birthday'] = removeHtmlTags( $this->input->post('birthday') );
				}

				if ( !empty($this->input->post('email')) ) {

					// if( email_exists(removeHtmlTags( $this->input->post('email') )) != $user_obj->id ) {

					// 	$error = new SKD_Error( 'email_exists', __('Email này đã được sử dụng.'));
					// }

					$user_array['email'] = removeHtmlTags( $this->input->post('email') );
				}

				if( !is_skd_error($error) ) {

					$user_array = apply_filters( 'pre_update_profile', $user_array, $user_obj );

					$user_meta 	= apply_filters( 'pre_update_profile_meta', $user_meta, $user_obj );

					$error = update_user( $user_array );

					if( !is_skd_error($error) && have_posts($user_meta) ) {

						foreach ($user_meta as $user_meta_key => $user_meta_value) {

							if ( !empty( $user_meta_value ) ) update_user_meta( $user_obj->id, $user_meta_key, $user_meta_value );
						}

					}
				}

				if( is_skd_error($error) ) {

					foreach ($error->errors as $error_key => $error_value) {

						$this->template->set_message( notice('error', $error_value[0]), $error_key );
					}

				}
				else {
					$this->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_profile_success' );
				}

			}
		}

	}

	public function password() {

		if( $this->input->post() ) {

			$user_obj = get_user_current();

			$error = array();

			if( have_posts( $user_obj ) ) {

				$user_array = (array)$user_obj;

				$user_meta = array();

				$error = array();

				if ( empty($this->input->post('old_password')) || generate_password($this->input->post('old_password'), $user_obj->username, $user_obj->salt ) != $user_obj->password ) {

					$error = new SKD_Error( 'invalid_old_password', __('Mật khẩu củ không chính xác.'));
				}

				if ( strlen($this->input->post('password')) < 6 || strlen($this->input->post('password')) > 32 ) {
					if( is_skd_error($error) ) $error->add( 'invalid_old_password', __('Mật khẩu không được nhỏ hơn 6 hoặc lớn hơn 32 ký tự.') );
					else $error = new SKD_Error( 'invalid_old_password', __('Mật khẩu không được nhỏ hơn 6 hoặc lớn hơn 32 ký tự.'));
				}

				if ( !empty($this->input->post('new_password')) ) {

					$user_array['password'] = removeHtmlTags( $this->input->post('new_password') );
				}
				else {

					if( is_skd_error($error) ) $error->add( 'invalid_new_password', __('Mật khẩu mới không chính xác.') );
					else $error = new SKD_Error( 'invalid_new_password', __('Mật khẩu mới không chính xác.'));
				}

				if ( empty($this->input->post('re_new_password')) ||  $this->input->post('re_new_password') != $this->input->post('new_password') ) {

					if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.') );
					else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.'));
				}

				if( !is_skd_error($error) ) {

					$user_array = apply_filters( 'pre_update_password', $user_array, $user_obj );

					$error = update_user( $user_array );

					if( !is_skd_error($error) && have_posts($user_meta) ) {

						foreach ($user_meta as $user_meta_key => $user_meta_value) {

							if ( !empty( $user_meta_value ) ) update_user_meta( $user_obj->id, $user_meta_key, $user_meta_value );
						}

					}
				}

				if( is_skd_error($error) ) {

					foreach ($error->errors as $error_key => $error_value) {

						$this->template->set_message( notice('error', $error_value[0]), $error_key );
					}

				}
				else {
					$this->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_password_success' );
				}

			}
		}

	}

	public function logout() {

		user_logout();

		$redirect = $this->input->get('redirect');

		if ( !empty($redirect) ) {

			$redirect = urldecode($redirect);
    	
	        $redirect = removeHtmlTags($redirect);

	    }
	    else $redirect = base_url();

		redirect( $redirect, 'refresh' );

	}

	public function action_links() {
		$args = array(
			'user' => array(
				'label' => __('Thông tin tài khoản'),
				'icon'  => '<i class="fal fa-user"></i>',
				'url'	=> my_account_url(true)
			),
			'password' => array(
				'label' => __('Đổi mật khẩu'),
				'icon'  => '<i class="fal fa-lock-open-alt"></i>',
				'url'	=> my_account_url(true).'/password'
			),
			'logout' => array(
				'label' => __('Đăng xuất'),
				'icon'  => '<i class="fal fa-sign-out"></i>',
				'url'	=> logout_url( login_url() )
			),
		);

		return apply_filters('my_action_links', $args );
	}
}