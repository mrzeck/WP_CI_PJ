<?php
include 'gallery-ajax.php';

/* TEMPLATE *********************************************************/
if( !function_exists('object_gallery_admin_form') ) {

	function object_gallery_admin_form() {

		$ci =& get_instance();

		if(isset($ci->gallery_support)) {
			$key = $ci->template->class;

			if( isset($ci->gallery_support[$key]) && $ci->gallery_support[$key] == true) {
				$ci->template->render_file('function/gallery/html/gallery-box');
			}
			else if($key == 'post_categories' && isset($ci->gallery_support['cate_type'][$ci->cate_type]) && $ci->gallery_support['cate_type'][$ci->cate_type] == true  ) {
				$ci->template->render_file('function/gallery/html/gallery-box');
			}
			else if($key == 'post' && isset($ci->gallery_support['post_type'][$ci->post_type]) && $ci->gallery_support['post_type'][$ci->post_type] == true  ) {
				$ci->template->render_file('function/gallery/html/gallery-box');
			}
		}
	}
}

add_action( 'before_admin_form_left', 'object_gallery_admin_form', 50);


/* PROCESS DATA *********************************************************/
if( !function_exists('object_gallery_process_data') ) {
	/**
	 * @ Save gallery item cho object
	 * */
	function object_gallery_process_data($data) {

		$ci =& get_instance();

		if(isset($ci->data['form_data_post']['gallery'])) {
			$data['gallery'] = $ci->data['form_data_post']['gallery'];
			unset($ci->data['form_data_post']['gallery']);
		}

		return $data;
	}
}

add_filter( 'skd_form_process_data', 'object_gallery_process_data', 10, 2);


if( !function_exists('object_gallery_from_add') ) {
	/**
	 * @ Save gallery item cho object
	 * */
	function object_gallery_from_add($id, $model, $data_outside, $current_model, $ins_data) {

		$ci =& get_instance();

		if(isset($data_outside['gallery']) && count($data_outside['gallery'])) {

			$model->settable('gallerys');

			foreach ($data_outside['gallery'] as $key => $gallery) {

				$file = array();

				$file['object_id'] 		= $id;

				$file['object_type'] 	= $ci->data['module'];

				$file['options'] = array();

				if($ci->data['module'] == 'post') $file['object_type'] = $ci->data['module'].'_'.$ci->post_type;

				foreach ($gallery as $field => $value) {

					if($field == 'value') {

						$file['value'] 	= $value;

						$file['type']= get_file_type($value);
					}

					if($field == 'order') {

						$file['order'] 	= $value;

					}

					if(substr($field,0,7) == 'option_') {

						$op_key = substr($field, 7);

						$file['options'][$op_key] = $value;
					}
				}

				insert_gallery($file);
			}

			$model->settable($current_model);
		}

	}
}

add_action( 'save_object_add', 'object_gallery_from_add', 10, 5);

if( !function_exists('object_gallery_from_edit') ) {
	/**
	 * @ Save gallery item cho object
	 * */
	function object_gallery_from_edit($id, $model, $data_outside, $current_model, $ins_data) {

		$ci =& get_instance();

		if(isset($data_outside['gallery']) && count($data_outside['gallery'])) {

			$model->settable('gallerys');

			foreach ($data_outside['gallery'] as $item_id => $item) {

				$file = array();

				$file['id'] 		= $item_id;

				$file['order'] 		= $item['order'];

				$file['value'] 		= $item['value'];

				insert_gallery($file);
			}

			// die;

			$model->settable($current_model);
		}

	}
}

add_action( 'save_object_edit', 'object_gallery_from_edit', 10, 5);


