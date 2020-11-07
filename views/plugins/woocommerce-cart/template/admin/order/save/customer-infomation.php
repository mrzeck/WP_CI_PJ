<?php
    $fields = get_checkout_fields();

    $fields['billing']['billing_fullname']['value'] = $customer->firstname.' '.$customer->lastname;

    $fields['billing']['billing_email']['value'] = $customer->email;

    $fields['billing']['billing_phone']['value'] = $customer->phone;

    $fields['billing']['billing_address']['value'] = get_user_meta($customer->id, 'address', true);

    $fields = apply_filters('wcmc_order_save_customer_fields', $fields, $customer, (isset($order) && have_posts($order)) ? $order : array() );
?>

<input type="hidden" name="customer_id" class="form-control" value="<?php echo $customer->id;?>">

<!-- Tìm kiếm khách hàng -->
<section class="ui-layout__section">
    <h3>Thanh Toán</h3>
    <div class="order-customer-info">
        <?php do_action('before_order_save_billing_customer');?>

        <?php foreach ($fields['billing'] as $key => $field): ?>

		<?php echo _form($field, ( !empty($field['value']) ) ? $field['value'] : '');?>

	    <?php endforeach ?>

        <?php do_action('after_order_save_billing_customer');?>
    </div>

    <div class="clearfix"> </div>
</section>

<section class="ui-layout__section">
    <h3>Giao nhận</h3>
    <div class="order-customer-info">
        <p><span>Giống địa chỉ thanh toán</span></p> 
    </div>
    <div class="clearfix"> </div>
</section>
