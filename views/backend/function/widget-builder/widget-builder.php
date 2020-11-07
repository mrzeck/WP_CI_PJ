<?php if(!is_super_admin() || get_option('cms_widget_builder', 0) == 0) return false;

include 'widget-builder-ajax.php';

/*================ LOAD FILE ASSET ==========================*/
if(!function_exists('widget_builder_script_footer'))  {

    function widget_builder_script_footer() {

        $ci =& get_instance();

        $active = get_option('theme_current');
        ?>
        <script src="<?php echo base_url();?>views/backend/assets/js/widget-builder.js?v=<?php echo cms_info('version');?>" defer></script>
        <?php
    }

    add_action('cle_footer', 'widget_builder_script_footer');
}
/*================ LOAD HTML ==========================*/
if(!function_exists('widget_builder'))  {

    function widget_builder() {

        $ci =& get_instance();

        include 'html/widget-builder-content.php';
    }

    add_action('cle_footer', 'widget_builder');
    add_action('cle_footer', 'tiny' );
}