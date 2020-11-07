<?php
$wcmc_email = get_option('wcmc_email',[
	'customer_order_new' 	=> 'on',
	'admin_order_new' 		=> 'on',
]);
?>


<div class="box">
	<div class="box-content">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-5">
					<label for="wcmc_email[customer_order_new]" class="control-label">Đơn hàng mới - Khách hàng</label>
					<p style="color:#999;margin:5px 0 5px 0;">Gửi email xác nhận cho khách hàng khi đặt hàng thành công.</p>
				</div>
				<div class="col-md-7 text-right">
					<div class="group">
						<div class="radio">
							<label><input type="radio" name="wcmc_email[customer_order_new]" class="icheck " value="on" <?php echo ($wcmc_email['customer_order_new'] == 'on')?'checked':'';?>>&nbsp;&nbsp;Bật</label>
						</div>
						<div class="radio">
							<label> <input type="radio" name="wcmc_email[customer_order_new]" class="icheck " value="off" <?php echo ($wcmc_email['customer_order_new'] == 'off')?'checked':'';?>>&nbsp;&nbsp;Tắt</label>
						</div>
					</div>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-5">
					<label for="wcmc_email[admin_order_new]" class="control-label">Đơn hàng mới - Quản trị</label>
					<p style="color:#999;margin:5px 0 5px 0;">Gửi email xác nhận cho admin khi đặt hàng thành công.</p>
				</div>
				<div class="col-md-7 text-right">
					<div class="group">
						<div class="radio">
							<label> <input type="radio" name="wcmc_email[admin_order_new]" class="icheck " value="on" <?php echo ($wcmc_email['admin_order_new'] == 'on')?'checked':'';?>>&nbsp;&nbsp;Bật</label>
						</div>
						<div class="radio">
							<label> <input type="radio" name="wcmc_email[admin_order_new]" class="icheck " value="off" <?php echo ($wcmc_email['admin_order_new'] == 'off')?'checked':'';?>>&nbsp;&nbsp;Tắt</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo notice('warning','Để sử dụng được chức năng gửi email bạn phải cấu hình SMTP cho hệ thống.');?>

<style>
	.group { margin-bottom:10px; }
	.radio, .checkbox {
		display: inline-block;
	}
</style>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_ajax_setting_email_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>