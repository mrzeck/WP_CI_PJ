<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct() {

		parent::__construct('frontend');

		$this->data['module'] 		= 'home';

		$this->load->model($this->data['module'].'_model');
	}

	public function index() {
		$this->template->render();
	}

	public function close() {
		
		if(@file_exists(VIEWPATH.$this->template->name.'/cms-close.php'))
		{
			$this->load->view($this->template->name.'/cms-close.php', $this->data);
		}
		else {
			$this->load->view('backend/cms-close.php', $this->data);
		}
	}

	public function password() {

		if($this->input->post()) {

			$password = removeHtmlTags($this->input->post('password'));

			$cms_password = get_option('cms_password');

			if($password == $cms_password) {

				$_SESSION['cms_close_password'] = true;

				redirect();
			}
			else {

				$this->template->set_message(notice('error', 'Mật khẩu đăng nhập không chính xác.'));
			}
		}
		
		if(@file_exists(VIEWPATH.$this->template->name.'/cms-password.php'))
		{
			$this->load->view($this->template->name.'/cms-password.php', $this->data);
		}
		else {
			$this->load->view('backend/cms-password.php', $this->data);
		}
	}

	public function search() {

		$type 		= removeHtmlTags($this->input->get('type'));

		$keyword 	= removeHtmlTags($this->input->get('keyword'));

		$objects 	= array();

		if( $type == '' ) $type = 'post';

		$post_type = get_post_type( $type );

		if( have_posts($post_type) ) {

			$objects =  gets_post( array(
				'where' 		=> array('public' => 1, 'trash' => 0, 'post_type' => $type ),
				'where_like' 	=> array( 'title' => array($keyword) ),
			) );
		}

		$this->data['objects'] = apply_filters( 'get_search_data', $objects, $type, $keyword );

		$this->template->render();
	}

	public function page_404() {

		$this->template->error('404');
	}

	public function page( $callback = '' ) {
		if( function_exists($callback) )
			echo call_user_func( $callback, $this, $this->home_model);
	}
}