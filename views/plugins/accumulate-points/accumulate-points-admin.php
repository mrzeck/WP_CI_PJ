<?php
include 'admin/tddq-navigation.php';
function accumulate_points_setting() {
    $ci =&get_instance();
    $views  = removeHtmlTags( $ci->input->get('view') );
    if($views == '' || $views == 'setting') {
        $tab    = removeHtmlTags( $ci->input->get('tab') );
        include 'admin/views/tddq-setting.php';
    }
    if($views == 'customer') {
        $id         = (int)removeHtmlTags( $ci->input->get('id') );
        $customer   = get_user($id);
        $point = (int)get_user_meta($customer->id, 'tddq_point', true);
        $historys = gets_tddq_history([
            'where' => [
                'user_id' => $customer->id,
                'type'  => 'history',
            ]
        ]);
        include 'admin/views/tddq-customer-detail.php';
    }
}
function accumulate_points_withdrawal() {
    $ci =&get_instance();
    $history_limit = 20;
    $args = [
        'where' => [
            'type'  => 'withdrawal',
        ]
    ];
    $total = count_tddq_history($args);
    //Phân trang
    $url        = admin_url('plugins?page=accumulate-points-withdrawal&paging={paging}');
    $pagination = pagination($total, $url, $history_limit);
    $params = array(
        'limit'  => $history_limit,
        'start'  => $pagination->getoffset(),
        'orderby'=> 'order, created desc',
    );
    $args['params'] = $params;
    $historys = gets_tddq_history($args);
    include 'admin/views/tddq-customer-withdrawal.php';  
}