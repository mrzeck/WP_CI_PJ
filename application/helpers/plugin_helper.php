<?php
/**
=======================================================
PLUGIN INSERT FILE
=======================================================
*/
if ( ! function_exists( 'plugin_get_include' ) ) {
	function plugin_get_include( $plugin_name, $template_path = '' , $args = '', $return = false) {

		$ci =& get_instance();

		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path = VIEWPATH.$ci->data['template']->name.'/'.$plugin_name.'/'.$template_path.'.php';

		if( !file_exists($path))  $path 	= $ci->plugin->dir.'/'.$plugin_name.'/template/'.$template_path.'.php';

		if( !file_exists($path))  $path 	= $ci->plugin->dir.'/'.$plugin_name.'/'.$template_path.'.php';

		ob_start();
		
		if(file_exists($path)) {
			include $path;
		}

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}


if ( ! function_exists( 'get_plugins' ) ) {
	/**
	 * get_plugin
	 * Since: 2.0.2
	 */
	function get_plugins( $plugin_name = '' ) {

		$ci =& get_instance();

		$ci->load->library('plugin');

		$info = array();

		if( $plugin_name == '' ) {

			$ci->load->helper('directory');

			$path 		= FCPATH.$ci->plugin->dir;

			$plugins 	= directory_map($path,true);

			$plugin 	= null;

			foreach ($plugins as $key => $plugin_name) {

				$pl = new plugin($plugin_name);

				if( $pl->is_plugin($plugin_name) == false ) continue;

				$info[$plugin_name]['name']        = $pl->name;
				
				$info[$plugin_name]['description'] = $pl->description;
				
				$info[$plugin_name]['version']     = $pl->version;
				
				$info[$plugin_name]['author']      = $pl->author;
				
				$info[$plugin_name]['active']      = $pl->active;
				
				$info[$plugin_name]['label']       = $pl->label;
			}
		}
		else {

			$pl = new plugin( $plugin_name );

			if( $pl->is_plugin($plugin_name) == false ) return $info;

			$info['name'] 			= $pl->name;

			$info['description'] 	= $pl->description;

			$info['version'] 		= $pl->version;

			$info['author'] 		= $pl->author;

			$info['active'] 		= $pl->active;

			$info['label'] 			= $pl->label;
		}

		

		return $info;
	}
}
/**
 * Get Headers function
 * @param str #url
 * @return array
 */
function getHeaders($url)
{
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_NOBODY, true );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
	curl_setopt( $ch, CURLOPT_HEADER, false );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt( $ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "/".SPATH."uploads/cacert.pem");
	curl_exec( $ch );
	$headers = curl_getinfo( $ch );
	curl_close( $ch );
	return $headers;
}

/**
 * Download
 * @param str $url, $path
 * @return bool || void
 */
function download($url, $path)
{
	# open file to write
	$fp = fopen ($path, 'w+');
	# start curl
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	# set return transfer to false
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
	curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	# increase timeout to download big file
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
	# write data to local file
	curl_setopt( $ch, CURLOPT_FILE, $fp );
	# execute curl
	curl_exec( $ch );
	# close curl
	curl_close( $ch );
	# close local file
	fclose( $fp );

	if (filesize($path) > 0) return true;
}

/**
 * Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
 *
 * @since 2.8.0
 *
 * @param string $file The filename of the plugin (__FILE__).
 * @return string the filesystem path of the directory that contains the plugin.
 */
function plugin_dir_path( $name = '') {
	$ci =& get_instance();
	return $ci->plugin->get_path($name);
}