<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model($this->data['module'].'_model');
	}
	/**
	 * [index hiển thị trang chủ]
	 * @return [type] [description]
	 */
	public function index() {
		$this->template->render();
	}

	public function system() {

		$this->data['system_tabs'] = $this->system_tabs();

		$this->template->render();
	}

	public function system_tabs() {

		$tab = [];

		if(current_user_can('edit_cms_status')) $tab['cms-status'] = ['label' => 'Trạng thái hệ thống', 	'callback' => 'skd_system_cms_status','icon' => '<i class="fal fa-shield-check"></i>'];

		$tab['cms-contact'] = ['label' => 'Thông tin liên hệ', 	'callback' => 'skd_system_cms_contact','icon' => '<i class="fal fa-address-book"></i>'];

		if(current_user_can('edit_smtp')) $tab['cms-smtp'] = ['label' => 'Email SMTP', 'callback' => 'skd_system_cms_smtp', 'icon' => '<i class="fal fa-mail-bulk"></i>'];
		
		return apply_filters('skd_system_tab', $tab);
		
	}

	public function about() {

		$this->template->render();
	}

	public function ajax() {

		if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
		{
		    $action = ($this->input->get('action') != null)?$this->input->get('action'):$this->input->post('action');

			if(function_exists($action) && isset_ajax_all($action)) {

				$model = $this->home_model;

				if(isset_ajax_login($action) && $this->skd_security->is_login()) {
					$action($this, $model);
					return true;
				}

				if( isset_ajax_admin($action) && current_user_can('loggin_admin') ) {
					$action($this, $model);
					return true;
				}

				if(isset_ajax_admin_nov($action) && is_admin()) {
					$action($this, $model);
					return true;
				}

				if(isset_ajax($action)) {

					$this->config->set_item('language', $_SESSION['language']);

					$this->lang->load('general');

					$action($this, $model);

					return true;
				}

				echo "ajax not found!";

			}

		} else {
		    redirect( admin_url('') );
		}

		return false;
	}

	public function update_core() {

		$this->skd_security->auth_backend();

		$this->load->library('service_api');

		$version = $this->service_api->cms_version();

		if ( version_compare( $version, cms_info('version') ) === 1 ){

			if( !isset( $_SESSION['_submit_update_core'] ) ) $_SESSION['_submit_update_core'] = md5(time());

			if( $this->input->post('_submit_update_core') != null && $this->input->post('_submit_update_core') == $_SESSION['_submit_update_core'] ) {

				$action = $this->input->get('action');

				if( $action == 'do-upgrade-core' ) {

					$check = $this->download_upgrade_package( $version );

					if( $check == true ) $check = $this->extract_package( $version );

					if( $check == true ) {

						$list_file_upgrade = $this->get_all_files( FCPATH.'application/upgrade/'.$version, $version );

						if( have_posts( $list_file_upgrade )) $this->copy_files( $list_file_upgrade, $version);
					}
					
				}
			}
		} else {
			redirect( admin_url('home/about') );
		}

		$this->template->render();
	}

	public function download_upgrade_package( $version ) {

		if ( !is_dir( FCPATH.'application/upgrade' ) ) mkdir( FCPATH.'application/upgrade' );

		$this->template->set_message(notice('success','Downloading upgrade package'), array('name' => 'download'));

	    $url 			= SERVERAPI.'release/vitechcenter-'.$version.'-no-theme.zip';

	    $temp_filename 	= FCPATH.'application/upgrade/vitechcenter-'.$version.'-no-theme.zip';

	    $headers 		= getHeaders($url);

	    if ($headers['http_code'] === 200) {

			if (download($url, $temp_filename)) {

				return true;
		  	}
		}

		return false;
	}

	public function extract_package( $version ) {

		$this->template->set_message(notice('success','Extract package...'), array('name' => 'extract'));

		$temp_filename 	= FCPATH.'application/upgrade/vitechcenter-'.$version.'-no-theme.zip';

		$zip = new ZipArchive;

	    if ($zip->open($temp_filename) === TRUE) {

	        $zip->extractTo(FCPATH.'application/upgrade/'.$version);

	        $zip->close();

	        unlink( $temp_filename );

	        $this->template->set_message(notice('success','Extract package success'), array('name' => 'extract-success'));

	        return true;

	    } else {

	        $this->template->set_message(notice('error','Extract package failed'), array('name' => 'extract-failed'));

	        return false;
	    }
	}

	public function get_all_files( $dir, $version ){

	    $result = array();

	    $scan_dir = scandir($dir); 

	    foreach ($scan_dir as $key => $value) 
	    { 
	        if (!in_array($value,array(".","..")))
	        { 
	            if (is_dir($dir.'/'.$value)) 
	            { 
	                $sub_dir_files = $this->get_all_files($dir.'/'.$value, $version );

	                $result[] = $dir.'/'.$value;

	                $result = array_merge($result, $sub_dir_files);
	            }
	            else 
	            { 
	                $result[] = $dir.'/'.$value; 
	            } 
	        }
	    }

	    return $result; 
	}

	public function copy_files( $files, $version ) {

	    foreach( $files as $key => $file) {

	        $file = substr( $file, strlen( FCPATH.'application/upgrade/'.$version.'/'));

	        $path = FCPATH.'application/upgrade/'.$version.'/'.$file;

	        if ( is_dir( $path ) ){

	            if (!file_exists($file)){

	                mkdir($file);
	            }
	        }
	        else {

	            copy( $path, $file);

	            unlink( $path );
	        }
	    }

	    $this->template->set_message(notice('success','Upgrade version success'), array('name' => 'success'));

	    $this->delete_directory( FCPATH.'application/upgrade' );
	}
	
	public function delete_directory( $dirname ) {

        if (is_dir($dirname)) $dir_handle = opendir($dirname);

     	if (!$dir_handle) return false;

    	while($file = readdir($dir_handle)) {

           if ($file != "." && $file != "..") {

                if (!is_dir($dirname."/".$file))
                    unlink($dirname."/".$file);
                else
                    $this->delete_directory($dirname.'/'.$file);
           }
     	}

    	closedir($dir_handle);

    	rmdir($dirname);

     	return true;
	}
}