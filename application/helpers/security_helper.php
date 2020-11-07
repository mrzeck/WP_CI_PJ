<?php
/*---------------------------
 * Kiểm tra dữ liệu dạng object, array
 *---------------------------*/
if(!function_exists('have_posts')) {
	function have_posts($object, $type = null)
	{
		if(is_array($object) || is_object($object)) {

			if(count((array)$object)) {

				if($type == 'array') return (is_array($object))?true:false;

				if($type == 'object') return (is_object($object))?true:false;

				return (is_array($object) || is_object($object))?true:false;
			}
		}
		return false;
	}
}
/*---------------------------
 * Kiểm đang đứng ở admin hay frontend
 *---------------------------*/
if(!function_exists('superadmin')) {
	function superadmin($id = 0)
	{
		$ci =& get_instance();
		GLOBAL $superadmins;
		$listadmin = explode(',',$superadmins);
		if($id == 0) $id = $ci->data['user']->id;
		if(in_array($id, $listadmin) !== false) return true;
		return false;
	}
}
/**
 * random chuỗi ký tự ngẫu nhiên
 * */
if(!function_exists('random')) 
{
	function random($leng = 168, $char = FALSE){
		if($char == FALSE) $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
		else $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		mt_srand((double)microtime() * 1000000);
		$salt = '';
		for ($i=0; $i<$leng; $i++){
			$salt = $salt . substr($s, (mt_rand()%(strlen($s))), 1);
		}
		return $salt;
	}
}
/**
 * xóa bỏ ký tự html
 * */
if(!function_exists('removeHtmlTags')) {
	function removeHtmlTags($str = '')
	{
		return preg_replace('([!"#$&’\(\)\*\+,\-\./0123456789:;<=>\?ABCDEFGHIJKLMNOPQRSTUVWXYZ\[\\\]\^_‘abcdefghijklmnopqrstuvwxyz\{\|\}~¡¢£⁄¥ƒ§¤“«‹›ﬁﬂ–†‡·¶•‚„”»…‰¿`´ˆ˜¯˘˙¨˚¸˝˛ˇ—ÆªŁØŒºæıłøœß÷¾¼¹×®Þ¦Ð½−çð±Çþ©¬²³™°µ ÁÂÄÀÅÃÉÊËÈÍÎÏÌÑÓÔÖÒÕŠÚÛÜÙÝŸŽáâäàåãéêëèíîïìñóôöòõšúûüùýÿž€\'])', '', strip_tags($str));
	}
}

