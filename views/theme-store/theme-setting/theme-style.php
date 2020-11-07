<?php
if(!function_exists('cle_add_style_header'))  {
    
	function cle_add_style_header() {
		$ci =& get_instance();

        $add_on_url = $ci->template->get_assets().'add-on/';

        $font_family = get_font_family();

        $font_googles = '';

        foreach ($font_family as $key => $font) {
            if($font['type'] == 'google') $font_googles .= $font['load'].'|';
        }

        $font_googles = trim($font_googles, '|');

        //google fonts
        cle_register_style('googfont',      'https://fonts.googleapis.com/css?family='.$font_googles);
        //reset
        cle_register_style('reset',         $ci->template->get_assets().'css/reset.css');

        //bootstrap
        cle_register_style('bootstrap',     $add_on_url.'bootstrap-3.3.7/css/bootstrap.min.css');

        //font-awesome
        cle_register_style('font-awesome',  base_url().'scripts/font-awesome/css/all.min.css',null, ['webfonts' => base_url().'scripts/font-awesome']);
        
        //custom style
        cle_register_style('style',         $ci->template->get_assets().'css/style.css');

        //animate
        cle_register_style('animate',       $add_on_url.'animate/animate.css', null, ['all' => $add_on_url.'animate']);

        //dropdownhover
        cle_register_style('bootstrap-dropdownhover', $add_on_url.'bootstrap-dropdownhover/bootstrap-dropdownhover.min.css');

        //owl
        cle_register_style('owlcarousel2',  $add_on_url.'owlcarousel2-2.3.0/assets/owl.carousel.min.css');

        cle_register_style('owlcarousel2',  $add_on_url.'owlcarousel2-2.3.0/assets/owl.theme.default.min.css');
        
        //ihover
        cle_register_style('ihover',       $add_on_url.'ihover/ihover.min.css');

        //fancybox
        cle_register_style('fancybox',       $add_on_url.'fancybox-3/jquery.fancybox.min.css');
    }

    add_action('cle_enqueue_style', 'cle_add_style_header');
}

if(!function_exists('cle_add_script_footer'))  {

	function cle_add_script_footer() {

		$ci =& get_instance();

        $add_on_url = $ci->template->get_assets().'add-on/';

        cle_register_script('bootstrap',                $add_on_url.'bootstrap-3.3.7/js/bootstrap.min.js');

        cle_register_script('jquery-ui',                $ci->template->get_assets().'js/jquery-ui-1.9.1.min.js');

        cle_register_script('script',                   $ci->template->get_assets().'js/script.js');

        //wow
        cle_register_script('wow', $ci->template->get_assets().'add-on/wow/wow.min.js');

        //bootstrap-dropdownhover
        cle_register_script('bootstrap-dropdownhover', $add_on_url.'bootstrap-dropdownhover/bootstrap-dropdownhover.js');

        //owl
        cle_register_script('owlcarousel2',             $add_on_url.'owlcarousel2-2.3.0/owl.carousel.min.js');
    
        //fancybox
        cle_register_script('fancybox',       $add_on_url.'fancybox-3/jquery.fancybox.min.js');

        cle_register_script('lazyload',       $add_on_url.'lazyload/lazyload.min.js');
    }

    add_action('cle_enqueue_script', 'cle_add_script_footer');
}