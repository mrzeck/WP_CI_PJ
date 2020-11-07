<?php
/* default hook */
add_action('admin_header', 	'cle_enqueue_style');
add_action('admin_footer', 	'cle_enqueue_script');

add_action('cle_header', 	'cle_enqueue_style');
add_action('cle_footer', 	'cle_enqueue_script');