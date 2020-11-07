<?php
/**
 * Customer new order email
 * @version 1.8
 */
do_action( 'woocommerce_email_header', $order, $email_id);

do_action( 'woocommerce_email_order_metas', $order, $email_id );

do_action( 'woocommerce_email_order_details', $order, $email_id );

do_action( 'woocommerce_email_footer', $order, $email_id);