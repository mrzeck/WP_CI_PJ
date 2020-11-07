<?php 
if( !function_exists('products_categories_metabox_price') ) {
    function products_categories_metabox_price(){
        add_meta_box('price_metabox','Lựa chọn thương hiệu hiển thị','thuonghieu_metabox_callback','products_categories',1);
    }
    add_action( 'init', 'products_categories_metabox_price');
}
if( !function_exists('thuonghieu_metabox_callback') ) {
    /**
     * [hook_metabox_callback nội dung metabox]
     */
    function thuonghieu_metabox_callback( $object ) {
       $a=(wcmc_gets_brand_mutilevel_option  ());
       unset($a['0']);
        $thuonghieu = '';
        if( have_posts($object)) {
            $thuonghieu = get_metadata( 'products_categories', $object->id, 'thuonghieu', true );
        }
        $input = array( 'field' => 'thuonghieu', 'label' => 'Thương hiệu hiển thị','type' => 'select2-multiple', 'options' => $a);
        echo _form($input, $thuonghieu);
       
    }
}

if( !function_exists('product_categories_metabox_thuonghieu_save') ) {
/**
 * [real_metabox_save lưu thông tin metabox khi sau khi lưu dự án]
 */
function product_categories_metabox_thuonghieu_save($post_id, $model) {
    $ci =& get_instance();
    $data = $ci->input->post();
    if($ci->data['module'] == 'products_categories' && isset($data['thuonghieu']) ) {
        $data         = $ci->input->post();
        $currenttable = $model->gettable();
        $metabox = $data['thuonghieu'];
        update_metadata( 'products_categories', $post_id, 'thuonghieu', $metabox );
    }  
    
}
add_action('save_object', 'product_categories_metabox_thuonghieu_save', '', 2);
}

if( !function_exists('product_categories_metabox_thuonghieu_delete') ) {
    /**
     * [wcmc_metabox_product_save thực hiện khi xóa sản phẩm]
     */
    function product_categories_metabox_thuonghieu_delete( $module, $data, $r ) {
        $ci =& get_instance();
        if( $module == 'products' ) {
            $listID = array();
            //xóa object
            if(is_numeric($data)) $listID[] = $data;
            //xóa nhiều dữ liệu
            if(have_posts($data)) $listID   = $data;
            if(have_posts($listID)) {
                foreach ( $listID as $product_id ) {
                delete_metadata( 'products_categories', $product_id, 'thuonghieu' );  
                }
            }
        }
    }
    add_action('ajax_delete_before_success', 'product_categories_metabox_thuonghieu_delete', 10, 3);
}



 ////////////////////////////////////////////////////////////khoảng giá //////////////////////////////////////////////////////////



if( !function_exists('products_categories_metabox_khoanggia') ) {
    function products_categories_metabox_khoanggia(){
        add_meta_box('khoanggia_metabox','Lựa chọn khoảng giá hiển thị','khoanggia_metabox_callback','products_categories',2);
    }
    add_action( 'init', 'products_categories_metabox_khoanggia');
}
if( !function_exists('khoanggia_metabox_callback') ) {
    /**
     * [hook_metabox_callback nội dung metabox]
     */
    function khoanggia_metabox_callback( $object ) {
     $_listPrice = get_option('wcmc_filter_price_option', serialize(array()));
     // show_r($_listPrice)
     $b=array();
     foreach ($_listPrice as $key => $val) {
     	// show_r($val);
         $b[$val['min_price'].'-'.$val['max_price']]=$val['label'];
     }
        $khoanggia = '';
        if( have_posts($object)) {
            $khoanggia = get_metadata( 'products_categories', $object->id, 'khoanggia', true );
        }
        $input = array( 'field' => 'khoanggia', 'label' => 'Khoảng giá hiển thị','type' => 'select2-multiple', 'options' => $b);
        echo _form($input, $khoanggia);
       
    }
}

if( !function_exists('product_categories_metabox_khoanggia_save') ) {
/**
 * [real_metabox_save lưu thông tin metabox khi sau khi lưu dự án]
 */
function product_categories_metabox_khoanggia_save($post_id, $model) {
    $ci =& get_instance();
    $data = $ci->input->post();
    if($ci->data['module'] == 'products_categories' && isset($data['khoanggia']) ) {
        $data         = $ci->input->post();
        $currenttable = $model->gettable();
        $metabox = $data['khoanggia'];
        update_metadata( 'products_categories', $post_id, 'khoanggia', $metabox );
    }  
    
}
add_action('save_object', 'product_categories_metabox_khoanggia_save', '', 2);
}

if( !function_exists('product_categories_metabox_khoanggia_delete') ) {
    /**
     * [wcmc_metabox_product_save thực hiện khi xóa sản phẩm]
     */
    function product_categories_metabox_khoanggia_delete( $module, $data, $r ) {
        $ci =& get_instance();
        if( $module == 'products' ) {
            $listID = array();
            //xóa object
            if(is_numeric($data)) $listID[] = $data;
            //xóa nhiều dữ liệu
            if(have_posts($data)) $listID   = $data;
            if(have_posts($listID)) {
                foreach ( $listID as $product_id ) {
                delete_metadata( 'products_categories', $product_id, 'khoanggia' );  
                }
            }
        }
    }
    add_action('ajax_delete_before_success', 'product_categories_metabox_khoanggia_delete', 10, 3);
}

?>