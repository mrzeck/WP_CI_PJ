<?php
/**
Plugin name     : User Role Editor
Plugin class    : user_role_editor
Plugin uri      : http://vitechcenter.com
Description     : Trình chỉnh sửa vai trò người dùng SKD plugin cho phép bạn thay đổi vai trò người dùng và khả năng dễ dàng. 
Author          : SKDSoftware Dev Team
Version         : 1.3.0
*/
define( 'URE_NAME', 'user-role-editor' );

define( 'URE_PATH', plugin_dir_path( URE_NAME ) );

class user_role_editor {

    private $name = 'user_role_editor';

    public  $ci;

    function __construct() {

        $this->ci =&get_instance();

        $role = get_role('root');

        $role->add_cap('role_editor');

        $role->add_cap('role_editor_user');
    }

    public function active() { }

    public function uninstall() { }
}

include 'user-role-function.php';

include 'user-role-ajax.php';

include 'user-role-admin.php';