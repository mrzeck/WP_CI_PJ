<?php
/**
Plugin name     : Excel Editor
Plugin class    : excel_editor
Plugin uri      : http://sikido.vn
Description     : Hỗ trợ các thư viện thao tác với excel.
Author          : Nguyễn Hữu Trọng
Version         : 1.0.0
*/
define( 'EXCEL_NAME', 'excel-editor' );

define( 'EXCEL_PATH', plugin_dir_path( EXCEL_NAME ) );

class excel_editor {

    private $name = 'excel_editor';

    public  $ci;

    function __construct() {

        $this->ci =&get_instance();
    }

    public function active() { }

    public function uninstall() { }
}

include 'class/excelEditor.class.php';

include 'class/excelSheet.class.php';

include 'excel-helper.php';

include 'excel-ajax.php';

if(is_admin()) {

    include 'excel-order.php';

    include 'excel-admin.php';
}
