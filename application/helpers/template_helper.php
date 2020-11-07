<?php
/**
=======================================================
SET TEMPLATE DEFAULT
=======================================================
*/
if(!function_exists('set_template_default')) {

	function set_template_default($class, $method, $layout = '', $view = '') {

		$ci =& get_instance();

		if($class != null && $method != null) {
			if($layout != null) $ci->data['template']->default[$class][$method]['layout'] = $layout;
			if($view != null) $ci->data['template']->default[$class][$method]['view'] = $view;
		}
	}
}
//include template file
if(!function_exists('get_template_file')) {

	function get_template_file($views_path, $args = '', $return = false) {
		$ci =& get_instance();
		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path = VIEWPATH.$ci->template->name.'/'.$views_path.'.php';

		if(file_exists($path)) {
			ob_start();
			include $path;
			$buffer = ob_get_contents();
			ob_end_clean();
			if( $return  == true) {
				return $buffer;
			} else {
				echo $buffer;
			}
		}
		else {
			echo 'Không tìm thấy file '.$path.'!';
			die;
		}
	}
}

/**
=======================================================
TEMPLATE SUPPORT
=======================================================
*/
if(!function_exists('template_support')) {
	function template_support($class = '', $group = array(), $field = array()) {
		$ci =& get_instance();
		if($class != null ) {
			$tempale = $ci->data['template']->support;
			if(have_posts($group)) $tempale[$class]['group'] = $group;
			if(have_posts($field)) $tempale[$class]['field'] = $field;
			$ci->data['template']->support = $tempale;
		}
	}
}

if(!function_exists('template_support_action')) {
	
	function template_support_action($remove_group = '', $remove_field = '', &$form, $class = '') {

		$ci 		=& get_instance();

		$template 	= (isset($ci->data['template'])) ? $ci->data['template'] : array();

		$support_group = array();

		$support_field = array();

		if(isset($template->support[$class]['group']) && have_posts($template->support[$class]['group'])) {
			$support_group = $template->support[$class]['group'];
		}

		if(isset($template->support[$class]['field']) && have_posts($template->support[$class]['field'])) {
			$support_field = $template->support[$class]['field'];
		}

		//remove group các chức năng
		if(have_posts($support_group)) {
			foreach ($support_group as $key => $group) { $remove_group = str_replace($group,'',$remove_group); }
		}
		$remove_group = trim($remove_group);
		$remove_group = trim($remove_group,',');
		if($remove_group != '') $form = form_remove_group($remove_group, $form);

		//remove các field các chức năng
		if(have_posts($support_field)) {
			foreach ($support_field as $key => $group) { $remove_field = str_replace($group,'',$remove_field); }
		}
		$remove_field = trim($remove_field);
		$remove_field = trim($remove_field,',');
		if($remove_field != '') $form = form_remove_field($remove_field, $form);
	}
}

if(!function_exists('is_page')) {
	/**
	 * [is_page description]
	 * @param  string  $page [description]
	 * @return boolean       [description]
	 * @since 2.4.0 
	 */
	function is_page( $page = '' ) {

		$ci =& get_instance();
		
		return $ci->template->is_page($page);
	}
}

if(!function_exists('is_home')) {
	/**
	 * [is_page description]
	 * @param  string  $page [description]
	 * @return boolean       [description]
	 * @since 2.4.0 
	 */
	function is_home() {

		$ci =& get_instance();
		
		return $ci->template->is_home();
	}
}

/*---------------------------
 * Kiểm đang đứng ở admin hay frontend
 *---------------------------*/
if(!function_exists('is_admin'))  {
	function is_admin() {
		$ci =& get_instance();
		$admin = $ci->uri->segment(1);
		if($admin == URL_ADMIN) return true;
		return false;
	}
}

