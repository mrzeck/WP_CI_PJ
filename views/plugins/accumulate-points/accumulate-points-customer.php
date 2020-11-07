<?php
function tddq_my_action_links( $args ) {

	$newArgs = array();

	foreach ($args as $key => $value) {

		if( $key == 'logout' ) {
            $newArgs['tddq-withdrawal2'] = array(
                    'label' => __('Tạo Link chia sẻ'),
                    'icon'  => '<i class="fal fa-money-check-alt"></i>',
                    'url'   => my_account_url().'/affiliate-marketing',
                );
			$newArgs['tddq'] = array(
				'label' => __('Điểm thưởng'),
				'icon'  => '<i class="fas fa-money-check-alt"></i>',
				'url'	=> my_account_url().'/accumulate-points',
            );
            
            if(tddq_pay_type_check(2)) {

                $newArgs['tddq-withdrawal'] = array(
                    'label' => __('Rút điểm thưởng'),
                    'icon'  => '<i class="fal fa-money-check-alt"></i>',
                    'url'	=> my_account_url().'/accumulate-points-withdrawal',
                );

            }
		}

		$newArgs[$key] = $value;
	}

	return $newArgs;
}

add_filter('my_action_links', 'tddq_my_action_links');


function tddq_my_account_view( $view ) {

	$ci =& get_instance();

	$method = $ci->uri->segment('3');

	$lang = $ci->uri->segment('1');

	if( ($ci->language['default'] != $ci->language['current'] || $ci->uri->segment('1') ==  $ci->language['default']) && $lang == $ci->language['current'] ) $method = $ci->uri->segment('4');

	$view = TDDQ_PATH.'template/user/accumulate-points';

	return $view;
}

add_filter('my_account_view_accumulate-points', 'tddq_my_account_view');
//////////////////////////////////////
 function tddq_my_account_view_affiliate( $view ) {

        $ci =& get_instance();

        $method = $ci->uri->segment('3');

        $lang = $ci->uri->segment('1');

        if( ($ci->language['default'] != $ci->language['current'] || $ci->uri->segment('1') ==  $ci->language['default']) && $lang == $ci->language['current'] ) $method = $ci->uri->segment('4');

        $view = TDDQ_PATH.'template/user/affiliate-marketing';
        return $view;
    }

    add_filter('my_account_view_affiliate-marketing', 'tddq_my_account_view_affiliate');
/////////////////////////////////////
if(tddq_pay_type_check(2)) {
    function tddq_my_account_view_withdrawal( $view ) {

        $ci =& get_instance();

        $method = $ci->uri->segment('3');

        $lang = $ci->uri->segment('1');

        if( ($ci->language['default'] != $ci->language['current'] || $ci->uri->segment('1') ==  $ci->language['default']) && $lang == $ci->language['current'] ) $method = $ci->uri->segment('4');

        $view = TDDQ_PATH.'template/user/accumulate-points-withdrawal';
        
        return $view;
    }

    add_filter('my_account_view_accumulate-points-withdrawal', 'tddq_my_account_view_withdrawal');
}


function tddq_customer_detail_header($customer) {

    ?>
    <a class="btn btn-default" href="javascript:;">
        <i class="fal fa-credit-card"></i>  <b><?php echo (int)get_user_meta($customer->id, 'tddq_point', true);?></b> điểm
    </a>
    <a class="btn btn-default" href="<?php echo admin_url('plugins?page=accumulate-points&view=customer&id='.$customer->id);?>">
        <img src="https://www.choicehotels.com/cms/images/ppc_icon6/ppc_icon6.png" style="width:30px;" />  Quản lý điểm thưởng
    </a>
 
    <style>
        .fancybox-slide > * { padding:0; }
        #customer-edit { max-width:500px; }
        #customer-edit h2 {
            background-color:#2C3E50; color:#fff; margin:0; padding:10px;
            font-size:18px;
        }
        #customer-edit form {
            padding:10px;
            overflow:hidden;
        }
        #customer-edit form .group{
            margin-bottom:10px;
        }
    </style>

    <?php
}

add_action('customer_detail_header_action', 'tddq_customer_detail_header');