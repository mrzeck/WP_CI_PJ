<?php
include 'admin/wcmc-from.php';

include 'admin/wcmc-navigation.php';

include 'admin/wcmc-menu.php';

include 'admin/wcmc-setting.php';

include 'admin/wcmc-table.php';

include 'admin/wcmc-action-bar.php';

include 'admin/wcmc-roles.php';

include 'admin/wcmc-suppliers.php';

//Xóa sản phẩm
function wcmc_action_product_delete($res, $table, $id) {

    if(is_numeric($id)) {

        $res = delete_product($id);
    }
    else if(have_posts($id)) {

        $res = delete_list_product($id);
    }
    
    return $res;
}

add_filter('delete_object_products', 'wcmc_action_product_delete', 1, 3 );

//Xóa danh mục sản phẩm
function wcmc_action_products_categories_delete($res, $table, $id) {

    if(is_numeric($id)) {

        $res = wcmc_delete_category($id);
    }
    else if(have_posts($id)) {

        $res = wcmc_delete_list_category($id);
    }
    
    return $res;
}

add_filter('delete_object_products_categories', 'wcmc_action_products_categories_delete', 1, 3 );