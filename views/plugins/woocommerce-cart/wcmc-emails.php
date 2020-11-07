<?php
include 'emails/email-new-order.php';

include 'emails/email-admin-new-order.php';

function wc_get_email_order_items( $order ) {
	wcmc_get_template_cart('email/email-order-items', array( 'items' => $order->items));
}


//Gửi Email khi đặt hàng thành công
function wcmc_send_email_new_order( $id ) {

    $wcmc_email = get_option('wcmc_email',[
        'customer_order_new' 	=> 'on',
        'admin_order_new' 		=> 'on',
    ]);

    $order = wcmc_get_order( $id );

    if($wcmc_email['customer_order_new'] == 'on') {
        //Gửi cho khách hàng
        $config = array(
            'from_email' => get_option('contact_mail'),
            'fullname'   => $order->billing_fullname,
            'to_email'   => $order->billing_email,
            'subject'    => 'Xác nhận đơn hàng '.$order->code,
            'content'    => wcmc_email_new_order( $order, 'new_order' ),
        );

        send_mail( $config );
    }

    if($wcmc_email['admin_order_new'] == 'on') {
        //Gửi cho admin
        $config = array(
            'from_email' => get_option('contact_mail'),
            'fullname'   => $order->billing_fullname,
            'to_email'   => get_option('contact_mail'),
            'subject'    => 'Xác nhận đơn hàng '.$order->code,
            'content'    => wcmc_email_admin_new_order( $order, 'new_order' ),
        );

        send_mail( $config );
    }
}

add_action('woocommerce_checkout_after_success', 'wcmc_send_email_new_order', 10, 1 );

