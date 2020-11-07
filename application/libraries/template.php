<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class template {
    //template info
    public $name   		= '';
    public $label   	= '';
    public $description = '';
    public $version   	= '';
    public $author   	= '';
    public $screenshot  = '';

    //laytout & views
    private $layout = '';
    private $view     = '';

    //class & function
    public  $class   = '';
    public  $method   = '';
    public  $support   = array();

    //đường dẫn thư mục chứa template
    private $assets   = VIEWPATH;

    //các file
    private $css      		= array();
    private $min_css      	= 'styles.min.css';
	private $js       		= array();
	private $min_js       	= 'scripts.min.js';
	private $minify_path   	= array();

	//message
	private $message = array();

    function __construct($name = 'backend'){

		$ci =& get_instance();

		$this->name = $name;

		$this->info();

		$this->class   = $ci->router->fetch_class();

		$this->method  = $ci->router->fetch_method();
	}

    //kiểm tra template có tồn tại hay không
    public function isset_template($name = null) {
        if($name == null) $name = $this->name;
		return @is_dir(VIEWPATH.$name) && file_exists(VIEWPATH.$name.'/config.php') ? true : false;
	}
	public function get_path() {
		return VIEWPATH.$this->name;
	}
    //set template
    public function set_template($name = null) {
		//kiểm tra thư mục template có tồn tại không
		if($this->isset_template($name)) {
			$this->name     = $name;
			$this->assets 	.= $this->name.'/assets';
		}
		else {
			echo "Template don't exists!";
			die;
		}
	}

    public function get_name() {
		return $this->name;
	}

	public function info($name = null) {
		if($name == null) $name = $this->name;
		if($this->isset_template($name)) {
			$string = file(VIEWPATH.$name.'/config.php');
			$count 	= 0;
			foreach ($string as $k => $val) {
				$val 		= trim($val);
				$pos_start  = stripos($val,':')+1;
				$pos_end    = strlen($val);
				//Template name
				if(strpos($val,'Template Name',0) 	!== false || 
				strpos($val,'template name',0) 	!== false || 
				strpos($val,'Template name',0) 	!== false ||
				strpos($val,'template Name',0) 	!== false) {
					$this->label 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}

				//Template description
				if(strpos($val,'Description',0) 	!== false || 
				strpos($val,'description',0) 	!== false) {
					$this->description 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//Template version
				if(strpos($val,'Version',0) 	!== false || 
				strpos($val,'version',0) 	!== false) {
					$this->version 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}

				//Template author
				if(strpos($val,'Author',0) 	!== false || 
				strpos($val,'author',0) 	!== false) {
					$this->author 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}

				if($count == 4) :
					if(file_exists(VIEWPATH.$name.'/assets/screenshot.png')) {
						$this->screenshot = 'views/'.$name.'/assets/screenshot.png';
					}
					break; 
				endif; 
			}
		}
	}

    /**
	lAYOUT
	*/
    public function set_layout($layout = null) {

		$class   = $this->class;

		$method  = $this->method;

		$ci =& get_instance();

		if($layout ==  null) {

			/** FRONTEND */
			if($this->name != 'backend') {

				/* category page */
		    	if(!$this->is_home() && $this->method == 'index' &&  isset($ci->data['category']) ) {

		    		$category = $ci->data['category'];
		    		
		    		if( !empty($category->theme_layout) ) {

		    			$this->layout = $category->theme_layout;
		    		}
		    		else {

		    			if( !empty($category->slug) ) $this->layout = 'template-'.$this->class.'-'.$category->slug;

		    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($category->cate_type)) $this->layout = 'template-'.$this->class.'-'.$category->cate_type;
		    		}
			    	
		    	}

		    	/* single page */
		    	if(!$this->is_home() && $this->method == 'detail') {

		    		if( isset($ci->data['object']) && have_posts($ci->data['object']) ) {

		    			$object = $ci->data['object'];

		    			if( !empty($object->theme_layout) ) {

			    			$this->layout = $object->theme_layout;
			    		}
			    		else {

			    			$this->layout = 'template-'.$this->class.'-'.$object->slug;

			    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($object->post_type)) $this->layout = 'template-'.$this->class.'-'.$object->post_type;
			    		}


			    		if( !file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($ci->data['category']->theme_layout) ) {

			    			$category = $ci->data['category'];

			    			if( !empty($category->theme_layout) ) {

				    			$this->layout = $category->theme_layout;
				    		}
				    		else {

				    			$this->layout = 'template-'.$this->class.'-'.$category->slug;

				    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($category->cate_type)) $this->layout = 'template-'.$this->class.'-'.$category->cate_type;
				    		}

			    		}
		    		}		    		
		    	}
		    }
		}
		//nếu layout tồn tại thì set layout
		else if(file_exists(VIEWPATH.$this->name.'/'.$layout.'.php')) {
			$this->layout = $layout;
		}

        //nếu không có layout thì lấy layout theo layout config
		if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php') && $this->name != 'backend' && isset($ci->data['template']->default[$class][$method]['layout'])) {
			$this->layout  = $ci->data['template']->default[$class][$method]['layout'];
		}
		//sử dụng layout mặc định nếu không lấy được layout
		if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php')) $this->layout = 'template-home';

		if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php')) {
			echo notice('error','Layout <b>'.$this->layout.'</b> không tồn tại vui lòng kiểm tra lại template <b>'.$this->name.'</b>',true);
			die;
		}
	}

    public function get_layout() {
		return $this->layout;
	}
    /**
	VIEWS
	*/
    public function set_view($view = null) {

		$class   = $this->class;

		$method  = $this->method;

		$default = (isset($ci->data['template']->default))?$ci->data['template']->default:'';

		$ci =& get_instance();

		if($view ==  null) {
			/** FRONTEND */
			if($this->name != 'backend') {

				/* category page */
		    	if(!$this->is_home() && $this->method == 'index' && isset($ci->data['category']) ) {
		    		
		    		$category = $ci->data['category'];

		    		if( !empty($category->theme_view) ) {

		    			$this->view = $category->theme_view;
		    		}
		    		else {

		    			if( !empty($category->slug) ) $this->view = $this->class.'-'.$category->slug;

		    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($category->cate_type)) $this->view = $this->class.'-'.$category->cate_type;
		    		}
		    	}

		    	/* single page */
		    	if(!$this->is_home() && $this->method == 'detail') {

		    		if( isset($ci->data['object']) && have_posts($ci->data['object']) ) {

		    			$object = $ci->data['object'];

		    			if( !empty($object->theme_view) ) {

			    			$this->view = $object->theme_view;
			    		}
			    		else {

			    			$this->view = $this->class.'-'.$object->slug;

			    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($object->post_type)) $this->view = $this->class.'-'.$object->post_type;
			    		}

			    		if( !file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($ci->data['category']->theme_view) ) {

			    			$category = $ci->data['category'];

			    			if( !empty($category->theme_view) ) {

				    			$this->view = $category->theme_view;
				    		}
				    		else {

				    			$this->view = $this->class.'-'.$category->slug;

				    			if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($category->cate_type)) $this->view = $this->class.'-'.$category->cate_type;
				    		}

			    		}
		    		}
		    		else {
		    			$this->view = '404-error';
		    		}	    		
		    	}

		    	if( $this->class == 'user' ) {
		    		$this->view = 'user-'.$this->method;
		    	}
		    }
		}
		//nếu view tồn tại thì set view
		elseif(file_exists(VIEWPATH.$this->name.'/'.$view.'.php')) {
			$this->view = $view;
		}
		
		if(!file_exists($view.'.php')) {
			//nếu không có layout thì lấy layout theo view config
			if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php') && $this->name != 'backend' && isset($default[$class][$method]['view']) && !empty($default[$class][$method]['view'])) {
				$this->view = $default[$class][$method]['view'];
			}

			if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php') && file_exists(VIEWPATH.$this->name.'/'.$class.'-'.$method.'.php')) {
				$this->view = $class.'-'.$method;
			}

			//sử dụng view mặc định nếu không lấy được view
			if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php')) $this->view = 'home-index';

			if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php')) {
				echo notice('error','View <b>'.$this->view.'</b> không tồn tại vui lòng kiểm tra lại template <b>'.$this->name.'</b>',true);
				die;
			}
		}
		else { $this->view = $view; }
	}
	public function get_view($param = TRUE) {

		return ($param == TRUE)?$this->name.'/'.$this->view:$this->view;
	}
    /**
	RENDER
	*/
    public function render($view = null, $layout = null) {

    	$ci =& get_instance();

		$this->set_layout($layout);

		$this->set_view($view);

		do_action( 'template_redirect' );

		$ci->load->view($this->name.'/'.$this->layout, $ci->data);

		if(DEBUG == TRUE) $ci->output->enable_profiler(TRUE);
	}

    public function render_view($type = false)
	{
		$ci =& get_instance();

		if(@file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php'))
		{
			$ci->load->view($this->name.'/'.$this->view, $ci->data, $type);
		}
		else
		{
			if($this->view != null) {
				$ci->load->view($this->view, $ci->data, $type);
			}
		}
	}

	public function error($type = '404')
	{
		$this->set_layout('template-full-width');
		$this->render($type.'-error');
	}

    //xuất INCLUDE ra views
	public function render_include($views_path = null, $data = NULL, $type = false)
	{
		$ci =& get_instance();
		if($type == true) return $ci->load->view($this->name.'/include/'.$views_path, (have_posts($data))?$data:$ci->data, $type);
		$ci->load->view($this->name.'/include/'.$views_path, (have_posts($data))?$data:$ci->data, $type);
	}

	public function render_file($views_path = null, $args = NULL, $type = false)
	{
		$ci =& get_instance();
		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path = VIEWPATH.$this->name.'/'.$views_path.'.php';

		if(file_exists($path)) {
			include $path;
		}
		else {
			echo 'File not found!';
			die;
		}
	}

    //check page
    public function is_page($page = 'home_index')
	{
		if($page == $this->class.'_'.$this->method) return true;
		return false;
	}

	public function is_home()
	{
		if('home_index' == $this->class.'_'.$this->method) return true;
		return false;
	}

	public function get_page()
	{
		return $this->class.'_'.$this->method;
	}

    //style - script
	public function get_assets() {
		return base_url().$this->assets.'/';
	}

    public function reset_style() {

		$this->css = array();
	}

	public function reset_script() {

		$this->js = array();
	}

    public function set_style($css = '', $path = '', $minify = false) {
		
		if(is_string($css) && !empty($css)) {
			//kiểm tra path đã được add hay chưa
			if(in_array($path, $this->css) === false) {

				$ext  = explode("/", $path);
				
				$ext  = array_pop($ext);
				
                if(!isset($this->css[$ext])) {

					$this->css[$ext] = $path;

					if(have_posts($minify)) {
						
						$this->minify_path[$path] = $minify;
					}
				}
            }
		}
	}

	public function set_script($js = '', $path = '', $minify = false) {

		if(in_array($path, $this->js) === false) {

			$ext  = explode("/", $path);
			
			$ext  = array_pop($ext);
			
            if(!isset($this->js[$ext])) {

				$this->js[$ext] = $path;

				if(have_posts($minify)) {
					
					$this->minify_path[$path] = $minify;
				}
			}
        }
	}

    public function get_css() {

		if(have_posts($this->css)) {

			$this->css = array_unique($this->css);

			if(is_admin() || get_option('cms_minify_css', 0) == 0) {

				foreach ($this->css as $key => $val) {
					$str = '<link rel="stylesheet';
					$ext = pathinfo($val, PATHINFO_EXTENSION);
					if($ext != 'css' && !empty($ext) && !is_numeric($ext)) $str .= '/'.$ext;
					$str .= '" href="'.$val.'">';
					echo $str;
				}
			}
			else {
				foreach ($this->css as $key => $val) {

					if($this->check_minify($val) == false) {

						$str = '<link rel="stylesheet';

						$ext = pathinfo($val, PATHINFO_EXTENSION);
						
						if($ext != 'css' && !empty($ext) && !is_numeric($ext)) $str .= '/'.$ext;

						$str .= '" href="'.$val.'">';

						echo $str;

						unset($this->css[$key]);
					}
				}

				if(have_posts($this->css)) {
					echo '<style type="text/css">';
					echo $this->minify_css($this->css);
					echo '</style>';
				}
			}
		}
	}

    public function get_js() {

		if(have_posts($this->js)) {

			$this->js = array_unique($this->js);

			if(is_admin() || get_option('cms_minify_js', 0) == 0) {
				foreach ($this->js as $key => $val) {
					echo '<script type="text/javascript" src="'.$val.'" charset="utf-8"></script>';
				}
			}
			else {
				foreach ($this->js as $key => $val) {
					if($this->check_minify($val) == false) {
						echo '<script defer type="text/javascript" src="'.$val.'" charset="utf-8"></script>';
						unset($this->js[$key]);
					}
				}
				if(have_posts($this->js)) {
					echo '<script type="text/javascript" defer src="'.$this->minify_js($this->js).'" charset="utf-8"></script>';
				}
			}
		}
	}

	public function check_minify($path) {

		$my_theme_file = true;

		if(isset($this->minify_path[$path]) && have_posts($this->minify_path[$path])) {
			return true;
		}

		if(strpos($path, 'views/'.$this->name) !== false ) {

			return true;
		}

		if(strpos($path, 'views/'.$this->name.'/assets') === false ) {

			$my_theme_file = false;
		}

		return $my_theme_file;
	}

	public function minify_css($files = []) {

		if(!have_posts($files)) $files = $this->css;

		$path = FCPATH.VIEWPATH.$this->name.'/assets/';

		$path_min_css = $path.'css/'.$this->min_css;

		$createMinFile = false;

		if(!file_exists($path_min_css)) $createMinFile = true;

		$files_temp = [];

		foreach ($files as $key => &$value) {

			$files_temp[$key]['path'] = (isset($this->minify_path[$value]) && have_posts($this->minify_path[$value]))? $this->minify_path[$value] : [];

			$value = str_replace(base_url(),'',$value);

			$files_temp[$key]['file'] = $value;
		}

		$files = $files_temp;

		if(!$createMinFile) {

			$btime = filemtime($path_min_css);

			foreach ($files as $key => $value) {

				$time = filemtime($value['file']);

				if($time > $btime){

					unlink($path_min_css);

					$createMinFile = true;

					break;
				}
			}
		}

		if($createMinFile) {

			$ci = &get_instance();

			$ci->load->library('skd_minify');

			$ci->skd_minify->css($files);

			$ci->skd_minify->deploy_css(TRUE, 'css/'.$this->min_css);
		}

		$sContentCss = str_replace('../fonts', $this->get_assets().'fonts', concatenate_files(array($path_min_css)));
		
		$sContentCss = str_replace('../images', $this->get_assets().'images', $sContentCss);
		
		return $sContentCss;
	}

	public function minify_js($files = []) {

		if(!have_posts($files)) $files = $this->js;

		$path = FCPATH.VIEWPATH.$this->name.'/assets/';

		$path_min_js = $path.'js/'.$this->min_js;

		$createMinFile = false;

		if(!file_exists($path_min_js)) $createMinFile = true;

		$files_temp = [];

		foreach ($files as $key => &$value) {

			$files_temp[$key]['path'] = (isset($this->minify_path[$value]) && have_posts($this->minify_path[$value]))? $this->minify_path[$value] : [];

			$value = str_replace(base_url(),'',$value);

			$files_temp[$key]['file'] = $value;
		}

		$files = $files_temp;

		if(!$createMinFile) {

			$btime = filemtime($path_min_js);

			foreach ($files as $key => $value) {

				$time = filemtime($value['file']);

				if($time > $btime){

					unlink($path_min_js);

					$createMinFile = true;

					break;
				}
			}
		}

		if($createMinFile) {

			$ci = &get_instance();

			$ci->load->library('skd_minify');

			$ci->skd_minify->js($files);

			$ci->skd_minify->deploy_js(TRUE, 'js/'.$this->min_js);
		}

		return $this->get_assets().'js/'.$this->min_js;
	}

	public function minify_clear( $type = '' ) {
		
		$path = FCPATH.VIEWPATH.$this->name.'/assets/'.$type;

		if($type == 'css') $minify = $path.'/'.$this->min_css;

		if($type == 'js')  $minify = $path.'/'.$this->min_js;

		if(isset($minify) && file_exists($minify)) {

			unlink($minify);

			return true;
		}

		if(empty($type)) {

			$minify_css = $path.'/css'.$this->min_css;

			$minify_js = $path.'/js'.$this->min_js;

			if(file_exists($minify_css)) unlink($minify_css);

			if(file_exists($minify_js)) unlink($minify_js);

			return true;
		}

		return false;
	}
	/**
	MESSAGE
	*/
	public function set_message($message = '', $type = 'str') {

		$def = array( 'name' => 'all', 'type' => 'str', );

		if( is_array($type) ) { 

			$type = array_merge($def, $type);

		}
		else {

			if( $type == 'str') $def['type'] 	= $type;

			if( $type != 'str') $def['name'] 	= $type;

			$type 			= $def;

		}

		if( $type['type'] == 'str' ) {

			$this->message[$type['name']] = $message;

		} else {

			$_SESSION['template_message'] = $message;

		}
	}

	public function get_message( $key = null, $return = false)
	{

		if( $key != null && isset($this->message[$key])) {

			if( $return ) return $this->message[$key];

			echo $this->message[$key];

			unset($this->message[$key]);
		}
		else {

			$mess = $this->message;

			if(isset($_SESSION['template_message'])) {

				$mess[] = $_SESSION['template_message'];

				unset($_SESSION['template_message']);
			}

			if( have_posts($mess) ) {

				if( $return ) return $mess;

				foreach ($mess as $key => $val) {

					if(is_array($val)) show_r($val); else echo $val;
				}

			}
			
		}
	}

	public function get_widget($index = null)
	{
		$ci =& get_instance();
		
		if($index == null)
			return $ci->widget;
		else {
			if(isset($ci->widget[$index]))
				return $ci->widget[$index];
			else return null;
		}
	}
}