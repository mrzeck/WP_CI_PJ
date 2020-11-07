<?php
if( !function_exists('woocommerce_filter_price_custom_tabs') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function woocommerce_filter_price_custom_tabs( $tabs ) {

		$tabs['price'] = array( 'label' => 'Khoảng Giá', 	'callback' => 'wcmc_filter_price_html');

		return $tabs;
	}

	add_filter( 'woocommerce_filter_setting_tabs', 'woocommerce_filter_price_custom_tabs', 10 );
}


if( !function_exists('wcmc_filter_price_html') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function wcmc_filter_price_html() {

		$ci =& get_instance();

		wcmc_filter_get_include('admin/views/html-settings-tab-price');

	}
}


if( !function_exists('wcmc_filter_ajax_price_save') ) {
	/**
	 * [woocommerce_settings_tabs hàm tạo dữ liệu các tab cấu hình
	 */
	function wcmc_filter_ajax_price_save( $ci, $model ) {

		$ci =& get_instance();

		$result['type'] = 'error';

		$result['message'] = 'Lưu dữ liệu thất bại';

		if( $ci->input->post() ) {

			$post = $ci->input->post();

			$filter_price = array();

			if( isset($post['filter_price']) && have_posts( $post['filter_price'] )) {

				foreach ( $post['filter_price'] as $value) {
					
					if( $value['min_price'] == '' || $value['max_price'] == '' ||$value['label'] == '') continue;

					$filter_price[] = $value;

				}
			}


			$filter_price = serialize($filter_price);

			$price = get_option('wcmc_filter_price_option', serialize(array()));

			if( $price != $filter_price ) {

				if( update_option('wcmc_filter_price_option', $filter_price) ) {

					$result['type'] = 'success';

					$result['message'] = 'Lưu dữ liệu thành công';
				}
			}

		}

		echo json_encode( $result );

	}

	register_ajax_admin( 'wcmc_filter_ajax_price_save' );
}