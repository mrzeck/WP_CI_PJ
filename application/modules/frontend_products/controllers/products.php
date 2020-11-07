<?php defined('BASEPATH') OR exit('No direct script access allowed');

class products extends MY_Controller {

	function __construct() {

		parent::__construct('frontend');

		$this->load->model($this->data['module'].'_model');
	}


	/*==================== DISPLAY ================*/
	public function index($slug = '') {

		$model = $this->data['module'].'_model';

		do_action('controllers_products_index', removeHtmlTags($slug) );

		$this->template->render();
	}

	public function detail($slug = '')
	{
		$model = $this->data['module'].'_model';

		do_action('controllers_products_detail', removeHtmlTags($slug) );
		
		if(have_posts($this->data['object'])) {
			$this->template->render();
		}
		else $this->template->error('404');
	}
}