<?php
if(!function_exists('fbm_fanpage')) 
{
	/**
	 * [fbm_fanpage hiển thị fanpgae facebook]
	 * @param  array  $param [description]
	 * @return [type]        [description]
	 */
	function fbm_fanpage($param = array()) {
		
		if(!isset($param['height'])) $param['height'] = '300px';

		if(!isset($param['small'])) $param['small'] = 'false';

		if(!isset($param['cover'])) $param['cover'] = 'false';

		if(!isset($param['tab'])) $param['tab'] = 'timeline';

		echo '<div class="fb-page"
		data-height="'.$param['height'].'" 
		data-href="'.$param['href'].'&__mref=message_bubble" 
		data-tabs="'.$param['tab'].'" data-small-header="false" 
		data-adapt-container-width="true" 
		data-hide-cover="'.$param['cover'].'" 
		data-show-facepile="true"><div class="fb-xfbml-parse-ignore">
		<blockquote cite="'.$param['href'].'&__mref=message_bubble">
		<a href="'.$param['href'].'&__mref=message_bubble">FACEBOOK</a></blockquote></div></div>';
	}
}