/**
=======================================================
STYLE - SCRIPT
=======================================================
*/
if(!function_exists('cle_enqueue_style'))  {
	function cle_enqueue_style() {
		$ci =& get_instance();
		$ci->template->reset_script();
		$ci->template->reset_style();

		do_action( 'cle_enqueue_style' );

		$ci->template->get_css();
		$ci->template->get_js();
	}
}

if(!function_exists('cle_enqueue_script'))  {
	function cle_enqueue_script() {
		$ci =& get_instance();
		$ci->template->reset_script();
		$ci->template->reset_style();
		do_action( 'cle_enqueue_script' );
		$ci->template->get_js();
		$ci->template->get_css();
	}
}

if(!function_exists('admin_register_style'))  {
	
	function admin_register_style($id = null, $path = null, $page = null) {

		if(is_admin() && $id != null && $path != null) {

			$ci =& get_instance();

			$current_page = $ci->template->get_page();

			if($page == null) {
				$ci->template->set_style($id, $path);
			}
			else if(is_string($page) && $ci->template->is_page($page)) {
				$ci->template->set_style($id, $path);
			}
			else if(is_string($page) && $ci->template->class == $page) {
				$ci->template->set_style($id, $path);
			}
			else if(is_array($page) && in_array($current_page, $page) !== false) {
				$ci->template->set_style($id, $path);
			}
		}
	}
}

if(!function_exists('admin_register_script'))  {
	function admin_register_script($id = null, $path = null, $page = null) {
		if(is_admin() && $id != null && $path != null) {
			$ci =& get_instance();

			$current_page = $ci->template->get_page();

			if($page == null) {
				$ci->template->set_script($id, $path);
			}
			else if(is_string($page) && $current_page == $page) {
				$ci->template->set_script($id, $path);
			}
			else if(is_string($page) && $ci->template->class == $page) {
				$ci->template->set_script($id, $path);
			}
			else if(is_array($page) && in_array($current_page, $page) !== false) {
				$ci->template->set_script($id, $path);
			}
		}
	}
}

if(!function_exists('cle_register_style'))  {
	function cle_register_style($id = null, $path = null, $page = null, $minify = false) {
		if(!is_admin() && $id != null && $path != null) {
			$ci =& get_instance();
			$current_page = $ci->template->get_page();
			if($page == null) {
				$ci->template->set_style($id, $path, $minify);
			}
			else if(is_string($page) && $ci->template->is_page($page)) {
				$ci->template->set_style($id, $path, $minify);
			}
			else if(is_string($page) && $ci->template->class == $page) {
				$ci->template->set_style($id, $path, $minify);
			}
			else if(is_array($page) && in_array($current_page, $page) !== false) {
				$ci->template->set_style($id, $path, $minify);
			}
		}
	}
}

if(!function_exists('cle_register_script'))  {

	function cle_register_script($id = null, $path = null, $page = null, $minify = false) {

		if(!is_admin() && $id != null && $path != null) {
			$ci =& get_instance();
			$current_page = $ci->template->get_page();

			if($page == null) {
				$ci->template->set_script($id, $path, $minify);
			}
			else if(is_string($page) && $current_page == $page) {
				$ci->template->set_script($id, $path, $minify);
			}
			else if(is_string($page) && $ci->template->class == $page) {
				$ci->template->set_script($id, $path, $minify);
			}
			else if(is_array($page) && in_array($current_page, $page) !== false) {
				$ci->template->set_script($id, $path, $minify);
			}
		}
	}
}

/**
=======================================================
HIỂN THỊ HÌNH ẢNH
=======================================================
*/

