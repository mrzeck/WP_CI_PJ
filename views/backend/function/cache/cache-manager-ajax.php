<?php
// ajax setting tinymce
function ajax_admin_cache_clear( $ci, $model ) {

	$result['status'] = 'error';

	$result['message'] = 'Lưu dữ liệu không thành công!';

	if($ci->input->post()) {

		$data 			= removeHtmlTags($ci->input->post('data'));

		switch ( $data ) {
			
			case 'cms':
				cache_libary();
				$list_cache = scandir('views/cache');
				foreach ($list_cache as $key => $value) {
					if( $value == 'index.html' ) continue;
					if( $value == '.htaccess' ) continue;
					if( $value == '.' ) continue;
					if( $value == '..' ) continue;
					if( file_exists( 'views/cache/'.$value ) ) unlink( 'views/cache/'.$value );
				}
			break;

			case 'option':
				delete_cache('system');
			break;

			case 'widget':
				delete_cache('widget_', true);
				delete_cache('sidebar_', true);
			break;

			case 'gallery':
				delete_cache('gallery_', true);
			break;

			case 'category':
				delete_cache('post_category_', true);
			break;

			case 'user':
				delete_cache('user_', true);
			break;

			case 'metadata':
				delete_cache('metabox_', true);
			break;
			
			default:
				$cache = cache_manager_object();

				if( !empty($cache[$data]['callback'])) {

					call_user_func( $cache[$data]['callback'], $data );
				}

				break;
		}

		$result['status'] = 'success';

		$result['message'] = 'Cache đã xóa thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_admin_cache_clear');