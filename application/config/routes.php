<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
require_once( BASEPATH .'database/DB'. EXT );

$language 	= array('vi' => 'vi');

$db 	=& DB();

$query 	= $db->get_where( 'cle_system', array('option_name' => 'language'));

$result = $query->result();

if( count($result) != 0 ) {
	
	$language 	= unserialize($result[0]->option_value);

	$language 	= array_keys($language);
}


$uri_string = '';

$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
{
	$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
}
elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
{
	$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
}

if (strncmp($uri, '?/', 2) === 0)
{
	$uri = substr($uri, 2);
}

$parts = preg_split('#\?#i', $uri, 2);

$uri   = $parts[0];

$uri   = str_replace(array('//', '../'), '/', trim($uri, '/'));

$slug  = explode( '/', $uri );

$slug = ( count($slug) && is_array($slug) )?(in_array($slug[0], $language) !== false)?(isset($slug[1]))?$slug[1]:'trang-chu':$slug[0]:'';

/*========================================================================
 *				FRONTEND
 *=======================================================================*/

$route[URL_PRODUCT]   			= 'frontend_products/products/index';

$route['search']   		= 'frontend_home/home/search';

$route['close']   		= 'frontend_home/home/close';

$route['password']   	= 'frontend_home/home/password';

$route['404']   		= 'frontend_home/home/page_404';

$route['watermark/(:any)']   = 'frontend_home/home/watermark/$1';

$modules['frontend'] = array( URL_HOME => 'home');

foreach ($modules['frontend'] as $key => $val) {
	$route[$key]                                      = 'frontend_'.$val.'/'.$val.'/index';
	$route[$key.'/([a-zA-Z0-9+-]+)'] 				  = 'frontend_'.$val.'/'.$val.'/$1';
	$route[$key.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)'] = 'frontend_'.$val.'/'.$val.'/$1/$2';
}

$route['tai-khoan']                                   = 'frontend_user/user/index';
$route['tai-khoan/([a-zA-Z0-9+-]+)']                  = 'frontend_user/user/index/$1';
$route['tai-khoan/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)'] = 'frontend_user/user/index/$1/$2';

$slug = trim($slug,'?');

$slug = explode('?', $slug);

$slug = array_shift($slug);

$query 	= $db->get_where( 'cle_routes', array('slug' => $slug));

$result = $query->result();

if(count($result)) {
	
	foreach( $result as $row ) {

		if(isset($route[ $row->slug ])) continue;

		if( $row->callback == null && !isset($route[ $row->slug ]) ) {

			$route[ $row->slug ]  = $row->controller.$row->slug;

			$route[ $row->slug.'/([a-zA-Z0-9+-]+)' ]  = $row->controller.$row->slug.'/$1';
		}
		else {
			$route[ $row->slug ]  = $row->controller.$row->callback;
		}
	}

} else {
	if(!isset($route[ $slug ])) $route[ $slug ]  = 'frontend_post/post/detail/'.$slug;
}


foreach ($language as $key_lang => $lang)  
{
	foreach ($route as $k => $val) 
	{
		$route[$lang.'/'.$k] = $val;
	}
}

/*========================================================================
 *				BACKEND
 *=======================================================================*/
//Primary module
$modules['backend'] = array('home', 'user','theme','plugins', 'page' ,'post','products','gallery');

//Module SUB
$modules['backend_child'] = array(
	'theme' 		=> array('menu','widgets'),
	'user' 			=> array('group'),
	'post'			=> array('post_categories'),
	'products'		=> array('products_categories'),
);

$route[URL_ADMIN]          	= 'backend_home/home/index';

$route[URL_ADMIN.'/ajax'] 	= 'backend_home/home/ajax';

$route[URL_ADMIN.'/system'] = 'backend_home/home/system';

$route[URL_ADMIN.'/login'] 	= 'backend_user/user/login';

foreach ($modules['backend_child'] as $key => $val) {

	foreach ($val as $value) {

		$route[URL_ADMIN.'/'.$key.'/'.$value]                                                  		= 'backend_'.$key.'/'.$value.'/index';

		$route[URL_ADMIN.'/'.$key.'/'.$value.'/([a-zA-Z0-9+-_]+)']                                   = 'backend_'.$key.'/'.$value.'/$1';

		$route[URL_ADMIN.'/'.$key.'/'.$value.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)']                  = 'backend_'.$key.'/'.$value.'/$1/$2';

		$route[URL_ADMIN.'/'.$key.'/'.$value.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)'] = 'backend_'.$key.'/'.$value.'/$1/$2/$3';
	}
}

foreach ($modules['backend'] as $key => $val) {

	$route[URL_ADMIN.'/'.$val]                                                       	= 'backend_'.$val.'/'.$val.'/index';

	$route[URL_ADMIN.'/'.$val.'/([a-zA-Z0-9+-_]+)']                                   	= 'backend_'.$val.'/'.$val.'/$1';

	$route[URL_ADMIN.'/'.$val.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)']                  	= 'backend_'.$val.'/'.$val.'/$1/$2';

	$route[URL_ADMIN.'/'.$val.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)'] = 'backend_'.$val.'/'.$val.'/$1/$2/$3';
}

$route['404_override']                   = '';

$route['default_controller']             = 'frontend_home/home/index';

// print_r($route);
/* End of file routes.php */
/* Location: ./application/config/routes.php */