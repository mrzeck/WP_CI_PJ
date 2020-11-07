<?php
if ( ! function_exists( 'wcmc_admin_navigation' ) ) {
    /**
     * [wcmc_admin_navigation description]
     * @return [type] [description]
     */
    function wcmc_admin_navigation() {

        $ci =&get_instance();

        //sản phẩm
        if( current_user_can('wcmc_product_list') )     register_admin_nav('Sản phẩm', 'products', 'products','page',array('icon' => '<img src="'.WCMC_PATH.'assets/images/wcmc.png" />'));

        if( current_user_can('wcmc_product_list') )     register_admin_subnav('products', 'Sản phẩm', 'products','products');

        if( current_user_can('wcmc_product_cate_list') ) register_admin_subnav('products', 'Danh mục', 'products_categories','products/products_categories','products');

        

        $position_woocommerce_settings = 'products_categories';

        $taxonomies = get_object_taxonomies( 'products', 'object' );

        foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {

            if( $taxonomy_value['post_type'] == 'products' ) {

                $position_woocommerce_settings = $taxonomy_key;

                register_admin_subnav('products', $taxonomy_value['labels']['name'], $taxonomy_key,'post/post_categories?cate_type='.$taxonomy_key.'&post_type=products');
            }
        }

        if( current_user_can('wcmc_product_setting') ) register_admin_subnav('products', 'Cài đặt', 'woocommerce_settings','plugins?page=woocommerce_settings&view=settings',	array('callback' => 'woocommerce_settings'), $position_woocommerce_settings);

         

        // if( current_user_can('wcmc_product_cate_list') ) register_admin_subnav('products', 'Nhà sản xuất', 'suppliers','plugins?page=suppliers',	array('callback' => 'woocommerce_suppliers'), 'products_categories');
    }

    add_action('init', 'wcmc_admin_navigation', 10);
}