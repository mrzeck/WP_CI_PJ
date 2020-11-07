<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plugin {

	public $ci;

	public $name;

	public $label   	= '';

	public $class   	= '';

	public $description = '';

	public $version   	= '';

    public $author   	= '';

    public $screenshot  = '';

    public $active 		= 0;

    function __construct($name = NULL) {

		$this->ci 	= &get_instance();

		$this->dir 	= 'views/plugins';

		$this->name = $name;

		$this->info();

		if($this->is_active()) $this->active = 1;
	}

	public function is_plugin($name = null) {
		if($name == null) $name = $this->name;
		$dir 		= FCPATH.$this->dir;
		return @is_dir($dir.'/'.$name) && file_exists($dir.'/'.$name.'/'.$name.'.php') ? true : false;
	}

	/**
	@ kiểm tra plugin name đã cài đặt hay chưa
	*/
	public function is_setup($name = '') {
		if($name == null) $name = $this->name;
		if(!isset($this->ci->plugins['active']) || !have_posts($this->ci->plugins['active'])) return false;
		$key = array_keys($this->ci->plugins['active']);
		if(in_array($name, $key) !== false) return true;
		return false;
	}

	/**
	@ kiểm tra plugin name đã active hay chưa
	*/
	public function is_active($name = '') {
		if($name == null) $name = $this->name;
		if($this->is_setup($name)) {
			if($this->ci->plugins['active'][$name] == true) return true;
		}
		return false;
	}

	/**
	@ lấy thông tin plugin
	**/
	public function info($name = '') {
		if($name == null) $name = $this->name;
		if($this->is_plugin($name)) {
			$dir 			= FCPATH.$this->dir.'/'.$name;
			$plugin_info 	= file($dir.'/'.$name.'.php');
			$count = 0;
			foreach ($plugin_info as $k => $val) {
				$val 		= trim($val);
				$pos_start  = stripos($val,':')+1;
				$pos_end    = strlen($val);
				//plugin name
				if(strpos($val,'Plugin Name',0) 	!== false ||
					strpos($val,'plugin Name',0) 	!== false ||
					strpos($val,'Plugin name',0) 	!== false ||
					strpos($val,'plugin name',0) 	!== false) {
					$this->label 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin class
				if(strpos($val,'Plugin Class',0) 	!== false ||
					strpos($val,'plugin Class',0) 	!== false ||
					strpos($val,'Plugin class',0) 	!== false ||
					strpos($val,'plugin class',0) 	!== false) {
					$this->class = trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin description
				if(strpos($val,'Description',0) 	!== false ||
					strpos($val,'description',0) 	!== false) {
					$this->description 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin version
				if(strpos($val,'Version',0) 	!== false ||
				strpos($val,'version',0) 	!== false) {
					$this->version 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin author
				if(strpos($val,'Author',0) 	!== false ||
				strpos($val,'author',0) 	!== false) {
					$this->author 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}

				if($count == 5) :
					if(file_exists($dir.'/screenshot.png')) {
						$this->screenshot = $this->dir.'/'.$name.'/screenshot.png';
					}
					break;
				endif;
			}
		}
	}

	public function get_path($name = '') {
		return $this->dir.'/'.$name.'/';
	}

	public function include_plugin($name = null) {
		if($name == null) $name = $this->name;
		require_once($this->dir.'/'.$name.'/'.$name.'.php');
	}

	public function load_file() {
		if(have_posts($this->ci->plugins['active'])) {
			foreach ($this->ci->plugins['active'] as $key => $active) {
				if($this->is_plugin($key) && $active == true) $this->include_plugin($key);
				else unset($this->ci->plugins['active'][$key]);
			}
		}
		
	}
}