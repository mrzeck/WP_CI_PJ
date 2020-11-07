<?php
register_ajax_admin('ajax_cmi_active_style');
/**
 * [ajax_cmi_active_style action style]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_cmi_active_style($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$name = $ci->input->post('name');

		$name = removeHtmlTags($name);

		$option = cmi_fix_version( get_option('cmi_active') );

		$option = array_merge( array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0, 'style-4' => 0), $option);

		if( !have_posts($option) ) {

			$option = array( 'style-1' => 0, 'style-2' => 0, 'style-3' => 0, 'style-4' => 0);

			$option[$name] = 1;

		} else {

			if( isset($option[$name]) ) {
				if($option[$name] == 0) $option[$name] = 1;
				else $option[$name] = 0;
			}
		}

		if( have_posts($option)) {

			update_option( 'cmi_active', serialize($option) );

			$result['message'] 	= 'Cập nhật dữ liệu thành công!';

			$result['type'] 	= 'success';

		}
		
	}

	echo json_encode($result);
}


register_ajax_admin('ajax_cmi_save_style');
/**
 * [ajax_cmi_save_style save setting style]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_cmi_save_style($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$name   = $ci->input->post('style');
		
		$name   = removeHtmlTags($name);
		
		//Xử lý data

		$data   = $ci->input->post();

		unset($data['action']); unset($data['style']); unset($data['csrf_test_name']);
		unset($data['post_type']);
		unset($data['cate_type']);

		foreach ($data as $key => $value) {
			if( is_string($value))
				$data[$key] = removeHtmlTags($value);
		}
		
		$option = cmi_fix_version( get_option('cmi_style') );

		if( !have_posts($option) ) $option = array();

		if( have_posts($data) ) $option[$name] = $data;

		if( have_posts($option)) {

			update_option( 'cmi_style', serialize($option) );

			$result['message'] 	= 'Cập nhật dữ liệu thành công!';

			$result['type'] 	= 'success';

		}
		
	}

	echo json_encode($result);
}


