<?php defined('BASEPATH') OR exit('No direct script access allowed');

class plugins extends MY_Controller {

	function __construct() {
		
		parent::__construct();

		$this->load->model($this->data['module'].'_model');
	}

	public function index() {

		$page = $this->input->get('page');
		
		if( !empty($page) ) { 
			$this->page($page); 
		} 
		else {

			$this->load->helper('directory');

			$path 		= FCPATH.$this->plugin->dir;

			$plugins 	= directory_map($path,true);

			$plugin 	= null;

			foreach ($plugins as $key => $name) {

				$version = null;

				if($this->plugin->is_plugin($name)) {

					$plugin[$key] = new plugin($name);

					// if( empty($_SESSION['plugin_version'][$name])) $_SESSION['plugin_version'][$name] = $this->service_api->plugin_version( $name );

					// $plugin[$key]->version_new = $this->service_api->plugin_version( $name );

					// if(isset($plugin[$key]->version_new->status) && $plugin[$key]->version_new->status == 'error') $plugin[$key]->version_new = 'error';
				}
			}

			$this->data['list_plugin'] = $plugin;

			$this->template->render();
		}
	}

	public function download()
	{
		$this->data['plugins'] = $this->service_api->gets_plugin();

		if(isset($this->data['plugins']->status) && $this->data['plugins']->status == 'error') {

			$this->template->set_message(notice('error', 'Không thể kết nối đến server'));

			$this->data['plugins'] = [];
		}
		else {

			if($this->data['plugins']->total == 0) {

				$this->template->set_message(notice('error', 'Không có plugin nào để sử dụng.'));
			}

			$this->data['plugins'] = $this->data['plugins']->data;
		}

		$this->template->render();
	}

	public function license()
	{
		if( $this->input->post() ) {

			$api_user 		= removeHtmlTags($this->input->post('api_user'));

			$api_secret_key = removeHtmlTags($this->input->post('api_secret_key'));

			update_option('api_user', 		$api_user);

			update_option('api_secret_key', $api_secret_key);

			$this->template->set_message(notice('success', 'Lưu dữ liệu thành công!'));
		}

		$this->data['api_user'] = get_option('api_user');
		$this->data['api_secret_key'] = get_option('api_secret_key');

		$this->template->render();
	}

	//active plugin
	public function active( $name = '') {

		//kiểm tra plugin có tồn tại không
		if($this->plugin->is_plugin($name)){
			//khởi tạo thông tin plugin
			$plugin = new plugin($name);

			//include file cần thiết
			$plugin->include_plugin();

			//kiểm tra đối tượng plugin
			if(class_exists($plugin->class)){
				/**
				@plugin chưa cài đặt
				*/
				if(!$plugin->is_setup()) {
					//khởi tạo plugin
					$current_plugin = new $plugin->class();
					//chạy hàm active của plugin
					if(method_exists($current_plugin, 'active')) {
						$current_plugin->active();
					}
					//active plugin
					$this->plugins['active'][$name] = true;

					$message = notice('success','plugin <b>'.$plugin->label.'</b> đã được kích hoạt !<br />');
				}
				/**
				@ plugin đã cài đặt
				*/
				else {
					/**
					@ plugin đang bật thì tắt và ngược lại
					*/
					if($plugin->is_active())  {
						$this->plugins['active'][$name] = false;
						$message = notice('success','plugin <b>'.$plugin->label.'</b> đã được tắt !<br />');
					}
					else {
						$this->plugins['active'][$name] = true;
						$message = notice('success','plugin <b>'.$plugin->label.'</b> đã được kích hoạt !<br />');
					}
				}

				update_option( 'plugin_active', serialize($this->plugins['active']) );
			}
			/**
			@ đối tượng plugin chưa được khởi tạo
			*/
			else {
				$message[] = notice('danger','plugin <b>'.$plugin->label.'</b> class chưa được khai báo !<br />');
			}

			$this->template->set_message($message,'flashdata');

			redirect( URL_ADMIN .'/plugins');
		}
	}
	//xóa plugin
	public function remove( $name = '')
	{
		$message = '';

		if($this->plugin->is_plugin($name)){
			//khởi tạo thông tin plugin
			$plugin = new plugin($name);

			//include file cần thiết
			$plugin->include_plugin();

			if($plugin->is_active())  {

				$message = notice('error','Plugin <b>'.$plugin->label.'</b> đang chạy không thể gở khỏi hệ thống !<br />');

			}
			else {

				unset($this->plugins['active'][$name]);

				//khởi tạo plugin
				$current_plugin = new $plugin->class();

				if(method_exists($current_plugin, 'uninstall')) {
					$current_plugin->uninstall();
				}

				if( !isset( $this->plugins['active'] )) $this->plugins['active'] = array();

				

				if( update_option( 'plugin_active', serialize($this->plugins['active']) ) ) {

					$this->load->helper("file");

					$path = $this->plugin->dir.'/'.$name;

					delete_files($path, true);

					rmdir($path);

					$message = notice('success','Plugin <b>'.$plugin->label.'</b> đã được gở khỏi hệ thống !<br />');
				}
			}
			
		} else {

			$message = notice('error','Plugin <b>'.$name.'</b> không tồn tại !');

		}

		$this->template->set_message($message,'flashdata');

		redirect(URL_ADMIN.'/plugins');
	}