if(!function_exists('get_img')) {
	/**
	 * [get_img tạo html <img>]
	 * @param  string  $img    [đường dẫn, tên hình ảnh]
	 * @param  string  $alt    [alt cho ảnh]
	 * @param  array   $params [attribute cho thẻ img]
	 * @param  string  $type   [nguồn ảnh từ thư mục upload: sourc, medium, thumb]
	 * @param  boolean $return [true nếu muốn trả về kết quả và false nếu muốn in kết quả ra]
	 * @return [type]          [string]
	 */
	function get_img($img = '', $alt = '',$params = array(), $type ='source' , $return = false) {

		$url = get_img_link($img, $type);

		/* singe 3.0.0 */
		$url  	 = apply_filters('get_img_url', $url, array('img' => $img, 'alt' => $alt, 'params' => $params, 'type' => $type));
		
		/* singe 3.0.0 */
		$params  = apply_filters('get_img_params', $params, array('img' => $img, 'alt' => $alt, 'type' => $type));
		
		$html = '<img src="'.$url.'" alt="'.removeHtmlTags($alt).'" ';

		if(have_posts($params)) {
			foreach ($params as $key => $value)
				$html .= $key.'="'.$value.'" ';
		}
		$html .=' loading="lazy" />';

		$html = apply_filters('get_img', $html, array('url' => $url, 'img' => $img, 'alt' => $alt, 'params' => $params, 'type' => $type));

		if($return) return $html; else echo $html;
	}
}

if(!function_exists('get_img_link')) {

	/**
	 * [get_img_link tạo đường dẫn hình ảnh]
	 * @param  string  $img    [đường dẫn, tên hình ảnh]
	 * @param  string  $type   [nguồn ảnh từ thư mục upload: sourc, medium, thumb]
	 * @param  boolean $return [true nếu muốn trả về kết quả và false nếu muốn in kết quả ra]
	 * @return [type]          [đường link ảnh]
	 */
	function get_img_link($img = '', $type = 'source', $return = true) {

		$ci =& get_instance();
		//kiểm tra có phải url img không
		if(is_url($img)) $url = $img;
		//nếu không phải get 
		else {
			if($type == 'source') 		 $url = SOURCE.$img;
			else if($type == 'thumbail') $url = THUMBAIL.$img;
			else if($type == 'medium') 	 $url = MEDIUM.$img;
			else 						 $url = $type.'/'.$img;

			if($type ==  'watermark') return base_url().$url;

			$url_check =  urldecode($url);

			if (!file_exists($url_check)) {

				$url = SOURCE.$img;

				$url_check =  urldecode($url);
			}

			//get nếu file không tồn tại
			if (!file_exists($url_check)) {
				stream_context_set_default( [
				    'ssl' => [
				        'verify_peer' => false,
				        'verify_peer_name' => false,
				    ],
				]);
				//kiểm tra template
				$url = get_img_template_link($img);

				$file_headers = @get_headers($url);

				if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
				    $url = $ci->template->get_assets().'images/no-images.png';
				}
			}
		}

		$ch = curl_init();

		$url = apply_filters('get_img_link', $url, array(
			'img'    => $img,
			'type'   => $type,
		));

		if($return) return $url; else echo $url;
	}
}

if(!function_exists('get_img_template')) {
	function get_img_template($img = '', $alt = '',$params = array(), $return = true)
	{
		$ci =& get_instance();
		$html = '<img src="';
		$html .= $ci->template->get_assets().'images/'.$img.'"';
		$html .= ' alt="'.$alt.'" ';
		if(have_posts($params)) {
			foreach ($params as $key => $value) {
				$html .= $key.'="'.$value.'" ';
			}
		}
		$html .='/>';
		if($return) return $html; else echo $html;
	}
}

if(!function_exists('get_img_template_link')) {
	function get_img_template_link($img = '', $return = true)
	{
		$ci =& get_instance();
		$url = $ci->template->get_assets().'images/'.$img;
		if($return) return $url; else echo $url;
	}
}

if(!function_exists('get_img_fontend')) {
	function get_img_fontend($img = '', $alt = '',$params = array(), $return = false)
	{
		$url = get_img_fontend_link($img);
		
		$html = '<img src="'.$url.'" alt="'.removeHtmlTags($alt).'" ';
		if(have_posts($params)) {
			foreach ($params as $key => $value)
				$html .= $key.'="'.$value.'" ';
		}
		$html .='/>';
		if($return) return $html; else echo $html;
	}
}

