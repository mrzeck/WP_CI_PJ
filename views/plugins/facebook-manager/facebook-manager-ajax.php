<?php
register_ajax_admin('ajax_fbm_active');
/**
 * [ajax_fbm_active action style]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_fbm_active($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$name = $ci->input->post('name');

		$name = removeHtmlTags($name);

		$option = get_option('fbm_active');

		$option = fbm_fix_version($option);

		if( !have_posts($option) ) {

			$option = array( 'fbm-send-message' => 0, 'fbm-tab' => 0);

			$option[$name] = 1;

		} else {

			if( isset($option[$name]) ) {
				if($option[$name] == 0) $option[$name] = 1;
				else $option[$name] = 0;
			}
		}

		if( have_posts($option)) {

			update_option( 'fbm_active', serialize($option) );

			$result['message'] 	= 'Cập nhật dữ liệu thành công!';

			$result['type'] 	= 'success';

		}
		
	}

	echo json_encode($result);
}


register_ajax_admin('ajax_fbm_save_setting');
/**
 * [ajax_fbm_save_setting save setting style]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_fbm_save_setting($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$name   = $ci->input->post('style');
		
		$name   = removeHtmlTags($name);
		
		//Xử lý data

		$data   = $ci->input->post();

		unset($data['action']); unset($data['style']); unset($data['csrf_test_name']);

		foreach ($data as $key => $value) {
			if( is_string($value)) $data[$key] = removeHtmlTags($value);
			else if( count($value) == 1 )
				$data[$key] = removeHtmlTags($value[0]);
		}
		
		$option = get_option('fbm_setting');
		
		$option = fbm_fix_version($option);

		if( !have_posts($option) ) $option = array();

		if( have_posts($data) ) $option[$name] = $data;

		if( have_posts($option)) {

			update_option( 'fbm_setting', serialize($option) );

			$result['message'] 	= 'Cập nhật dữ liệu thành công!';

			$result['type'] 	= 'success';

		}
		
	}

	echo json_encode($result);
}


