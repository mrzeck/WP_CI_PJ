<?php
if ( ! function_exists( 'dmd_admin_navigation' ) ) {
    function dmd_admin_navigation() {
        if( is_super_admin() ) {
            register_admin_nav('Demo data', 'demo-data', 'plugins?page=demo-data','page', array('icon' => '<img src="https://tooltip.cminds.com/wp-content/uploads/features/woocommerce.png" />', 'callback' => 'dmd_callback'));
        }
    }
    add_action('init', 'dmd_admin_navigation', 10);
}
if ( ! function_exists( 'dmd_callback' ) ) {
    function dmd_callback() {
        include 'demo-data-view.php';
    }
}
if ( ! function_exists( 'dmd_ajax_create_data' ) ) {
    function dmd_ajax_create_data( $ci, $model ) {
        $result['status']  = 'error';
        $result['message'] = __('Lưu dữ liệu không thành công');
        if( $ci->input->post() ) {
            $data = $ci->input->post();
            if( $data['data'] == 'post' ) {
                $post = $data['post'];
                $total = dmd_create_post( $post['num'], $post['type'], $post['width'], $post['height'] );
            }
            if( $data['data'] == 'product' ) {
                $product = $data['product'];
                $total = dmd_create_product( $product['num'], $product['type'], $product['width'], $product['height'], (int)$product['price'],(int)$product['price_sale'] );
            }
            $result['status']  = 'success';
            $result['message'] = __('Lưu dữ liệu thành công.');
        }
        echo json_encode($result);
    }
    register_ajax_admin('dmd_ajax_create_data');
}
if ( ! function_exists( 'dmd_create_post' ) ) {
    function dmd_create_post( $num, $type, $width, $height ) {
        $total = 0;
        include 'demo-data-xml.php';
        if( $type == 0 ) $data_title = array_merge( $title['post']['fashion'], $title['post']['technology'], $title['post']['land'] );
        else $data_title = $title['post'][$type];
        $categories = gets_post_category( array('where' => array('cate_type' => 'post_categories')) );
        foreach ($categories as $category) {
            for ( $i = 0; $i < $num; $i++ ) {
                $post = array(
                    'title'     => $data_title[array_rand( $data_title, 1)],
                    'excerpt'   => 'We all know how hard it can be to make a site look like the demo, so to make your start into the world of X as easy as possible we have included the demo content from our showcase site. Simply import the sample files we ship with the theme and the core structure for your site is already built.',
                    'image'     => 'https://via.placeholder.com/'.$width.'x'.$height,
                    'post_type' => 'post'
                );
                insert_post( $post, array('taxonomy' => array( 'post_categories' => array($category->id) ) ) );
                $total++;   
            }
        }
        return $total;
    }
}
if ( ! function_exists( 'dmd_create_product' ) ) {
    function dmd_create_product( $num, $type, $width, $height, $price, $price_sale ) {
        $total = 0;
        include 'demo-data-xml.php';
        if( $type == 0 ) $data_title = array_merge( $title['product']['fashion'], $title['product']['technology'] );
        else $data_title = $title['product'][$type];
        $categories = wcmc_gets_category();

        foreach ($categories as $category) {
            for ( $i = 0; $i < $num; $i++ ) {
                $price      = ($price == 0 ) ? $data_price[array_rand( $data_price, 1)] : $price;
                $price_sale = ($price_sale == 0 ) ? $data_price_sale[array_rand( $data_price_sale, 1)] : $price_sale;
                $pr = array(
                    'title'      => $data_title[array_rand( $data_title, 1)],
                    'slug'      => slug($data_title[array_rand( $data_title, 1)]),
                    'excerpt'    => 'We all know how hard it can be to make a site look like the demo, so to make your start into the world of X as easy as possible we have included the demo content from our showcase site. Simply import the sample files we ship with the theme and the core structure for your site is already built.',
                    'image'      => 'https://via.placeholder.com/'.$width.'x'.$height,
                    'price'      => $price ,
                    'price_sale' => $price_sale,
                    'categories' => array($category->id),
                    'type'          =>'product'
                );
                insert_product($pr);
                $total++;   
            }
        }
        return $total;
    }
}
