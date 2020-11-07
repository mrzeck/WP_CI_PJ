<?php
if(!function_exists('cms_info'))  {

    function cms_info( $str = '' ) {

        global $skd_version, $skd_db_version;

        $info = array(
            'version'    => $skd_version,
            'db_version' => $skd_db_version,
        );

        if( !empty($info[$str])) $info = $info[$str];
    
        return $info;
    }
}

if(!function_exists('show_r'))  {
	function show_r($param = array()) {
		echo '<pre>'; print_r($param); echo '</pre>';
	}
}

if(!function_exists('debug'))  {
	function debug($param = array()) {
		echo '<pre>'; print_r($param); echo '</pre>';
		echo var_dump($param);
		$ci =& get_instance();
		$ci->load->library('profiler');
		echo "<html><body>";
		echo $ci->profiler->run();
		echo '</body></html>';
		die;
	}
}

/*---------------------------
 * Phân trang
 *---------------------------*/
if(!function_exists('pagination'))  {
	
	function pagination($total = 10, $url, $number = 10, $page = 0) {

		$ci =& get_instance();

        if($page == 0) {
            if((int)$ci->input->get('paging') != 0) $page = (int)$ci->input->get('paging');
            else $page = (int)$ci->input->get('page');
        }

		$config  = array (
		    'current_page'  => ($page != 0) ? $page : 1, // Trang hiện tại
		    'total_rows'    => $total, // Tổng số record
		    'number'		=> $number,
		    'url'           => $url,
		);

		return new paging($config);
	}
}
/*---------------------------
 * Load model
 *---------------------------*/
if(!function_exists('get_model')){
	function get_model($model = '', $path = 'frontend') {

        $ci =& get_instance();

        if($model == 'post_categories') {

            $path = 'backend_post';
        }
        else if($model == 'products_categories') {

            $path = 'backend_products';
        }
        else {

            if($path == 'frontend' || $path == 'backend') 	$path 	= $path.'_'.$model;
        }

        if(strpos($model, '_model') === false) $model 	= $model.'_model';

        $path_model = $path.'/'.$model;

        if(!isset($ci->$model)) { $ci->load->model($path_model); }
        
		return $ci->$model;
	}
}

/*---------------------------
 * cắt chuổi theo từ
 *---------------------------*/
if(!function_exists('str_word_cut'))
{
	function str_word_cut($string,$num){

	    $limit = $num - 1 ;

	    $str_tmp = '';

	    $arrstr = explode(" ", $string);

	    if ( count($arrstr) <= $num ) { return $string; }

	    if (!empty($arrstr)) {
	        for ( $j=0; $j< count($arrstr) ; $j++) {
	            $str_tmp .= " " . $arrstr[$j];
	            if ($j == $limit)
	                    { break; }
	        }
	    }
	    return $str_tmp.'...';
	}
}
/*---------------------------
 * Lấy Youtube ID
 *---------------------------*/
if(!function_exists('getYoutubeID')){
	function getYoutubeID($youtube = '')
	{
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube, $matches);

	  	if (!empty($matches)) {
	   		return $matches[1];
	  	}

	  	return null;
	}
}

if(!function_exists('is_skd_error')){
    /**
     * @since 2.2.0
     */
    function is_skd_error( $thing ) {
        return ( $thing instanceof SKD_Error );
    }
}

if(!function_exists('is_serialized')){
    /**
     * @since 2.2.0
     */
    function is_serialized( $data, $strict = true ) {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace )
                return false;
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 )
                return false;
            if ( false !== $brace && $brace < 4 )
                return false;
        }
        $token = $data[0];
        switch ( $token ) {
            case 's' :
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }
        return false;
    }
}

if(!function_exists('add_magic_quotes')){
    /**
     * @since 2.2.0
     */
    function add_magic_quotes( $array ) {
        foreach ( (array) $array as $k => $v ) {
            if ( is_array( $v ) ) {
                $array[$k] = add_magic_quotes( $v );
            } else {
                $array[$k] = addslashes( $v );
            }
        }
        return $array;
    }
}