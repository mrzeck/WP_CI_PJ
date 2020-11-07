<?php
function wcmc_roles_group( $group ) {



    $group['product'] = [
		'label' => __('Sản phẩm'),
		'capbilities' => array_keys(wcmc_capbilities_product())
    ];

	return $group;
}

add_filter( 'user_role_editor_group', 'wcmc_roles_group' );

function wcmc_roles_label( $label ) {

	$label = array_merge( $label, wcmc_capbilities_product() );

	return $label;
}

add_filter( 'user_role_editor_label', 'wcmc_roles_label' );


/**
 * Các quyền liên quan đến sản phẩm
 */
function wcmc_capbilities_product() {

    $label['wcmc_product_list']        = 'Quản lý sản phẩm';
	$label['wcmc_product_edit']        = 'Thêm / Cập nhật sản phẩm';
	$label['wcmc_product_delete']      = 'Xóa sản phẩm';
	
	$label['wcmc_product_cate_list']   = 'Quản lý danh mục sản phẩm';
	$label['wcmc_product_cate_edit']   = 'Thêm / Cập nhật danh mục sản phẩm';
	$label['wcmc_product_cate_delete'] = 'Xóa danh mục sản phẩm';

	$label['wcmc_product_setting']     = 'Quản lý cài đặt sản phẩm';
    
	return $label;
}