if(!function_exists('get_img_fontend_link')) {
	
	function get_img_fontend_link($img = '', $return = true)
	{
		$ci =& get_instance();
		//kiểm tra có phải url img không
		if(is_url($img)) $url = $img;
		//nếu không phải get 
		else {
			$url = SOURCE.$img;

			$url_check =  urldecode($url);

			//get nếu file không tồn tại
			if (!file_exists($url_check)) {
				stream_context_set_default( [
				    'ssl' => [
				        'verify_peer' => false,
				        'verify_peer_name' => false,
				    ],
				]);
				//kiểm tra template
				$url = base_url().'views/'.$ci->data['template']->name.'/assets/images/'.$img;
				$file_headers = @get_headers($url);

				if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
				    $url = $ci->data['template']->get_assets().'images/no-images.png';
				}
			}
		}

		if($return) return $url; else echo $url;
	}
}

if(!function_exists('get_img_plugin')) {
	function get_img_plugin($name, $img = '', $alt = '',$params = array(), $return = false)
	{
		$url = get_img_plugin_link($name, $img);
		$html = '<img src="'.$url.'" alt="'.removeHtmlTags($alt).'" ';
		if(have_posts($params)) {
			foreach ($params as $key => $value)
				$html .= $key.'="'.$value.'" ';
		}
		$html .='/>';
		if($return) return $html; else echo $html;
	}
}

if(!function_exists('get_img_plugins_link')) {
	function get_img_plugin_link($name, $img = '', $return = true)
	{
		$ci =& get_instance();
		$url = $ci->plugin->get_path($name).$img;
		if($return) return $url; else echo $url;
	}
}
/**
=======================================================
TẠO THÔNG BÁO
=======================================================
*/
//template thông báo
if(!function_exists('notice')) 
{
	function notice($status = 'success', $mess = '', $css_inser = false)
	{
		if($status == 'info') {
			$icon = '<i class="fal fa-info"></i>';
			$header = 'Thông tin';
		}
		if($status == 'success') {
			$icon = '<i class="fal fa-check"></i>';
			$header = 'Thành công';
		}
		if($status == 'experimental') {
			$icon = '<svg style="height:1.5em; width:1.5em;" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M14.38 14.59L11 7V3h1v-1H3v1h1v4L0.63 14.59c-0.3 0.66 0.19 1.41 0.91 1.41h11.94c0.72 0 1.2-0.75 0.91-1.41zM3.75 10l1.25-3V3h5v4l1.25 3H3.75z m4.25-2h1v1h-1v-1z m-1-1h-1v-1h1v1z m0-3h1v1h-1v-1z m0-3h-1V0h1v1z" /></svg>';
			$header = 'Chú ý';
		}
		if($status == 'warning') {
			$icon = '<i class="fal fa-exclamation-triangle"></i>';
			$header = 'Lưu ý';
		}
		if($status == 'danger' || $status == 'error') {
			$icon = '<i class="fal fa-times"></i>';
			$header = 'Lỗi';
		}

		$messages  = ($css_inser == true)?'<link rel="stylesheet" href="'.base_url().'views/backend/assets/css/style.css">':'';

		$messages .= "
		<div class=\"toast toast--{$status} add-margin\">
			<div class=\"toast__icon\">
				{$icon}
			</div>
			<div class=\"toast__content\">
				<p class=\"toast__type\">{$header}</p>
				<p class=\"toast__message\">{$mess}</p>
			</div>
			<div class=\"toast__close\">
				<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 15.642 15.642\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" enable-background=\"new 0 0 15.642 15.642\">
					<path fill-rule=\"evenodd\" d=\"M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z\"></path>
				</svg>
			</div>
		</div>";

		
		return $messages;
	}
}
//hiển thị thông báo cho admin
if(!function_exists('admin_notices')) {
	function admin_notices() {
		$ci =& get_instance();
		$ci->template->get_message();
		do_action('admin_notices');
	}
}

