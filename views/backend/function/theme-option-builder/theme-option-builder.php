<?php
if(!is_super_admin()) return false;

include 'theme-option-builder-ajax.php';
/*================ LOAD FILE ASSET ==========================*/
if(!function_exists('skd_theme_option_style_header'))  {
    
    function skd_theme_option_style_header() {

        $ci =& get_instance();

        $active = get_option('theme_current');
        ?>
        <link rel="stylesheet" href="views/<?php echo $active;?>/assets/css/admin/store-widget.css">
        <link rel="stylesheet" href="<?php echo PLUGIN;?>/icheck/skins/square/blue.css">
        <link rel="stylesheet" href="<?php echo PLUGIN;?>/colorpicker/css/bootstrap-colorpicker.min.css">
        <link rel="stylesheet" href="<?php echo PLUGIN;?>/ToastMessages/jquery.toast.css">
        <link rel="stylesheet" href="<?php echo PLUGIN;?>/air-datepicker/datepicker.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css">
        <?php
    }

    add_action('cle_header', 'skd_theme_option_style_header');
}

if(!function_exists('skd_theme_option_script_footer'))  {

    function skd_theme_option_script_footer() {

        $ci =& get_instance();

        $active = get_option('theme_current');
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js" defer></script>
        <script src="<?php echo PLUGIN;?>/air-datepicker/datepicker.min.js" defer></script>
        <script src="<?php echo PLUGIN;?>/air-datepicker/i18n/datepicker.vi.js" defer></script>
        <script src="<?php echo PLUGIN;?>/colorpicker/js/bootstrap-colorpicker.min.js" defer></script>
        <script src="<?php echo PLUGIN;?>/icheck/icheck.min.js" defer></script>
        <script src="<?php echo PLUGIN;?>/tinymce/tinymce.min.js" defer></script>
        <script src="<?php echo PLUGIN;?>/ToastMessages/jquery.toast.js" defer></script>
        <script src="<?php echo 'views/'.$active;?>/assets/js/store-widget.js" defer></script>
        <script src="<?php echo base_url();?>views/backend/assets/js/SerializeJSON.js" defer></script>
        <script src="<?php echo base_url();?>views/backend/assets/js/script.js" defer></script>
        <?php
    }

    add_action('cle_footer', 'skd_theme_option_script_footer');
}
/*================ LOAD HTML ==========================*/
if(!function_exists('skd_theme_option_builder'))  {

    function skd_theme_option_builder() {

        $ci =& get_instance();

        include 'html/theme-option-builder-content.php';
    }

    add_action('cle_footer', 'tiny' );

    add_action('cle_footer', 'skd_theme_option_builder');
}