	//upgrade
	public function upgrade( $name = '')
	{
		$plugin = get_plugins( $name );

		if( have_posts( $plugin ) ) {

			$version = $plugin['version'];

			$pl    = $this->service_api->get_plugin( $plugin['name'] );

			if($pl->status == 'success') {

				$pl = $pl->data;

				$check = $this->download_upgrade_package( $pl );

				if( $check == true ) $check = $this->extract_package( $pl );

				if( $check == true ) $this->template->set_message(notice('success','Cập nhật plugin thành công'), 'flasdata');
			}
		}

		redirect( URL_ADMIN.'/plugins' );
	}

	public function download_upgrade_package( $pl ) {

		$this->template->set_message(notice('success','Downloading upgrade package'), array('name' => 'download'));

	    $url = $pl->file;

		$dir = $this->plugin->dir.'/';

		$temp_filename = basename( $url );

		$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

		$temp_filename  = $dir . $temp_filename . '.zip';

	    $headers 		= getHeaders($url);

	    if ($headers['http_code'] === 200) {

			if (download($url, $temp_filename)) {

				return true;
		  	}
		}

		return false;
	}

	public function extract_package( $pl ) {

		$this->template->set_message(notice('success','Extract package...'), array('name' => 'extract'));

		$url = $pl->file;

		$dir = $this->plugin->dir.'/';

		$temp_filename = basename( $url );

		$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

		$temp_filename  = $dir . $temp_filename . '.zip';

		$zip = new ZipArchive;

	    if ($zip->open($temp_filename) === TRUE) {

	        $zip->extractTo( $dir );

	        $zip->close();

	        unlink( $temp_filename );

	        $this->template->set_message(notice('success','Extract package success'), array('name' => 'extract-success'));

	        return true;

	    } else {

	        $this->template->set_message(notice('error','Extract package failed'), array('name' => 'extract-failed'));

	        return false;
	    }
	}

	public function page( $page = '')
	{

		$this->data['page'] = $page;
		
		$callback_page = null;
		
		$current_page  = null;
		
		$admin_nav     = $this->admin_nav;

		$admin_nav_sub = $this->admin_nav_sub;

		if(isset($admin_nav[$page])) {

			$callback_page = $this->admin_nav[$page];

			$current_page = $this->admin_nav[$page];
		}

		if($callback_page ==  null) {

			foreach ( $admin_nav_sub as $key => $sub) {

				if(isset($sub[$page])) {

					$callback_page = $sub[$page];

					$current_page  = $key;

					break;
				}
			}
		}

		$this->data['callback_page'] = $callback_page;
		
		$this->data['group']         = $current_page;
		
		$this->data['active']        = $callback_page['key'];

		// if( function_exists($callback_page['callback']) ) {

		// 	echo call_user_func( $callback_page['callback'], $this, $this->plugins_model );

		// 	return true;
		// }

		if( have_posts($callback_page)) {

			$this->template->render('plugins-page');

		}
	}
}