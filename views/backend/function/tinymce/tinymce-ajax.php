<?php
// ajax setting tinymce
function ajax_admin_tinymce($ci,$model) {

	$result['status'] = 'error';

	$result['message'] = 'Lưu dữ liệu không thành công!';

	if($ci->input->post()) {

		$data 			= $ci->input->post('info');

		update_option('tinymce_config_general', $data );

		$result['status'] = 'success';

		$result['message'] = 'Lưu dữ liệu  thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_admin_tinymce');


function ajax_admin_tinymce_shortcut($ci,$model){

	$result['status'] = 'error';

	$result['message'] = 'Lưu dữ liệu không thành công!';

	if($ci->input->post()) {

		$data 			= $ci->input->post('info');

		update_option('tinymce_config_general_shortcut', $data );

		$result['status'] = 'success';

		$result['message'] = 'Lưu dữ liệu  thành công!';
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_admin_tinymce_shortcut');


// ajax add font size and font-family
function ajax_admin_tinymce_font($ci,$model){

	$result['status']  = 'error';

	$result['message'] = 'Lưu dữ liệu không thành công!';

	if($ci->input->post()) {

		$data['font_size'] 		= $ci->input->post('fontsize');//luu font chữ

		$data['font_family'] 	= $ci->input->post('font_family');//lưu font chữ

		$data['font_size']	 	= explode(',', $data['font_size']);

		update_option('tinymce_config_font_size', $data['font_size'] );

		update_option('tinymce_config_font_family', $data['font_family']);

		$result['status'] = 'success';

		$result['message'] = 'Lưu dữ liệu thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_admin_tinymce_font');