/**
=======================================================
BOX DASHBOARD ADMIN HOME PAGE
=======================================================
*/

/**
add widget dashboard
*/
if(!function_exists('add_dashboard_widget')) {
	function add_dashboard_widget( $id , $title, $content, $option = array()) {
		$ci =& get_instance();
		if(!isset($ci->widget_dashboard[$id])) {
			$ci->widget_dashboard[$id] = array('title' => $title, 'callback' => $content, 'option' => $option);
		}
	}
}

if(!function_exists('get_dashboard_widget')) {
	/**
	 * @since 2.4.3
	 */
	function get_dashboard_widget( $id = null ) {

		$ci =& get_instance();

		if(isset($ci->widget_dashboard[$id])) return $ci->widget_dashboard[$id];

		return false;
	}
}

if(!function_exists('gets_dashboard_widget')) {
	/**
	 * @since 2.4.3
	 */
	function gets_dashboard_widget() {

		$ci =& get_instance();

		return $ci->widget_dashboard;
	}
}
/**
remove widget dashboard
*/
if(!function_exists('remove_dashboard_widget')) {
	function remove_dashboard_widget( $id = null ) {
		$ci =& get_instance();
		if(!isset($ci->widget_dashboard[$id])) {
			unset($ci->widget_dashboard[$id]);
		}
	}
}
/**
show widget dashboard
*/
if(!function_exists('show_dashboard_widget')) {
	function show_dashboard_widget($id,  $widget = null ) {
		$ci =& get_instance();

		$option = array('col' => 4);
		$option = array_merge($option, $widget['option']);
		if($widget != null) {
			?>
			<div id="wg_dashboard_<?= $id;?>">
				<?php if(function_exists($widget['callback'])) $widget['callback']( $widget ); ?>
			</div>
			<?php
		}
	}
}
/**
Hiển thị tất cả widget dashboard
*/
if(!function_exists('cle_dashboard_setup')) {
	/**
	 * @since 2.0.0
	 * @since 2.4.3 edit sort and hide widget
	 */

	function cle_dashboard_setup() {

		$ci =& get_instance();

		do_action('cle_dashboard_setup');

		do_action('cle_dashboard_remove');

		$dashboard_sort = get_option('dashboard_sort', array() );

		$dashboard 		= get_option('dashboard', array());

		$widget_dashboard = array();

		$widget_dashboard_temp  = gets_dashboard_widget();

		foreach ( $dashboard_sort as $id_dashboard ) {

			if( isset($dashboard[$id_dashboard]) && $dashboard[$id_dashboard] == 0 ) continue;

			if( isset($widget_dashboard_temp[$id_dashboard])) {

				$widget_dashboard[$id_dashboard] = $widget_dashboard_temp[$id_dashboard];

				unset($widget_dashboard_temp[$id_dashboard]);
			}
		}

		if( have_posts($widget_dashboard_temp) ) {

			foreach ( $widget_dashboard_temp as $id_dashboard => $value_dashboard ) {

				if( isset($dashboard[$id_dashboard]) && $dashboard[$id_dashboard] == 0 ) continue;

				$widget_dashboard[$id_dashboard] = $widget_dashboard_temp[$id_dashboard];
			}
		}

		if(have_posts($widget_dashboard)) {

		 	foreach ($widget_dashboard as $id => $widget) {

		 		$option = array('col' => 4);
				$option = array_merge($option, $widget['option']);

		 		echo '<li class="list-dashboard-item col-md-'.$option['col'].'" style="overflow:hidden" data-id="'.$id.'">';
			 		show_dashboard_widget($id, $widget);
				echo '</li>';
		 	}
		}
	}
}
/**
=======================================================
THEME OPTIONS
=======================================================
*/
if(!function_exists('register_theme_option_group')) {
	/* tạo group theme option */
	function register_theme_option_group($group = array()) {

		$ci =& get_instance();

		$defaults = array('label' => '', 'icon' => '', 'position' => 0 );

		if(have_posts($group)) {

			foreach ($group as $key => $value) {

				$value = array_merge( $defaults, $value );

				$ci->theme_option['group'][$key] = $value;				
			}

			$groups = array();

			foreach ($ci->theme_option['group'] as $key => $value) {

				$position = $value['position'];

				$groups[$position][] = array(
					'key' 	=> $key,
					'value' => $value,
				);
			}

			ksort($groups);

			$ci->theme_option['group'] = array();

			foreach ($groups as $value) {
				foreach ($value as $val) {
					$ci->theme_option['group'][$val['key']] = $val['value'];
				}
			}


		}
	}
}

