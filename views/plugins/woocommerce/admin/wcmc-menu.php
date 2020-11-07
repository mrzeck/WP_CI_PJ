<?php
/**
 * =====================================================================================================================
 * ADD THÊM TÙY CHỌN PRODUCT VÀO MENU
 * =====================================================================================================================
 */

if ( ! function_exists( 'woocommerce_admin_menu_list_object' ) ) {
    /**
     * @Load dữ liệu danh mục sản phẩm trong quản trị menu
     */
    function woocommerce_admin_menu_list_object( $list_object ) {

        $ci =& get_instance();

        $list_object['product'] = array ( 'label' => 'Danh mục sản phẩm', 'type' => 'products_categories');

        $list_object['product']['data'] = wcmc_gets_category(array('mutilevel' => 'data'));

        $list_object['product']['data'][0] = (object)array('id' => 0, 'name' => '<b>Sản phẩm</b>');

        return $list_object;
    }

    add_filter('admin_menu_list_object',  'woocommerce_admin_menu_list_object', 10);
}