<?php
	$style 	= 'color:'.get_option('product_title_color');

	$url 	= get_url($val->slug);

	$title 	= $val->title;

	printf('<h3 style="%s"><a href="%s" title="%s" style="%s">%s</a></h3>', $style, $url, $title, $style, $title );
?>