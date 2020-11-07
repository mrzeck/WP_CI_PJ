<?php
/**
=================================================
USER
=================================================
*/
//user : login
function ajax_user_login($ci, $model) {

	$result['message'] = 'Đăng nhập không thành công!';

	$result['type'] = 'error';

	if($ci->input->post()) {

		$model->settable('users');

		$username = removeHtmlTags($ci->input->post('username'));

		$password = removeHtmlTags($ci->input->post('password'));

		$user = skd_signon(['username' => $username, 'password' => $password]);

		if( is_skd_error($user) ) {

			foreach ($user->errors as $error_key => $error_value) {

				$result['message'] = $error_value[0];
			}

		}
		else {

			if( !user_has_cap( $user->id, 'loggin_admin') ) {

				$result['type'] 	= 'error';

				$result['message'] 	= 'Bạn không có quyền truy cập!';

				unset($_SESSION['user']);

				setcookie("user_login",  $user_cookie, time()-10, base_url());
			}
			else {

				do_action('skd_admin_login', $user );

				$_SESSION['allow_upload']	= true;

				$result['type'] 			= 'success';

				$result['message'] 			= 'Đăng nhập thành công!';
			}
		}
	}

	echo json_encode($result);
}
register_ajax_admin_nov('ajax_user_login');

//user: check root pass and rest pass user
function ajax_reset_pass($ci, $model) {

	$result['message'] = 'Thay đổi mật khẩu thất bại!';

	$result['type'] = 'error';

	if($ci->input->post()) {

		if(!superadmin()) {

			$result['message'] = 'Bạn chưa có quyền thay đổi mật khẩu thành viên!';

			echo json_encode($result);

			return false;
		}

		$model->settable('users');

		$post 			= $ci->input->post();

		$user_current 	= $ci->data['user'];

		//kiểm tra quyền thay đổi
		if($post['check'] == 'false') {

			$post['password'] = generate_password($post['password'], $user_current->username, $user_current->salt);
			
			if($post['password'] == $user_current->password) {

				$result['type'] = 'success';
			}
			else {

				$result['message'] = 'Bạn chưa có quyền thay đổi mật khẩu thành viên này!';
			}
		}
		//tiến hành reset pass
		if($post['check'] == 'true') {

			if(!superadmin($user_current->id)) {

				$result['message'] = 'Bạn chưa có quyền thay đổi mật khẩu thành viên này!';

				echo json_encode($result);

				return false;
			}

			$user = $model->get_where(array('id'=>$post['id']));

			$data['salt'] 		=  random(32, TRUE);

			$data['password'] 	=  generate_password($post['password'], $user->username, $data['salt']);
			
			if($model->update_where($data, array('id' => $user->id))) {

				$result['type'] 	= 'success';

				$result['message'] 	= 'Reset mật khẩu thành công.';
			}
		}
	}
	echo json_encode($result);
}
register_ajax_login('ajax_reset_pass');

function ajax_user_trash($ci, $model) {

	$result['message'] 	= 'Xóa thành viên không thành công.';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('users');

		$id = (int)$ci->input->post('id');

		$user 			= get_user_by('id', $id);

		$user_current 	= get_user_current();

		if( !have_posts($user_current) ) {

			$result['type'] 	= 'error';

			$result['message'] 	= 'Vui lòng đăng nhập lại tài khoản.';

			echo json_encode($result);

			return false;
		}

		if( !have_posts($user) ) {

			$result['type'] 	= 'error';

			$result['message'] 	= 'ID Thành viên không đúng.';

			echo json_encode($result);

			return false;
		}

		if( is_super_admin( $user->id ) ) {

			$result['type'] 	= 'error';

			$result['message'] 	= 'Bạn không có quyền xóa thành viên này.';

			echo json_encode($result);

			return false;
		}

		if( $user_current->id == $id ) {

			$result['type'] 	= 'error';

			$result['message'] 	= 'Bạn không thể xóa tài khoản của bạn.';

			echo json_encode($result);

			return false;
		}

		$user_update = update_user( array( 'id' => $user->id, 'status' => 'trash' ) );

		if ( ! is_skd_error( $user_update ) ) {

			$result['type'] 	= 'success';

			$result['message'] 	= 'Xóa thành viên thành công.';
		}
	}

	echo json_encode($result);
}
register_ajax_admin_nov('ajax_user_trash');

