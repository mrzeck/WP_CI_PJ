<?php
function ajax_skd_theme_option_builder_save($ci, $model) {

    $result['message'] 	= 'Cập nhật cấu hình không thành công!';
    
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('system');

		$post = $ci->input->post();

		unset($post['action']);

		$theme_option = get_option('theme_option');

		if(have_posts($post) && have_posts($theme_option)) {

            //Save cms option
            $cms_widget_builder = (int)$post['cms_widget_builder'];

            $cms_minify_css      = (int)$post['cms_minify_css'];

            $cms_minify_js       = (int)$post['cms_minify_js'];

            update_option( 'cms_widget_builder', $cms_widget_builder );

            update_option( 'cms_minify_css', $cms_minify_css );

            update_option( 'cms_minify_js', $cms_minify_js );

            //Save theme option
			$option = array();

			foreach ($ci->theme_option['option'] as $key => $value) {
					$option[$value['field']] = $value;
			}
			foreach ($post as $key => $value) {
				if( isset($option[$key]) &&  ($option[$key]['type'] == 'image' || $option[$key]['type'] == 'file' || $option[$key]['type'] == 'video')) {
					$post[$key] = process_file($value);
				}
				unset($option[$key]);
			}

			if(isset($option) && have_posts($option)) {
				foreach ($option as $key => $value) {
					if( $value['type'] == 'checkbox') {
						if(isset($value['options']) && is_array($value['options']) === true)  { $post[$key] = array();}
						else $post[$key] = 0;
					}
				}
			}
            
			update_option( 'theme_option', $post );

			$ci->data['template']->minify_clear('css');

            $result['type'] 	= 'success';
            
			$result['message'] 	= 'Cập nhật thành công!';
		}

	}
	echo json_encode($result);
}
register_ajax_admin('ajax_skd_theme_option_builder_save');

function ajax_skd_theme_option_minify($ci, $model) {

    $result['message'] 	= 'Cập nhật cấu hình không thành công!';
    
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('system');

		$post = $ci->input->post();

		$type = removeHtmlTags($post['type']);

		if($ci->data['template']->minify_clear($type)) {

			$result['type'] 	= 'success';
            
			$result['message'] 	= 'Cập nhật thành công!';
		}
	}

	echo json_encode($result);
	
}
register_ajax_admin('ajax_skd_theme_option_minify');
