<?php
function wcmc_action_attribute_delete($res, $table, $id) {

    if(is_numeric($id)) {

        $res = delete_attribute($id);
    }
    else if(have_posts($id)) {

        $res = delete_list_attribute($id);
    }
    
    return $res;
}

add_filter('delete_object_wcmc_attribute', 'wcmc_action_attribute_delete', 1, 3 );

function wcmc_action_attribute_item_delete($res, $table, $id) {

    if(is_numeric($id)) {

        $res = delete_attribute_item($id);
    }
    else if(have_posts($id)) {

        $res = delete_list_attribute_item($id);
    }
    
    return $res;
}

add_filter('delete_object_wcmc_attribute_item', 'wcmc_action_attribute_item_delete', 1, 3 );