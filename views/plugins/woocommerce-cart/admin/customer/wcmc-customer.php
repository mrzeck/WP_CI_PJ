<?php
include 'wcmc-customer-table.php';
include 'wcmc-customer-heading.php';
include 'wcmc-customer-fields.php';
include 'wcmc-customer-action-bar.php';
if ( ! function_exists( 'woocommerce_customers' ) ) {
    function woocommerce_customers($ci, $model) {
        $ci =&get_instance();
        $views  = removeHtmlTags( $ci->input->get('view') );
        if( $views == 'list' || $views == '' ) {
            $customers = gets_customer();
            $args = array(
                'items' => $customers,
                'table' => 'users',
                'model' => $model,
                'module'=> 'users',
            );
            $table_list = new skd_woocomerce_customer_list_table($args);
            include WCMC_CART_PATH.'admin/views/customer/html-customer-list.php';
        }
        if( $views == 'detail') {
            $id = (int)$ci->input->get('id');
            $customer = get_user($id);
            if(have_posts($customer)) {
                include WCMC_CART_PATH.'admin/views/customer/html-customer-detail.php';
            }
            else {
            }
        }
        if( $views == 'created') {
            if( current_user_can('customer_add') ) {
                if( $ci->input->post() ) {
                    $user_meta = array();
                    $error = array();
                    if ( !empty($ci->input->post('username')) ) {
                        $user_array['username'] = removeHtmlTags( $ci->input->post('username') );
                    }
                    if ( !empty($ci->input->post('firstname')) ) {
                        $user_array['firstname'] = removeHtmlTags( $ci->input->post('firstname') );
                    }
                    else {
                        if( is_skd_error($error) ) $error->add( 'empty_firstname', __('Họ khách hàng không được bỏ trống.') );
                        else $error = new SKD_Error( 'empty_firstname', __('Họ khách hàng không được bỏ trống.'));
                    }
                    if ( !empty($ci->input->post('lastname')) ) {
                        $user_array['lastname'] = removeHtmlTags( $ci->input->post('lastname') );
                    }
                    else {
                        if( is_skd_error($error) ) $error->add( 'empty_lastname', __('Tên khách hàng không được bỏ trống.') );
                        else $error = new SKD_Error( 'empty_lastname', __('Tên khách hàng không được bỏ trống.'));
                    }
                    if ( !empty($ci->input->post('phone')) ) {
                        $user_array['phone'] = removeHtmlTags( $ci->input->post('phone') );
                    }
                    if ( !empty($ci->input->post('email')) ) {
                        if( email_exists(removeHtmlTags( $ci->input->post('email') )) != false ) {
                            $error = new SKD_Error( 'email_exists', __('Email này đã được sử dụng.'));
                        }
                        $user_array['email'] = removeHtmlTags( $ci->input->post('email') );
                    }
                    else {
                        if( is_skd_error($error) ) $error->add( 'empty_email', __('Email khách hàng không được bỏ trống.') );
                        else $error = new SKD_Error( 'empty_email', __('Email khách hàng không được bỏ trống.'));
                    }
                    if ( !empty($ci->input->post('password')) ) {
                        $user_array['password'] = removeHtmlTags( $ci->input->post('password') );
                    }
                    else {
                        if( is_skd_error($error) ) $error->add( 'empty_password', __('Mật khẩu không được bỏ trống.') );
                        else $error = new SKD_Error( 'empty_password', __('Mật khẩu không được bỏ trống.'));
                    }
                    if ( empty($ci->input->post('re_password')) ||  $ci->input->post('re_password') != $ci->input->post('password') ) {
                        if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.') );
                        else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.'));
                    }
                    if ( !empty($ci->input->post('address')) ) {
                        $user_meta['address'] = removeHtmlTags( $ci->input->post('address') );
                    }
                    if ( !empty($ci->input->post('note')) ) {
                        $user_meta['note'] = removeHtmlTags( $ci->input->post('note') );
                    }
                    $error = apply_filters('admin_registration_errors', $error, $user_array, $user_meta );
                    if( !is_skd_error($error) ) {
                        $user_array = apply_filters( 'admin_pre_user_register', $user_array );
                        $user_meta  = apply_filters( 'admin_pre_user_register_meta', $user_meta );
                        $user_array['customer'] = 1;
                        $error = insert_user( $user_array );
                        $user_array['role'] = 'customer';
                        if( !is_skd_error($error) && have_posts($user_meta) ) {
                            foreach ($user_meta as $user_meta_key => $user_meta_value) {
                                if ( !empty( $user_meta_value ) ) update_user_meta( $error, $user_meta_key, $user_meta_value );
                            }
                        }
                    }
                    if( is_skd_error($error) ) {
                        foreach ($error->errors as $error_key => $error_value) {
                            $ci->template->set_message( notice('error', $error_value[0]), $error_key );
                        }
                    }
                    else {
                        $ci->template->set_message( notice('success', __('Tạo tài khoản thành công.')), 'register_success' );
                        redirect( admin_url('plugins?page=customers') );
                    }
                }
                include WCMC_CART_PATH.'admin/views/customer/html-customer-created.php';
            }
            else {
                echo notice('error', 'Bạn không có đủ quyền để sử dụng chức năng này.');
            }
        }
    }
}
/**
 * @param template TRANG CHI TIẾT KHÁCH HÀNG
 */
