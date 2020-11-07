<?php
if ( ! function_exists( 'wcmc_cart_admin_navigation' ) ) {

    function wcmc_cart_admin_navigation() {

        $ci =&get_instance();

        if( current_user_can('customer_list') ) register_admin_nav('Khách hàng', 'customers', 'plugins?page=customers', 'products', array('icon' => '<img src="'.WCMC_CART_PATH.'assets/images/customer.png" />', 'callback' => 'woocommerce_customers'));

        if( current_user_can('wcmc_order_list') ) register_admin_nav('Đơn hàng', 'woocommerce', 'plugins?page=woocommerce_order&view=shop_order', 'products', array('icon' => '<img src="'.WCMC_CART_PATH.'assets/images/woocommerce.png" />', 'callback' => 'woocommerce_order'));

        if( current_user_can('wcmc_order_list') ) register_admin_subnav('woocommerce', 'Đơn hàng','woocommerce_order','plugins?page=woocommerce_order&view=shop_order',    array('callback' => 'woocommerce_order'));
        
        if( current_user_can('wcmc_order_setting') ) register_admin_subnav('woocommerce', 'Cài đặt','woocommerce_cart_settings','plugins?page=woocommerce_cart_settings',    array('callback' => 'woocommerce_cart_settings'));
        
        if( current_user_can('wcmc_attributes_list') ) register_admin_subnav('products', 'Tùy chọn', 'woocommerce_attributes', 'plugins?page=woocommerce_attributes', array('callback' => 'woocommerce_attributes'), 'products_categories');
    }

    add_action('init', 'wcmc_cart_admin_navigation', 20);

}