//tạo option theme cho group */
if(!function_exists('register_theme_option_field')) {
	function register_theme_option_field($field = array()) {
		$ci =& get_instance();
		if(have_posts($field)) {
			foreach ($field as $key => $value) {
				$ci->theme_option['option'][] = $value;
			}
		}
	}
}

//save theme option
if(!function_exists('theme_option_save')) {
	function theme_option_save($option_name, $option_value) {

		$ci =& get_instance();

		$theme_option = get_option('theme_option');

		if(have_posts($theme_option)) {

			$theme_option = unserialize($theme_option->option_value);

			$theme_option[$option_name] = $option_value;

			return update_option('theme_option', serialize($theme_option));
		}

		return null;
	}
}

function add_option($option_name, $option_value) {

	$ci =& get_instance();

	$model = get_model('plugins', 'backend');

	$model->settable('system');

	if ( ! $option_name ) {
		return false;
	}

	do_action( "add_{$option_name}_option", $option_name, $option_value );

	if( is_array($option_value) || is_object($option_value) ) $option_value = serialize($option_value);

	$data['option_name'] 	= $option_name;
	$data['option_value'] 	= $option_value;

	$mid = $model->add( array(
		'option_name' 	=> $option_name,
		'option_value' 	=> $option_value,
	) );

	if ( ! $mid )
		return false;

	delete_cache('system');

	do_action( "added_{$option_name}_meta", $mid, $option_name, $option_value );

	return $mid;
}

function update_option($option_name, $option_value) {

	$ci =& get_instance();

	if ( ! $option_name ) {
		return false;
	}

	if( is_array($option_value) || is_object($option_value) ) $option_value = serialize($option_value);

	$model = get_model('plugins', 'backend');

	$model->settable('system');

	$option = $model->get_where(array('option_name' => $option_name));

	if ( !have_posts( $option ) ) {
		return add_option( $option_name, $option_value );
	}

	$where = array( 'id' => $option->id, 'option_name' => $option_name );

	do_action( "update_{$option_name}_option", $option, $option_name, $option_value );

	$data['option_value'] = $option_value;

	$result = $model->update_where( $data, $where );

	if ( ! $result )
		return false;
	
	if(cache_exists('system')) {

		$system_cache = get_cache('system');

		$system_cache = (array)$system_cache;
		
	} else {
		$system_cache = [];
	}

	$system_cache[$option_name] = $option_value;

	save_cache('system', $system_cache);

	return true;
}

function delete_option($option_name) {

	$ci =& get_instance();

	$model = get_model('plugins', 'backend');

	$model->settable('system');

	if ( ! $option_name ) return false;

	$where['option_name']   = $option_name;

	do_action( "delete_{$option_name}_option", $where, $option_name);

	$count =  $model->delete_where($where);

	if ( !$count )
		return false;

	delete_cache('system');

	do_action( "deleted_{$option_name}_option", $where, $option_name );

	return true;
}

