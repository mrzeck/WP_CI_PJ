<?php
if(!function_exists('ajax_service_header_download')) {
	/**
	 * [ajax_plugin_wg_download download widget]
	 */
	function ajax_service_header_download($ci, $model) {

		$result['message'] 	= 'Download header không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= removeHtmlTags($ci->input->post('id'));

			$type 	= removeHtmlTags($ci->input->post('type'));

			$hd 	= array();

			if( $type == 'header' ) 	$hd = $ci->service_api->get_header($id);

			if( $type == 'navigation' ) $hd = $ci->service_api->get_navigation($id);

			if( $type == 'top-bar' ) 	$hd = $ci->service_api->get_top_bar($id);

			if( !have_posts($hd) || $hd->status == 'error' || !have_posts($hd->data) ) {

				echo json_encode($result);

				return false;
			}

			$url = $hd->data->file;

			$dir = VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style/';

			$temp_filename = basename( $url );

			$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

			$temp_filename  = $dir . $temp_filename . '.zip';

			$headers = getHeaders($url);

			if ($headers['http_code'] === 200) {

				if (download($url, $temp_filename)) {

					$result['message'] 	= 'Download header thành công';

					$result['type'] 	= 'success';
			  	}
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_service_header_download');
}

if(!function_exists('ajax_service_header_install')) {
	/**
	 * [ajax_plugin_wg_install giải nén file zip widget]
	 */
	function ajax_service_header_install($ci, $model) {

		$result['message'] 	= 'Cài đặt header không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= removeHtmlTags($ci->input->post('id'));

			$type 	= removeHtmlTags($ci->input->post('type'));

			$hd 	= array();

			if( $type == 'header' ) 	$hd = $ci->service_api->get_header($id);

			if( $type == 'navigation' ) $hd = $ci->service_api->get_navigation($id);

			if( $type == 'top-bar' ) 	$hd = $ci->service_api->get_top_bar($id);

			if( !have_posts($hd) || $hd->status == 'error' || !have_posts($hd->data) ) {

				echo json_encode($result);

				return false;
			}

			$url = $hd->data->file;

			$dir = 'views/'.$ci->data['template']->name.'/theme-header/'.$type.'-style/';

			$temp_filename = basename( $url );

			$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

			$temp_filename  = $dir . $temp_filename . '.zip';

			if( file_exists($temp_filename) ) {

				$zip = new ZipArchive;

				if( $zip->open($temp_filename) === TRUE ) {
						
					$zip->extractTo($dir);

					$zip->close();

					unlink( $temp_filename );

					$header_style = get_option( 'header_style_install', array() );

					$header_style[$hd->data->folder] = 1;

					update_option( 'header_style_install', $header_style );

					$result['message'] 	= 'Cài đặt header thành công';

					$result['type'] 	= 'success';

					$result['folder'] 	= $hd->data->folder;
				}

			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_service_header_install');
}

if(!function_exists('ajax_admin_header_active')) {
	/**
	 * [ajax_plugin_wg_install giải nén file zip widget]
	 */
	function ajax_admin_header_active($ci, $model) {

		$result['message'] 	= 'Kích hoạt header không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$type 	= removeHtmlTags($ci->input->post('type'));

			$folder = removeHtmlTags($ci->input->post('folder'));

			$path 	= FCPATH.VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style';

			$dir 	= $path.'/'.$folder;

			$header_style_active = get_option('header_style_active', array(
				'header' 		=> array(),
				'navigation' 	=> array(),
				'top-bar' 		=> array(),
			) );

			$header_style_active[$type] = array( $folder => $dir );

			update_option('header_style_active', $header_style_active );

			$result['message'] 	= 'Kích hoạt header thành công';

			$result['type'] 	= 'success';

		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_admin_header_active');
}

if(!function_exists('ajax_admin_header_unactive')) {
	/**
	 * [ajax_plugin_wg_install giải nén file zip widget]
	 */
	function ajax_admin_header_unactive($ci, $model) {

		$result['message'] 	= 'Tắt header không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$type 	= removeHtmlTags($ci->input->post('type'));

			$folder = removeHtmlTags($ci->input->post('folder'));

			$path 	= FCPATH.VIEWPATH.$ci->data['template']->name.'/theme-header/'.$type.'-style';

			$dir 	= $path.'/'.$folder;

			$header_style_active = get_option('header_style_active', array(
				'header' => array(),
				'navigation' => array(),
				'top-bar' => array(),
			) );

			if( isset($header_style_active[$type][$folder]) ) unset($header_style_active[$type][$folder]);

			update_option('header_style_active', $header_style_active );

			$result['message'] 	= 'Tắt header thành công';

			$result['type'] 	= 'success';

		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_admin_header_unactive');
}

if(!function_exists('ajax_admin_layout_save')) {

	function ajax_admin_layout_save($ci, $model) {

		$result['message'] 	= 'Cập nhật dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			if($ci->input->post('layout')) {

				$layout 				= $ci->input->post('layout');

				$layout_page            = removeHtmlTags($layout['page-layout']);

				$layout_post            = removeHtmlTags($layout['post-layout']);

				$layout_post_category   = removeHtmlTags($layout['post-category-layout']);

				

				$layout_list = theme_layout_list();

				if(isset($layout_list[$layout_page])) 			update_option('layout_page', $layout_page );

				if(isset($layout_list[$layout_post]))			update_option('layout_post', $layout_post );

				if(isset($layout_list[$layout_post_category])) 	update_option('layout_post_category', $layout_post_category );
			
				if(isset($layout['products-category-layout'])) {

					$layout_products_category   = removeHtmlTags($layout['products-category-layout']);

					if(isset($layout_list[$layout_products_category])) 	update_option('layout_products_category', $layout_products_category );
				}
			}

			if($ci->input->post('post_category')) {

				$layout = $ci->input->post('post_category');

				$post_category = array();

				$post_category['style'] 		= removeHtmlTags($layout['style']);

				if(isset($layout['sidebar'])) $post_category['sidebar'] 		= add_magic_quotes($layout['sidebar']);

				$post_category['horizontal'] 	= add_magic_quotes($layout['horizontal']);

				update_option('layout_post_category_setting', $post_category );
			}

			if($ci->input->post('post')) {

				$layout = $ci->input->post('post');

				$post = array();

				if(isset($layout['sidebar'])) $post['sidebar'] 		= add_magic_quotes($layout['sidebar']);

				update_option('layout_post_setting', $post );
			}

			if($ci->input->post('banner')) {

				$layout = $ci->input->post('banner');

				$banner = get_option('layout_banner_setting');

				if(!is_array($banner)) $banner = array();

				$banner['height'] = removeHtmlTags($layout['height']);

				$banner['page']   = removeHtmlTags($layout['page']);

				$banner['post']   = removeHtmlTags($layout['post']);
				
				$banner['post_category']   = removeHtmlTags($layout['post_category']);

				if(isset($layout['products_category'])) $banner['products_category']   = removeHtmlTags($layout['products_category']);

				update_option('layout_banner_setting', $banner );
			}

			$result['message'] 	= 'Cập nhật dữ liệu thành công';

			$result['type'] 	= 'success';

		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_admin_layout_save');
}
