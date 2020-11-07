<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $frontend 			= false;

	public $data     			= NULL; //dùng chứa dữ liệu đưa xuống views

	//ngôn ngữ
	public $language 			= NULL;

	//chứa short code
	public $shortcode_tags 		= array();

	//chưa thông tin đăng ký ajax
	public $ajax 				= null;

	//admin dashboard home index
	public $widget_dashboard 	= null;

	//admin navigation
	public $admin_nav 			= array();

	public $admin_nav_sub 		= array();

	//theme
	public $system 				= array();

	public $theme_option 		= array();

	public $menu_option 		= array();

	public $navigation 			= array();

	public $widget 				= array();

	public $sidebar 			= array();

	//taxonomy
	public $taxonomy = '';

	public $metaboxs = array();

	public $cate_type = null;

	public $post_type = null;

	public $url_type = null;

	//gallery
	public $gallery_options = '';
	//col
	public $col = array();

	//chứa thông tin các plugin
	public $plugins = array();


	public $roles = array();

	function __construct($MODULE = 'backend') {

		parent::__construct();

		$this->data['ci'] =&get_instance();

		$this->frontend = ($MODULE == 'backend')?false:true;

		$this->check();

		$this->load();
				
		do_action('init');

		if($MODULE == 'backend') {

			$this->data['group'] 	= $this->router->fetch_class();

			$this->data['active'] 	= $this->data['group'];

			$this->data['module']   = $this->data['group'];

			$this->data['ajax']     = $this->data['group'];


			$this->cate_type     	= $this->input->get('cate_type');

			$this->post_type     	= $this->input->get('post_type');

			if($this->cate_type == '' && is_page('post_categories_index')) {
				echo notice('error','category type don\'t exits!', true);
            	die;
			}

			if($this->post_type == '' && is_page('post_index')) {
				echo notice('error','post type don\'t exits!', true);
            	die;
			}

			if(!empty($this->post_type) && empty($this->cate_type)) {

				$post_type = get_post_type($this->post_type);

				if(isset($post_typ['cate_type'])) $this->cate_type = $post_typ['cate_type'];
			}


            if($this->cate_type != null && $this->post_type != null) $this->url_type = '?cate_type='.$this->cate_type.'&post_type='.$this->post_type;

            if($this->cate_type != null && $this->post_type == null) $this->url_type = '?cate_type='.$this->cate_type;

            if($this->cate_type == null && $this->post_type != null) $this->url_type = '?post_type='.$this->post_type;

            /**
             * hook admin_int
             * @singe 2.0.4
             */
            do_action('admin_init');

			//load form
			$this->gets_layout();

			$this->gets_view();

			$this->form_gets_field();
			//template
			$this->template->set_template('backend');
		}

		if($MODULE == 'frontend') {

			$this->data['module']   = $this->router->fetch_class();

			/* load ngôn ngữ */
			if(in_array($this->uri->segment(1), array_keys($this->language['language_list']))) {

				$_SESSION['language'] = $this->uri->segment(1);
			}

			if( empty($_SESSION['language']) || ( isset($_SESSION['language']) && !isset($this->language['language_list'][$_SESSION['language']]) ) ) {

				$_SESSION['language'] = $this->language['default'];
			}

			$this->language['current'] = $_SESSION['language'];

			$this->config->set_item('language', $_SESSION['language']);

			$this->lang->load('general');

			$this->template->set_template($this->system->theme_current);

			$cms_status = get_option('cms_status', 'public');

			if($cms_status == 'close' && !is_page('home_close')) {
				redirect('close');
			}

			if($cms_status == 'close-home' && is_page('home_index') && !is_page('home_close')) {
				
				if($this->uri->segment(1) == '') redirect('close');
			}

			if($cms_status == 'password' && !is_page('home_password')) {

				if(!isset($_SESSION['cms_close_password'])) redirect('password');
			}
		}

		$this->skd_security->roles();

		$this->skd_security->capabilities();
	}


	function check() {

		global $required_php_version;

		$php_version_current = phpversion();

		$php_current = explode('.', $php_version_current );

		$php_version = explode('.', $required_php_version );

		$error_php = 'Phiên bản PHP của bạn cần nâng cấp lên phiên bản <b>'.$required_php_version.'</b> để website có thể vận hành. phiên bản hiển tại của bạn là '.$php_version_current;

		$error_head = 'LỖI PHP VERSION';

		if( $php_current[0] < $php_version[0] ) echo show_error($error_php, 500, $error_head);

		if( $php_current[0] == $php_version[0] && $php_current[1] < $php_version[1] ) echo show_error($error_php, 500, $error_head);

	}

	function load() {

		$this->load->helper(array('cache', 'security','template', 'commont','ajax','uri','menu','forms', 'taxonomy', 'metabox', 'email', 'plugin', 'post', 'page', 'user', 'capabilities','gallery'));
		
		$this->load->library(array('skd_security', 'skd_mail', 'template','paging', 'skd_list_table', 'cart', 'service_api', 'skd_error', 'skd_roles'));

		$this->load->driver('cache', array('adapter' => 'file'));

		$this->roles = new SKD_Roles();

		//Load thông tin widget
		if(@file_exists(FCPATH.APPPATH.'libraries/widget.php'))
			require_once(FCPATH.APPPATH.'libraries/widget.php');

		//Load thông tin menu
		if(@file_exists(FCPATH.APPPATH.'libraries/walker_nav_menu.php'))
			require_once(FCPATH.APPPATH.'libraries/walker_nav_menu.php');

		//Ngôn ngữ
		$this->language['default'] 			= 'vi'; // language mặc định

		$this->language['language_list'] 	= array('vi' => array('label' => 'Tiếng Việt'));

		$this->language['current'] 			= 'vi';

		//ajax
		$this->ajax = array('nopriv' => array(), 'login' => array(), 'admin' => array(), 'admin_nov' => array());
		//theme option
		$this->theme_option = array('group' => array(), 'option' => array());

		//Load thông tin system
		if(@file_exists(FCPATH.VIEWPATH.'backend/config.php')) require_once(FCPATH.VIEWPATH.'backend/config.php');

		$this->load_options();

		//load plugin
		$this->plugins['active'] = get_option('plugin_active', array() );

		if( !have_posts($this->plugins['active']) ) $this->plugins['active'] = array();

		$this->load->library('plugin');
		
		$this->plugin->load_file();

		//load thông tin template
		if($this->data['template']->isset_template()) require_once(FCPATH.VIEWPATH.$this->data['template']->name.'/config.php');

		$this->theme_option();

		//Load Thông tin user
		if($this->skd_security->is_login()) $this->data['user'] = (object) $this->skd_security->get_user();
	}

	/**
	 * [System Load các cấu hình hệ thống trong bảng system]
	 */
	public function load_options() {

		$this->system = (object)get_cache('system');

		$this->data['template'] = new template(get_option('theme_current'));

		$this->data['system'] = $this->system;
	}


	public function theme_option() {

		do_action('theme_option_setup');

		//chưa Filed option trong theme
		$options_field	= $this->theme_option['option'];
		// chứa value của mặc định của option
		$options_value 	= array();
		// chưa option value đã lưu trong database
		$theme_option 	= array();

		//tạo value cho các option chưa có value mặc định
		foreach ($options_field as $key => $value) {
			$default 						= array('value' => '');
			$options_field[$key]			= array_merge($default, $value);
			$options_value[$value['field']] 	= $options_field[$key]['value'];
		}

		//lấy dữ liệu theme_option
		$theme_option = (array)get_option('theme_option');

		//nếu theme_option chưa có giá trị thì gán giá trị mới vào
		if(!have_posts($theme_option)) $theme_option = (array)$options_value;

		$this->system = (object)array_merge((array)$this->system, $theme_option);

		//kiểm tra có xuất hiện option mới hay xóa option hay không
		$change = false;

		foreach ($options_field as $key => $value) {
			if(isset($this->system->{$value['field']}))
				$options_value[$value['field']] = $this->system->{$value['field']};
			else {
				$change = true;
				$this->system->{$value['field']} = $value['value'];
			}
		}

		if($change) update_option('theme_option', serialize($options_value));

		$this->data['system'] = $this->system;

	}

	/**
	 * [Field form] khởi tạo các trường input
	 */
	public function form_gets_field($data = array()) {

		if(!isset($data['class']) || $data['class'] == '') $data['class'] = $this->router->fetch_class();
		//group các field nằm bên trái phía trên cùng
		$form['leftt'] 	= array();

		//group các field đa ngôn ngữ
		$form['lang'] 	= array( 'info' => 'Thông Tin', );

		//group các field nằm bên trái phía dưới
		$form['leftb'] 	= array( );

		//group các field nằm bên phải
		$form['right'] 	= array( 'media' => 'Media', 'seo' => 'Seo', 'theme' => 'Giao Diện' );

		//danh sách tất cả field
		$form['field']  = array();

		foreach ($this->language['language_list'] as $key => $name) {

			if($key == $this->language['default']) $rules = 'trim|required'; else $rules = 'trim';

			$form['field'][$key.'_title']   = array('group' => 'info', 'lang'=> $key, 	'field' => $key.'[title]', 'label' => 'Tiêu đề', 'type'	=> 'text', 'note' 	=> 'Tiêu đề bài viết được lấy làm thẻ H1', 'rules' => $rules);
			
			$form['field'][$key.'_excerpt'] = array('group' => 'info', 'lang'=> $key,	'field' => $key.'[excerpt]','label' => 'Tóm tắt', 'type' => 'wysiwyg-short');
			
			$form['field'][$key.'_content'] = array('group' => 'info', 'lang'=> $key,	'field' => $key.'[content]','label' => 'Nội dung','type' => 'wysiwyg');
		}
		
		$form['field']['image']           = array('group' => 'media', 'field' => 'image', 			'label' => 'Ảnh đại diện', 		'value'=>'','type' => 'image');
		
		$form['field']['public']          = array('group' => 'media', 'field' => 'public', 			'label' => 'Hiển Thị', 			'value'=>1, 'type' => 'switch',);
		
		$form['field']['slug']            = array('group' => 'seo',   'field' => 'slug', 			'label' => 'Slug', 				'value'=>'','type' => 'text',);
		
		$form['field']['seo_title']       = array('group' => 'seo',   'field' => 'seo_title', 		'label' => 'Meta title', 		'value'=>'','type' => 'text',);
		
		$form['field']['seo_keywords']    = array('group' => 'seo',   'field' => 'seo_keywords', 	'label' => 'Meta Keyword', 		'value'=>'','type' => 'text',);
		
		$form['field']['seo_description'] = array('group' => 'seo',   'field' => 'seo_description', 'label' => 'Meta Description', 	'value'=>'','type' => 'textarea',);
		
		$form['field']['theme_layout']    = array('group' => 'theme', 'field' => 'theme_layout', 	'label' => 'Template Layout', 	'value'=>0, 'type' => 'select',);
		
		$form['field']['theme_view']      = array('group' => 'theme', 'field' => 'theme_view', 		'label' => 'Template View', 	'value'=>0, 'type' => 'select',);


		switch ($data['class']) {
			case 'page':

				$form['param']['slug'] 		= 'title';

				$form['param']['redirect'] 	= URL_ADMIN.'/page/edit/{slug}';

				$remove_group = 'media,theme';

				$remove_field = 'excerpt';

				template_support_action($remove_group, $remove_field, $form, $data['class']);

				$form = apply_filters("manage_page_input", $form);
			break;

			case 'post_categories':

				$taxonomy = get_cate_type($this->cate_type);

				if( have_posts( $taxonomy ) ) {

					$form['param']['parent']   = $taxonomy['parent'];

					$form['param']['slug']     = 'name';

					$form['param']['redirect'] = URL_ADMIN.'/post/post_categories'.$this->url_type;

					foreach ($this->language['language_list'] as $key => $name) {

						if($key == $this->language['default']) $rules = 'trim|required'; else $rules = 'trim';

						$param = array('group' => 'info', 'lang' => $key, 	'field' => $key.'[name]', 'label' => 'Tiêu đề', 'type'	=> 'text', 'note' 	=> 'Tiêu đề bài viết được lấy làm thẻ H1', 'rules' => $rules);
						
						$form['field'] = form_add_field( $form['field'], $param, 'excerpt');
					}

					//danh mục
					if($taxonomy['parent'] == true) {

						$form['right']['category'] 	=  'Danh mục';

						$form['field']['parent_id'] =  array('group' => 'category', 'field' => 'parent_id', 	'label' => 'Danh mục cha', 'value'=>$this->input->get('category'), 'type' => 'select', 'rules' => 'trim');
						
						$form['right']   			= form_add_group( $form['right'], 'category', 'Danh mục', 'media');
					}

					$form = form_remove_field( 'title', $form);

					$taxonomy_support_group = $taxonomy['supports']['group'];

					if( have_posts( $taxonomy_support_group ) ) {

						$supports_group = array_merge( $form['leftt'], $form['leftb'], $form['right'] );

						foreach ($taxonomy_support_group as $support_group_key ) {
							if( isset( $supports_group[$support_group_key] )) unset( $supports_group[$support_group_key] );
						}

						if( have_posts( $supports_group ) ) {

							foreach ($supports_group as $support_group_key => $support_group_value ) {

								if( isset( $form['leftt'][$support_group_key] ) ) unset($form['leftt'][$support_group_key]);

								if( isset( $form['leftb'][$support_group_key] ) ) unset($form['leftb'][$support_group_key]);

								if( isset( $form['right'][$support_group_key] ) ) unset($form['right'][$support_group_key]);
							}
						}
					}

					$taxonomy_support_field = $taxonomy['supports']['field'];

					if( have_posts( $taxonomy_support_field ) ) {

						$supports_field = $form['field'];

						if( in_array( 'name', $taxonomy_support_field ) === false ) $taxonomy_support_field[] = 'name';

						foreach ($taxonomy_support_field as $support_field_key ) {

							if( $support_field_key == 'name' || $support_field_key == 'excerpt' || $support_field_key == 'content' ) {

								foreach ($this->language['language_list'] as $language_key => $name) {

									if( isset( $supports_field[$language_key.'_'.$support_field_key] )) unset( $supports_field[$language_key.'_'.$support_field_key] );
								}
									
							}
							else if( isset( $supports_field[$support_field_key] )) unset( $supports_field[$support_field_key] );
						}

						if( have_posts( $supports_field ) ) {

							foreach ($supports_field as $support_field_key => $support_field_value ) {

								if( isset( $form['field'][$support_field_key] ) ) unset($form['field'][$support_field_key]);
							}
						}
					}
					
					if($this->template->method == 'index') {

						$form = form_remove_group('seo,theme', $form);

						$form = form_remove_field('excerpt,content,public', $form);
					}

					$remove_group = (( in_array('image', $taxonomy['supports'] ) !== false )?'':'media,').'theme';

					$remove_field = 'content,excerpt';

					template_support_action($remove_group, $remove_field, $form, $data['class']);

					$form = apply_filters("manage_categories_".$this->cate_type.'_input', $form);
				}
			break;

			case 'post':
				
				$post                      = get_post_type($this->post_type);
				
				$post_support_group        = $post['supports']['group'];

				$post_support_field 		= $post['supports']['field'];
				
				$form['param']['slug']     = 'title';
				
				$form['param']['redirect'] = URL_ADMIN.'/post'.$this->url_type;

				if( (int)$this->input->get('page') != 0 && (int)$this->input->get('page') != 1 ) {

					$form['param']['redirect'] .= '&page='.(int)$this->input->get('page');
				}

				if( isset($post['taxonomies']) && have_posts($post['taxonomies']) ) {

					$form['right'] 		= form_add_group( $form['right'], 'taxonomies', 'Chuyên Mục', 'media');

					$post_support_group[] = 'taxonomies';

					foreach ($post['taxonomies'] as $key_taxonomy) {

			            $taxonomy = $this->taxonomy['list_cat_detail'][$key_taxonomy];

			            $form['field']['taxonomy_'.$key_taxonomy] =  array(
							'group'=> 'taxonomies', 
							'field'=> 'taxonomy['.$key_taxonomy.']', 
							'label'=> $taxonomy['labels']['name'], 
							'type' => 'popover',
							'module' => 'post_categories',
							'key_type' => $key_taxonomy
						);

			            $post_support_field[] 	= 	'taxonomy_'.$key_taxonomy;
			        }
				}

				
				if( have_posts( $post_support_group ) ) {

					$supports_group            = array_merge( $form['leftt'], $form['leftb'], $form['right'] );

					foreach ($post_support_group as $support_group_key ) {
						if( isset( $supports_group[$support_group_key] )) unset( $supports_group[$support_group_key] );
					}

					if( have_posts( $supports_group ) ) {

						foreach ($supports_group as $support_group_key => $support_group_value ) {

							if( isset( $form['leftt'][$support_group_key] ) ) unset($form['leftt'][$support_group_key]);

							if( isset( $form['leftb'][$support_group_key] ) ) unset($form['leftb'][$support_group_key]);

							if( isset( $form['right'][$support_group_key] ) ) unset($form['right'][$support_group_key]);
						}
					}
				}


				if( have_posts( $post_support_field ) ) {

					$supports_field = $form['field'];

					if( in_array( 'title', $post_support_field ) === false ) $post_support_field[] = 'title';

					foreach ($post_support_field as $support_field_key ) {

						if( $support_field_key == 'title' || $support_field_key == 'excerpt' || $support_field_key == 'content' ) {

							foreach ($this->language['language_list'] as $language_key => $name) {

								if( isset( $supports_field[$language_key.'_'.$support_field_key] )) unset( $supports_field[$language_key.'_'.$support_field_key] );
							}
								
						}
						else if( isset( $supports_field[$support_field_key] )) unset( $supports_field[$support_field_key] );
					}

					if( have_posts( $supports_field ) ) {

						foreach ($supports_field as $support_field_key => $support_field_value ) {

							if( isset( $form['field'][$support_field_key] ) ) unset($form['field'][$support_field_key]);
						}
					}
				}

				$remove_group = 'theme';

				$remove_field = '';

				template_support_action( $remove_group, $remove_field, $form, $data['class']);

				$form = apply_filters("manage_post_".$this->post_type."_input", $form);
			break;

			default:

				$form = apply_filters("form_gets_field_".$data['class'], $form);

				$form = apply_filters("manage_".$data['class']."_input", $form);
				
				break;
		}

		//metabox
		foreach ($this->metaboxs as $key => $metabox) {

			$content 		= $metabox['content'];
			
			$content_box 	= $metabox['content_box'];

			if($metabox['module'] == null) {

				$form[$content] = form_add_group($form[$content], $key, $metabox['label'], $content_box);
			}
			else if($metabox['module'] == $data['class']) {

				$form[$content] = form_add_group($form[$content], $key, $metabox['label'], $content_box);

			}
			else if($metabox['module'] == 'post_'.$this->post_type && $data['class'] == 'post') {

				$form[$content] = form_add_group($form[$content], $key, $metabox['label'], $content_box);

			}
			else if($metabox['module'] == 'post_categories_'.$this->cate_type && $data['class'] == 'post_categories') {

				$form[$content] = form_add_group($form[$content], $key, $metabox['label'], $content_box);
			}
		}

		$this->data['form'] = $form;

		$this->form_gets_rules();

		return $this->data['form'];
	}

	//thêm dữ liệu vào các trường nhập liệu khi edit
	public function form_sets_field(&$object) {

		$object = apply_filters('sets_field_before',$object );

		$form_field = $this->data['form']['field'];

		$model = $this->data['module'].'_model';

		$current_model = $this->$model->gettable();

		//đa ngôn ngữ
		$this->$model->settable('language');

		$languages = array();

		if(count($this->language['language_list']) > 1) {
			$languages = $this->$model->gets_where(array('object_id' => $object->id, 'object_type' => $this->data['module']));
		}

		foreach($form_field as $key => $field) {

			//gán giá trị cho các field bình thường
			if( isset($object->{$field['field']}) ) { 
				$form_field[$key]['value'] = $object->{$field['field']}; 
			}
			//gán giá trị cho các field đa ngôn ngữ
			else if( isset($field['lang']) ) {

				$temp = str_replace($field['lang'].'[', '',$field['field']);

				$temp = str_replace(']', '',$temp);

				if( have_posts($languages) ) {

					foreach ($languages as $k => $value) {

						if($field['lang'] == $value->language ) {

							if(isset($value->$temp))  {
								
								$form_field[$key]['value'] = $value->$temp;

								break;
							}
						}
						else if(isset($object->$temp)) {
							
							$form_field[$key]['value'] = $object->$temp;
						}
					}

				} else if(isset($object->$temp)) {

					$form_field[$key]['value'] = $object->$temp;
				}
			}

		}

		$this->$model->settable($current_model);

		$this->data['form']['field'] = $form_field;
	}
	/**
	 * [Field form] điều kiện nhập liệu cho các trường input
	 */
	public function form_gets_rules() {

		$rules = array('rules' => 'trim');
		// gom nhóm các field
		$form_rule 	= array();

		//lọc các trường dữ liệu
		if( have_posts($this->data['form']['field']) ) {

			foreach ($this->data['form']['field'] as $key => $value) {

				if(empty($value['rules'])) {

					if($value['type'] == 'select' || $value['type'] == 'dropdow' ||$value['type'] == 'popover' ) {

						$form_rule[] = array_merge(array('rules' => array()), $value);

						continue;

					}
				}

				$form_rule[] = array_merge($rules, $value);
			}
		}

		return $this->data['form']['rules'] = $form_rule;
	}

	public function gets_layout()
	{
		$layout = array('0' => 'Mặc định');

		$dir = FCPATH.VIEWPATH.$this->data['template']->name;

		$this->load->helper('directory');

		$layouts = directory_map($dir, FALSE, TRUE);

		if(have_posts($layouts)) {
			foreach ($layouts as $key => $val) {
				if(is_numeric($key)) {
					$content = file($dir.'/'.$val);
					foreach ($content as $k => $value) {
						$value 		= trim($value);
						if(strpos($value,'Layout-Name',0) !== false || strpos($value,'Layout-name',0) !== false ||
						strpos($value,'layout-name',0) !== false || strpos($value,'layout-Name',0) !== false) {
							$pos_start  = stripos($value,':')+1;
							$pos_end    = strlen($value);
							$layout[str_replace('.php','',$val)] = trim(substr($value, $pos_start, $pos_end));
							break;
						}
					}
				}
			}
		}
		return $this->data['dropdown']['theme_layout'] = $layout;
	}

	public function gets_view()
	{
		$view = array('0' => 'Mặc định');

		$dir = FCPATH.VIEWPATH.$this->data['template']->name;

		$this->load->helper('directory');

		$views = directory_map($dir, FALSE, TRUE);

		if(have_posts($views)) {
			foreach ($views as $key => $val) {
				if(is_numeric($key)) {
					$content = file($dir.'/'.$val);

					foreach ($content as $k => $value) {
						$value 		= trim($value);
						if(strpos($value,'View Name',0) !== false || 
						strpos($value,'View name',0) 	!== false || 
						strpos($value,'view name',0) 	!== false ||
						strpos($value,'view Name',0) 	!== false ||
						strpos($value,'View-Name',0) 	!== false || 
						strpos($value,'View-name',0) 	!== false || 
						strpos($value,'view-name',0) 	!== false ||
						strpos($value,'view-Name',0) 	!== false
						) {
							$pos_start  = stripos($value,':')+1;
							$pos_end    = strlen($value);
							$view[str_replace('.php','',$val)] = trim(substr($value, $pos_start, $pos_end));
							break;
						}
					}
				}
			}
		}

		return $this->data['dropdown']['theme_view'] = $view;
	}

	/* SLUG */
	//router
	public function router_url($module='') {
		if($module == '') $module = $this->data['module'];
		if($module == 'page') 					return 'page';
		if($module == 'post') 					return 'post';
		if($module == 'products') 				return 'products';
		if($module == 'post_categories') 		return 'post_categories';
		if($module == 'products_categories') 	return 'products_categories';
		return $module;
	}

	public function router_controller($module='') {
		if($module == '') $module = $this->data['module'];
		if($module == 'page') 					return 'frontend_page/page/detail/';
		if($module == 'post') 					return 'frontend_post/post/detail/';
		if($module == 'products') 				return 'frontend_products/products/detail/';
		if($module == 'post_categories') 		return 'frontend_post/post/index/';
		if($module == 'products_categories') 	return 'frontend_products/products/index/';
		return $module;
	}

	//tạo slug khi add
	public function create_slug($slug = '', $model = '') {

		$slug 	= slug($slug);

		if( $model == '' ) {
			$model 	= $this->{$this->data['module'].'_model'};
		}

		$current_model = $model->gettable();

		$model->settable('routes');

		$count 	= $model->count_where(array('slug'=> $slug));

		$temp 	= $slug;

		if($count == 1) {
			$i = 1;
			while ($count == 1) {
				$slug 	= $temp.'-'.$i;
				$count 	= $model->count_where(array('slug' =>$slug));
				$i++;
			}
		}

		$model->settable($current_model);

		return $slug;
	}
	//tạo slug khi edit
	public function edit_slug($slug, $id, $model = '') {

		$slug   = slug($slug);

		if( $model == '' ) {
			$model 	= $this->{$this->data['module'].'_model'};
		}

		$temp   = $slug;

		$current_model = $model->gettable();

		$model->settable('routes');

		$count  = $model->_general(array(
			'table'       => $model->gettable(),
			'param' 	  => 'slug = \''.$slug.'\' AND object_id <> '.$id,
			'count'       => TRUE
		));

		if($count == 1) {
			$i = 1;
			while ($count == 1) {
				$slug 	= $temp.'-'.$i;
				$count 	= $model->_general(array(
												'table'       => $model->gettable(),
												'param' 	  => 'slug = \''.$slug.'\' AND object_id <> '.$id,
												'count'       => TRUE
											));
				$i++;
			}
		}

		$model->settable($current_model);

		return $slug;
	}

	//xử lý dữ liệu bên ngoài
	public function _process_data() {

		$post = $this->data['form_data_post'];

		$data = null;

		//xử lý dữ liệu price
		if(isset($post['price'])) $post['price'] 			= str_replace(',', '', trim($post['price']));
		if(isset($post['price'])) $post['price'] 			= str_replace('.', '', trim($post['price']));
		if(isset($post['price_sale'])) $post['price_sale'] 	= str_replace(',', '', trim($post['price_sale']));
		if(isset($post['price_sale'])) $post['price_sale'] 	= str_replace('.', '', trim($post['price_sale']));

		if(isset($post['price'])) $post['price'] = (int)$post['price'];

		if(isset($post['price_sale'])) $post['price_sale'] = (int)$post['price_sale'];

		//xử dữ liệu relationships
		if(($this->data['module'] == 'post' || $this->data['module'] == 'products') && isset($post['category_id'])) {
			$data['relationships'] = $post['category_id'];
			unset($post['category_id']);
		}

		if(($this->data['module'] == 'post' || $this->data['module'] == 'products') && isset($post['taxonomy'])) {
			$data['taxonomy'] = $post['taxonomy'];
			unset($post['taxonomy']);
		}

		if(isset($post['add']))  unset($post['add']);
		if(isset($post['edit'])) unset($post['edit']);

		$this->data['form_data_post'] = $post;

		$data = apply_filters( 'skd_form_process_data', $data);

		return $data;
	}

	/**
	 * [submit form] add form
	 */
	public function _form_add($rules = array(), $post = array(), $data_outside = array()) {

		$model = $this->data['module'].'_model';

		//dữ liệu đa ngôn ngữ
		$languages_list   = array_keys($this->language['language_list']);
		$language_current = $this->language['current'];
		$language_default = $this->language['default'];

		//dữ liệu data
		$ins_lang = array();
		$ins_data = array();

		$param = $this->data['form']['param'];

		$field = array();

		foreach ($this->data['form']['field'] as $key => $value) {
			$field[$value['field']] = $value['field'];
		}

		//tách dữ liệu đa ngôn ngữ ra
		foreach ($post as $key => $val) {
			if( in_array($key, $languages_list) ) {
				$ins_lang[$key] = $val;
			}
			else {
				if( in_array($key, $field) ) $ins_data[$key] = $val;
			}
		}

		if(isset($ins_lang[$language_default])) {

			$ins_data = array_merge($ins_data, $ins_lang[$language_default]);

			unset($ins_lang[$language_default]);
		}

		//xử lý các trường input đặc biệt
		$ins_data = process_data($ins_data, $rules);

		//xử lý slug
		if(isset($param['slug'])) {
			if(empty($ins_data['slug'])) 
				$ins_data['slug'] = $this->create_slug($ins_data[$param['slug']]);
			else 
				$ins_data['slug'] = $this->create_slug($ins_data['slug']);
		}

		//get cat type
		if($this->data['module'] == 'post_categories' && isset_cate_type($this->cate_type)) {
			$ins_data['cate_type'] = $this->cate_type;
		}
		//get post type
		if($this->data['module'] == 'post') {
			$ins_data['post_type'] = $this->post_type;
		}

		$ins_data = apply_filters( 'save_object_before', $ins_data, $data_outside );

		do_action('save_object_before', $ins_data, $data_outside );

		$check = apply_filters( "check_save_before", null, $ins_data, $data_outside );

		if ( null !== $check ) {

			$result['message'] 		= 'Thêm dữ liệu thất bại.';

			$result['check'] 		= $check;

			return $result;
		}

		if( $this->$model->db_field_exists('user_created') && empty($ins_data['user_created']) ) {

			$user = get_user_current();

			if( have_posts($user)) $ins_data['user_created'] = $user->id;
		}

		$id = $this->$model->add($ins_data);

		if($id > 0) {

			//lấy table hiện tại
			$current_model = $this->$model->gettable();

			//xử lý router
			if(isset($ins_data['slug']) && ( $this->data['module'] == 'page' || $this->data['module'] == 'post' || $this->data['module'] == 'post_categories' || $this->data['module'] == 'products_categories' || $this->data['module'] == 'products')) {
				$this->$model->settable('routes');

				$router['slug'] 		= $ins_data['slug'];
				$router['object_type']	= $this->data['module'];
				$router['directional']	= $this->router_url($this->data['module']);
				$router['controller']	= $this->router_controller($this->data['module']);
				$router['object_id']	= $id;

				$this->$model->add($router);
				$this->$model->settable($current_model);
			}

			//categories
			if(isset($param['parent'])) {
				if( !class_exists('nestedset')) $this->load->library('nestedset');
				$nestedset = new nestedset(array('model' => $this->data['module'].'_model', 'table' => $this->$model->gettable()));
				$nestedset->get();
				$nestedset->recursive(0, $nestedset->set());
				$nestedset->action();
			}

			//relationships
			if(isset($data_outside['relationships']) && have_posts($data_outside['relationships'])) {

				$this->$model->settable('relationships');

				$relationships['object_id'] 		= $id;

				$relationships['object_type'] 	= $this->data['module'];

				if( $this->data['module'] == 'products' ) $relationships['value'] 			= 'products_categories';

				foreach ($data_outside['relationships'] as $key => $value) {

					$relationships['category_id'] = $value;

					$this->$model->add($relationships);
				}

				$this->$model->settable($current_model);
			}

			//taxonomy
			if(isset($data_outside['taxonomy']) && have_posts($data_outside['taxonomy'])) {

				$this->$model->settable('relationships');

				$taxonomy['object_id'] 		= $id;

				$taxonomy['object_type'] 	= $this->data['module'];

				foreach ($data_outside['taxonomy'] as $taxonomy_key => $taxonomy_value ) {

					$taxonomy['value'] 		= $taxonomy_key;

					foreach ($taxonomy_value as $taxonomy_id) {

						$taxonomy['category_id'] = $taxonomy_id;

						$this->$model->add($taxonomy);
					}
					
				}

				$this->$model->settable($current_model);

			}

			//thêm các đối tượng ngôn ngữ khác
			if(count($ins_lang)) {

				$this->$model->settable('language');

				foreach ($ins_lang as $key => $val) {

					$lang 					= $ins_lang[$key];
					
					$lang['language'] 		= $key;

					$lang['object_id']  	= $id;

					$lang['object_type']  	= $this->data['module'];

					if($this->$model->add($lang)) unset($ins_lang[$key]);
				}

				$this->$model->settable($current_model);
			}

			do_action( 'save_object_add', $id, $this->$model, $data_outside, $current_model, $ins_data );

			do_action( 'save_object', $id, $this->$model, $data_outside, $current_model, $ins_data );

			//chuyển đến trang
			if(!isset($param['redirect'])) $param['redirect'] = '';

			$param['redirect'] = str_replace('{id}',$id,$param['redirect']);
			$param['redirect'] = str_replace('{slug}',$ins_data['slug'],$param['redirect']);

			$result['redirect'] 	= $param['redirect'];
			$result['id'] 			= $id;
			$result['type'] 		= 'success';
			$result['message'] 		= 'Thêm dữ liệu thành công';
		}
		else
		{
 			$result['message'] 		= 'Thêm dữ liệu thất bại.';
		}

		return $result;
	}

	/**
	 * [submit form] edit form
	 */
	public function _form_edit($rules = array(), $post = array(), $id = 0, $data_outside = array()) {

		$model = $this->data['module'].'_model';

		//dữ liệu đa ngôn ngữ
		$languages_list   = array_keys($this->language['language_list']);

		$language_current = $this->language['current'];

		$language_default = $this->language['default'];

		//dữ liệu data
		$ins_lang = array();
		$ins_data = array();

		$param = $this->data['form']['param'];

		$field = array();

		foreach ($this->data['form']['field'] as $key => $value) {
			$field[$value['field']] = $value['field'];
		}

		//tách dữ liệu đa ngôn ngữ ra
		foreach ($post as $key => $val) {
			if( in_array($key, $languages_list) ) {
				$ins_lang[$key] = $val;
			}
			else {
				if( in_array($key, $field) ) $ins_data[$key] = $val;
			}
		}

		if(isset($ins_lang[$language_default])) {

			$ins_data = array_merge($ins_data, $ins_lang[$language_default]);

			unset($ins_lang[$language_default]);
		}

		//xử lý các trường input đặc biệt
		$ins_data = process_data($ins_data, $rules);

		//xử lý slug
		if(isset($param['slug'])) {
			if(empty($ins_data['slug'])) $ins_data['slug'] = $this->edit_slug($ins_data[$param['slug']], $id);
			else $ins_data['slug'] = $this->edit_slug($ins_data['slug'], $id);
		}

		$ins_data = apply_filters( 'save_object_before', $ins_data, $data_outside );

		do_action('save_object_before', $ins_data, $data_outside );

		$check = apply_filters( "check_save_before", null, $ins_data, $data_outside );

		if ( null !== $check ) {

			$result['message'] 		= 'Cập nhật dữ liệu thất bại.';

			$result['check'] 		= $check;

			return $result;
		}

		if( $this->$model->db_field_exists('user_updated') && empty($ins_data['user_updated']) ) {

			$user = get_user_current();

			if( have_posts($user)) $ins_data['user_updated'] = $user->id;
		}

		if($this->$model->update_where($ins_data, array('id'=> $id))) {
			//lấy table hiện tại
			$current_model = $this->$model->gettable();

			//xử lý router
			if(isset($ins_data['slug']) && ( $this->data['module'] == 'page' || $this->data['module'] == 'post' || $this->data['module'] == 'post_categories' || $this->data['module'] == 'products_categories' || $this->data['module'] == 'products')) {
				
				$this->$model->settable('routes');

				$router['slug'] 		= $ins_data['slug'];
				$router['controller']	= $this->router_controller($this->data['module']);
				$router['directional']	= $this->router_url($this->data['module']);

				if($this->$model->update_where($router, array('object_id' => $id, 'object_type' => $this->data['module'])) == 0)
				{
					$router['object_type']	= $this->data['module'];
					$router['directional']	= $this->router_url($this->data['module']);
					$router['controller']	= $this->router_controller($this->data['module']);
					$router['object_id']	= $id;

					$this->$model->add($router);
				}

				$this->$model->settable($current_model);
			}

			//categories
			if(isset($param['parent'])) {

				if( !class_exists('nestedset')) $this->load->library('nestedset');

				$nestedset = new nestedset(array('model' => $this->data['module'].'_model', 'table' => $this->$model->gettable()));

				$nestedset->get();

				$nestedset->recursive(0, $nestedset->set());

				$nestedset->action();
			}

			if(isset($data_outside['relationships']) && have_posts($data_outside['relationships'])) {

				$this->$model->settable('relationships');

				$relationships['object_id']   = $id;
				
				$relationships['object_type'] = $this->data['module'];
				
				if( $this->data['module'] == 'products') $relationships['value'] 			= 'products_categories';

				$temp = $this->$model->gets_where_in( array('field' => 'category_id', 'data' => $data_outside['relationships']), $relationships );

				if( have_posts($temp) ) {

					$relationships_old       = array();

					$relationships_intersect = array();

					foreach ($temp as $value) {
						$relationships_old[$value->category_id] = $value->category_id;
					}

					//Xóa relationships bị xóa
					$this->$model->delete_where_notin( array('field' => 'category_id', 'data' => $relationships_old), $relationships );

					//Thêm relationships mới
					foreach ($data_outside['relationships'] as $id_category) {

						if( in_array($id_category, $relationships_old ) === false ) {
							
							$relationships['category_id'] = $id_category;

							$this->$model->add($relationships);
						}
					}

				}
				else {

					$this->$model->delete_where( $relationships );

					foreach ($data_outside['relationships'] as $key => $id_category) {

						$relationships['category_id'] = $id_category;

						$this->$model->add($relationships);
					}
				}

				$this->$model->settable($current_model);
				
			}

			//taxonomy
			if(isset($data_outside['taxonomy']) && have_posts($data_outside['taxonomy'])) {

				$this->$model->settable('relationships');

				$this->$model->settable('relationships');

				$temp = $this->$model->gets_where(array('object_id' => $id, 'object_type' => $this->data['module']), array('select' => 'value', 'groupby' => array('value')));
				
				$taxonomy_cate_type = array();

				foreach ($temp as $temp_key => $temp_value) {

					if($temp_value->value == 'products_categories') continue;

					$taxonomy_cate_type[$temp_value->value] = $temp_value->value;
				}

				$taxonomy['object_id'] 		= $id;

				$taxonomy['object_type'] 	= $this->data['module'];

				foreach ($data_outside['taxonomy'] as $taxonomy_key => $taxonomy_value ) {

					if(isset($taxonomy_cate_type[$taxonomy_key])) unset($taxonomy_cate_type[$taxonomy_key]);

					$this->$model->delete_where(array('object_id' => $id, 'object_type' => $this->data['module'], 'value' => $taxonomy_key ));

					$taxonomy['value'] 		= $taxonomy_key;

					foreach ($taxonomy_value as $taxonomy_id) {

						$taxonomy['category_id'] = $taxonomy_id;

						$this->$model->add($taxonomy);
					}
				}

				if( have_posts($taxonomy_cate_type) ) {

					foreach ($taxonomy_cate_type as $ta_cate_type) {

						$this->$model->delete_where(array('object_id' => $id, 'object_type' => $this->data['module'], 'value' => $ta_cate_type ));
					}
				}
			}
			else {

				$this->$model->settable('relationships');

				$this->$model->delete_where(array('object_id' => $id, 'object_type' => $this->data['module'], 'value <>' => 'products_categories'));
			}

			$this->$model->settable($current_model);

			//thêm các đối tượng ngôn ngữ khác
			if(count($ins_lang)) {

				$this->$model->settable('language');

				$object_lang = $this->$model->gets_where(array('object_id' => $id, 'object_type' => $this->data['module']));

				if(!empty($object_lang)) 
				{
					foreach ($object_lang as $key_lang => $lang) 
					{
						foreach ($ins_lang as $key => $val)
						{
							if($key == $lang->language) 
							{
								if($this->$model->update_where($ins_lang[$key], array('id' => $lang->id))) unset($ins_lang[$key]);
							}
						}
					}
				}

				if(!empty($ins_lang))
				{
					foreach ($ins_lang as $key => $val)
					{
						$lang = $ins_lang[$key];
						$lang['language'] 		= $key;
						$lang['object_id']  	= $id;
						$lang['object_type']  	= $this->data['module'];
						if($this->$model->add($lang)) unset($ins_lang[$key]);
					}
				}

				
				$this->$model->settable($current_model);
			}

			do_action( 'save_object_edit', $id, $this->$model, $data_outside, $current_model, $ins_data );
			do_action( 'save_object', $id, $this->$model, $data_outside, $current_model, $ins_data );

			//chuyển đến trang
			if(!isset($param['redirect'])) $param['redirect'] = '';

			$param['redirect'] = str_replace('{id}',$id, $param['redirect']);

			$param['redirect'] = str_replace('{slug}', $ins_data['slug'], $param['redirect']);

			$result['redirect'] 	= $param['redirect'];

			$result['type'] 		= 'success';

			$result['message'] 		= 'Cập nhật dữ liệu thành công';
		}
		else
		{
 			$result['message'] 		= 'Cập nhật dữ liệu thất bại.';
		}

		return $result;
	}

	/**
	 * [submit form] submit form
	 */
	public function form_action($object = array(), $rules = array(), $rs = false)
	{	
		if($this->input->post() != null)
		{
			//lấy dữ liệu post
			$this->data['form_data_post']         = $this->input->post();

			//xử lý dữ liệu bên ngoài
			$data_outside = $this->_process_data();

			$post = $this->data['form_data_post'];

			//kiểm tra dữ liệu
			if(!have_posts($rules)) $rules = $this->data['form']['rules'];

			$this->form_validation->set_rules($rules);

			if($this->form_validation->run())
        	{
        		if(have_posts($object))
        			$result = $this->_form_edit($rules, $post, $object->id, $data_outside);
        		else
					$result = $this->_form_add($rules, $post, $data_outside);
					
				if($rs == false) {

					if($result['type'] == 'success')  {

						$this->template->set_message(notice('success',$result['message']),'flash');

						if(!empty($result['redirect'])) {
							redirect($result['redirect']);
						}
					}
					
					if($result['type'] == 'error') $this->template->set_message(notice('danger', $result['message']));
				}
				else {
					return $result;
				}

        		
        	}
         	else
         	{
				if($rs == false) {
					$this->template->set_message(notice('danger',validation_errors()));
				}
				else {
					return [
						'type' 		=> 'error',
						'message' 	=> validation_errors()
					];
				}
         	}
		}
	}


	//khôi phục dữ liệu xóa tạm
	public function untrash()
	{
		if($this->input->get('id') != null)
		{
			//lấy dữ liệu post
			$id    = (int)$this->input->get('id');

			$model = $this->data['module'].'_model';

			$count = $this->$model->count_where(array('id' => $id));

			if($count) {
				$this->$model->update_where(array('trash' => 0), array('id' => $id));
				$mesage = 'Khôi phục dữ liệu thành công!';
				$this->template->set_message(notice('success',$mesage),'flash');
			}
			else {
				$mesage = 'Dữ liệu không tồn tại!';
				$this->template->set_message(notice('danger',$mesage),'flash');
			}
			redirect(URL_ADMIN.'/'.$this->data['module'].$this->url_type);
		}
	}

	/*========================================================*/
	/* 	ajax delete
	/*========================================================*/
	public function ajax_delete()
	{
		$result['message'] 	= 'Xóa dữ liệu không thành công!';
		$result['type'] 	= 'error';

		if($this->input->post())
		{
			$data 	= $this->input->post('data');

			$module = $this->data['module'];

			do_action('ajax_delete_after_success', $module, $data);

			$r 		= $this->_del($data);

			

			//Xóa đối tượng
			if(is_numeric($data) && $r != false) {

				$result['data'] 	= $r;

				$result['message'] 	= 'Xóa dữ liệu thành công!';

				$result['type'] 	= 'success';

				do_action('ajax_delete_object_before_success', $module, $data, $r);

				do_action('ajax_delete_before_success', $module, $data, $r);
			}
			//xóa nhiều đối tượng
			if(have_posts($data) && have_posts($r)) {

				$result['data'] 	= $r;

				$result['message'] 	= 'Xóa dữ liệu thành công!';

				$result['type'] 	= 'success';

				do_action('ajax_delete_list_before_success', $module, $data, $r);

				do_action('ajax_delete_before_success', $module, $data, $r);
			}
		}

		echo json_encode($result);
	}

	public function _del($data = '' , $module='')
	{
		if($module == '') $module = $this->data['module'];
		//Xóa đối tượng
		if(is_numeric($data)) return $this->_del_object($module, $data);
		//xóa nhiều đối tượng
		if(have_posts($data)) return $this->_del_checked($module, $data);
	}

	public function _del_object($module, $id = 0) {

		$model 		= $module.'_model';

		//nếu object tồn tại
		if($this->$model->count_where(array('id' => $id)) == 1) {

			$current_model = $this->$model->gettable();

			//xóa các dữ liệu liên quan
			if($module == 'page' || $module == 'post' || $module == 'products') {

				$this->_del_normal($model, $this->data['module'], $id);

			}

			if($module == 'post_categories' || $module == 'products_categories') {


				//Get danh mục con của danh mục vừa xóa
				if($module == 'products_categories') {

					$this->$model->settable_category('products_categories');

				} else {

					$this->$model->settable_category('categories');

				}

				$object = $this->$model->get_where(array('id' => $id));

				$listID = $this->$model->gets_category_sub($object);

				//xóa relationships
				if( $module == 'post_categories' ) {

					$category = get_post_category( $id );

					if( have_posts($category) ) {

						$this->$model->settable('relationships');

						$this->$model->delete_where( array( 'category_id' => $id, 'value' => $category->cate_type ) );
					}
				}

				//xóa các danh mục con
				if(have_posts($listID)) {
					//xóa danh mục con
					$this->$model->settable($current_model);

					$this->_del_list($model, $this->data['module'], $listID);
				}

				$this->$model->settable($current_model);

				return $listID;
			}

			//xóa đối tượng
			$this->$model->settable($current_model);

			$this->$model->delete_where(array('id' => $id));

			return array($id);
		}
		return false;
	}

	public function _del_checked($module, $listID = array())
	{
		$model 		= $module.'_model';
		$result = array();
		foreach ($listID as $key => $id) {
			$this->_del_object($module, $id);
			$result[] = $id;
		}
		return $result;
	}
	//xóa bình thường
	public function _del_normal($model, $module, $id = 0)
	{
		$where['object_type'] = $module;

		if($id != 0) $where['object_id'] = $id;

		//xóa router
		$this->$model->settable('routes');
		$this->$model->delete_where($where);
		//xóa ngôn ngữ
		$this->$model->settable('language');
		$this->$model->delete_where($where);
		//xóa gallery
		$this->$model->settable('gallerys');
		$this->$model->delete_where($where);

		if( $module == 'products' ) {

			$this->$model->settable('relationships');

			$where['object_type'] = 'products';

			$this->$model->delete_where($where);
		}
	}

	//xóa theo list id
	public function _del_list($model, $module, $listID = 0)
	{
		$where['object_type'] = $module;

		$data['field'] = 'object_id';

		$data['data']  = $listID;

		$current_model = $this->$model->gettable();

		//xóa router
		$this->$model->settable('routes');

		$this->$model->delete_where_in( $data, $where );

		//xóa ngôn ngữ
		$this->$model->settable('language');

		$this->$model->delete_where_in( $data, $where );

		//xóa gallery
		$this->$model->settable('gallerys');

		$this->$model->delete_where_in( $data, $where );

		if( $module == 'products' ) {

			$this->$model->settable('relationships');

			$this->$model->delete_where_in( $data, $where );
		}

		//xóa chính bản thân
		$this->$model->settable($current_model);
		$data['field'] = 'id';
		$data['data']  = $listID;
		$this->$model->delete_where_in($data);
	}

	/**
	 * [GetsCategory lấy danh sách categories theo category parent và order không phân cấp]
	 * @param integer $parent_id [id của category cha cần lấy con]
	 * @param array   $where     [điều kiện lấy]
	 * kết quả trả về có dạng :
	 * array(
	 * 	categori 1
	 *  category 1.1
	 *  category 1.2
	 *  category 2
	 *  category 2.1
	 * )
	 */
	public function gets_category($parent_id = 0, $trees = NULL, $where = array())
	{
		$model = $this->data['module'].'_model';

		if($trees == NULL) $trees = array();

		$wheres = array('parent_id' => $parent_id);

		$wheres = array_merge($wheres, $where);

		if( $this->$model->gettable_category() == null ) $this->$model->settable('categories');
			
		$root = $this->$model->gets_where( $wheres , array( 'type' => 'object', 'orderby' =>'order' ) );

		if( isset( $root ) &&  have_posts($root) ) {
			foreach ($root as $key => $val) {
				$trees[] = $val;
				$trees = $this->gets_category($val->id , $trees, $where);
			}
		}

		return $trees;
	}

	/**
	 * [multilevelcategory lấy danh sách category theo category cha có phân cấp]
	 * kết quả có dạng :
	 * array(
	 * category 1
	 * 	->child ( category 1.1, category 1.2)
	 * category 2
	 *  ->child ( category 2.1 )
	 * )
	 */
	public function multilevel_categories($categories = array(), $where =  array(), $model, $param = array())
	{
		if(is_array($categories)) {
			foreach ($categories as $key => $value) {
				$wheres = array('parent_id' => $value->id);
				$wheres = array_merge($wheres, $where);
				$categories[$key]->child = $model->gets_where($wheres, $param);
				$this->multilevel_categories($categories[$key]->child, $where, $model, $param);
			}
		}
		else
		{
			$wheres = array('parent_id' => $categories->id);
			$wheres = array_merge($wheres, $where);
			$categories->child = $model->gets_where($wheres, $param);
			$this->multilevel_categories($categories->child, $where, $model, $param);
			return $categories->child;
		}
		return $categories;
	}


	//insert post
	public function insert_post($data = array() )
	{

		$model = get_model('post', 'backend');

		$model->settable('post');

		$object = array();

		//kiểm tra edit post hay add post
		if( isset($data['id']) ) {

			$data['id'] = (int)removeHtmlTags($data['id']);

			$object = $model->get_where(array('id' => $id));

			if( !have_posts($object) ) return false;
		}
		
		$this->data['module'] 	= 'post';

		$this->data['param'] 	= array('slug' => 'title');

		if( !isset($data['title']) ) return false;

		$data[$this->language['default']]['title'] = removeHtmlTags($data['title']); unset($data['title']);

		if( isset($data['excerpt']) ) $data[$this->language['default']]['excerpt'] = $data['excerpt']; unset($data['excerpt']);

		if( isset($data['content']) ) $data[$this->language['default']]['content'] = $data['content']; unset($data['content']);

		$defaults 	= array( 'post_type' => 'post', );

		$data 		= array_merge($defaults, $data);

		$this->post_type = $data['post_type'];

		$rules 	= $this->form_gets_field( array('class' => 'post') );

		if( !have_posts($object) )
			return $this->_form_add($rules, $data);
		else
			return $this->_form_edit($rules, $data, $id);
	}

	public function delete_post($id = 0, $trash = false )
	{
		$id = (int)removeHtmlTags($id);

		if( $id == 0 ) return false;

		$model = get_model('post', 'backend');

		$this->data['module'] 	= 'post';

		$model->settable('post');

		//kiểm tra post có tồn tại không
		$count  = $model->count_where(array( 'id' => $id));

		if( $count == 1 ) {
			 //nếu bỏ vào thùng rác
			 if( $trash == true ) return $model->update_where(array('trash' => 1), array('id' => $id));

			 return $this->_del($id, 'post');
		}

		return false;
	}

	//insert page
	public function insert_page($data = array() )
	{

		$model = get_model('page', 'backend');

		$model->settable('page');

		$object = array();

		//kiểm tra edit page hay add page
		if( isset($data['id']) ) {

			$data['id'] = (int)removeHtmlTags($data['id']);

			$object = $model->get_where(array('id' => $id));

			if( !have_posts($object) ) return false;
		}
		
		$this->data['module'] 	= 'page';

		$this->data['param'] 	= array('slug' => 'title');

		if( !isset($data['title']) ) return false;

		$data[$this->language['default']]['title'] = removeHtmlTags($data['title']); unset($data['title']);

		if( isset($data['excerpt']) ) $data[$this->language['default']]['excerpt'] = $data['excerpt']; unset($data['excerpt']);

		if( isset($data['content']) ) $data[$this->language['default']]['content'] = $data['content']; unset($data['content']);

		$rules 	= $this->form_gets_field( array('class' => 'page') );

		if( !have_posts($object) )
			return $this->_form_add($rules, $data);
		else
			return $this->_form_edit($rules, $data, $id);
	}

	public function delete_page($id = 0, $trash = false )
	{
		$id = (int)removeHtmlTags($id);

		if( $id == 0 || $id == NULL || $id == '') return false;

		$model = get_model('page', 'backend');

		$this->data['module'] 	= 'page';

		$model->settable('page');

		//kiểm tra post có tồn tại không
		$count  = $model->count_where(array( 'id' => $id));

		if( $count == 1 ) {
			 //nếu bỏ vào thùng rác
			 if( $trash == true ) return $model->update_where(array('trash' => 1), array('id' => $id));

			 return $this->_del($id, 'page');
		}

		return false;
	}
}