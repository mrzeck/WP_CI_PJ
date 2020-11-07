<?php
/**
Plugin name     : Tags Manager
Plugin class    : tags_manager
Plugin uri      : http://vitechcenter.com
Description     : Ứng dụng quản lý tag, bạn có thể thêm xóa một hoặc nhiều tag cho sản phẩm và bài viết của bạn dễ dàng và thuận tiện nhất.
Author          : Nguyễn Hữu Trọng
Version         : 1.0.0
*/
define( 'TAG_NAME', 'tags-manager' );

define( 'TAG_PATH', plugin_dir_path( TAG_NAME ) );

class tags_manager {

    private $name = 'tags_manager';
    
    public  $ci;

    public function active() {

        $ci = &get_instance();

        $model = get_model('plugins');

        $page = array(
            'title'     => 'Tag',
            'content'   => '',
        );

        $page_cart      = $ci->insert_page($page);

        tags_database_table_create();
    }

    public function uninstall() {

        $ci = &get_instance();

        tags_database_table_drop();
    }
}

include 'tags-helper.php';

include 'tags-database.php';

if(is_admin()) include 'tags-admin.php';

include 'tags-template.php';


