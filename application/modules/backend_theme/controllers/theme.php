<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->skd_security->auth_backend();
	}

	public function index() {

		$this->load->helper('directory');

		$model = $this->data['module'].'_model';

		$theme_path = FCPATH.'views/';
		
		$theme = directory_map($theme_path,true);

		$template = null;

		unset($theme['backend']);

		foreach ($theme as $key => $value) {
			if(is_string($value) && $value != 'backend' && $this->template->isset_template($value)) {
				$id = ($value == $this->system->theme_current) ? 0 : $key;
				$template[$id] = new template($value);
			}
		}

		ksort($template);

		$this->data['list_template'] = $template;

		$this->template->render();
	}

	public function option() {

		if( current_user_can('edit_theme_options') ) {

			$this->template->render();
		}
		else $this->template->error('404');
	}

	public function editor() {
		$this->template->render();
	}

	public function editor_scan() {

		$dir = $this->data['template']->get_path();

		$response = scan($dir);

		header('Content-type: application/json');

		echo json_encode(array(
			"name" => $this->data['template']->name,
			"type" => "folder",
			"path" => $dir,
			"items" => $response
		));
	}
}

function scan($dir){

	$files = array();

	// Is there actually such a folder/file?

	if(file_exists($dir)){
	
		foreach(scandir($dir) as $f) {
		
			if(!$f || $f[0] == '.') {
				continue; // Ignore hidden files
			}

			if(is_dir($dir . '/' . $f)) {
				// echo $f.'<br/>';
				// The path is a folder

				$files[] = array(
					"name" => $f,
					"type" => "folder",
					"path" => $dir . '/' . $f,
					"items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
				);
			}
			
			else {

				// It is a file

				$files[] = array(
					"name" => $f,
					"type" => "file",
					"path" => $dir . '/' . $f,
					"size" => filesize($dir . '/' . $f) // Gets the size of this file
				);
			}
		}
	
	}

	return $files;
}