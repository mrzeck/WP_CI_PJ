<?php
if ( ! function_exists( 'wcmc_filter_admin_navigation' ) ) {


    function wcmc_filter_admin_navigation() {

        $ci =&get_instance();
        
        register_admin_subnav('products', 'Tìm kiếm', 'woocommerce_filter_setting', 'plugins?page=woocommerce_filter_setting', array('callback' => 'woocommerce_filter_setting'));
    }

    add_action('init', 'wcmc_filter_admin_navigation', 20);

}