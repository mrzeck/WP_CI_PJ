<?php
if ( ! function_exists( 'ure_admin_navigation' ) ) {

    function ure_admin_navigation() {

        $ci =&get_instance();

        if( current_user_can('role_editor') ) register_admin_subnav('user', 'Phân quyền', 'user_role_editor','plugins?page=user_role_editor',array('callback' => 'user_role_editor'));
    }

    add_action('init', 'ure_admin_navigation', 10);
}

if ( ! function_exists( 'user_role_editor' ) ) {

    function user_role_editor() {

        $ci =&get_instance();

        $user = get_user_current();

        $role 			= skd_roles()->get_names();

        if( is_super_admin( $user->id ) ) $role_name_default = 'root';
        else $role_name_default = 'administrator';

        $role_name 		= ( $ci->input->get('role') == '' ) ? $role_name_default : $ci->input->get('role');

        $role_current 	= get_role( $role_name )->capabilities;
        
        $role_label = user_role_editor_label();

        $role_group = user_role_editor_group();


        if( !is_super_admin() && $role['root'] ) unset($role['root']);

        include 'html/user_role_editor.php';
    }
}

if ( ! function_exists( 'user_role_tab' ) ) {

    function user_role_tab( $args ) {

        if( current_user_can('role_editor_user') ) {

            $args['role'] = array(
            	'label' => __('Phân Quyền'),
            	'callback' => 'user_role_tab_callback'
            );

        }

        return $args;
    }

    add_filter('admin_my_action_links', 'user_role_tab' );
}

if ( ! function_exists( 'user_role_tab_callback' ) ) {

    function user_role_tab_callback( $user ) {

    	$role 			= skd_roles()->get_names();

        $role_name 		= user_role( $user->id );

    	$role_name 		=  array_pop( $role_name );

        if( is_super_admin() ) {
            
            $role_name_default = 'root';
        }
        else $role_name_default = 'administrator';

    	$role_all 		= get_role( $role_name_default )->capabilities;

        $role_default   = get_role( $role_name )->capabilities;

        $role_current 	= get_role_caps( $user->id );

    	$role_label 	= user_role_editor_label();

        $role_group     = user_role_editor_group();

        if($role_name_default == 'administrator') {

            foreach ($role_group as &$role_group_value) {

                foreach ($role_group_value['capbilities'] as $key => $cap) {
                    
                    if(empty($role_all[$cap])) unset($role_group_value['capbilities'][$key]);
                }
            }
        }

        if( !is_super_admin() && $role['root'] ) unset($role['root']);

        include 'html/user_role_tab.php';
    }
}