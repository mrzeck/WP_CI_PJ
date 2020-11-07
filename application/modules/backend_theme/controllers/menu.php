<?php defined('BASEPATH') OR exit('No direct script access allowed');

class menu extends MY_Controller {

	public $list_object = array('page' => array('label' => 'Trang Nội Dung', 'type' => 'page'));

	function __construct() {

		parent::__construct();

		$this->skd_security->auth_backend();

		$this->load->model($this->data['module'].'_model');

		$this->data['group'] 	= 'theme';

		$this->data['ajax'] 	= 'theme/menu';

	}
	/*==================== DISPLAY ================*/
	/**
	 * [Hiển thị trang edit post]
	 */
	public function index()
	{
		$model = $this->{$this->data['module'].'_model'};

		if( current_user_can('edit_theme_menus') ) {

			$model->settable('group');

			//lấy toàn bộ menu
			$this->data['menus'] = $model->gets_where(array('object_type' => 'menu'));

			$this->data['menu'] = array();

			$id = (int)$this->input->get('id');

			if(have_posts($this->data['menus'])) {

				$this->data['menu'] = $model->get_where(array('id' => $id, 'object_type' => 'menu'));


				if(!have_posts($this->data['menu']))
				{
					$this->data['menu'] = $this->data['menus'][0];
				}

				if(have_posts($this->data['menu'])) {

					$model->settable('relationships');

					$this->data['relationships'] = array();

					$relationships = $model->gets_where(array('object_id' => $this->data['menu']->id, 'object_type' => 'menu'));

					foreach ($relationships as $key => $re) {

						$this->data['relationships'][$re->id] = $re->category_id;
					}


					//Làm việc với menu item
					$model->settable('menu');

					$menus = $model->gets_where(array('menu_id' => $this->data['menu']->id, 'parent_id' => 0));

					$this->data['items'] = $this->multilevel_categories($menus,array('menu_id' => $this->data['menu']->id), $model);

					//get dữ liệu cho item menu
					$model->settable('page');

					$this->list_object['page']['data'] = $model->gets_where( array('trash' => 0), array('select' => 'id, title') );


					//get dữ liệu cho item categories
					$model->settable('categories');

					$cate_type = get_cate_type_detail();

					$this->load->library('nestedset');

					if(have_posts($cate_type)) {

						foreach ($cate_type as $key => $value) {

							if($value['show_in_nav_menus'] == true) {

								$this->list_object[$key] = array ( 'label' => $value['labels']['name'], 'type' => 'categories');

								$nestedset = new nestedset(array(
									'model' => $this->data['module'].'_model',
									'table' => 'categories',
									'where' => array('cate_type' => $key)));

								$this->list_object[$key]['data'] = $nestedset->get_data_backend('object');

								unset($this->list_object[$key]['data'][0]);
							}
						}
					}


					//get dữ liệu cho item post
					$model->settable('post');

					$post_type = get_post_type_detail();

					if(have_posts($post_type)) {

						foreach ($post_type as $key => $value) {

							if($value['show_in_nav_menus'] == true) {

								$this->list_object[$key] = array ( 'label' => $value['labels']['name'], 'type' => 'post');
								
								$this->list_object[$key]['data'] = $model->gets_where(array('post_type' => $key, 'trash' => 0), array('select' => 'id, title', 'limit' => 30));
							}
						}
					}

					$this->list_object = apply_filters('admin_menu_list_object', $this->list_object );
				}
			}

			$this->template->render();
		}
		else $this->template->error('404');
	}
}