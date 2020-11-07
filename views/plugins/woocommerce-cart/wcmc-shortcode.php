<?php
/**
 * WooCommerce Shortcodes class.
 */

function wcmc_shortcode() {
	$prefix = 'wcmc_shortcode_';
	$shortcodes = array(
		'woocommerce_cart'           => $prefix.'cart',
		'woocommerce_checkout'       => $prefix.'checkout',
		'woocommerce_success'       => $prefix.'success',
	);

	foreach ( $shortcodes as $shortcode => $function ) {
		add_shortcode( $shortcode , $function );
	}
}

function wcmc_shortcode_cart() {
	ob_start();

    $ci = &get_instance();

    if( $ci->input->get('action') ==  'del' ) {

        $rowid = $ci->input->get('key');

        $data = array( 'rowid' => $rowid, 'qty' => 0 );

        $ci->cart->update($data);

        redirect('gio-hang');
    }

    if( $ci->input->post('action') == 'update' ) {

        $rowid = $ci->input->post('qty');

        foreach ($rowid as $id => $qty ) {

            $data = array( 'rowid' => $id, 'qty' => $qty );

            $ci->cart->update($data);
        }

        redirect('gio-hang');
    }

    do_action('wcmc_page_cart');

	wcmc_get_template_cart('cart/cart');

	return ob_get_clean();
}


function wcmc_shortcode_checkout() {

	ob_start();

	$ci = &get_instance();

	$model  = get_model('plugins','backend');

    do_action('wcmc_page_checkout');

	wcmc_get_template_cart('checkout/checkout');
    
	return ob_get_clean();
}


function wcmc_shortcode_success() {

    ob_start();

    $ci     = &get_instance();

    $id     = (int)$ci->input->get('id');

    $token  = $ci->input->get('token');

    $ci->data['order'] = wcmc_get_order( $id );

    do_action('wcmc_page_success', $ci->data['order'] );

    $token = apply_filters('wcmc_page_success_token', $token, $ci->data['order'] );

    if(isset($_SESSION['token']) && have_posts($ci->data['order']) && $_SESSION['token'] != null && $token == $_SESSION['token']) {
    
        wcmc_get_template_cart('success/success');
    }

    return ob_get_clean();
}