<?php defined('BASEPATH') OR exit('No direct script access allowed');

class gallery extends MY_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model($this->data['module'].'_model');
	}

	public function index() {

		$model = $this->data['module'].'_model';

		$this->$model->settable('group');

		$this->data['gallerys'] = $this->$model->gets_where(array('object_type' => 'gallery'));

		if(have_posts($this->data['gallerys'])) {

			$id = (int)$this->input->get('id');

			$this->data['object'] = $this->$model->get_where(array('id' => $id));

			if(!have_posts($this->data['object'])) $this->data['object'] = $this->data['gallerys'][0];

			$this->$model->settable('gallerys');

			$this->data['gallery_item'] = gets_gallery( $this->data['object']->id );
		}

		$this->template->render();
	}

}