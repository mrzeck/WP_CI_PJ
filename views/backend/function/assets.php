<?php
if(!function_exists('admin_add_style_header'))  {

	function admin_add_style_header() {
		$ci =& get_instance();
        /** Tất cả page */
        admin_register_style('googfont',    'https://fonts.googleapis.com/css?family=Roboto:100%2C100italic%2C300%2C300italic%2C400%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|Roboto+Slab:100%2C300%2C400%2C700&subset=greek-ext%2Cgreek%2Ccyrillic-ext%2Clatin-ext%2Clatin%2Cvietnamese%2Ccyrillic');
        admin_register_style('reset',       $ci->template->get_assets().'css/reset.css');
        //bootstrap-editable
        admin_register_style('bootstrap-editable',     $ci->template->get_assets().'/bootstrap-editable/css/bootstrap-editable.css');
        admin_register_style('bootstrap',   $ci->template->get_assets().'css/bootstrap.min.css');
        
        admin_register_script('jquery',     $ci->template->get_assets().'js/jquery.js');
        admin_register_style('font_awesome',PLUGIN.'/font-awesome/css/all.min.css');
        /** Login page */
        admin_register_style('login',       $ci->template->get_assets().'css/login.css','user_login');
        //icheck
        admin_register_style('icheck',          PLUGIN.'/icheck/skins/square/blue.css');

        admin_register_script('icheck',         PLUGIN.'/icheck/icheck.min.js');
        //ToastMessages
        admin_register_style('ToastMessages',   PLUGIN.'/ToastMessages/jquery.toast.css');

        admin_register_script('ToastMessages',  PLUGIN.'/ToastMessages/jquery.toast.js');
        //fancybox
        admin_register_style('fancybox',        PLUGIN.'/fancybox-3.0/jquery.fancybox.css');

        admin_register_script('fancybox',       PLUGIN.'/fancybox-3.0/jquery.fancybox.js');
        //bootstrap-confirm-delete
        admin_register_style('bootstrap-confirm-delete',   PLUGIN.'/bootstrap-confirm-delete/bootstrap-confirm-delete.css');

        admin_register_script('bootstrap-confirm-delete',  PLUGIN.'/bootstrap-confirm-delete/bootstrap-confirm-delete.js');
        //colorpicker
        admin_register_style('colorpicker',     PLUGIN.'/colorpicker/css/bootstrap-colorpicker.min.css');

        admin_register_script('colorpicker',    PLUGIN.'/colorpicker/js/bootstrap-colorpicker.min.js');

         admin_register_script('sortable',       $ci->template->get_assets().'/add-on/sortable/sortable.min.js');

         admin_register_style('theme',       $ci->template->get_assets().'css/style.css?v='.cms_info('version'));
    }

    add_action('cle_enqueue_style', 'admin_add_style_header');
}

if(!function_exists('admin_add_style_footer'))  {

	function admin_add_style_footer() {

		$ci =& get_instance();
        admin_register_script('jquery-ui',      $ci->template->get_assets().'js/jquery-ui.js?v=1.0');

        admin_register_script('bootstrap',      $ci->template->get_assets().'js/bootstrap.min.js');

        admin_register_script('ajax',           $ci->template->get_assets().'js/ajax.js');

        admin_register_script('plugin',           $ci->template->get_assets().'js/plugin.js?v='.cms_info('version'), array('plugins_download', 'plugins_widget', 'widgets_index'));
        //sortable
        admin_register_script('sortable',       PLUGIN.'/sortable/jquery.mjs.nestedSortable.js', array('menu_index'));
        //tinymce
        admin_register_script('tinymce',        PLUGIN.'/tinymce/tinymce.min.js');
        
        //custom script
        admin_register_script('user',       $ci->template->get_assets().'js/user.js','user');
        admin_register_script('theme',      $ci->template->get_assets().'js/theme.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('menu',      $ci->template->get_assets().'js/menu.js', array('menu_index'));
        admin_register_script('widgets',   $ci->template->get_assets().'js/widget.js?v='.cms_info('version'), array('widgets_index'));
        admin_register_script('gallery',   $ci->template->get_assets().'js/gallery.js?v='.cms_info('version'), array('gallery_index'));
	
        //code
        admin_register_style('code',          PLUGIN.'/codemirror/lib/codemirror.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/lib/codemirror.js', array('theme_index','theme_editor','theme_option','home_system'));

        admin_register_style('code',          PLUGIN.'/codemirror/addon/hint/show-hint.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_style('code',          PLUGIN.'/codemirror/addon/dialog/dialog.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_style('code',          PLUGIN.'/codemirror/addon/display/fullscreen.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_style('code',          PLUGIN.'/codemirror/addon/search/matchesonscrollbar.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_style('code',          PLUGIN.'/codemirror/theme/icecoder.css', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_style('code',          PLUGIN.'/codemirror/theme/darkpastel.css', array('theme_index','theme_editor','theme_option','home_system'));

        

        admin_register_script('code',        PLUGIN.'/codemirror/mode/css/css.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/mode/xml/xml.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/mode/javascript/javascript.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/mode/htmlmixed/htmlmixed.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/mode/clike/clike.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/mode/php/php.js', array('theme_index','theme_editor','theme_option','home_system'));

        admin_register_script('code',        PLUGIN.'/codemirror/addon/hint/show-hint.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/hint/css-hint.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/hint/javascript-hint.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/dialog/dialog.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/display/fullscreen.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/search/searchcursor.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/search/search.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/scroll/annotatescrollbar.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/search/matchesonscrollbar.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/edit/closebrackets.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/addon/edit/matchbrackets.js', array('theme_index','theme_editor','theme_option','home_system'));
        admin_register_script('code',        PLUGIN.'/codemirror/keymap/sublime.js', array('theme_index','theme_editor','theme_option','home_system'));

        admin_register_style('tagjs',      $ci->template->get_assets().'add-on/tag_bootstrap/bootstrap-tagsinput.css');
        admin_register_script('tagjs',     $ci->template->get_assets().'add-on/tag_bootstrap/bootstrap-tagsinput.js');

        //datetime
        admin_register_style('datepicker',     PLUGIN.'/air-datepicker/datepicker.min.css');
        admin_register_script('datepicker',    PLUGIN.'/air-datepicker/datepicker.min.js');
        admin_register_script('datepicker',    PLUGIN.'/air-datepicker/i18n/datepicker.vi.js');

        admin_register_script('script',         $ci->template->get_assets().'js/script.js?v='.cms_info('version'));

        admin_register_script('bootstrap-editable',    $ci->template->get_assets().'/bootstrap-editable/js/bootstrap-editable.min.js');
        
        admin_register_script('SerializeJSON',    $ci->template->get_assets().'/js/SerializeJSON.js');
        
        // global $wp_filter;

        // show_r($wp_filter);

    }
    add_action('cle_enqueue_script', 'admin_add_style_footer');
}