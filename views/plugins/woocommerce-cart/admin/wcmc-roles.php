<?php
function wcmc_cart_roles_group( $group ) {

    $group['order'] = [
		'label' => __('Đơn hàng'),
		'capbilities' => array_keys(wcmc_capbilities_order())
    ];

    $group['customer'] = [
		'label' => __('Khách hàng'),
		'capbilities' => array_keys(wcmc_capbilities_customer())
    ];

    if(isset($group['product'])) {

        $group['product']['capbilities'] = array_merge(
            $group['product']['capbilities'],
            array_keys(wcmc_capbilities_product_attributes())
        );
    }

	return $group;
}

add_filter( 'user_role_editor_group', 'wcmc_cart_roles_group' );

function wcmc_cart_roles_label( $label ) {

	$label = array_merge( $label, wcmc_capbilities_order() );
	$label = array_merge( $label, wcmc_capbilities_customer() );
	$label = array_merge( $label, wcmc_capbilities_product_attributes() );

	return $label;
}

add_filter( 'user_role_editor_label', 'wcmc_cart_roles_label' );

/**
 * Các quyền liên quan đến đơn hàng
 */
function wcmc_capbilities_order() {

    $label['wcmc_order_list']   = 'Quản lý đơn hàng';
	$label['wcmc_order_add']    = 'Thêm đơn hàng mới';
	$label['wcmc_order_copy']   = 'Nhân bản đơn hàng';
	$label['wcmc_order_edit']   = 'Cập nhật đơn hàng';
    $label['wcmc_order_delete'] = 'Xóa đơn hàng';

    $label['wcmc_order_setting'] = 'Quản lý cài đặt đơn hàng';
    
	return $label;
}

/**
 * Các quyền liên quan đến khách hàng
 */
function wcmc_capbilities_customer() {

    $label['customer_list']           = 'Quản lý khách hàng';
	$label['customer_active']         = 'Kích hoạt tài khoản khách hàng';
	$label['customer_add']            = 'Thêm khách hàng mới';
    $label['customer_edit']           = 'Cập nhật thông tin khách hàng';
    $label['customer_reset_password'] = 'Reset mật khẩu khách hàng';
    
    $label['customer_block']  = 'Block khách hàng';
    
	return $label;
}

/**
 * Các quyền liên quan đến khách hàng
 */
function wcmc_capbilities_product_attributes() {

    $label['wcmc_attributes_list']   = 'Quản lý tùy chọn';
    $label['wcmc_attributes_add']    = 'Thêm tùy chọn';
    $label['wcmc_attributes_edit']   = 'Cập nhật tùy chọn';
	$label['wcmc_attributes_delete'] = 'Xóa tùy chọn';
    
	return $label;
}