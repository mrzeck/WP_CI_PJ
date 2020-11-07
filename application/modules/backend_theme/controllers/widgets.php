<?php defined('BASEPATH') OR exit('No direct script access allowed');

class widgets extends MY_Controller {

	function __construct() {

		parent::__construct();

		$this->skd_security->auth_backend();

		$this->data['group'] 	= 'theme';

		$this->data['ajax'] 	= 'theme/widgets';

		$this->load->model($this->data['module'].'_model');
	}

	public function index()
	{
		$model = $this->data['module'].'_model';

		if( current_user_can('edit_theme_menus') ) {

			//Get widget
			$objects = $this->template->get_widget();

			$this->data['objects'] = array();

			foreach ($objects as $key_object => $value_object) {

				$this->data['objects'][$key_object] = array(
					'id' 		=> 0,
					'widget_id' => $key_object,
					'sidebar_id' => null,
					'name' => $value_object->name,
					'widget_name' => $value_object->name,
					'options' => array(),
				);

				$this->data['objects'][$key_object] = (object)$this->data['objects'][$key_object];
			}

			$widgets = $this->$model->gets(array('orderby' => 'order'));

			if(have_posts($this->data['objects'])) {
				//xóa những widget không tồn tại
				if(have_posts($widgets)) {
					foreach ($widgets as $key => $widget) {
						
						if($this->template->get_widget($widget->widget_id) == null) {
							$this->$model->delete_where(array('widget_id' => $widget->widget_id));
						}

						if(isset($this->data['objects'][$widget->widget_id])) {
							$widgets[$key]->widget_name = $this->data['objects'][$widget->widget_id]->name;
						}
					}
				}
			}

			//Get sidebar
			if(have_posts($this->sidebar)) {
				foreach ($this->sidebar as $key => &$option) {

					$option['widget'] = array();

					if(isset($widgets) && have_posts($widgets)) {
						foreach ($widgets as $k => $val) {
							if($val->sidebar_id == $key) $option['widget'][] = $val;
						}
					}
				}
			}

			//hiển thị
			$this->template->render();
		}
		else $this->template->error('404');
	}

	/*===================== CREAT FORM =====================*/
	public function ajax_widget_get_data()
	{
		$result['type'] = 'error';
		$result['data'] = '';
		$result['message'] = 'Cập nhật không thành công!';

		if($this->input->post()) {

			$id 	= $this->input->post('id');
			$data 	= $this->input->post('value');
			$model = $this->data['module'].'_model';
			$widget = $this->$model->get_where(array('id' => $id));
			if(have_posts($widget)) {
				if($widget->object == 'page'|| $widget->object == 'post' || $widget->object == 'products' || $widget->object == 'menu_group')
				{
					$result['data'] = $this->widget_data_gets_object($widget->object, $data);
				}
				else if($widget->object == 'post_categories' || $widget->object == 'product_categories') {
					$result['data'] = $this->widget_data_gets_categories($widget->object, $data);
				}
				else if($widget->object == 'gallerys') {
					$result['data'] = $this->widget_data_gets_galleries($widget->object, $data);
				}
			}
		}

		echo json_encode($result);
	}
	//các hàm lấy dữ liệu
	public function widget_data_gets_object($object ='', $data = '')
	{
		$model 	= $this->data['module'].'_model';
		$this->$model->settable($object);
		$objects = $this->$model->gets();
		$result ='<select name="data" class="form-control" id="data">';
		foreach ($objects as $key => $val) {
			$name = (isset($val->title))?$val->title:$val->name;
			$result .='<option value="'.$val->id.'" '.(($val->id == $data)?'selected="selected"':'').'>'.$name.'</option>';
		}
		$result .='</select>';
		return $result;
	}

	public function widget_data_gets_categories($object ='', $data = '')
	{
		$model 	= $this->data['module'].'_model';
		$result = '';
		if($object == 'post_categories') {
			$this->load->model('backend_post/post_categories_model');
			$this->load->library('nestedset', array( 'model' => 'post_categories_model', 'table' => 'categories'));
			$objects = $this->nestedset->get_dropdown_backend();
			$result ='<select name="data" class="form-control" id="data">';
			foreach ($objects as $key => $val) {
				$result .='<option value="'.$key.'" '.(($key == $data)?'selected="selected"':'').'>'.$val.'</option>';
			}
			$result .='</select>';
		}

		if($object == 'product_categories') {
			$this->load->model('backend_products/product_categories_model');
			$this->load->library('nestedset', array( 'model' => 'product_categories_model', 'table' => 'product_categories'));
			$objects = $this->nestedset->get_dropdown_backend();
			$result ='<select name="data" class="form-control" id="data">';
			foreach ($objects as $key => $val) {
				$result .='<option value="'.$key.'" '.(($key == $data)?'selected="selected"':'').'>'.$val.'</option>';
			}
			$result .='</select>';
		}

		return $result;
	}

	public function widget_data_gets_galleries($object ='', $data = '')
	{
		$model 	= $this->data['module'].'_model';
		$this->$model->settable('gallery_group');
		$objects = $this->$model->gets_where(array('type' => 'image'));
		if(have_posts($objects)) {
			$result ='<select name="data" class="form-control" id="data">';
			foreach ($objects as $key => $val) {
				$result.='<option value="'.$val->id.'" '.(($val->id == $data)?'selected="selected"':'').'>'.$val->name.'</option>';
			}
			$result .='</select>';
		}
		else {
			$result ='<p><a href="'.base_url().URL_ADMIN.'/gallery">Chưa có gallery nào, click vào đây để tạo gallery!</a></p>';
		}
		return $result;
	}

}