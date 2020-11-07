<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class skd_security {

	private $ci;

	private $user = null;

	function __construct($params = NULL){
		
		$this->ci =& get_instance();

		$this->user = get_user_current();

		if(have_posts($this->user) && is_admin() && !isset($_SESSION['allow_upload'])) {

			$_SESSION['allow_upload'] = true;
		}

		if( have_posts($this->user) && !isset($this->user->status) ) {

			$model = get_model('user', 'backend');

			if( $model->db_field_exists('group_id','users') ) {

				$model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `group_id` `status` VARCHAR(50) NOT NULL");

				$model->query("UPDATE `".CLE_PREFIX."users` SET `status`='public'");

				$model->query("ALTER TABLE `".CLE_PREFIX."users` CHANGE `status` `status` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public'");

			}
		}
	}

	public function roles() {

		$ci = $this->ci;

		if( is_admin() ) {

			$admin_allowed_pages = array('home_ajax');

			$method = $ci->template->method;

			if(strpos($method, 'ajax_') !== false) return;

			foreach ($admin_allowed_pages as $page) {
				
				if( $ci->template->is_page($page) ) return;
			}

			if( $this->is_login() ) {

				$redirect_to 	= apply_filters('login_redirect_to', $ci->input->get('redirect_to') );

				if( current_user_can('loggin_admin') ) {

					if($method == 'login' || $method == 'forgot' || $method == 'reset') {

						if( !empty($redirect_to) ) redirect($redirect_to);
					}

					return;
				}
				else { redirect(base_url()); return; }
			}

			if($method != 'login' && $method != 'forgot' && $method != 'reset') {
				redirect( URL_ADMIN.'/login?redirect_to='.urlencode(fullurl()));
			}
		}
		else {

			$params = removeHtmlTags( $ci->uri->segment('2') );

			$class = $ci->template->class;

			if( $this->is_login() ) {

				$allowed_pages = array( 'user_login', 'user_register' );

				foreach ($allowed_pages as $page) {
					
					if( $class.'_'.$params == $page ) redirect( my_account_url() );
				}

			}
			else {

				$allowed_pages = array( 'user_index', 'user_password' );

				foreach ($allowed_pages as $page) {
					
					if( $class.'_'.$params == $page ) redirect( login_url() );
				}

				if( $class == 'user' && $params != 'login' && $params != 'register' ) redirect( login_url() );
			}

		}
	}

	public function capabilities() {

		$ci = $this->ci;

		$template = $ci->template;

		if( is_admin() && $this->is_login() ) {

			$post_types = $ci->taxonomy['list_post_detail'];

			foreach ($post_types as $post_key => $post_type ) {
			}

			//Phân quyền cho các chức năng sử dụng ajax
			if( $template->is_page('home_ajax') ) {
				if( $ci->input->post('action') == 'ajax_trash' && (
					( !current_user_can('delete_pages') && $ci->input->post('table') == 'page' ) ||
					( !current_user_can('delete_posts') && $ci->input->post('table') == 'post' )
				) ) {
					echo json_encode(array('type' => 'error', 'message' => __('Bạn không thể thực hiện chức năng này.')));
					die;
				}
			}

			//phân quyền cho các chức năng load page bình thường
			if( 
				(($template->is_page('page_index') || $template->is_page('page_edit')) && !current_user_can('edit_pages')) ||
				(($template->is_page('post_categories_index') || $template->is_page('post_categories_edit')) && $ci->cate_type == 'post_categories' && !current_user_can('manage_categories')) ||
				(($template->is_page('gallery_index')) && !current_user_can('edit_gallery')) ||
				($template->is_page('home_update_core') && !current_user_can('update_core'))
			) {
				show_error('Bạn không thể truy cập vào đây.');
			}

			//Phân quyền trang user
			if( $template->class == 'user' ) {

		        if( $template->method == 'index' && !current_user_can('list_users') ) {
		            show_error('Bạn không thể truy cập vào đây.');
		        }
		    }

			//phân quyền cho các chức năng xóa
			if( $template->is_page('page_ajax_delete') && !current_user_can('delete_pages') ) {
				echo json_encode(array('type' => 'error', 'message' => __('Bạn không thể thực hiện chức năng này.')));
				die;
			}
		}
			
	}
	/**
	 * kiểm tra đăng nhập backend
	 * */
	public function auth_backend()
	{
	}

	/* kiểm tra đã đăng nhập hay chưa */
	public function is_login()
	{
		if(have_posts($this->user)) return true;
		return false;
	}

	public function is_admin()
	{
		if(have_posts($this->user) && $this->user->group_id < 3 ) return true;
		return false;
	}


	/*Lấy user */
	public function get_user($type = 'object')
	{
		if($type == 'object') return (object)$this->user;
		return $this->user;
	}
	/**
	 * [encode_session mã hóa session]
	 */
	public function encode_session($param = '')
	{
		return base64_encode(json_encode($param));
	}
	/**
	 * [decode_session giải mã session]
	 */
	public function decode_session($param = '')
	{
		return json_decode(base64_decode($param));
	}
}