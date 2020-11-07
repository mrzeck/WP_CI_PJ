<?php
/**
 * Admin new order email
 * @version 1.8
 */
do_action( 'woocommerce_email_admin_header', $order, $email_id);

do_action( 'woocommerce_email_admin_order_metas', $order, $email_id );

do_action( 'woocommerce_email_admin_order_details', $order, $email_id );

do_action( 'woocommerce_email_admin_footer', $order, $email_id);