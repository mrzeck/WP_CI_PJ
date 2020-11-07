<?php
function add_navigation_page() {
    $ci =& get_instance();
    //Home
    register_admin_nav('Dashboard',         'home',     '', '', array( 'icon' => '<img src="views/backend/assets/images/home.png" />' ));

    //page
    if(current_user_can('edit_pages') ) register_admin_nav('Trang nội dung',    'page',     'page', '',array('icon' => '<img src="views/backend/assets/images/icon-page.png" />') );

    //Post
    if(current_user_can('view_posts') ) register_admin_nav('Bài viết', 'post', 'post?post_type=post', '', array('icon' => '<img src="views/backend/assets/images/icon-post.png">'));
    if(current_user_can('view_posts') ) register_admin_subnav('post', 'Bài viết', 'post', 'post?post_type=post');
    if(current_user_can('add_posts') )  register_admin_subnav('post', 'Viêt bài viết', 'post-add',  'post/add?post_type=post');

    //Gallery
    if(current_user_can('edit_gallery')) register_admin_nav('Thư viện', 'gallery', 'gallery', '',array('icon' => '<img src="views/backend/assets/images/icon-gallery.png" />') );

    //Theme
    if(current_user_can('edit_themes'))  register_admin_nav('Giao diện', 'theme',    'theme/option','',array('icon' => '<img src="views/backend/assets/images/icon-theme.png" />'));

    if(current_user_can('switch_themes'))         register_admin_subnav('theme', 'Theme', 'theme','theme');
    if(current_user_can('edit_theme_options'))    register_admin_subnav('theme', 'Cấu hình giao diện',    'option','theme/option');
    if(current_user_can('edit_theme_menus'))      register_admin_subnav('theme', 'Menu',                  'menu','theme/menu');
    if(current_user_can('edit_theme_widgets'))    register_admin_subnav('theme', 'Widget',                'widgets','theme/widgets');
    if(current_user_can('edit_theme_editor'))     register_admin_subnav('theme', 'Editor', 'editor','theme/editor');

    //System
    if(current_user_can('edit_setting') )          register_admin_nav('Hệ Thống',  'system',   'system', '', array('icon' => '<img src="views/backend/assets/images/icon-settings.png" />'));
    if(current_user_can('edit_setting_tinymce'))   register_admin_subnav('system', 'Hệ thống', 'system','system');
    if(current_user_can('edit_setting_cache') )    register_admin_subnav('system', 'Quản lý cache', 'cache','plugins?page=cache', array('callback' => 'cache_manager'));
    if(current_user_can('edit_setting_tinymce'))   register_admin_subnav('system', 'Cấu hình soạn thảo', 'tinymce','plugins?page=tinymce', array('callback' => 'tinymce_editor'));
    if(current_user_can('edit_setting_audit') )    register_admin_subnav('system', 'Nhật ký hoạt động', 'audit-log','plugins?page=audit-log', array('callback' => 'audit_log_manager'));

    //Plugin
    if(current_user_can('edit_plugins')) register_admin_nav('Mở rộng', 'plugins', 'plugins', '',array('icon' => '<img src="views/backend/assets/images/icon-plugin.png" />') );
    
    //User
    $user = get_user_current();

    if( have_posts($user) ) {

        if( current_user_can('list_users') ) {

            register_admin_nav('Thành viên', 'user', 'user','',array('icon' => '<img src="views/backend/assets/images/icon-user.png" />'));

            register_admin_subnav('user', 'Danh sách', 'user_list', 'user','');
        }
        else {
            register_admin_nav('Thành viên', 'user', 'user/edit?view=profile&id='.$user->id,'',array('icon' => '<img src="views/backend/assets/images/icon-user.png" />'));
        }

        register_admin_subnav('user', 'Hồ sơ của bạn', 'user', 'user/edit?view=profile&id='.$user->id,'');
    }
}

add_action('admin_init', 'add_navigation_page');