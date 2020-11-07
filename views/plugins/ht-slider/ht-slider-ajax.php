<?php
register_ajax_admin('ajax_hts_add_item');
/**
 * [ajax_hts_add_item thêm item vào slider]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_hts_add_item($ci, $model) {
	$result['message'] 	= 'Thêm dữ liệu không thành công!';
	$result['type'] 	= 'error';
	if($ci->input->post()) {
		$post = $ci->input->post(); unset($post['action']);
		if(have_posts($post)) {

			//Lấy id slider
			$data['group_id'] = (int)removeHtmlTags($post['slider_id']);

			$model->settable('group');

			$count 	= $model->count_where(array('object_type' => 'ht-slider', 'id' => $data['group_id']));

			if( $count )
			{
				$model->settable('gallerys');

				$data['object_type'] = 'ht-slider';

				$id = $model->add($data);

				if( $id ) {

					delete_cache('gallery_', true);

					$result['type'] 	= 'success';

					$result['id'] 		= $id;

					$result['message'] 	= 'Cập nhật thành công';
				}
			}
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_hts_del_item');
/**
 * [ajax_hts_del_item xóa item slider]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_hts_del_item($ci, $model) {

	$result['message'] 	= 'Xóa dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$id = $ci->input->post('id');

		$id = (int)removeHtmlTags($id);

		$model->settable('gallerys');

		if( $model->count_where(array('id' => $id)) ) {

			$id = $model->delete_where(array('id' => $id));

			if( $id ) {
				delete_cache('gallery_', true);

				$result['type'] 	= 'success';

				$result['id'] 		= $id;

				$result['message'] 	= 'Xóa dữ liệu thành công';
			}
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_hts_save_item');

/**
 * [ajax_hts_save_item lưu cấu hính item slider]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_hts_save_item($ci, $model) {

	$result['message'] 	= 'Lưu dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$post = $ci->input->post(); unset($post['action']);

		if(have_posts($post)) {

			$model->settable('gallerys');

			$item 	= $model->get_where(array('id' => $post['id']));

			if(have_posts($item))
			{
				$data['value'] = trim($post['value']);

				$data['value'] = removeHtmlTags($post['value']);

				$data['value'] = process_file($data['value']);

				$data['type']  = 'image';

				$data['options'] = array(
					'url'              => removeHtmlTags($post['url']),
					'name'             => removeHtmlTags($post['name']),
					'transition'       => removeHtmlTags($post['transition']),
					'data_transition'  => removeHtmlTags($post['data-transition']),
					'data_slotamount'  => removeHtmlTags($post['data-slotamount']),
					'data_masterspeed' => removeHtmlTags($post['data-masterspeed']),
				);

				$data['options'] = serialize($data['options']);

				$id = $model->update_where($data, array('id' => $post['id']));

				if($id) {

					delete_cache( 'gallery_', true );

					$result['type'] 	= 'success';

					$result['id'] 		= $id;

					$result['message'] 	= 'Cập nhật thành công';
				}
			}
		}
	}
	echo json_encode($result);
}