function get_option($option_name = '', $value = '') {

	$ci =& get_instance();

	if ( ! $option_name ) return false;

	if( !isset($ci->system->$option_name) && cache_exists('system') ) {

		$ci->system   = (object)get_cache('system');
	}

	if( isset($ci->system->$option_name)) return (is_serialized($ci->system->$option_name))?unserialize($ci->system->$option_name):$ci->system->$option_name;

	if(isset($ci->system->theme_option)) {

		$theme_option = unserialize($ci->system->theme_option);

		if(isset($theme_option[$option_name])) return (is_serialized($theme_option[$option_name]))?unserialize($theme_option[$option_name]):$theme_option[$option_name];
	}

	$model = get_model('plugins', 'backend');

	$model->settable('system');

	$where['option_name']   = $option_name;

	$params = ['select' => '`option_name`,`option_value`'];

	$system = $model->get_where($where, $params);

	if(cache_exists('system')) {

		$system_cache = get_cache('system');

		if(is_object($system_cache)) $system_cache = (array)$system_cache;

	} else {

		$system_cache = [];
	}

	if(have_posts($system)) {

		$system_cache[$option_name] = $system->option_value;

		$value = (is_serialized($system->option_value))?unserialize($system->option_value):$system->option_value;
	}
	else {

		if(is_object($system_cache)) $system_cache = (array)$system_cache;

		$system_cache[$option_name] = $value;
	}

	save_cache('system', $system_cache);

	$ci->system   = (object)get_cache('system');

	return $value;
}

function get_option_all() {

	$ci =& get_instance();

	$system = get_cache('system');

	if( $system ) return $system;

	$model = get_model('plugins', 'backend'); $model->settable('system');

	$_system = $model->gets_where();

	if(have_posts($_system)) {

		$system = (object)array();

		foreach ($_system as $key => $value) {
			$system->{$value->option_name} =  $value->option_value;
		}

		save_cache('system', $system);

		return $system;
	}

	return '';
}
/**
=======================================================
THEME WIDGET
=======================================================
*/
//tạo group theme option */
if(!function_exists('register_sidebar')) {
	function register_sidebar($args = array()) {
		$ci =& get_instance();

		$i = count($ci->sidebar) + 1;

		$defaults = array(
			'name'          => sprintf('Sidebar %d', $i ),
			'id'            => "sidebar-$i",
			'description'   => '',
		);

		$args = array_merge($defaults, $args);

		if(have_posts($args)) {
			$ci->sidebar[$args['id']] = $args;
		}
	}
}

if(!function_exists('register_widget')) {

	function register_widget( $key ) {

		$ci =& get_instance();

		$ci->widget[$key] = new $key;
	}
}

/* load widget in sidebar */
if(!function_exists('dynamic_sidebar'))  {

	function dynamic_sidebar( $index ) {

		$ci =& get_instance();

		$model = get_model('widgets_model', 'backend_theme');

		$model->settable('widget');

		$cache_sidebar_id = 'sidebar_'.md5($index.'_'.$ci->data['template']->name);

		$sidebar = get_cache($cache_sidebar_id);

		if( !is_array($sidebar) ) {

			$sidebar = $model->gets_where(array('sidebar_id' => $index, 'template' => $ci->data['template']->name),array('orderby'=>'order'));

			save_cache($cache_sidebar_id, $sidebar);
		}

		if(have_posts($sidebar)) {

			foreach ($sidebar as $key => $value) {

				$widget  		= $ci->template->get_widget($value->widget_id);

				if( have_posts($widget) ) {

					$widget->form();

					$widget->id 	= $value->id;

					$widget->name 	= $value->name;

					$widget->get_option(unserialize($value->options));

					$wg_option     = $widget->get_option_object();

					if(class_exists('skd_multi_language') && !is_language_default()) {

						if(isset($wg_option->{'title_'.$ci->language['current']})) $widget->name = $wg_option->{'title_'.$ci->language['current']};
					}

					$widget->widget($wg_option);
				}
			}
		} else {
				
			$widget = new Widget('widget_none', 'widget none');

			$widget->widget_none($index);
		}
	}
}

