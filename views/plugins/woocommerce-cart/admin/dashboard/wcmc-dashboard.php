<?php
function wcmc_cart_admin_dashboard() {
    add_dashboard_widget('wcmc_cart_dashboard_order', 'Thống kê đơn hàng', 'wcmc_cart_dashboard_order', array('col' => 12));
}

add_action('cle_dashboard_setup', 'wcmc_cart_admin_dashboard');

function wcmc_cart_dashboard_order($widget) {

    $time_start = date('Y-m').'-01 00:00:00';

    $time_end   = lastmonth_by_time().' 23:59:59';

    $args = [
        'where' => [
            'created >=' => $time_start,
            'created <=' => $time_end
        ]
    ];

    $args_doanhthu = $args;

    $args_doanhthu['operator']['col']       = 'total';
    $args_doanhthu['where']['status <>']    = 'wc-cancelled';

    $count_doanhhthu = gets_order($args_doanhthu);

    $count_order = count_order($args);

    $args_order = $args;

    $args_order['where']['status'] = 'wc-wait-confim';
    
    $count_order_wait_confim = count_order($args_order);

    $args_order['where']['status'] = 'wc-processing';

    $count_order_processing = count_order($args_order);

    $args_order['where']['status'] = 'wc-completed';

    $count_order_completed = count_order($args_order);

    $args_order['where']['status'] = 'wc-cancelled';

    $count_order_cancelled = count_order($args_order);

    $count_customer = count_customer($args);
    ?>
    <div class="box wcmc-dashboard-order">
        <div class="col-md-3">
            <div class="header"> <h2>TỔNG QUAN TRONG THÁNG</h2></div>
            <div class="box-content" style="height: auto;">
                <div class="tk-order__item order__item_doanhthu">
                    <div class="tk-icon">
                        <?php if (version_compare(cms_info('version'), '2.5.5') >= 0 ) {?>
                        <i class="fal fa-sack-dollar"></i>
                        <?php } else { ?>
                        <i class="fal fa-badge-dollar"></i>
                        <?php } ?>
                    </div>
                    <div class="tk-title">
                        <label>Doanh thu</label>
                        <p class="number"><?php echo number_format($count_doanhhthu);?>đ</p>
                    </div>
                </div>

                <div class="tk-order__item order__item_donhang">
                    <div class="tk-icon">
                        <i class="fal fa-store"></i>
                    </div>
                    <div class="tk-title">
                        <label>Đơn hàng</label>
                        <p class="number"><?php echo number_format($count_order);?></p>
                    </div>
                </div>

                <div class="tk-order__item order__item_customer">
                    <div class="tk-icon">
                        <i class="fal fa-user"></i>
                    </div>
                    <div class="tk-title">
                        <label>Khách hàng</label>
                        <p class="number"><?php echo $count_customer;?></p>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-9" style="border-left:1px solid #ccc;">
            <div class="header"> <h2>ĐƠN HÀNG HIỆN TẠI</h2></div>
            <div class="box-content" style="height: auto;">
                <div class="col-sm-6 col-md-6">
                    <div class="number-order__item">
                        <div class="tk-icon">
                            <i class="fal fa-clipboard-check"></i>
                        </div>
                        <div class="tk-title">
                            <p>
                                <span class="number"><?php echo number_format($count_order_wait_confim);?></span> Đợi duyệt
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6">
                    <div class="number-order__item">
                        <div class="tk-icon">
                            <i class="fal fa-shipping-fast"></i>
                        </div>
                        <div class="tk-title">
                            <p>
                                <span class="number"><?php echo number_format($count_order_processing);?></span> Đang xử lý
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6">
                    <div class="number-order__item">
                        <div class="tk-icon">
                            <i class="fal fa-box-check"></i>
                        </div>
                        <div class="tk-title">
                            <p>
                                <span class="number"><?php echo number_format($count_order_completed);?></span> Đã hoàn thành
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6">
                    <div class="number-order__item">
                        <div class="tk-icon">
                            <i class="fal fa-dolly"></i>
                        </div>
                        <div class="tk-title">
                            <p>
                                <span class="number"><?php echo number_format($count_order_cancelled);?></span> Bị hủy
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
	</div>

    <style>
        .wcmc-dashboard-order .tk-order__item {
            overflow:hidden;
            margin-bottom:15px;
        }
        .wcmc-dashboard-order .tk-order__item .tk-icon {
            font-size:40px;
            text-align:center;
            float:left;
            width:50px;
            margin-right:10px;
        }
        .wcmc-dashboard-order .tk-order__item .tk-title .number {
            font-size:18px;
            font-weight:bold;
        }

        .order__item_doanhthu .tk-icon, .order__item_doanhthu .tk-title .number {
            color:#A7A4D7;
        }

        .order__item_donhang .tk-icon, .order__item_donhang .tk-title .number {
            color:#2C92FE;
        }

        .order__item_customer .tk-icon, .order__item_customer .tk-title .number {
            color:#FFCD36;
        }

        .number-order__item {
            text-align:center;
            border:1px solid #2C92FE;
            border-radius:4px;
            margin-bottom:15px;
        }
        .number-order__item .tk-icon {
            font-size:40px;
        }
        .number-order__item .tk-title {
            color:#AEB1B5;
        }
        .number-order__item .tk-title .number {
            font-size:20px;
            font-weight:500;
            color:#2C92FE;
        }
    </style>
    <?php
}

if(!function_exists('lastmonth_by_time')) {
	/**
	 * [month_lastday ngày cuối cùng của tháng]
	 */
	function lastmonth_by_time( $time = 0, $format = 'Y-m-d' ) {

		$month = 0;

		$year = 0;

		if( empty($time) ) {

			$month = date('m');

			$year = date('Y');

		}
		else {

			if( (int) $time == 0  ) $time = strtotime($time);

			$month = date('m', $time );

			$year = date('Y', $time );
		}

	   	$result = strtotime("{$year}-{$month}-01");

	   	$result = strtotime('-1 second', strtotime('+1 month', $result));

	   	return date($format, $result);
	}
}