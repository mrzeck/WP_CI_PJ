<?php
include 'include/wcmc-helper-metadata.php';

include 'include/wcmc-helper-product.php';

include 'include/wcmc-helper-suppliers.php';

if ( ! function_exists( 'wcmc_get_template' ) ) {
	
	function wcmc_get_template( $template_path = '' , $args = '', $return = false ) {

		$ci =& get_instance();

		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path = VIEWPATH.$ci->data['template']->name.'/woocommerce/'.$template_path.'.php';

		if( !file_exists($path) ) $path = $ci->plugin->dir.'/woocommerce/template/'.$template_path.'.php';

		ob_start();

		include $path;

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

if ( ! function_exists( 'wcmc_get_include' ) ) {

	function wcmc_get_include( $template_path = '' , $args = '', $return = false) {

		$ci =& get_instance();

		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path 	= $ci->plugin->dir.'/woocommerce/'.$template_path.'.php';

		if( !file_exists($path) ) $path = $ci->plugin->dir.'/woocommerce/'.$template_path.'.php';

		ob_start();

		include $path;

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

if( !function_exists('wcmc_get_template_version') ) {
	/**
	 * Print a single notice immediately.
	 * @since 2.3.2
	 */
	function wcmc_get_template_version() {

		$ci =& get_instance();
		
		$path = VIEWPATH.$ci->data['template']->name.'/woocommerce/version.php';

		$version = '1.0.0';

		if(!file_exists($path)) {

			$path = $ci->plugin->dir.'/woocommerce/template/version.php';

			if(!file_exists($path)) {

				return $version;
			}
		}

		$string = file($path);

		$count 	= 0;

		foreach ($string as $k => $val) {

			$val 		= trim($val);

			$pos_start  = stripos($val,' * ')+1;

			$pos_end    = strlen($val);

			//Template name
			if(strpos($val,'@version',0) 	!== false) {
				$version 	= trim(substr($val, $pos_start, $pos_end)); $count++;
			}
		}

		$version = str_replace('@version','', $version);
		
		$version = trim($version);

		return $version;
	}
}