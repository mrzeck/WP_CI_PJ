<?php
//Get full url
if(!function_exists('fullurl')){
	function fullurl($base64 = FALSE){
		$currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
		$currentURL .= $_SERVER['SERVER_NAME'];
		if($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443'){
			$currentURL .= ':'.$_SERVER['SERVER_PORT'];
		}
		$currentURL .= $_SERVER['REQUEST_URI'];
		if($base64 == FALSE){
			return $currentURL;
		}
		else{
			return base64_encode($currentURL);
		}
	}
}


if(!function_exists('removeutf8')){
	function removeutf8($value = NULL){
		$chars = array(
			'a'	=>	array('ấ','ầ','ẩ','ẩ','ẫ','ấ','ậ','ậ', 'Ấ','Ầ','Ấ','Ẩ','Ẩ','Ẫ','Ẫ','Ậ','Ậ','ắ','ăn','ằ','ắ','ẳ','ẳ','ẵ','ẵ','ặ','ặ','Ắ','Ă','Ằ','Ắ','Ẳ','Ẳ','Ẵ','Ẵ','Ặ','Ặ','á','á','à','à','ả','ả','ã','ã','ạ','ạ','â','ă','Á','Á','À','À','Ả','Ả','Ã','Ã','Ạ','Ạ','Â','Â','Ă','Ă'),
			'e' =>	array('ế','ề','ề','ế','ể','ể','ễ','ễ','ệ','ệ','Ế','Ế','Ề','Ề','Ể','Ể','Ễ','Ễ','Ệ','Ệ','é','é','è','è','ẻ','ẻ','ẽ','ẽ','ẹ','ẹ','ê','ê','É','É','È','È','Ẻ','Ẻ','Ẽ','Ẽ','Ẹ','Ẹ','Ê','Ế'),
			'i'	=>	array('í','í','ì','ì','ỉ','ỉ','ĩ','ĩ','ị','ị','I','Í','Í','Ì','Ì','Ỉ','Ỉ','Ĩ','Ĩ','Ị','Ị'),
			'o'	=>	array('ố','ô','ồ','ồ','ổ','ổ','ỗ','ỗ','ộ','ộ','Ố','Ố','Ồ','Ồ','Ổ','Ổ','Ổ','Ỗ','Ỗ','Ộ','Ộ','ớ','ớ','ờ','ờ','ở','ở','ỡ','ỡ','ợ','ợ','Ớ','Ớ','Ờ','Ờ','Ở','Ở','Ỡ','Ỡ','Ợ','Ợ','ó','ó','ò','ò','ỏ','ỏ','õ','õ','ọ','ọ','ô','ô','ơ','ơ','Ó','Ó','Ò','Ò','Ỏ','Ỏ','Õ','Õ','Ọ','Ọ','Ô','Ô','Ơ','Ơ'),
			'u'	=>	array('ứ','ứ','ừ','ừ','ử','ử','ữ','ữ','ự','ự','Ứ','Ứ','Ừ','Ừ','Ử','Ử','Ữ','Ữ','Ự','Ự','ú','ú','ù','ù','ủ','ủ','ũ','ũ','ụ','ụ','ư','ư','Ú','Ú','Ù','Ù','Ủ','Ủ','Ũ','Ũ','Ụ','Ụ','Ư','Ư'),
			'y'	=>	array('ý','ý','ỳ','ỳ','ỷ','ỷ','ỹ','ỹ','ỵ','ỵ','Ý','Ý','Ỳ','Ỳ','Ỷ','Ỷ','Ỹ','Ỹ','Ỵ','Ỵ'),
			'd'	=>	array('đ','đ','Đ','Đ'),
			''	=>	array(',','.'),
		);
		foreach ($chars as $key => $arr)
			foreach ($arr as $val)
				$value = str_replace($val, $key, $value);
		return $value;
	}
}

if(!function_exists('slug')){
	function slug($value = NULL, $char = '-'){	
		$value = removeutf8($value);
		$value = str_replace('-', ' ', trim($value));
		$value = preg_replace('/[^a-z0-9-]+/i', ' ', $value);
		$value = trim(preg_replace('/\s\s+/', ' ', $value));
		return strtolower(str_replace(' ', $char, trim($value)));
	}
}

if(!function_exists('is_url')){
	function is_url($url){	
		if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) // `i` flag for case-insensitive
			return true; 
		return false; 
	}
}

if(!function_exists('get_url')){

	function get_url( $slug ) {

		$ci =& get_instance();

		return apply_filters( 'get_url', $slug ) ;
	}
}


if(!function_exists('admin_url')){

	function admin_url( $url ) {

		$ci =& get_instance();

		$url = removeHtmlTags( $url );

		$url = base_url().URL_ADMIN.'/'.ltrim($url, '/');

		return apply_filters( 'admin_url', $url ) ;
	}
}

if(!function_exists('is_ssl')){
	/**
     * @since 2.2.0
     */
	function is_ssl() {

	    if ( isset( $_SERVER['HTTPS'] ) ) {

	        if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
	            return true;
	        }
	 
	        if ( '1' == $_SERVER['HTTPS'] ) {
	            return true;
	        }

	    } elseif ( isset($_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
	        return true;
	    }

	    return false;
	}
}