/**
=================================================
THEME
=================================================
*/
/**
Ajax theme active
*/
function ajax_theme_active($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Kích hoạt theme không thành công!';

	$data =  $ci->input->post('value');

	if($data != null && $ci->template->isset_template($data)) {

		$id = update_option( 'theme_current', $data );
		
		if($id != 0) {
			$result['type'] 	= 'success';
			$result['message'] 	= 'Kích hoạt theme thành công!';
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_theme_active');

/**
Ajax theme info
*/
function ajax_theme_info($ci, $model) {
	$result['type'] 	= 'error';
	$result['message'] 	= 'Lấy thông tin theme không thành công!';

	$name =  $ci->input->post('value');

	if($name != null && $ci->template->isset_template($name)) {
		$template = new template($name);
		$result['type'] 	= 'success';
		$result['data'] 	= $ci->load->view($ci->template->get_name().'/include/ajax-page/theme_info',array('info'=> (object)$template), true);
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_theme_info');

/**
 * [ajax_dashboard_sort sắp xếp dashboard]
 */
function ajax_dashboard_sort($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'cập nhật không thành công!';

	if($ci->input->post()) {

		$data = $ci->input->post('data');

		$data = add_magic_quotes($data);

		update_option('dashboard_sort', $data );

		$result['type'] 	= 'success';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_dashboard_sort');

/**
 * [ajax_dashboard_sort sắp xếp dashboard]
 */
function ajax_dashboard_save($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'cập nhật không thành công!';

	if($ci->input->post()) {

		$data = $ci->input->post('dashboard');

		$data = add_magic_quotes($data);

		update_option('dashboard', $data );

		$result['type'] 	= 'success';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_dashboard_save');

/**
=================================================
THEME OPTION
=================================================
*/
function ajax_theme_option_save($ci, $model) {
	$result['message'] 	= 'Cập nhật system không thành công!';
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('system');

		$post = $ci->input->post();

		unset($post['action']);

		$theme_option = get_option('theme_option');

		if(have_posts($post) && have_posts($theme_option)) {

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

			$result['type'] 	= 'success';
			$result['message'] 	= 'Cập nhật thành công!';
		}

	}
	echo json_encode($result);
}
register_ajax_admin('ajax_theme_option_save');
/**
=================================================
THEME EDITOR
=================================================
*/
function ajax_theme_editor($ci, $model) {
	$result['message'] 	= 'lấy nội dung file không thành công!';
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$path = $ci->input->post('path');

		if(!is_dir($path)) {

			$string = file($path);

			$ext = explode('.',$path);

			$ext = array_pop($ext);

			if($ext == 'js') 	$ext = 'javascript';
			if($ext == 'php') 	$ext = 'application/x-httpd-php';
			if($ext == 'css') 	$ext = 'text/css';

			$result['lang'] = $ext;

			$string = implode('',$string);
			$id = str_replace('/', '-', $path);
			$id = str_replace('.', '-', $id);
			$data = array(
				'name'  => 'editor-code',
				'id'    => 'editor-content'.$id,
				'value' => $string,
			);

			$data = form_textarea($data);

			$result['content'] 	= $data;

			$result['type'] 	= 'success';
		}

	}
	echo json_encode($result);
}
register_ajax_admin('ajax_theme_editor');

function ajax_theme_editor_save($ci, $model) {

	$result['message'] 	= 'Lưu file không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$ci->load->helper('file');

		$path 		= $ci->input->post('path');
		$content 	= $ci->input->post('content');

		if(!is_dir($path)) {

			write_file($path, $content);
			
			$result['type'] 	= 'success';
			$result['message'] 	= 'Lưu File thành công!';
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_theme_editor_save');
/**
=================================================
THEME MENU
=================================================
*/
function ajax_group_add($ci, $model) {

	$result['message'] 	= 'Thêm mới group không thành công!';
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('group');

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$data = array(
				'name' 			=> $post['name'],
				'object_type' 	=> $post['object_type'],
			);

			if(isset($post['options']) && !empty($post['options'])) $data['options'] = $post['options'];

			if(isset($data['options']) && have_posts($data['options'])) $data['options'] = serialize($data['options']);

			$model->add($data);

			$result['type'] 	= 'success';
			$result['message'] 	= 'Cập nhật thành công!';
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_group_add');

function ajax_menu_add($ci, $model) {

	$result['message'] 	= 'Thêm dữ liệu không thành công!';
	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$model->settable('group');

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$data['menu_id'] 		= $post['menu_id'];
			$data['type']    		= $post['type'];
			$data['object_type']    = $post['object_type'];
			$value           		= $post['data'];

			$count 					= 0;

			if($data['type'] == 'link')
			{
				$data['name'] = $value['name'];
				$data['slug'] = $value['link'];
				$model->settable('menu');

				$id = $model->add($data);

				if($id) {

					$object = $model->get_where(array('id'=>$id));

					if(have_posts($object)) {
						$fields[] = $ci->load->view($ci->template->name.'/include/loop/menu_item',array('ci' => $ci, 'val' =>$object), true);
						$count++;
					}
				}
			}
			else {
				foreach ($value as $key => $val) {

					$model->settable($data['type']);

					$object = $model->get_where(array('id' => $val));

					if(have_posts($object)) {

						$data['name'] 		= (isset($object->title))?$object->title:$object->name;

						$data['slug'] 		= $object->slug;

						$data['object_id'] 	= $val;
					}
					else if($val == 0 && $data['type'] == 'products_categories'){
						$data['name'] 		= 'Sản Phẩm';
						$data['slug'] 		= URL_PRODUCT;
						$data['object_id'] 	= $val;
					}

					$model->settable('menu');

					$id = $model->add($data);

					if($id) {

						$object = $model->get_where(array('id'=>$id));

						if(have_posts($object)) {
							$fields[] = $ci->load->view($ci->template->name.'/include/loop/menu_item',array('ci' => $ci, 'val' =>$object), true);
						}

						$count++;
					}
				}
			}

			if($count > 0)
			{

				$id = $post['menu_id'];

				delete_cache('menu-'.$id);

				do_action('ajax_menu_add_success', $id );

				$result['type'] 	= 'success';
				$result['fields'] 	= $fields;
				$result['message'] 	= 'Cập nhật thành công';
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_menu_add');

function ajax_menu_del($ci, $model) {

	$result['type'] 	= 'error';
	
	$result['message'] 	= 'Gở bỏ menu không thành công!';

	if($ci->input->post()) {

		$id = $ci->input->post('data');

		$model->settable('group');

		$object = $model->get_where( array( 'id' => $id, 'object_type' => 'menu') );

		if(have_posts($object))
		{

			if($model->delete_where(array('id' => $object->id)))
			{
				$model->settable('menu');

				if ( $model->delete_where( array( 'menu_id' => $id) ) ) {

					delete_cache('menu-'.$id);

					do_action('ajax_menu_del_success', $id );

					$result['type'] = 'success';

					$result['message'] = 'Gở bỏ menu thành công!';
				}

			}
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_menu_del');

function ajax_menu_sort($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$model->settable('group');

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$model->settable('menu');

			$data = $post['data'];

			$order = 0;

			recursive_item_sort($data, 0, $model);

			$id = $post['id'];

			delete_cache('menu-'.$id);

			do_action('ajax_menu_sort_success', $id );

			$result['type'] = 'success';

			$result['message'] = 'Cập nhật thành công!';
		}
	}
	echo json_encode($result);
}

function recursive_item_sort($data = '', $parent_id = 0, $model) {

	$model->settable('menu');

	foreach ($data as $key => $value) {

		$id = $value['id'];

		$model->update_where(array('order' => $key, 'parent_id' => $parent_id), array('id' => $id));

		if(isset($value['children'])) {
			recursive_item_sort($value['children'], $id, $model);
		}
	}
}
register_ajax_admin('ajax_menu_sort');

function ajax_menu_item_del($ci, $model) {

	$result['type'] = 'error';
	$result['message'] = 'Gở bỏ menu không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$model->settable('menu');

			$id = $post['id'];

			$object = $model->get_where(array('id'=>$id));

			if(have_posts($object))
			{
				$model->update_where(array('parent_id' => $object->parent_id),array('parent_id' => $object->id));

				if($model->delete_where(array('id' => $object->id)))
				{
					$menu_id = $object->menu_id;

					$items = $model->gets_where(array('menu_id' => $menu_id, 'parent_id' => 0));

					$ci->data['items'] = $ci->multilevel_categories($items, array('menu_id' => $menu_id), $model);

					$result['field_html'] = $ci->load->view($ci->template->name.'/include/ajax-page/menu_item', $ci->data, true);

					$result['type'] = 'success';

					$result['message'] = 'Gở bỏ menu thành công!';

					delete_cache('menu-'.$object->menu_id);

					do_action('ajax_menu_item_del_success', $object->menu_id );
				}
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_menu_item_del');

function ajax_menu_item_edit($ci, $model) {

	$result['message'] 	= 'Lấy dữ liệu không thành công!';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$model->settable('menu');

			$id = $post['id'];

	 		$object = $model->get_where(array('id' => $id));

	 		if(have_posts($object)) {

	 			$result['type'] 	= 'success';

				$result['message'] 	= 'Lấy dữ liệu thành công!';

				$result['data']  	= $ci->load->view($ci->template->name.'/include/ajax-page/menu_form_edit',array('val' =>$object), true);
	 		}
	 	}
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_menu_item_edit');

function ajax_menu_item_save($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$model->settable('menu');

			$id 			= $post['id'];
			$data['name'] 	= $post['name'];
			unset($post['name']);
			unset($post['id']);

			$object = $model->get_where(array('id'=>$id));

			if(have_posts($object))
			{
				if($object->name != $data['name']) $data['edit'] = 1;

				if($object->type == 'link') {
					$data['slug'] = $post['url'];
					unset($post['url']);
				}

				//option
				$data['data'] = array();
				if(isset($post) && have_posts($post)) {
					foreach ($post as $key => $val) {
						$data['data'][$key] = process_file($val);
					}
				}

				$data['data'] = serialize($data['data']);

				if($model->update_where($data, array('id' => $id)))
				{
					$result['type'] = 'success';
					$result['message'] = 'Cập nhật thành công!';

					delete_cache('menu-'.$object->menu_id);

					do_action('ajax_menu_item_save_success', $object->menu_id );
				}
			}
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_menu_item_save');

function ajax_menu_item_search($ci, $model) {

	$result = 'Không có kết quả nào!';

	if($ci->input->post()) {

		$post 			= $ci->input->post();

		$object 		= $post['object'];

		$object_type 	= $post['object_type'];

		$key    		= $post['key'];

		$where = array();

		$model->settable($object);

		if($object == 'categories') {
			$where['cate_type'] = $object_type;
		}

		if($object == 'post') {
			$where['post_type'] = $object_type;
		}

		if($object == 'categories' || $object == 'products_categories')
			$data['like']['name'] 		= array($key);
		else $data['like']['title'] 	= array($key);

		$objects = $model->gets_where_like($data, $where);

		if(have_posts($objects)) {
			$result = '';
			foreach ($objects as $key => $val) {
				$id = $val->id;
				$value = (isset($val->title))?$val->title:$val->name;
				$result .='<div class="checkbox"><label><input name="'.$object.'" type="checkbox" value="'.$id.'"> &nbsp;'.$value.'</label></div>';
			}
		}
	}

	echo $result;
}
register_ajax_admin('ajax_menu_item_search');

function ajax_menu_position($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		unset($post['action']);

		if(have_posts($post)) {

			$model->settable('relationships');

			$data['object_id']   = $post['id'];
			
			$data['category_id'] = $post['position'];
			
			$data['object_type'] = 'menu';
			
			$object              = $model->get_where($data);
			
			$position            = $model->get_where(array('category_id' => $post['position'], 'object_type' => 'menu'));

			$id = 0;

			if( have_posts($position) ) {

				if( have_posts($object) && $object->object_id == $position->object_id ) {

					$id = $model->delete_where($data);
				}

				if( !have_posts($object) ) {

					$id = $model->update_where($data, array( 'id' => $position->id ));
				}
				
			}
			else {
				$id = $model->add($data);
			}


			if( $id != 0 )
			{
				$result['type'] = 'success';

				$result['message'] = 'Cập nhật thành công!';
			}
		}
	}
	echo json_encode($result);
}

register_ajax_admin('ajax_menu_position');


/**
=================================================
THEME WIDGET SERVICE
=================================================
*/
if(!function_exists('ajax_widget_service')) {
	/**
	 * [ajax_plugin_wg_download download widget]
	 */
	function ajax_widget_service($ci, $model) {

		$result['message'] 	= 'Load widget không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$wg = get_cache('widget_service_item');

			$wg_cate = get_cache('widget_service_category');

			$status = 'errror';

			if( !have_posts($wg) || !cache_exists('widget_service_item') ) {

				$wg 	= $ci->service_api->gets_widget();

				if($wg->status == 'success') {

					$status = 'success';

					$wg = $wg->data;

					save_cache('widget_service_item', $wg, 8*60*60 ); //Lưu cache trong 8h
				}
			}

			if(!have_posts($wg_cate) || !cache_exists('widget_service_category') ) {

				$wg_cate = $ci->service_api->gets_widget_category();

				if($wg_cate->status == 'success') {

					$wg_cate = $wg_cate->data;

					save_cache('widget_service_category', $wg_cate, 8*60*60 ); //Lưu cache trong 8h
				}
			}

			if( !have_posts($wg) || $status == 'error' ) {

				$result['data']  	= $ci->load->view($ci->template->name.'/include/ajax-page/widget_license',$ci, true);

				echo json_encode($result);

				return false;
			}

			$result['data'] = $ci->load->view($ci->template->name.'/include/ajax-page/widget_service', array('widgets' => $wg, 'categories' => $wg_cate ), true);

			$result['data']  	.= '<div class="clearfix"></div>';

			$result['message'] 	= 'Load widget thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_widget_service');
}

if(!function_exists('ajax_widget_service_category')) {
	/**
	 * [ajax_plugin_wg_download download widget]
	 */
	function ajax_widget_service_category($ci, $model) {

		$result['message'] 	= 'Load widget không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= (int)$ci->input->post('cate');

			if( !cache_exists( 'widget_service_item_'.$id ) ) {

				$wg 	= $ci->service_api->gets_widget( $id );

				if($wg->status == 'success') {

					$wg = $wg->data;

					save_cache( 'widget_service_item_'.$id, $wg, 8*60*60 ); //Lưu cache trong 8h
				}
			}
			else $wg = get_cache( 'widget_service_item_'.$id );

			$result['data'] = '';

			if( have_posts($wg) ) {
				foreach ($wg as $key => $val) {
					$result['data']  	.= $ci->load->view($ci->template->name.'/include/ajax-page/widget_service_item',array('item' =>$val), true);
				}
			}
			else
				$result['data'] = notice('error', 'Danh mục này chưa có widget nào.');
			

			$result['data']  	.= '<div class="clearfix"></div>';

			$result['message'] 	= 'Load widget thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_widget_service_category');
}

/**
=================================================
THEME WIDGET
=================================================
*/

function ajax_widget_load($ci, $model)
{
	$result['type'] 	= 'error';

	$result['message'] 	= 'Load widget không thành công!';

	if($ci->input->post()) {

		$widgets = $ci->template->get_widget();

		if( have_posts($widgets) ) {

			$result['data'] 	= '';

			foreach ( $widgets as $key_widget => $widget ) {

				$widget = array(
					'id' 		=> 0,
					'widget_id' => $key_widget,
					'sidebar_id' => null,
					'name' => $widget->name,
					'widget_name' => $widget->name,
					'options' => array(),
				);

				$result['data'] .= $ci->template->render_include('loop/widget_item', array( 'val' => (object)$widget ),true);
			
			}

			$result['type'] 	= 'success';

			$result['message'] 	= 'Load widget thành công!';
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_load');

function ajax_widget_add($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Thêm widget không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$post               = $ci->input->post();

		$widget_id          = $post['widget_id'];

		$widget             = $ci->template->get_widget($widget_id);

		$data['name']       = $widget->name;

		$data['template']   = $ci->data['template']->name;

		$data['widget_id']  = $widget_id;

		$data['sidebar_id'] = $post['sidebar_id'];

		$data['options']    = serialize($widget->get_option());

		// $max_order = $model->get_where(array('sidebar_id' => $post['sidebar_id']), array('select' => 'MAX(`order`) as \'max_order\''));
		
		// if(have_posts($max_order)) {
		// 	$data['order'] = $max_order->max_order + 1;
		// }

		$data['id'] = $model->add($data);

		$data['widget_name'] = $widget->name;

		if($data['id']) {

			$cache_id = 'sidebar_'.md5($post['sidebar_id'].'_'.$ci->data['template']->name);

			delete_cache($cache_id);

			$result['id']       = $data['id'];

			$result['type'] 	= 'success';

			$result['message'] 	= 'Thêm widget thành công!';
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_add');

function ajax_widget_del($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Gở bỏ widget không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$post 	= $ci->input->post();

		$id 	= $post['id'];

		$widget = $model->get_where(array('id' => $id));

		if($model->delete_where(array('id' => $id)))
		{

			$cache_id = 'sidebar_'.md5($widget->sidebar_id.'_'.$ci->data['template']->name);

			delete_cache($cache_id);

			$result['type'] = 'success';

			$result['message'] = 'Gở bỏ widget thành công!';
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_del');

function ajax_widget_edit($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$post 	= $ci->input->post();

		$id 	= $post['id'];

		$ci->data['widget'] = $model->get_where(array('id' => $id));

		if(have_posts($ci->data['widget'])) {

			$ci->data['widget']->options = unserialize($ci->data['widget']->options);
			
			$widget = $ci->template->get_widget($ci->data['widget']->widget_id);

			$widget->form();

			$widget->set_name($ci->data['widget']->name);

			$widget->get_option($ci->data['widget']->options);

			$result['data'] = $ci->template->render_include('ajax-page/widget_form',array('widget'=>$widget),true);
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_edit');

function ajax_widget_save($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$post = $ci->input->post();

		$id = $ci->input->post('id');

		$object = $model->get_where(array('id' => $id));

		if(have_posts($object)) {

			$widget = $ci->template->get_widget($object->widget_id);

			$widget->form();

			$options = $widget->get_option($post);

			$temp = array_merge($widget->left, $widget->right);

			$input 	= array();

			foreach ($temp as $key => $value) {
				$input[$value['field']] = $value['type'];
			}

			foreach ($options as $key => $option) {

				if( isset($input[$key]) ) {

					if( $input[$key] == 'wysiwyg' ) continue;

					if( $input[$key] == 'text' ) $options[$key] = removeHtmlTags($option);

					if( $input[$key] == 'image' || $input[$key] == 'file' ) $options[$key] = process_file(removeHtmlTags($option));
				}
			}

			$data['name'] 		= $post['name'];

			$data['options'] 	= $options;

			$data 				= apply_filters('before_widget_save', $data );

			$data 				= apply_filters('wg_before_'.$object->widget_id.'_save', $data );

			$old_instance = [];
			
			$old_instance['name']    = $object->name;

			$old_instance['options'] = unserialize($object->options);

			$data 					 = $widget->update($data, $old_instance);

			$data['options'] 		 = serialize($data['options']);

			if($model->update_where($data, array('id'=>$object->id))) {

				$cache_id = 'sidebar_'.md5($object->sidebar_id.'_'.$ci->data['template']->name);

				delete_cache($cache_id);

				$result['type'] 	= 'success';

				$result['message'] 	= 'Cập nhật dữ liệu thành công';
			}
		}
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_widget_save');

function ajax_widget_sort($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post() != null) {

		$model->settable('widget');

		$data 	= $ci->input->post('data');

		$widget = $model->get_where(array('id' => $data[0]));

		foreach ($data as $key => $id) {

			$model->update_where( array('order' => $key), array('id' => $id));
		}

		$cache_id = 'sidebar_'.md5($widget->sidebar_id.'_'.$ci->data['template']->name);

		delete_cache($cache_id);

		$result['type'] = 'success';

		$result['message'] = 'Cập nhật thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_sort');

function ajax_widget_move($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$id  = $ci->input->post('widget_id');

		$data['sidebar_id'] = $ci->input->post('sidebar_id');

		$widget = $model->get_where(array('id' => $id));

		if($model->update_where($data, array('id' => $id)))
		{
			$cache_id = 'sidebar_'.md5($widget->sidebar_id.'_'.$ci->data['template']->name);

			delete_cache($cache_id);

			$cache_id = 'sidebar_'.md5($data['sidebar_id'].'_'.$ci->data['template']->name);

			delete_cache($cache_id);

			$result['type'] = 'success';

			$result['message'] = 'Cập nhật thành công!';
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_widget_move');


function ajax_widget_copy($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Nhân bảng không thành công!';

	if($ci->input->post()) {

		$model->settable('widget');

		$post               = $ci->input->post();

		$id          		= (int)$post['id'];

		$widget = $model->get_where(array('id' => $id));

		if( have_posts($widget) ) {

			$data['name']       = $widget->name;
			$data['template']   = $ci->data['template']->name;
			$data['widget_id']  = $widget->widget_id;
			$data['sidebar_id'] = $widget->sidebar_id;
			$data['options']    = $widget->options;

			//lấy số thứ tự
			$max_order = $model->get_where(array('sidebar_id' => $widget->sidebar_id), array('select' => 'MAX(`order`) as \'max_order\''));
			
			if(have_posts($max_order)) {
				$data['order'] = $max_order->max_order + 1;
			}

			$data['id'] 			= $model->add($data);

			$data['widget_name'] 	= $widget->name;

			if($data['id'])
			{

				$cache_id = 'sidebar_'.md5($widget->sidebar_id.'_'.$ci->data['template']->name);

				delete_cache($cache_id);

				$result['data'] 		= $ci->template->render_include('loop/widget_item',array('val' => (object)$data),true);

				$result['sidebar_id'] 	= $data['sidebar_id'];

				$result['type'] 		= 'success';

				$result['message'] 		= 'Nhân bản thành công!';
			}

		}
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_widget_copy');
/**
=================================================
GALLERY
=================================================
*/
function ajax_gallery_load($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Load dữ liệu không thành công!';

	if($ci->input->post()) {

		$id = (int)$ci->input->post('id');

        $result['type']    = 'success';
						
		$result['message'] = 'Cập nhật dữ liệu thành công.';

		$result['data']    	= '';

		$objects = gets_gallery( $id );

		foreach ($objects as $key => $val) {
			$result['data'] .= $ci->template->render_include('loop/gallery_item',array('val'=>$val),true);
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_gallery_load');

function ajax_gallery_save($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		$id = $ci->input->post('id');

		$rules[] = array('field' => 'group_id', 'label' => 'Gallery', 	'rules' => 'trim|required');

		$rules[] = array('field' => 'value', 	'label' => 'File dữ liệu', 	'rules' => 'trim|required');

		$ci->form_validation->set_rules($rules);

		if($ci->form_validation->run())
        {
        	$model->settable('group');

        	$group = $model->get_where(array('id' => $post['group_id'], 'object_type' => 'gallery'));

        	$type = get_file_type($post['value']);
        	
        	if(have_posts($group)) {

        		$gallery_array = array(
					'type'     => $type,
					'value'    => $post['value'],
					'options'  => (isset($post['option']))?$post['option']:array(),
					'group_id' => $group->id,
				);

         		//thêm item
         		if( $id != 0 ) {
         			$gallery_array['id'] = $id;
         		}

         		$gid = insert_gallery( $gallery_array );

         		//load dữ liệu nếu save thành công
         		if( !empty($gid) ) {

					$result['type']    = 'success';
						
					$result['message'] = 'Cập nhật dữ liệu thành công.';

         			$result['data']    	= '';

					$objects = gets_gallery( $group->id );

					foreach ($objects as $key => $val) {
						$result['data'] .= $ci->template->render_include('loop/gallery_item',array('val'=>$val),true);
					}
         		}
        	}
        	else {
     			$result['message'] 	= 'Gallery không tồn tại';
     		}
        }
        else
     	{
     		$result['message'] = validation_errors();
     	}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_gallery_save');

function ajax_gallery_get_item($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		$id = (int)$ci->input->post('id');

     	$result['data'] = _get_gallery( $id );

 		if(have_posts($result['data'])) {

 			$result['data']->options = get_gallery_meta( $id, '', false );

 			$result['type'] 	= 'success';

			$result['message'] 	= 'Lấy dữ liệu thành công!';
 		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_gallery_get_item');

function ajax_gallery_del_item($ci, $model) {

	$result['type'] = 'error';
	
	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();
		
		$id   = removeHtmlTags($ci->input->post('id'));
		
		$data = $ci->input->post('data');

		$model->settable('gallerys');

     	if(have_posts($data)) {

			if($model->delete_where_in(array('field' => 'id', 'data' => $data), array('group_id' => $id))) {

				$cache_id = 'gallery_'.md5('_group_'.$id);

				delete_cache( $cache_id );

				$objects = $model->gets_where(array('group_id' => $id));

				delete_cache('gallery_', true);

				$result['type'] 	= 'success';

				$result['message'] 	= 'Xóa dữ liệu thành công!';

				$result['data']    	= '';

				foreach ($objects as $key => $val) {

					$result['data'] .= $ci->template->render_include('loop/gallery_item',array('val'=>$val),true);
				}
			}
		}
		else {
			$result['message'] 	= 'Không có dữ liệu nào được xóa!';
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_gallery_del_item');

function ajax_gallery_sort_item($ci, $model) {

	$result['type'] 	= 'error';

	$result['message'] 	= 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$data = $ci->input->post('data');

    	if(have_posts($data)) {

    		foreach ( $data as $id => $order ) {

    			$gallery_array = array(
					'id'    => (int)$id,
					'order' => (int)$order,
				);

				insert_gallery( $gallery_array );
    		}

    		$result['type']    = 'success';
				
			$result['message'] = 'Cập nhật dữ liệu thành công.';
    	}

	}

	echo json_encode($result);
}

register_ajax_admin('ajax_gallery_sort_item');

function ajax_gallery_del($ci, $model) {

	$result['type'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$post = $ci->input->post();

		$id   = $ci->input->post('id');

		$model->settable('gallerys');

		$model->delete_where(array('group_id' => $id));

		$model->settable('group');

		$model->delete_where(array('id' => $id, 'object_type' =>'gallery'));

		$result['type'] 	= 'success';

		$result['message'] 	= 'Xóa dữ liệu thành công!';
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_gallery_del');
/**
=================================================
FORM
=================================================
*/
//kiểm tra form box đóng mở
function ajax_collapse($ci, $model) {

	$result['message'] 	= time();

	$result['type'] 	= 'error';

	if($ci->input->post('id')) {

		$id = $ci->input->post('id');

		if(get_cookie($id) != null) {
			delete_cookie($id);
		}
		else {
			set_cookie($id, $id, '86500');
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_collapse');

//kiểm tra dữ liệu
function ajax_form_validation($ci, $model) {

	$result['message'] 	= 'Thêm dữ liệu thất bại';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$post = $ci->input->post();

		$form = $ci->form_gets_field(array('class' => $post['module']));

		$ci->form_validation->set_rules($form['rules']);

		if(!$ci->form_validation->run())
		{
			$result['message'] 	= validation_errors();
		}
		else $result['type'] 	= 'success';
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_form_validation');

//kiểm tra dữ liệu
function ajax_form_submit_category($ci, $model) {

	$result['message'] 	= 'Thêm dữ liệu thất bại';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$post = $ci->input->post();

		$model = get_model($post['module']);

		$ci->data['module'] = $post['module'];

		$ci->form_gets_field(array('class' => $post['module']));

		$result = $ci->form_action(array(), array(), true);

		if($result['type'] == 'success') {

			$action_path = $post['module'];

			$args = [];

			$args['params'] = array( 'orderby' => 'order, created desc');

			$args['tree'] = array( 'parent_id' => 0 );

			if($post['module'] == 'post_categories') {

				$args['where'] = array( 'cate_type' => $post['cate_type'] );

				$function_get 	= 'gets_post_category';

				$function_count = 'count_post_category';

				if(class_exists('skd_cate_'.$post['cate_type'].'_list_table')) {
					$class_table = 'skd_cate_'.$post['cate_type'].'_list_table';
				} else $class_table = 'skd_category_list_table';

				$action_path = 'post/post_categories';
			}

			if($post['module'] == 'products_categories') {
				
				$function_get = 'wcmc_gets_category';

				$function_count = 'wcmc_count_category';

				$class_table = 'skd_product_category_list_table';

				$action_path = 'products/products_categories';
			}

			$function_get 	 = apply_filters('submit_category_function_get', $function_get, $post['module']);

			$function_count  = apply_filters('submit_category_function_count', $function_count, $post['module']);

			$class_table     = apply_filters('submit_category_class_table', $class_table, $post['module']);

			$args            = apply_filters('submit_category_args', $args, $post);

			$ci->data['ajax']= apply_filters('submit_category_action_path', $action_path, $post['module']);

			if(!isset($args['where'])) $args['where'] = [];

			if(!isset($args['params'])) $args['params'] = [];
			
			if(function_exists($function_get))
				$objects        	= $function_get( $args );
			else
				$objects          	= $model->gets_where( $args['where'], $args['params'] );

			if(function_exists($function_count))
				$total          = $function_count( $args );
			else
				$total          = $model->count_where( $args['where'] );

			$model = get_model($post['module']);

			/* tạo table */
			$args = array(
				'items' => $objects,
				'table' => $model->gettable(),
				'model' => $model,
				'module'=> $post['module'],
			);

			if($post['module'] == 'products_categories') {

				$args['_column_headers'] = $ci->col['product_category'];
			}
			
			$table_list = new $class_table($args);

			ob_start();

			$table_list->display();

			$result['item'] = ob_get_contents();

			ob_end_clean();

			//Get danh mục
			ob_start();

			$parent_id = [];

			if($post['module'] == 'post_categories') {

				$parent_id = gets_post_category( array('mutilevel' => $post['cate_type'] ) );
			}

			if($post['module'] == 'products_categories') {

				$parent_id = wcmc_gets_category( array('mutilevel' => 'option') );
			}

			$parent_id = apply_filters('submit_category_parent_id', $parent_id, $post['module']);

			$parent_id[0] = 'Chọn danh mục cha';

			foreach ($parent_id as $key => $value) {
				?>
				<option value="<?php echo $key;?>"><?php echo $value;?></option>
				<?php
			}

			$result['parent_id'] = ob_get_contents();

			ob_end_clean();
		}
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_form_submit_category');

/**
 * [ajax_up_boolean upload dữ liệu dạng true false ở table]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_up_boolean($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu thành công';

	$result['type'] 	= 'error';

	if($ci->input->post('id')) {

		$id 	= (int)$ci->input->post('id');

		$table 	= removeHtmlTags($ci->input->post('table'));

		$row 	= removeHtmlTags($ci->input->post('row'));

		if(is_numeric( $id )) {

			if($table == 'post_categories') $table = 'categories';

			$model->settable($table);

			$object = $model->get_where(array('id' => $id));

			if( have_posts($object) && isset( $object->$row ) ) {

				if( $object->$row == 0 ) $up[$row] = 1; else $up[$row] = 0;

				if( $model->update_where( $up, array('id' => $id)) ) {

					do_action( 'up_boolean_success', $table, $id  );

					$result['message'] 	= 'Cập nhật dữ liệu thành công';
					
					$result['type'] 	= 'success';
				}
			}

		}
		
	}

	echo json_encode($result);
}
register_ajax_admin('ajax_up_boolean');

/**
 * [ajax_up_boolean upload dữ liệu dạng ởtable]
 * @param  [type] $ci    [description]
 * @param  [type] $model [description]
 * @return [type]        [description]
 */
function ajax_up_table($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$id 	= (int)$ci->input->post('pk');

		$table 	= $ci->input->post('table');

		$row 	= $ci->input->post('name');

		$value 	= $ci->input->post('value');

		$type 	= $ci->input->post('type');

		if(is_numeric( $id )) {

			$model->settable($table);

			$object = $model->get_where(array('id' => $id));

			if( have_posts($object) && isset( $object->$row ) ) {

				$up[$row] = $value;

				if( $type == 'number' ) $up[$row] = (int)$value;

				if( $type == 'text' ) 	$up[$row] = removeHtmlTags($value);

				if( $row == 'price' || $row == 'price_sale')  { 
					$up[$row] = str_replace(',', '', $up[$row]);
					$up[$row] = str_replace('.', '', $up[$row]);
				}

				if($model->update_where($up, array('id' => $id))) {

					do_action( 'up_table_success', $table, $id  );

					$result['message'] 	= 'Cập nhật dữ liệu thành công';
					
					$result['type'] 	= 'success';
				}
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_up_table');


function ajax_input_popover_search($ci, $model) {

	$result['message'] 	= 'Cập nhật dữ liệu không thành công';

	$result['type'] 	= 'error';

	if($ci->input->post()) {

		$keyword 		= removeHtmlTags($ci->input->post('keyword'));

		$data_select 	= $ci->input->post('select');

		$module 		= removeHtmlTags($ci->input->post('module'));

		$object 		= [];

		if($module == 'post_categories') {

			$key_type 		= removeHtmlTags($ci->input->post('key_type'));

			$object = gets_post_category([
				'where'		 => [ 'cate_type' => $key_type ],
				'where_like' => [ 'name' => array($keyword), ]
			]);
		}
		else if($module == 'post') {

			$key_type 		= removeHtmlTags($ci->input->post('key_type'));
			
			$object = gets_post([
				'where'		 => [ 'post_type' => $key_type ],
				'where_like' => [ 'title' => array($keyword), ]
			]);
		}
		else if($module == 'page') {

			$object = gets_page([
				'where_like' => [ 'name' => array($keyword), ]
			]);
		}
		else {

			$object = apply_filters('input_popover_'.$module.'_search', $object, $keyword);
		}

		$result['type'] 	= 'success';

		$result['data'] = '<li class=""><a>Không có dữ liệu.</a></li>';

		if( have_posts($object) ) {

			$result['data'] = '';

			foreach ($object as $key => $value) {

				$active = (!empty($data_select[$value->id]))?'option--is-active':'';

				$name   = '';

				if(isset($value->name)) $name = $value->name;

				if(isset($value->title)) $name = $value->title;

				$str = '<li class="option option-'.$value->id.' '.$active.'" data-key="'.$value->id.'"><a href=""> <span class="icon"><i class="fal fa-check"></i></span> <span class="label-option">'.$name.'</span> </a> </li>';

				$result['data'] .= apply_filters('input_popover_'.$module.'_search_template', $str, $value, $active );
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_input_popover_search');

/**
=================================================
XÓA DỮ LIỆU
=================================================
*/
function ajax_trash($ci, $model) {

	$result['message'] 	= 'Xóa dữ liệu không thành công';

	$result['type'] 	= 'error';

	if($ci->input->post('data')) {

		$data 	= $ci->input->post('data');

		$table 	= $ci->input->post('table');

		$model->settable($table);

		if(is_numeric($data)) {

			$count = $model->count_where(array('id' => $data, 'trash' => 0));

			if($count != 0) {

				if($model->update_where(array('trash' => 1), array('id' => $data))) {

					$result['message'] 	= 'Xóa dữ liệu thành công';

					$result['type'] 	= 'success';
				}
			}
			else {
				$result['message'] 	= 'Dữ liệu cần xóa không tồn tại!';
			}
		}
		else if(have_posts($data)) {

			$in['field'] = 'id';

			$in['data']  = $data;
			
			if($model->update_where_in($in, array('trash' => 1))) {

				$result['message'] 	= 'Xóa dữ liệu thành công';

				$result['type'] 	= 'success';
				
				$result['data'] 	= $data;
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_trash');

function ajax_delete($ci, $model) {

	$result['message'] 	= 'Xóa dữ liệu không thành công';

	$result['type'] 	= 'error';

	if($ci->input->post('data')) {

		$id 	= $ci->input->post('data');

		$table 	= $ci->input->post('table');

		$res = [];

		do_action('ajax_delete_after_success', $table, $id);

		if(is_numeric($id)) {

			if($table == 'page') $res = delete_page($id);

			if($table == 'post') $res = delete_post($id);

			if($table == 'post_categories') $res = delete_category($id);

			$res = apply_filters('delete_object_'.$table, $res, $table, $id);

			do_action('ajax_delete_object_before_success', $table, $id, $res);

		}
		else if(have_posts($id)) {

			if($table == 'page') $res = delete_list_page($id);

			if($table == 'post') $res = delete_list_post($id);

			if($table == 'post_categories') $res = delete_list_category($id);

			$res = apply_filters('delete_object_'.$table, $res, $table, $id);

			do_action('ajax_delete_list_before_success', $table, $id, $res);
		}

		if($res != false) {

			$result['data'] 	= $res;

			$result['message'] 	= 'Xóa dữ liệu thành công!';

			$result['type'] 	= 'success';

			do_action('ajax_delete_before_success', $table, $id, $res);
		}
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_delete');

function ajax_undo($ci, $model) {

	$result['message'] 	= 'Phục hồi dữ liệu không thành công';

	$result['type'] 	= 'error';

	if($ci->input->post('data')) {

		$data 	= $ci->input->post('data');

		$table 	= $ci->input->post('table');

		$model->settable($table);

		if(have_posts($data)) {

			$in['field'] = 'id';

			$in['data']  = $data;

			if($model->update_where_in($in, array('trash' => 0))) {

				$result['message'] 	= 'Phục hồi dữ liệu thành công';

				$result['type'] 	= 'success';

				$result['data'] 	= $data;
			}
		}
	}
	echo json_encode($result);
}
register_ajax_admin('ajax_undo');
/**
=================================================
PLUGIN
=================================================
*/
if(!function_exists('ajax_plugin_wg_download')) {
	/**
	 * [ajax_plugin_wg_download download widget]
	 */
	function ajax_plugin_wg_download($ci, $model) {

		$result['message'] 	= 'Download widget không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post('name')) {

			$id 	= removeHtmlTags($ci->input->post('name'));

			$wg 	= $ci->service_api->get_widget($id);

			if($wg->status == 'success') {

				$wg = $wg->data;

				$url = $wg->file;

				$dir = 'views/'.$ci->data['template']->name.'/widget/';

				$temp_filename = basename( $url );

				$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

				$temp_filename  = $dir . $temp_filename . '.zip';
				
				$headers = getHeaders($url);

				if ($headers['http_code'] === 200) {

					if (download($url, $temp_filename)) {

						$result['message'] 	= 'Download widget thành công';

						$result['type'] 	= 'success';
					}
				}
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_plugin_wg_download');
}

if(!function_exists('ajax_plugin_wg_install')) {
	/**
	 * [ajax_plugin_wg_install giải nén file zip widget]
	 */
	function ajax_plugin_wg_install($ci, $model) {

		$result['message'] 	= 'Cài đặt widget không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post('name')) {

			$id 	= removeHtmlTags($ci->input->post('name'));

			$wg 	= $ci->service_api->get_widget($id);

			if($wg->status == 'success') {

				$wg = $wg->data;

				$url = $wg->file;

				$dir 	= 'views/'.$ci->data['template']->name.'/widget/';

				$temp_filename = basename( $url );

				$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

				$temp_filename  = $dir . $temp_filename . '.zip';

				if( file_exists($temp_filename) ) {

					$zip = new ZipArchive;

					if( $zip->open($temp_filename) === TRUE ) {
							
						$zip->extractTo($dir);

						$zip->close();

						unlink( $temp_filename );

						$result['message'] 	= 'Cài đặt widget thành công';

						$result['type'] 	= 'success';
					}

				}
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_plugin_wg_install');
}

if(!function_exists('ajax_plugin_download')) {
	/**
	 * [ajax_plugin_wg_download download widget]
	 */
	function ajax_plugin_download($ci, $model) {

		$result['message'] 	= 'Download plugin không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post('name')) {

			$id 	= removeHtmlTags($ci->input->post('name'));

			$pl 	= $ci->service_api->get_plugin($id);

			if($pl->status == 'success') {

				$pl = $pl->data;

				$url = $pl->file;

				$dir = $ci->plugin->dir.'/';

				$temp_filename = basename( $url );

				$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

				$temp_filename  = $dir . $temp_filename . '.zip';
				
				$headers = getHeaders($url);

				if ($headers['http_code'] === 200) {

					if (download($url, $temp_filename)) {

						$result['message'] 	= 'Download plugin thành công';

						$result['type'] 	= 'success';
					}
				}
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_plugin_download');
}

if(!function_exists('ajax_plugin_install')) {
	/**
	 * [ajax_plugin_wg_install giải nén file zip widget]
	 */
	function ajax_plugin_install($ci, $model) {

		$result['message'] 	= 'Cài đặt plugin không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post('name')) {

			$id 	= removeHtmlTags($ci->input->post('name'));

			$pl 	= $ci->service_api->get_plugin($id);

			if($pl->status == 'success') {

				$pl = $pl->data;

				$dir 	= $ci->plugin->dir.'/';

				$url = $pl->file;

				$temp_filename = basename( $url );

				$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

				$temp_filename  = $dir . $temp_filename . '.zip';

				if( file_exists($temp_filename) ) {

					$zip = new ZipArchive;

					if( $zip->open($temp_filename) === TRUE ) {
							
						$zip->extractTo($dir);

						$zip->close();

						unlink( $temp_filename );

						$result['message'] 	= 'Cài đặt plugin thành công';

						$result['type'] 	= 'success';
					}

				}
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('ajax_plugin_install');
}

/**
=================================================
SERVICE
=================================================
*/

function ajax_service_license_save($ci, $model)
{
	$result['status'] = 'error';

	$result['message'] = 'Cập nhật không thành công!';

	if($ci->input->post()) {

		$api_user 		= removeHtmlTags($ci->input->post('api_user'));

		$api_secret_key = removeHtmlTags($ci->input->post('api_secret_key'));

		update_option('api_user', 		$api_user);

		update_option('api_secret_key', $api_secret_key);

 		$result['status'] 	= 'success';

		$result['message'] 	= 'Cập nhật dữ liệu thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_service_license_save');

/**
=================================================
SYSTEM
=================================================
*/
function ajax_system_save( $ci, $model ) {

	$result['status']  = 'error';

	$result['message'] = __('Lưu dữ liệu không thành công');
	
	if( $ci->input->post() ) {

		$data = $ci->input->post();

		$tab = removeHtmlTags($data['system_tab_key']);

		$tab = str_replace('-', '_', $tab);

		$result['status']  = 'success';

		$result['message'] = __('Lưu dữ liệu thành công.');

		$result =  apply_filters('system_'.$tab.'_save', $result, $data);
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_system_save');

function ajax_email_smtp_test( $ci, $model ) {

	$result['status']  = 'error';

	$result['message'] = __('Lưu dữ liệu không thành công');
	
	if( $ci->input->post() ) {

		$data = $ci->input->post();

		$ci->load->library('skd_mail');

		$config = array(
			//gửi email từ
			'from_email' => (!empty($data['smtp-test-from']))? removeHtmlTags($data['smtp-test-from']) : get_option('contact_mail'),
			//tên người gửi
			'fullname'   => (!empty($data['smtp-test-name']))? removeHtmlTags($data['smtp-test-name']) : 'vitechcenter - vitechcenter',
			//gửi đến mail
			'to_email'   => (!empty($data['smtp-test-to']))? removeHtmlTags($data['smtp-test-to']) : get_option('contact_mail'),
			//tiêu đề mail
			'subject'    => (!empty($data['smtp-test-subject']))? removeHtmlTags($data['smtp-test-subject']) : 'Kiểm tra tính năng gửi email',
			//nội dung mail
			'content'    => (!empty($data['smtp-test-']))? removeHtmlTags($data['smtp-test-']) : 'Đây là nội dung kiểm tra.',
		);

		$mail = new skd_mail($config);

		$mail->set_user( get_option('smtp-user') );
		$mail->set_pass( get_option('smtp-pass') );
		$mail->set_host( get_option('smtp-server') );
		$mail->set_port( get_option('smtp-port') );

		$result['status']  = 'success';

		$result['message'] = __('Lưu dữ liệu thành công.');

		$result['data'] = $mail->send();
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_email_smtp_test');




