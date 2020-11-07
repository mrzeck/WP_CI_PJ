<?php
if(!function_exists('excel_config')) {

    function excel_config() {

        $order_row = excel_order_row();

        $config = [
            'order_row' => [],
        ];

        foreach ($order_row as $key => $label) {
            $config['order_row'][$key]['us']    = true;
            $config['order_row'][$key]['id']    = $key;
            $config['order_row'][$key]['label'] = $label;
            $config['order_row'][$key]['order'] = 0;
        }

        $option_save = get_option('excel_congfig', []);

        if(!isset($option_save['order_row']) || !have_posts($option_save['order_row'])) $option_save['order_row'] = $config['order_row'];
        else {
            $option_save['order_row'] = array_merge($config['order_row'], $option_save['order_row']);
        }
        
        return $option_save; 
    }
}

if(!function_exists('excel_order_row')) {
    
    function excel_order_row() {

        $row = [
            'code'             => 'Mã đơn hàng',
            'created'          => 'Ngày đặt',
            'billing_fullname' => 'Họ tên khách hàng',
            'billing_email'    => 'Email',
            'billing_phone'    => 'Số điện thoại',
            'billing_address'  => 'Địa chỉ',
            'quantity'         => 'Số sản phẩm',
            'status'           => 'Tình trạng',
            'total'            => 'Tổng tiền',
            'order_note'       => 'Ghi chú',
        ];

        return apply_filters('excel_order_row', $row);
    }
}