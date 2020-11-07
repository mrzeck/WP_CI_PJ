<?php
include 'include/wcmc-helper-cities.php';

include 'include/wcmc-helper-order.php';

include 'include/wcmc-helper-attributes.php';

include 'include/wcmc-helper-customer.php';

if ( ! function_exists( 'wcmc_get_template_cart' ) ) {
	function wcmc_get_template_cart( $template_path = '' , $args = '', $return = false ) {
		$ci =& get_instance();
		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path = VIEWPATH.$ci->data['template']->name.'/woocommerce-cart/'.$template_path.'.php';
		ob_start();
		if(file_exists($path)) {
			include $path;
		}
		else {
			$path = $ci->plugin->dir.'/woocommerce-cart/template/'.$template_path.'.php';
			include $path;
		}

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

if ( ! function_exists( 'wcmc_get_include_cart' ) ) {
	
	function wcmc_get_include_cart( $template_path = '' , $args = '', $return = false) {
		$ci =& get_instance();
		extract($ci->data);
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path 	= $ci->plugin->dir.'/woocommerce-cart/'.$template_path.'.php';
		ob_start();
		if(file_exists($path)) {
			include $path;
		}

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

/**
 * [wcmc_order_code tạo mã đơn hàng]
 * @param  integer $id [description]
 * @return [type]      [description]
 */
function wcmc_order_creat_code( $id = 0 ) {

	return apply_filters( 'wcmc_order_creat_code', (1000 + $id) );
}

function wcmc_order_total( $total = 0 ) {

	$ci = &get_instance();

	$total 	= ($total == 0) ? $ci->cart->total() : $total;

	$shipping_price = 0;

	$data = $ci->input->post();

	if(!empty($data['shipping_type'])) {

		$shipping_type = removeHtmlTags($data['shipping_type']);

		$shipping 	   = woocommerce_cart_settings_tabs_shipping();

		$wcmc_shipping = get_option('wcmc_shipping', []);

		foreach ($shipping as $key => $ship) {

			if(isset($wcmc_shipping[$key]) && $wcmc_shipping[$key]['enabled'] == false) continue;

			$key_temp = str_replace( '-', '_', $key);

			if($key == $shipping_type) {

				$shipping_price = apply_filters('wcmc_shipping_price_'.$key_temp, 0 );
			}
		}
	}

	$total = $total + $shipping_price;

	$total = apply_filters( 'wcmc_order_total', $total );

	return $total;
}

function get_order_item_totals( $order ) {

	$ci = &get_instance();

	if(isset($order->_shipping_price)) {

		$wcmc_shipping = get_option('wcmc_shipping', []);

		if(isset($wcmc_shipping[$order->_shipping_type])) {

			$order->_shipping_label = $wcmc_shipping[$order->_shipping_type]['label'];

			if( $order->_shipping_price == 0 ) $order->_shipping_price = $wcmc_shipping[$order->_shipping_type]['price_default'];
		}

		$total[10]['label'] = $order->_shipping_label;

		$total[10]['value'] = ($order->_shipping_price == 0)?__('Liên hệ'):number_format($order->_shipping_price)._price_currency();
	}

	$total[20]['label'] = __('Thành tiền', 'wcmc_thanhtien'); 

	$total[20]['value'] = number_format($order->total)._price_currency();

	$totals = apply_filters( 'get_order_item_totals', $total, $order );

	ksort($totals);

	return $totals;
}

/**
 * Add and store a notice.
 *
 * @since 2.1
 * @param string $message The text to display in the notice.
 * @param string $notice_type Optional. The name of the notice type - either error, success or notice.
 */
function wcmc_add_notice( $message, $notice_type = 'success' ) {
    
    $notices = (isset($_SESSION['wc_notices']))?$_SESSION['wc_notices']:array();

    // Backward compatibility.
    if ( 'success' === $notice_type ) {
        $message = apply_filters( 'woocommerce_add_message', $message );
    }

    $message = removeHtmlTags( $message );

    $notices[ $notice_type ][] = apply_filters( 'woocommerce_add_' . $notice_type, $message );

    $_SESSION['wc_notices'] = $notices;
}

/**
 * Returns all queued notices, optionally filtered by a notice type.
 *
 * @since  2.1
 * @param  string $notice_type Optional. The singular name of the notice type - either error, success or notice.
 * @return array|mixed
 */
function wcmc_get_notices( $notice_type = '' ) {

    $all_notices = (isset($_SESSION['wc_notices']))?$_SESSION['wc_notices']:array();

    if ( empty( $notice_type ) ) {

        $notices = $all_notices;

        unset( $_SESSION['wc_notices'] );

    } elseif ( isset( $all_notices[ $notice_type ] ) ) {

        $notices = $all_notices[ $notice_type ];

        unset( $_SESSION['wc_notices'][$notice_type] );

    } else {

        $notices = array();

    }

    return $notices;
}

/**
 * Print a single notice immediately.
 *
 * @since 2.1
 * @param string $message The text to display in the notice.
 * @param string $notice_type Optional. The singular name of the notice type - either error, success or notice.
 */
function wcmc_print_notice( $message, $notice_type = 'success', $return = true ) {

    if ( 'success' === $notice_type ) {
        $message = apply_filters( 'woocommerce_add_message', $message );
    }

    return wcmc_get_template_cart( "notices/{$notice_type}", array(
        'messages' => array( apply_filters( 'woocommerce_add_' . $notice_type, $message ) ),
    ), $return );
}

/**
 * Print a single notice immediately.
 *
 * @since 2.3.1
 * @param string $message The text to display in the notice.
 * @param string $notice_type Optional. The singular name of the notice type - either error, success or notice.
 */
function wcmc_print_notice_label( $message, $notice_type = 'success', $return = true ) {

    if ( 'success' === $notice_type ) {

		$message = apply_filters( 'woocommerce_add_message_label', $message );
		
    }

    return apply_filters( 'woocommerce_add_' . $notice_type.'_label', $message );
}

if( !function_exists('wcmc_customer_client_register') ) {

	function wcmc_customer_client_register( $user_array ) {

        if(!is_admin()) {
			
			$user_array['customer'] = 1;

			$user_array['role'] = 'customer';
		}

        return $user_array;
	}

	add_filter( 'pre_user_register', 'wcmc_customer_client_register' );
}

if( !function_exists('wcmc_cart_get_template_version') ) {
	/**
	 * Print a single notice immediately.
	 * @since 2.3.2
	 */
	function wcmc_cart_get_template_version() {

		$ci =& get_instance();
		
		$path = VIEWPATH.$ci->data['template']->name.'/woocommerce-cart/version.php';

		$version = '1.0.0';

		if(!file_exists($path)) {

			$path = $ci->plugin->dir.'/woocommerce-cart/template/version.php';

			if(!file_exists($path)) {

				return $version;
			}
		}

		$string = file($path);

		$count 	= 0;

		foreach ($string as $k => $val) {

			$val 		= trim($val);

			$pos_start  = stripos($val,' * ')+1;

			$pos_end    = strlen($val);

			//Template name
			if(strpos($val,'@version',0) 	!== false) {
				$version 	= trim(substr($val, $pos_start, $pos_end)); $count++;
			}
		}

		$version = str_replace('@version','', $version);
		
		$version = trim($version);

		return $version;
	}
}