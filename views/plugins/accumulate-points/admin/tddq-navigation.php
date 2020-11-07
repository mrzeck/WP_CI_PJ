<?php
if ( ! function_exists( 'tddq_admin_navigation' ) ) {

    function tddq_admin_navigation() {

        $ci =&get_instance();

        if(tddq_pay_type_check(2)) {

            $count = count_tddq_history([
                'where' => [
                    'type' => 'withdrawal',
                    'status' => 'wait'
                ]
            ]);

            if($count != 0) $count = '<span style="display:inline-block;border-radius:50%; height:20px; width:20px; line-height:20px;text-align:center; background-color:red;">'.$count.'</span>';
            else $count = '';

            register_admin_nav('Yêu cầu rút điểm '.$count, 'accumulate-points-withdrawal', 'plugins?page=accumulate-points-withdrawal', 'woocommerce', array('callback' => 'accumulate_points_withdrawal', 'icon' => '<img src="https://fsmktingcdn-a82e.kxcdn.com/wp/wp-content/uploads/2017/10/atm.png">'));
        }
        register_admin_subnav('system', 'Cấu hình tích điểm', 'accumulate-points', 'plugins?page=accumulate-points', array('callback' => 'accumulate_points_setting'));
    }

    add_action('init', 'tddq_admin_navigation', 10);
}
