<?php
class skd_woocomerce_customer_list_table extends SKD_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'                => 'cb',
            'info'              => 'Thông tin',
            'email'             => 'Email',
            'phone'             => 'Điện thoại',
            'order_count'       => 'Đơn hàng',
            'order_count_now'   => 'Đơn hàng gần nhất',
            'order_total'       => 'Tổng chi tiêu',
            'action'            => 'Thao tác',
        );

        $this->_column_headers = apply_filters( "manage_woocomerce_customer_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function _column_info($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
       ?>
       <?php echo $item->firstname.' '.$item->lastname;?>
       <?php
       echo "</td>";
    }

    function _column_email($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->email;
        echo "</td>";
    }

    function _column_phone($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->phone;
        echo "</td>";
    }

    function _column_order_count_now($item, $column_name, $module, $table, $class)
    {
        echo '<td class="'.$class.'">';
        echo '#'.get_user_meta($item->id, 'order_recent', true);
        echo "</td>";
    }

    function _column_order_count($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->order_count;
        echo "</td>";
    }

    function _column_order_total($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.' text-center">';
        echo number_format($item->order_total).' đ';
        echo "</td>";
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        
        $url = URL_ADMIN.'/plugins?page=customers&view=detail&id='.$item->id;

        $class .= ' text-center';

        echo '<td class="'.$class.'">';

        echo '<a href="'.$url.'" class="btn btn-blue">Xem</a>';

        echo "</td>";
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_customer_custom_column', $column_name, $item );
    }
}