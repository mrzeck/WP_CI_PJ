<?php
function wcmc_email_new_order( $order, $email_id )
{
	$ci =& get_instance();

	wcmc_email_new_order_template( $order );

	return wcmc_get_template_cart('email/customer-new-order', array( 'order' => $order, 'email_id' => $email_id ), true );
}


function wcmc_email_new_order_template()
{
	add_action('woocommerce_email_header', 'wcmc_email_new_order_header', 10, 1);

	add_action('woocommerce_email_order_metas', 'wcmc_email_new_order_address', 10, 1);

	add_action('woocommerce_email_order_details', 'wcmc_email_new_order_details', 10, 1);

	add_action('woocommerce_email_footer', 'wcmc_email_new_order_footer', 10, 1);
}


function wcmc_email_new_order_header( $order )
{
	$ci =& get_instance();

	wcmc_get_template_cart('email/email-header', array( 'order' => $order));
}


function wcmc_email_new_order_footer( $order )
{
	$ci =& get_instance();

	wcmc_get_template_cart('email/email-footer', array( 'order' => $order));
}

function wcmc_email_new_order_details( $order )
{
	$ci =& get_instance();

	wcmc_get_template_cart('email/email-order-details', array( 'order' => $order));
}


function wcmc_email_new_order_address( $order )
{
	$ci =& get_instance();

	wcmc_get_template_cart('email/email-addresses', array( 'order' => $order));
}