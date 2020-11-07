<?php
/**
Plugin name     : Tích điểm đổi quà
Plugin class    : accumulate_points
Plugin uri      : http://sikido.vn
Description     : Ứng dụng sẽ tự động thưởng cho khách hàng một mức điểm nào đó bạn quy định. Và họ sẽ dễ dàng theo dõi được lượng điểm của họ cũng như dễ dàng nhận được quà tặng của bạn ngay lập tức.
Author          : Nguyễn Hữu Trọng
Version         : 1.0.0
*/
define( 'TDDQ_NAME', 'accumulate-points' );
define( 'TDDQ_PATH', plugin_dir_path( TDDQ_NAME ) );
class accumulate_points {
    private $name = 'accumulate_points';
    public  $ci;
    function __construct() {
        add_filter('manage_woocomerce_customer_columns', array( $this, 'customer_cell_points'));
        add_action('manage_customer_custom_column', array( $this, 'customer_cell_points_value'),2,2);
    }
    public function active() {
        $ci = &get_instance();
        $model = get_model('plugins');
        if( !class_exists('woocommerce_cart')) {
            echo notice('error', 'Bạn phải cài đặt plugins <b>WOOCOMMERCE CART</b> trước khi cài đặt plugin tích điểm!', true);
            die;
        }
        tddq_database_table_create();
    }
    public function uninstall() {
        $ci = &get_instance();
        $model = get_model('plugins');
        tddq_database_table_drop();
    }
    public function customer_cell_points($column) {
        $ci = &get_instance();
        $new_column = [];
        foreach ($column as $key => $value) {
            if($key == 'action') {
                $new_column['tddq'] = 'Điểm thưởng';
            }
            $new_column[$key] = $value;
        }
        return $new_column;
    }
    public function customer_cell_points_value($column_name, $item) {
        switch ( $column_name ) {
            case 'tddq':
                echo number_format((int)get_user_meta($item->id, 'tddq_point', true));
            break;
        }
    }
}
if(is_admin()) new accumulate_points();
include 'accumulate-points-database.php';
include 'accumulate-points-function.php';
include 'accumulate-points-ajax.php';
include 'accumulate-points-customer.php';
include 'accumulate-points-order.php';
if(is_admin()) include 'accumulate-points-admin.php';