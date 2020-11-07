<?php
/**
 * Email Header
 * @version 1.8
 */
$ci =& get_instance();
?>
<!DOCTYPE html>
<html lang="<?php echo $ci->language['current'];?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<title><?php echo get_option('general_label'); ?></title>
	</head>
	<body marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="template-email">
			<div style="width: 100%; background-color:#F4F7F0; padding:10px;font-family: Arial, Helvetica, sans-serif; line-height: 25px; font-size: 13px;">
				<div style="width: 700px; margin: 0 auto;">
					<div style="overflow: hidden;background-color: #3598DB; padding:30px 10px; width: 100%;">
						<div style="float: left; width: 50%">
							<h1 style="text-align: left; color:#fff; margin: 0;"><?php echo get_option('general_label');?></h1>
						</div>
						<div style="float: left; width: 50%; text-align: right">
							<p style="font-size: 18px; margin: 0px;"><strong>Đơn hàng: <?php echo $order->code;?></strong></p>
							<p style="color:#4C596B"><strong>Ngày: <?php echo date('d-m-Y');?></strong></p>
						</div>
					</div>