if( !function_exists('customer_detail_primary_content') ) {
    function customer_detail_primary_content( $customer ) {
        include WCMC_CART_PATH.'admin/views/customer/detail/html-content.php';
    }
    add_action('customer_detail_sections_primary', 'customer_detail_primary_content', 10, 1);
}
if( !function_exists('customer_detail_primary_order') ) {
    function customer_detail_primary_order( $customer ) {
        $orders = gets_order([
            'where' => [
                'user_created' => $customer->id
            ],
            'params' => [
                'limit' => 5
            ]
        ]);
        // show_r($orders);
        include WCMC_CART_PATH.'admin/views/customer/detail/html-content-order.php';
    }
    add_action('customer_detail_sections_primary', 'customer_detail_primary_order', 15, 1);
}
if( !function_exists('customer_detail_secondary_info') ) {
    function customer_detail_secondary_info( $customer ) {
        include WCMC_CART_PATH.'admin/views/customer/detail/html-customer-info.php';
    }
    add_action('customer_detail_sections_secondary', 'customer_detail_secondary_info', 20, 1);
}
/**
 * @param template TRANG THÊM KHÁCH HÀNG
 */
if( !function_exists('customer_created_primary_content') ) {
    function customer_created_primary_content( $customer ) {
        $fields = wcmc_customer_fields();
        include WCMC_CART_PATH.'admin/views/customer/created/html-content.php';
    }
    add_action('customer_created_sections_primary', 'customer_created_primary_content', 10, 1);
}
if( !function_exists('customer_created_secondary_info') ) {
    function customer_created_secondary_info( $customer ) {
        include WCMC_CART_PATH.'admin/views/customer/created/html-note.php';
    }
    add_action('customer_created_sections_secondary', 'customer_created_secondary_info', 20, 1);
}
/**
 * @param action HÀNH ĐỘNG KHI ĐƠN HÀNG THAY ĐỔI
 */
if( !function_exists('wcmc_customer_action_status_completed') ) {
    /**
     * [wcmc_customer_action_status_completed Khi đơn hàng đã hoàn thành]
     */
    function wcmc_customer_action_status_completed( $order ) {
        $ci =& get_instance();
        $action = $ci->input->post('wcmc-action');
        $action = removeHtmlTags($action);
        if( $order->status != $action ) {
            $customer = get_user($order->user_created);
            if(have_posts($customer)) {
                $customer->order_total += $order->total;
                insert_user((array)$customer);
            }
        }
    }
    add_action( 'woocommerce_order_action_wc-completed', 'wcmc_customer_action_status_completed' );
}
/**
 * @param action HÀNH ĐỘNG KHI ĐƠN HÀNG THAY ĐỔI
 */
if( !function_exists('wcmc_customer_edit_user_index_args') ) {
    /**
     * [wcmc_customer_action_status_completed Khi đơn hàng đã hoàn thành]
     */
    //customer = 0 : nhân viên
    //customer = 1 : khách hàng
    //customer = 2 : khách và nhân viên
    function wcmc_customer_edit_user_index_args( $args ) {
        $args['where']['customer <>'] = 1;
        return $args;
    }
    add_filter( 'edit_user_index_args', 'wcmc_customer_edit_user_index_args' );
}