<?php
if(!function_exists('tddq_checkout_review_order_after') && tddq_pay_type_check(1) ) {
	add_action('woocommerce_checkout_review_order_after', 	'tddq_checkout_review_order_after', 2);
	function tddq_checkout_review_order_after() {
		$ci =& get_instance();
		$point = 0;
		if( $ci->input->post() && is_user_logged_in() ) {
			$total = $ci->cart->total();
			$tddq_config = tddq_config();
			$tddq_point = (int)$ci->input->post('tddq_point');
			$error = tddq_checkout_check( $tddq_point, $ci->data['user']->id, $ci->cart->total() );
			if( is_skd_error($error) ) {?>
			<div class="discount-alert">
				<?php foreach ($error->errors as $er): ?>
					<?php echo notice('error', $er[0]);?>
				<?php endforeach ?>
			</div>
			<?php }
		?>
		<div class="woocommerce-box">
			<div class="tddq">
				<p>Sử dụng điểm thưởng để thanh toán cho đơn hàng nay. hiện có (<?php echo (int)get_user_meta($ci->data['user']->id, 'tddq_point', true);?>) điểm</p>
				<?php if( !empty($tddq_point) && !is_skd_error($error) ) {?>
				<div class="tddq_input">
					<strong><?php echo number_format($tddq_point*$tddq_config['point_conver']);?> (<?php echo number_format($tddq_point);?> điểm)</strong>
					<input type="hidden" name="tddq_point" class="form-control" value="<?php echo $tddq_point;?>">
				</div>
				<div class="tddq_button">
					<button class="btn btn-white" type="button" id="tddq_remove">THAY ĐỔI</button>
				</div>
				<?php } else { ?>
				<div class="tddq_input">
					<input type="text" name="tddq_point" class="form-control" value="<?php echo $tddq_point;?>">
				</div>
				<div class="tddq_button">
					<button class="btn btn-white" type="button" id="tddq_apply">ÁP DỤNG</button>
				</div>
				<?php } ?>
			</div>
		</div>
		<style>
			.tddq { overflow:hidden; }
			.tddq p { margin-bottom:10px!important; }
			.tddq_input, .tddq_button { float:left; }
			.tddq_button { width:80px; }
			.tddq_button .btn {
				-webkit-box-shadow: none;
				box-shadow: none;
				margin-bottom:0;
			}
			.tddq_input { width:calc(100% - 80px); padding-right:20px; }
			.tddq_input input.form-control { background-color:#F0F2F5;border-radius:5px;margin-bottom:0; }
		</style>
		<script type="text/javascript">
			$('#tddq_apply').click(function(){
				wcmc_update_order_review();
			});
			$('#tddq_remove').click(function(){
				$('input[name="tddq_point"]').val(0);
				wcmc_update_order_review();
			});
		</script>
		<?php
		}
	}
}
if(!function_exists('tddq_checkout_review_order') && tddq_pay_type_check(1) ) {
	add_action('woocommerce_checkout_review_order','tddq_checkout_review_order', 2);
	function tddq_checkout_review_order() {
		$ci =& get_instance();
		$point = 0;
		if( $ci->input->post() && is_user_logged_in() ) {
            $total = $ci->cart->total();
			$tddq_point = (int)$ci->input->post('tddq_point');
			$tddq_config = tddq_config();
			$error = tddq_checkout_check( $tddq_point, $ci->data['user']->id, $ci->cart->total() );
			if(!is_skd_error($error)) $tddq_point = $tddq_point*$tddq_config['point_conver'];
			else $tddq_point = 0;
			if(function_exists('wcmc_cart_get_template_version') && version_compare( wcmc_cart_get_template_version() , '1.3.0' ) >= 0 ) {
				?>
				<tr class="tddq-price">
					<td><?php echo __('Điểm thưởng');?></td>
					<td><?php echo number_format($tddq_point);?>đ</td>
				</tr>
				<?php
			} else {
				$point = tddq_calculate_point(wcmc_order_total(), $ci->data['user']->id);
				?>
				<tr class="tddq">
					<td colspan="2">
						<p style="margin-bottom:5px;">Sử dụng điểm thanh toán. bạn hiện có (<?php echo get_user_meta($ci->data['user']->id, 'tddq_point', true);?>) điểm</p>
						<div class="tddq_input">
							<input type="text" name="tddq_point" class="form-control" value="<?php echo (int)$ci->input->post('tddq_point');?>">
						</div>
						<div class="tddq_button">
							<button class="btn" type="button" id="tddq_apply">Sử dụng</button>
						</div>
					</td>
				</tr>
				<tr class="tddq-price">
					<td><?php echo __('Phần thưởng');?></td>
					<td><strong><?php echo number_format($point);?> điểm</strong></td>
				</tr>
				<style type="text/css">
					.woocommerce-cart-content .tddq .tddq_input {
						float: left; width: calc(100% - 60px);
					}
					.woocommerce-cart-content .tddq .tddq_input .form-control {
						height:40px; line-height:40px;
					}
					.tddq .tddq_button {
						float: left; width: 60px;
					}
					.tddq .tddq_button button {
						height: 40px; border-radius: 0;
					}
				</style>
				<script type="text/javascript">
					$('#tddq_apply').click(function(){
						data = {
							action : 'tddq_ajax_checkout_check',
							point  : $('input[name="tddq_point"]').val(),
						};
						$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');
						$jqxhr.done(function( data ) {
							if( data.status == 'success' ) {
								wcmc_update_order_review();
							}
							else {
								var notice = data.message;
								$('.woocommerce-checkout').prepend(notice);
								$('html, body').animate({
									scrollTop: $(".woocommerce-checkout .toast").offset().top - 100
								}, 500);
							}
						});
						return false;
					});
				</script>
			<?php
			}
        }
	}
}
if(!function_exists('tddq_checkout_review_order_before' ) ) {
	add_action('woocommerce_checkout_review_order_before', 	'tddq_checkout_review_order_before', 2);
	function tddq_checkout_review_order_before() {
		$ci =& get_instance();
		$point = 0;
		if (isset($_COOKIE['user_affiliate'])) {
			$cookie=$_COOKIE['user_affiliate'];
		}
		
		if (isset($cookie)) {
			
            $where=array('cookie'=>$cookie);
            $tontai=get_affiliate_history(array('where' => $where, 'params' => array()));
            
			$total = $ci->cart->total();
			$tddq_config = tddq_config();
			$tddq_point = (int)$ci->input->post('tddq_point');
			$error = tddq_checkout_check( $tddq_point, $tontai->user_id, $ci->cart->total() );
			if(!is_skd_error($error)) {
				$total = wcmc_order_total();
				$point = tddq_calculate_point($total, $tontai->user_id);
			}?>:
			<div class="woocommerce-box">
			<link href="https://fonts.googleapis.com/css?family=Trirong" rel="stylesheet">
			<div class="text-center">
				<p>CTV chia sẻ link sẽ nhận được</p>
				<h3 style="font-weight:100;font-size:35px;font-family: 'Trirong', serif;"><?php echo $point;?> điểm thưởng</h3>
				<p>Sau khi đơn hàng hoàn thành</p>
			</div>
		</div>
		<?php }else{
		if( $ci->input->post() && is_user_logged_in() ) {
			$total = $ci->cart->total();
			$tddq_config = tddq_config();
			$tddq_point = (int)$ci->input->post('tddq_point');
			$error = tddq_checkout_check( $tddq_point, $ci->data['user']->id, $ci->cart->total() );
			if(!is_skd_error($error)) {
				$total = wcmc_order_total();
				$point = tddq_calculate_point($total, $ci->data['user']->id);
			}
		?>
		<div class="woocommerce-box">
			<link href="https://fonts.googleapis.com/css?family=Trirong" rel="stylesheet">
			<div class="text-center">
				<p>Bạn sẽ nhận được</p>
				<h3 style="font-weight:100;font-size:35px;font-family: 'Trirong', serif;"><?php echo $point;?> điểm thưởng</h3>
				<p>Sau khi đơn hàng hoàn thành</p>
			</div>
		</div>
		<style>
		</style>
		<?php
		}
		}
	}
}
if(!function_exists('tddq_price_checkout_review') && tddq_pay_type_check(1)) {
	add_filter('wcmc_order_total', 	'tddq_price_checkout_review');
	function tddq_price_checkout_review($total) {
		$ci =& get_instance();
		if( $ci->input->post() && is_user_logged_in() ) {
			$tddq_point = (int)$ci->input->post('tddq_point');
			$config = tddq_config();
			$error = tddq_checkout_check( $tddq_point, $ci->data['user']->id, $ci->cart->total() );
			if(!is_skd_error($error)) $total = $total - $tddq_point*$config['point_conver'];
		}
		if($total < 0) $total = 0;
		return $total;
	}
}
if(!function_exists('tddq_checkout_process')) {
	add_action('woocommerce_checkout_process', 'tddq_checkout_process', 1);
	function tddq_checkout_process() {
		$ci =& get_instance();
		if(is_user_logged_in()) {
			$tddq_point    = (int)$ci->input->post('tddq_point');
			$account_point = (int)get_user_meta($ci->data['user']->id,'tddq_point', true);
			if( $tddq_point > $account_point ) {
				wcmc_add_notice( __('Bạn không đủ điểm thưởng'), 'error' );
			}
		}
	}
}
if(!function_exists('tddq_checkout_save'))  {
	add_action( 'woocommerce_checkout_order_after_save', 'tddq_checkout_save', 10, 1 );
	function tddq_checkout_save( $id ) {
		$ci =& get_instance();
        $order = wcmc_get_order( $id, false, true );

	        if(have_posts($order) ) {
	        	if (!is_user_logged_in()) {
	        		$customer = get_user_by('email', $order->billing_email);
	        		$ci->data['user']=(object)$customer;
	        	}
				$tddq_config = tddq_config();
				$tddq_point    = (int)$ci->input->post('tddq_point');
				$account_point = (int)get_user_meta($ci->data['user']->id, 'tddq_point', true);
				if (empty($account_point)) {
					$account_point=0;
				}
				$error = tddq_checkout_check( $tddq_point, $ci->data['user']->id, $order->total );
				if(!is_skd_error($error)) {
					$model = get_model('plugins', 'backend');
					update_order_meta($id, '_tddq_point_payment', $tddq_point*$tddq_config['point_conver']);
					update_user_meta($ci->data['user']->id, 'tddq_point', $account_point - $tddq_point );
					insert_tddq_history([
						'user_id' => $ci->data['user']->id,
						'point'   => -$tddq_point,
						'content' => 'Bạn đã dùng diểm thưởng (- '.$tddq_point.' điểm ) thanh toán cho đơn hàng đơn hàng: '.$order->code
					]);
					$model->settable('wcmc_order');
					if($tddq_point*$tddq_config['point_conver'] > $order->total ) $order->total = 0;
					else $order->total -= $tddq_point*$tddq_config['point_conver'];
					$model->update_where(array('total' => $order->total), array( 'id'=> $id ) );
				}
	            $point = tddq_calculate_point($order->total, $ci->data['user']->id);
				if($point != 0) update_order_meta($id, '_tddq_point', $point );
	        }
    	
	}
}
if(!function_exists( 'tddq_item_totals' ) ) {
	add_filter( 'get_order_item_totals', 'tddq_item_totals', 10, 2 );
	/**
	 * Update the order meta with field value
	 */
	function tddq_item_totals( $totals, $order ) {
		if( isset($order->_tddq_point_payment) ) {
			$totals[4]['label'] = 'Điểm thưởng';
			$totals[4]['value'] = number_format($order->_tddq_point_payment)._price_currency();
		}
		return $totals;
	}
}


if(!function_exists('tddq_order_action_wc_completed') ) {
	function tddq_order_action_wc_completed( $order ) {
		$ci =& get_instance();
		$action = $ci->input->post('wcmc-action');
		$action = removeHtmlTags($action);
		cache_libary();
				$list_cache = scandir('views/cache');
				foreach ($list_cache as $key => $value) {
					if( $value == 'index.html' ) continue;
					if( $value == '.htaccess' ) continue;
					if( $value == '.' ) continue;
					if( $value == '..' ) continue;
					if( file_exists( 'views/cache/'.$value ) ) unlink( 'views/cache/'.$value );
				}
				delete_cache('user_', true);
				delete_cache('metabox_', true);

		if( $order->status != $action ) {
            if(isset($order->_tddq_point)) {
                $user2 = get_user($order->user_share);
                if(have_posts($user2)) {
                	$a=get_user_meta($order->user_share, 'tddq_point', true);
                    update_user_meta($order->user_share, 'tddq_point', $order->_tddq_point + $a);
                    insert_tddq_history([
                        'user_id' => $order->user_share,
                        'point'   => $order->_tddq_point,
                        'content' => 'Bạn đã nhận được điểm thưởng (+ '.$order->_tddq_point.' điểm ) từ đơn hàng: '.$order->code
                    ]);
                }
            }
		}
	}
	add_action( 'woocommerce_order_action_wc-completed', 'tddq_order_action_wc_completed' ,100);
}