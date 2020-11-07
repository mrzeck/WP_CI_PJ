<?php
include 'admin/wcmc-navigation.php';

include 'admin/wcmc-attribute.php';

include 'admin/wcmc-product-metabox.php';

include 'admin/wcmc-setting.php';

include 'admin/order/wcmc-order.php';

include 'admin/customer/wcmc-customer.php';

include 'admin/dashboard/wcmc-dashboard.php';

include 'admin/wcmc-roles.php';


if ( ! function_exists( 'woocommerce_cart_admin_notices' ) ) {

    function woocommerce_cart_admin_notices() {

        if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) < 0) {

            if(superadmin()) echo notice('warning', 'Phiên bản Template woocommerce cart của bạn đã củ vui lòng nâng cấp lên để được hỗ trợ tốt nhất.');
        }
    }

    add_action('admin_notices', 'woocommerce_cart_admin_notices');
}


if ( ! function_exists( 'woocommerce_cart_admin_assets' ) ) {
    /**
     * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
     */
    function woocommerce_cart_admin_assets() {
        $ci =& get_instance();
        admin_register_style('wcmc-metabox', $ci->plugin->get_path('woocommerce-cart').'assets/css/admin/wc-product-metabox.css');
        admin_register_script('wcmc-metabox', $ci->plugin->get_path('woocommerce-cart').'assets/js/admin/wc-product-metabox.js');
        admin_register_script('wcmc-ot', $ci->plugin->get_path('woocommerce-cart').'assets/js/admin/wc-product-options.js');
        admin_register_script('wcmc-order', $ci->plugin->get_path('woocommerce-cart').'assets/js/admin/wc-product-order.js');
        admin_register_script('wcmc-order', $ci->plugin->get_path('woocommerce-cart').'assets/js/admin/wc-order.js');
    }

    add_action('cle_enqueue_script',    'woocommerce_cart_admin_assets');
}
/**
 * =====================================================================================================================
 * XỬ LÝ DỮ LIỆU KHI XÓA SẢN PHẨM
 * =====================================================================================================================
 */

if( !function_exists('wcmc_cart_product_delete') ) {
    /**
     * [wcmc_metabox_product_save thực hiện khi xóa sản phẩm]
     */
    function wcmc_cart_product_delete( $module, $data, $r ) {

        $ci =& get_instance();

        if( $module == 'products' ) {

            $listID = array();

            //xóa object
            if(is_numeric($data)) $listID[] = $data;

            //xóa nhiều dữ liệu
            if(have_posts($data)) $listID   = $data;

            if(have_posts($listID)) {

                $model = get_model('products');

                foreach ($listID as $product_id) {

                    delete_metadata( 'product', $product_id, 'attributes' );

                    $model->settable('relationships');

                    $model->delete_where(array('object_id' => $product_id, 'object_type' => 'attributes'));

                    $model->settable('wcmc_variations');

                    $variations = $model->gets_where(array('object_id' => $product_id));

                    foreach ($variations as $value) {
                        delete_metadata( 'wcmc_variations', $value->id );
                    }

                    $model->settable('wcmc_variations');
                    
                    $model->delete_where(array('object_id' => $product_id));

                }

                $model->settable('products');

            }
        }
    }

    add_action('ajax_delete_before_success', 'wcmc_cart_product_delete', 10, 3);
}
