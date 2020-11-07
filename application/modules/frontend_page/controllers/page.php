<?php defined('BASEPATH') OR exit('No direct script access allowed');

class page extends MY_Controller {

	function __construct() {

		parent::__construct('frontend');

		$this->load->model($this->data['module'].'_model');
	}

	public function detail($slug = '')
	{
		$model = $this->data['module'].'_model';

		$this->data['object'] = $this->$model->fget_where('page', array('slug' => $slug), array('select' => 'id, title, slug, excerpt, content, image, theme_layout, theme_view, seo_title, seo_description, seo_keywords'));

		$this->template->render();
	}
}