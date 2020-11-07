<?php
if( !function_exists('wcmc_setting_tab_email') ) {

	function wcmc_setting_tab_email($ci, $tab) {

		wcmc_get_include_cart('admin/views/setting/html-settings-tab-email');
	}
}

if( !function_exists('wcmc_ajax_setting_email_save') ) {

	function wcmc_ajax_setting_email_save( $ci, $model ) {

        $result['status']  = 'error';
        
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data = $ci->input->post('wcmc_email');

			$wcmc_email['customer_order_new'] = removeHtmlTags($data['customer_order_new']);

			$wcmc_email['admin_order_new'] = removeHtmlTags($data['admin_order_new']);

			if( have_posts($wcmc_email) ) {

				update_option( 'wcmc_email', $wcmc_email );

				$result['status']  = 'success';

				$result['message'] = __('Lưu dữ liệu thành công.');
			}
		}

        echo json_encode($result);
	}

	register_ajax_admin('wcmc_ajax_setting_email_save');
}