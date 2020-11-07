<?php
if ( ! function_exists( 'excel_admin_navigation' ) ) {

    function excel_admin_navigation() {

        $ci =&get_instance();

        register_admin_subnav('setting', 'Cấu hình xuất excel', 'excel-editor', 'plugins?page=excel-editor', array('callback' => 'excel_editor_index'));
    }

    add_action('init', 'excel_admin_navigation', 10);
}

function excel_editor_index($ci) {

    $views = $ci->input->get('view');

    if($views != 'excel-order') {

        $tab = $ci->input->get('tab');

        include 'admin/views/excel-setting.php';
    }
    else {
        return false;
    }
}

function export_excel_order(){

     $ci =& get_instance();

    if( !is_admin() ) return;

    if( !$ci->template->is_page('plugins_index') ) return;

    if( $ci->input->get('page') != 'excel-editor' ) return;

    $views = $ci->input->get('view');

    if($views == 'excel-order') {

        include 'export/excel-order.php';

        die;
    }
}

add_action('template_redirect', 'export_excel_order');