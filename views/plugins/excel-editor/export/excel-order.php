<?php
$excel = new Skd_Excel_Editor();

$excel_characters = $excel->get_excel_characters();

$excel_config = excel_config();

$args = array();

$customer_id = (int)$ci->input->get('customer_id');

if($customer_id != 0) {
    
    $args = [
        'where' => [
            'user_created' => $customer_id
        ]
    ];
}

$keyword = removeHtmlTags($ci->input->get('name'));

if( !empty($keyword) ) $args['meta_query'][] = array('key' => 'billing_fullname', 'value' => $keyword, 'compare' => 'LIKE');

$phone   = removeHtmlTags($ci->input->get('phone'));

if( !empty($phone) ) $args['meta_query'][] = array('key' => 'billing_phone', 'value' => $phone, 'compare' => 'LIKE');

$action   = removeHtmlTags($ci->input->get('action'));

if( !empty($action) ) $args['where']['status'] = $action;

$time_start = removeHtmlTags($ci->input->get('time_start'));

if( !empty($time_start) ) {

    $time_start = date('Y-m-d', strtotime($time_start)).' 00:00:00';

    $args['where']['created >='] = $time_start;
}

$time_end = removeHtmlTags($ci->input->get('time_end'));

if( !empty($time_end) ) {
    $time_end = date('Y-m-d', strtotime($time_end)).' 23:59:59';

    $args['where']['created <='] = $time_end;
}

$args = apply_filters( 'woocommerce_order_index_args', $args );

$orders = wcmc_gets_order($args);

$sheet = $excel->create_sheet('DANH SÁCH ĐƠN HÀNG');

$header = [];

$i = 0;

foreach ($excel_config['order_row'] as $key => $value) {

    if($value['us'] == true) {

        $header[$value['label']] = ['cell' => $excel_characters[$i].'3'];

        $i++;
    }
}

$header['DANH SÁCH ĐƠN HÀNG'] = [
    'mergeCells' => ['A1', $excel_characters[$i].'2'],
    'style' => [
        'font' => [ 'bold' => true, 'size' => 22 ],
        'alignment' => [ 'horizontal' => 'center', 'vertical'   => 'center', ]
    ]
];

$sheet->set_header($header);

$sheet->set_header_style([
    'font' => ['bold' => true, 'size' => 11,],
    'alignment' => [
        'vertical' => 'center'
    ],
]);

$sheet->set_body( array('A','4') );

/*============================ NHÂN VIÊN KINH DOANH =================================*/

$i = 0;

foreach ($orders as $key => $order):

    $item = array();

    $style = [
            'alignment' => [
                'vertical' => 'center'
            ],
    ];

    foreach ($excel_config['order_row'] as $row_id => $value) {

        if($value['us'] == true) {

            $item[$row_id]       = ['title' => ''];

            if(isset($order->{$row_id})) $item[$row_id]['title'] = $order->{$row_id};

            $item[$row_id] = apply_filters('excel_export_order_row_data', $item[$row_id], $row_id, $order );
        }
    }

    // $item['code']       = array( 'title' => $order->code );
    // $item['fullname']   = array( 'title' => $order->billing_fullname );
    // $item['phone']      = array( 'title' => $order->billing_phone );
    // $item['email']      = array( 'title' => $order->billing_email );
    // $item['address']    = array( 'title' => $order->billing_address );
    // $item['quantity']   = array( 'title' => $order->quantity );
    // $item['total']      = array( 'title' => $order->total,  'type' => 'number', 'type_format' => '#,##0' );
    // $item['order_note'] = array( 'title' => $order->order_note );
   
    if($i == 1) {

        $style['fill'] = [
            'fillType'      => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor'    => [
                'argb' => 'E6F7FF',
            ],
        ];

        $i = 0;
    }
    else {
        $i = 1;
    }

    foreach ($item as &$item_v) $item_v['style'] = $style;
    
    $sheet->set_body_item($item);
endforeach;

$filename = 'danh-sach-don-hang-'.time();

$excel->set_filename($filename);

$excel->write_download();