if(!function_exists('is_active_sidebar'))  {

	function is_active_sidebar( $index ) {

		$ci =& get_instance();

		$model = get_model('widgets_model', 'backend_theme');

		$model->settable('widget');

		$sidebar = $model->count_where(array('sidebar_id' => $index, 'template' => $ci->data['template']->name),array('orderby'=>'order'));
		
		if( $sidebar != 0 ) return true;

		return false;
	}
}

/**
=======================================================
Xử LÝ NỘI DUNG
=======================================================
*/
if(!function_exists('the_content'))  {
	function the_content( $content = '' ) {
		$ci =& get_instance();

		if($content == '' && isset($ci->data['object']->content)) $content = $ci->data['object']->content;
		$content = do_shortcode($content);
		$content = apply_filters( 'the_content', $content );
    	$content = str_replace( ']]>', ']]&gt;', $content );

    	echo $content;
	}
}

if( !function_exists('__') ) {

    function __( $str = '', $key = '' ) {

        $ci = &get_instance();

        $lang = apply_filters('lang_line', $ci->lang->line( $str ) );

        if( empty( $lang ) ) {
        	$lang = $ci->lang->line( $key );
        }

        return !empty($lang)?$lang:$str;
    }
}


/*---------------------------
 * Tạo Breadcrumb
 *---------------------------*/
if(!function_exists('Breadcrumb')){

	function Breadcrumb($data = array())
	{
		$bre = null;

		if(have_posts($data)) {

			$count = count($data);

			$bre .='<div class="btn-group btn-breadcrumb">';

			$bre .= apply_filters('breadcrumb_first', '<a href="'.base_url().'" class="btn btn-default">'.__('Trang Chủ','trang-chu').'</a>');

			foreach ($data as $key => $val) {

				$bre .= apply_filters('breadcrumb_icon', '<a class="btn btn-default btn-next"><i class="fa fa-caret-right"></i></a>');

				if( $key == ($count -1) ) {

					$bre .= apply_filters('breadcrumb_item_last', '<a class="btn btn-default">'.$val->name.'</a>', $val);

				} else {

					$bre .= apply_filters('breadcrumb_item', '<a href="'.get_url($val->slug).'" class="btn btn-default">'.$val->name.'</a>', $val);
				}
			}

			$bre .= apply_filters('breadcrumb_last', '</div>');
		}
		
	  	return $bre;
	}
}

if(!function_exists('admin_button_icon')){
	/**
	 * [admin_button_icon]
	 * @singel 2.3.5
	 */
	function admin_button_icon( $action ) {

		$icon = '';

		switch ( $action ) {
			case 'add': 	$icon = '<i class="fad fa-plus"></i>'; break;
			case 'edit': 	$icon = '<i class="fad fa-pencil"></i>'; break;
			case 'save': 	$icon = '<i class="fad fa-hdd"></i>'; break;
			case 'back': 	$icon = '<i class="fad fa-reply"></i>'; break;
			case 'undo': 	$icon = '<i class="fad fa-undo"></i>'; break;
			case 'delete': 	$icon = '<i class="fad fa-trash"></i>'; break;
			case 'search': 	$icon = '<i class="fad fa-search"></i>'; break;
			case 'cancel': 	$icon = '<i class="fad fa-ban"></i>'; break;
		}

		return apply_filters( 'admin_button_icon', $icon, $action );
	}
}

if(!function_exists('admin_loading_icon')){
	/**
	 * [admin_loading_icon]
	 * @singel 3.0.0
	 */
	function admin_loading_icon( $id = '', $class = '' ) {
		?>
		<div class="loading <?php echo $class;?>" id="<?php echo $id;?>">
			<div class="loading-content">
				<div class="loading-dot"></div>
				<div class="loading-dot"></div>
				<div class="loading-dot"></div>
				<div class="loading-dot"></div>
			</div>
		</div>
		<?php
	}
}

if(!function_exists('concatenate_files')){

	function concatenate_files($files) {

		$buffer = '';
		foreach($files as $file) {
			$buffer .= file_get_contents($file);
		}
		return $buffer;

	}
}