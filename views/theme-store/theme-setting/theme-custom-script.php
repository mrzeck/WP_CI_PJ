<?php
function theme_custom_script($tag_script = true) {
	$ci =& get_instance();
	ob_start();
	?>
	<script type="text/javascript">
		$(function(){
			<?php do_action('theme_custom_script');?>
		})
	</script>
	<?php do_action('theme_custom_script_no_tag');?>
	<?php

	$script = ob_get_contents();

    ob_end_clean();

    if($tag_script === false) {
        $script = str_replace('<script type="text/javascript">', '', $script);
        $script = str_replace('<script type=\'text/javascript\'>', '', $script);
		$script = str_replace('<script type="text/javascript" defer>', '', $script);
		$script = str_replace('<script type=\'text/javascript\' defer>', '', $script);
		$script = str_replace('<script defer type="text/javascript">', '', $script);
		$script = str_replace('<script defer type=\'text/javascript\'>', '', $script);
		$script = str_replace('</script>', '', $script);
		$script = str_replace('<script>', '', $script);
		return $script;
	}

	echo $script;
}
if(get_option('cms_minify_js', 0) == 0) {
    add_action('cle_footer', 'theme_custom_script');
}
//add script to footer
function footer_script() {
	$ci =& get_instance();
	echo get_option('footer_script');
}
add_action('cle_footer', 'footer_script');
