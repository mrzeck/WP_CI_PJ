<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model($this->data['module'].'_model');
	}

	/*================================ DISPLAY =================================*/
	/* not login */
	public function login() {

		$this->template->set_layout('template-login');

		$this->template->render();
	}

	public function logout() {

		user_logout();

		redirect(URL_ADMIN);
	}

	public function index() {
		
		$model = $this->data['module'].'_model';

		$args['where'] = array();
		$args['params'] = array( 'orderby' => 'created asc');

		if( !is_super_admin() ) {
			$args['where'] = array('id <>' => 1);
		}

		$args = apply_filters('edit_user_index_args', $args, get_user_current() );

		$total_rows = count_user( $args );

		$url  = base_url().URL_ADMIN.'/'.$this->data['module'].'?page={page}';

		if(!empty($keyword))    $url .= '&keyword='.$keyword;

		$this->data['pagination'] 	= pagination($total_rows, $url, 20);

		$params = array(
			'limit'  => 20,
			'start'  => $this->data['pagination']->getoffset(),
			'orderby'=> 'created asc',
		);

		$args['params'] = $params;

		$this->data['objects'] 		= gets_user( $args );

		/* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
        );

        $class_table = 'skd_user_list_table';

        $this->data['table_list'] = new $class_table($args);
		
		$this->template->render();
	}

	public function edit() {
		$id = (int)$this->input->get('id');

		$this->data['object'] = array();

		if( $id != 0 )
			$this->data['object'] = get_user_by( 'id', $id );

		if( !have_posts($this->data['object']) ) {
			$this->data['object'] = get_user_current();
		}

		$this->template->render();
	}

	public function add() {

		if( !current_user_can('create_users') ) return;

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

			$error = apply_filters('admin_registration_errors', $error, $user_array, $user_meta );

			if( !is_skd_error($error) ) {

				$user_array = apply_filters( 'admin_pre_user_register', $user_array );

				$user_meta 	= apply_filters( 'admin_pre_user_register_meta', $user_meta );

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

				$this->template->set_message( notice('success', __('Tạo tài khoản thành công.')), 'register_success' );

				redirect( URL_ADMIN.'/user' );

			}

		}

		$this->template->render();
	}
}