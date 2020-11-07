<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists('cache_libary') ) {
	/**
	 * @since 2.0.0
	 */
	function cache_libary () {
		if( !class_exists('cache') ) { $ci = &get_instance(); $ci->load->driver('cache', array('adapter' => 'file')); }
	}

}

if( !function_exists('cache_exists') ) {
	/**
	 * @since 2.0.0
	 */
	function cache_exists ( $cache_id = null ) {

		if( $cache_id == null || empty($cache_id) ) return false;

		cache_libary();

		$ci = &get_instance();

		if( $ci->cache->get($cache_id) === false ) return false;

		return true;
	}
}

if( !function_exists('get_cache') ) {
	/**
	 * @since 2.0.0
	 */
	function get_cache ( $cache_id = null ) {

		if( $cache_id == null || empty($cache_id) ) return false;

		cache_libary();

		$ci = &get_instance();

		return $ci->cache->get($cache_id);
	}
}

if( !function_exists('save_cache') ) {
	/**
	 * @since 2.0.0
	 */
	function save_cache ( $cache_id = null, $cache_value = null, $cache_time = TIME_CACHE ) {

		if( $cache_id == null || empty($cache_id) ) return false;

		cache_libary();

		$ci = &get_instance();

		$ci->cache->save($cache_id, $cache_value, $cache_time );

		return true;
	}
}

if( !function_exists('delete_cache') ) {
	/**
	 * @since 2.0.0
	 */
	function delete_cache ( $cache_id = null, $prefix = false ) {

		if( $cache_id == null || empty($cache_id) ) return false;

		cache_libary();

		$ci = &get_instance();

		if( $prefix == false && cache_exists($cache_id) ) $ci->cache->delete($cache_id);

		if( $prefix == true ) {

			$list_cache = scandir('views/cache');

			foreach ($list_cache as $key => $value) {
				if( strpos( $value, $cache_id ) !== false ) unlink( 'views/cache/'.$value );
			}
		}

		return true;
	}
}