<?php
function wcmc_admin_order_button_export_excel() {

    $ci =& get_instance();
    
	$filter = '';

	$post = $ci->input->get();

	foreach ($post as $key => $value) {
		if($key == 'page' || $key == 'view') continue;
		if(!empty($value)) {
			$filter .= '&'.$key.'='.$value;
		}
	}
	
	$filter = apply_filters('excel_export_order_filter', $filter);

	?>
	<a href="<?php echo admin_url('plugins?page=excel-editor&view=excel-order'.$filter);?>" class="btn btn-default"><i class="fas fa-file-excel"></i> Xuất Excel</a>
	<?php
}

add_action( 'admin_order_action_bar_heading', 'wcmc_admin_order_button_export_excel' );

function excel_order_row_custom($item_data, $row_id, $order) {

	$ci =& get_instance();
	
	if($row_id == 'status') $item_data['title'] = woocommerce_order_status_label($order->status);

	return $item_data;
}

add_filter( 'excel_export_order_row_data', 'excel_order_row_custom', 10, 3 );


