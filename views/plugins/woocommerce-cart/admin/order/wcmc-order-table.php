<?php
//dùng cho toàn bộ table
class skd_woocomerce_order_list_table extends SKD_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'               => 'cb',
            'code'             => 'Đơn hàng',
            'created'          => 'Ngày tạo',
            'billing_fullname' => 'Khách hàng',
            'billing_phone'    => 'Điện thoại',
            'status'           => 'Tình trạng',
            'total'            => 'Tổng tiền',
            'action'           => 'Thao tác',
        );

        $this->_column_headers = apply_filters( "manage_woocomerce_order_columns", $this->_column_headers );

        $this->_column_headers['action'] = 'Hành Động';

        return $this->_column_headers;
    }

    function _column_code($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/plugins?page=woocommerce_order&view=shop_order_detail&id='.$item->id;
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<a href="'.$url.'" style="font-weight:bold;">#'.$item->code.'</a>';
        echo "</td>";
    }

    function _column_created($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo date('d-m-Y', strtotime($item->created));
        echo "</td>";
    }

    function _column_billing_fullname($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<p>'.$item->billing_fullname.'</p>';
        echo '<p>'.$item->billing_email.'</p>';
        echo "</td>";
    }

    function _column_billing_phone($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo $item->billing_phone;
        echo "</td>";
    }

    function _column_status($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<span style="background-color:'.order_status_color($item->status).'; border-radius:10px; padding:0px 5px; display:inline-block;">'.order_status_label($item->status).'</span>';
        echo "</td>";
    }

    function _column_total($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo number_format($item->total)._price_currency().'</b>';
        echo "</td>";
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/plugins?page=woocommerce_order&view=shop_order_detail&id='.$item->id;
        $class .= ' text-center';

        echo '<td class="'.$class.'">';

        echo '<a href="'.$url.'" class="btn btn-blue">Xem</a>';

        if( current_user_can('wcmc_order_delete') ) {
            echo '<button class="btn-red btn woocommerce_cart_delete_order" data-id="'.$item->id.'">'.admin_button_icon('delete').'</button>';
        }

        echo "</td>";
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_orders_custom_column', $column_name, $item );
    }
}