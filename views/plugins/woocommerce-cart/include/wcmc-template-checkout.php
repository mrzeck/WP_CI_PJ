<?php
/** CONTENT : ******************************************************************/
function woocommerce_checkout_field_billing() {

	$fields = get_checkout_fields();

	wcmc_get_template_cart('checkout/form-billing', array( 'fields' => $fields ) );
}

add_action( 'wcmc_checkout_content', 'woocommerce_checkout_field_billing',20);

function woocommerce_checkout_field_shipping() {

	$fields = get_checkout_fields();

	wcmc_get_template_cart('checkout/form-shipping', array( 'fields' => $fields ) );
}

add_action( 'wcmc_checkout_content', 'woocommerce_checkout_field_shipping', 30);

function woocommerce_checkout_field_order() {

	$fields = get_checkout_fields();

	wcmc_get_template_cart('checkout/form-order', array( 'fields' => $fields ) );
}

add_action( 'wcmc_checkout_content', 'woocommerce_checkout_field_order', 50);

/** CHECKOUT : Phương thức thanh toán ******************************************************************/
function woocommerce_checkout_payment() {

	$payments = wcmc_gets_payment();

	wcmc_get_template_cart('checkout/payment', array( 'payments' => $payments ) );
}

if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {

	add_action( 'wcmc_checkout_content', 'woocommerce_checkout_payment', 40);
}
else {
	add_action( 'checkout_more', 'woocommerce_checkout_payment', 40);
}

if(version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0) {

	function payment_bacs_view( $payments ) {

		wcmc_get_template_cart('checkout/payment_bacs', array( 'payment' => $payments['bacs'] ) );
	}

	add_action( 'woocommerce_checkout_payment_description_view', 'payment_bacs_view');
}
else {
	function payment_cod_view( $payment ) {

		wcmc_get_template_cart('checkout/payment_cod', array( 'payment' => $payment ) );
	}

	add_action( 'woocommerce_checkout_payment_cod_view', 'payment_cod_view');

	function payment_bacs_view( $payment ) {

		wcmc_get_template_cart('checkout/payment_bacs', array( 'payment' => $payment ) );
	}

	add_action( 'woocommerce_checkout_payment_bacs_view', 'payment_bacs_view');
}

/** CHECKOUT : SHIPPING ******************************************************************/
function woocommerce_checkout_shipping() {

	$ci =& get_instance();

	$count 					= 0;
	
	$shipping 				= woocommerce_cart_settings_tabs_shipping();

	$wcmc_shipping 			= get_option('wcmc_shipping', []);

	$wcmc_cart_checkout 	= $ci->data['wcmc_cart_checkout'];

	$wcmc_shipping_default 	= get_option('wcmc_shipping_default', key($wcmc_shipping));

	$shipping_type 			= (isset($wcmc_cart_checkout['shipping_type']))?$wcmc_cart_checkout['shipping_type']:$wcmc_shipping_default;

	foreach ($shipping as $key => $ship) {

		$key_temp = str_replace( '-', '_', $key);

		if(isset($wcmc_shipping[$key])) {

			if($wcmc_shipping[$key]['enabled'] == false) continue;

			$ship['label'] 			= $wcmc_shipping[$key]['label'];

			$ship['price_default'] 	= 0;

			if(isset($wcmc_cart_checkout['wcmc_shipping_price_'.$key_temp])) {

				$ship['price_default'] = $wcmc_cart_checkout['wcmc_shipping_price_'.$key_temp];
			}

			if($ship['price_default'] == 0 ) $ship['price_default'] = $wcmc_shipping[$key]['price_default'];

			$count++;
		}
		else {
			
			$ship['price_default'] = __('Liên hệ');

			$count++;
		}
		?>
		<tr class="ship">
			<td>
				<div class="checkbox" style="margin:0;">
					<label style="padding:0;">
						<input type="radio" value="<?php echo $key;?>" name="shipping_type" <?php echo ($shipping_type == $key) ? 'checked' : '';?>>
						<?php echo $ship['label'];?>
					</label>
				</div>
			</td>
			<td><strong id="ship-<?php echo $key;?>"><?php echo (is_numeric($ship['price_default']))?number_format($ship['price_default'])._price_currency():$ship['price_default'];?></strong></td>
		</tr>
	
	<?php }
	if($count == 1) { ?>
	<style>
		input[name="shipping_type"] { display:none;}
	</style>
	<?php }
}

add_action( 'woocommerce_checkout_review_order', 'woocommerce_checkout_shipping', 10);
