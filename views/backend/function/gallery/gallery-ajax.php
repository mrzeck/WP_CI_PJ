<?php
/* AJAX *********************************************************/
if( !function_exists('ajax_object_gallery_save') ) {
	/**
	 * @ Save gallery item cho object
	 * */
	function ajax_object_gallery_save($ci, $model) {

		$result['type'] 	= 'error';

		$result['message'] 	= 'Cập nhật không thành công!';

		if($ci->input->post()) {

			$post 			= $ci->input->post();

			$post_type = $ci->input->post('post_type');

			$cate_type = $ci->input->post('cate_type');

			$rules[] = array('field' => 'value', 	'label' => 'File dữ liệu', 	'rules' => 'trim|required');

			$ci->form_validation->set_rules($rules);

			if($ci->form_validation->run())
	        {

				$gallery_array                = array();
				
				if( $post['id'] != 0) $gallery_array['id']          = (int)$post['id'];
				
				$gallery_array['object_id']   = (int)$post['object_id'];
				
				$gallery_array['object_type'] = $post['object_module'];
				
				$gallery_array['value']       = process_file($post['value']);
				
				$gallery_array['type']        = get_file_type($post['value']);

				//data object gallery item
				$class 	= $gallery_array['object_type'];

	        	foreach ($post as $key => $value) {
	        		if(substr($key,0,7) == 'option_') $gallery_array['options'][substr($key, 7)] = $value;
	        	}

	        	//get data object
	        	//categories
		        if(substr($class,0,16) == 'post_categories_') {
		            $model->settable('categories');
		            $class = 'post_categories';
		        }
	        	else if(substr($class,0,5) == 'post_') {
		            $model->settable('post');
		            $class = 'post';
		        }
				else $model->settable($class);

	        	$object = $model->get_where(array('id' => $gallery_array['object_id']));

	        	//Edit dữ liệu
	        	if(have_posts($object)) {

	         		$model->settable('gallerys');

	         		$id = insert_gallery( $gallery_array );

	         		$result['data'] = '';

	         		if( have_posts($id) ) {

	         			$gallerys = gets_gallery( array('where_in' => array('field' => 'id', 'data' => $id) ) );

	         			foreach ($gallerys as $gallery) {
	         				$result['data'] .= get_template_file('function/gallery/html/object_gallery_item', array('val' => $gallery, 'class' => $class, 'post_type' => $post_type, 'cate_type' => $cate_type), true);
	         			}
	         		}
	         		else {

	         			$gallery_array['id'] 	= $id;

	         			$gallery_array 			= (object)$gallery_array;

	         			$result['data'] = get_template_file('function/gallery/html/object_gallery_item', array('val' => $gallery_array, 'class' => $class, 'post_type' => $post_type, 'cate_type' => $cate_type), true);
	         		
	         		}

	         		$result['id'] = (int)$post['id'];
					
					$result['type'] = 'success';
	        	}
	        	//Add dữ liệu
	        	else {

	        		$result['data'] = '';

	        		if( have_posts( json_decode($gallery_array['value'])) ) {

						$value = json_decode($gallery_array['value']);

						foreach ($value as $path) {

							$gallery_array['value'] = $path;

							$gallery_array['type'] = get_file_type($path);

							$gallery_array['id'] = ( $post['id'] == 0) ? time().uniqid() : $post['id'];

							$result['data'] .= get_template_file('function/gallery/html/object_gallery_item', array('val' => (object)$gallery_array, 'class' => $class, 'post_type' => $post_type, 'cate_type' => $cate_type), true);
						}
					}
					else {

						if( $post['id'] == 0) $gallery_array['id'] = time(); else $gallery_array['id'] = $post['id'];

						$gallery_array = (object)$gallery_array;

						$result['data'] = get_template_file('function/gallery/html/object_gallery_item', array('val' => $gallery_array, 'class' => $class, 'post_type' => $post_type, 'cate_type' => $cate_type), true);
					}

					$result['id'] 	= $post['id'];

					$result['type'] = 'success';
	     		}

	     		if($result['type'] == 'success') {

	     			$cache_id = 'gallery_';

					delete_cache($cache_id, true);
	     		}
	        }
	        else
	     	{
	     		$result['message'] = validation_errors();
	     	}
		}

		echo json_encode($result);
	}
}

register_ajax_admin('ajax_object_gallery_save');

if( !function_exists('ajax_object_gallery_del') ) {
	/**
	 * @ Save gallery item cho object
	 * */
	function ajax_object_gallery_del($ci, $model) {

		$result['type'] = 'error';
		
		$result['message'] = 'Cập nhật không thành công!';

		if($ci->input->post()) {

			$post 			= $ci->input->post();

        	$id 			= (int)$ci->input->post('id');

			$data 			= $ci->input->post('data');

			if(have_posts($data)) {

				$model->settable('gallerys');

				if($model->delete_where_in(array('field' => 'id', 'data' => $data), array('object_id' => $id))) {

					$objects = $model->gets_where(array('object_id' => $id));

					$result['data']    	= '';

					foreach ($objects as $key => $val) {
						$result['data'] .= get_template_file('function/gallery/html/object_gallery_item', array('val' => $val), true);
					}

					$result['type'] 	= 'success';

					$result['message'] 	= 'Xóa dữ liệu thành công!';

				}

				if($result['type'] == 'success') {
					
	     			$cache_id = 'gallery_';

					delete_cache($cache_id, true);
	     		}

			}
			else {
				$result['message'] 	= 'Không có dữ liệu nào được xóa!';
			}
		}

		echo json_encode($result);
	}
}

register_ajax_admin('ajax_object_gallery_del');