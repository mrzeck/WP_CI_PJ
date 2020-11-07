<?php

$setting = get_option('_setting_checkout_cod');

$default = array (
	'woocommerce_cod_enabled' 	=> 0,
    'woocommerce_cod_title' 	=> 'Trả tiền khi nhận hàng (COD)',
    'woocommerce_cod_img' 	=> '',
);



if( have_posts($setting) ) $setting = array_merge($default, $setting);

else $setting = $default;

if( $ci->input->post() ) {

	$post = $ci->input->post();

	if(!isset( $post['woocommerce_cod_enabled'] ) ) $post['woocommerce_cod_enabled'] = 0;

	$setting = array_merge($setting, (array)$post);
}
?>
<div class="clearfix"></div><br/ >
<div class="box">
	<div class="box-content">
		<?php
			$input = array(
				'field' => 'woocommerce_cod_enabled',
				'label'	=> 'Bật trả tiền khi nhận hàng',
				'type'  => 'checkbox',
				'options' => 1,
				'after' => '<div>',
				'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Bật/Tắt</label></div>';
			echo '<div class="col-md-6">';
			// echo $setting['woocommerce_cod_enabled'];
			echo _form($input, $setting['woocommerce_cod_enabled']);
			echo '</div><div class="clearfix"></div>';

			$input = array(
				'field' => 'woocommerce_cod_title',
				'label'	=> 'Tiêu đề', 'type'  => 'title', 'after' => '<div>', 'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Tiêu đề</label></div>';
			echo '<div class="col-md-6">';
			echo _form($input, $setting['woocommerce_cod_title']);
			echo '</div><div class="clearfix"></div>';

			$input = array(
				'field' => 'woocommerce_cod_img', 'label'	=> 'Icon', 'type'  => 'image', 'after' => '<div>', 'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Icon</label></div>';
			echo '<div class="col-md-6">';
			echo _form($input, $setting['woocommerce_cod_img']);
			echo '</div><div class="clearfix"></div>';

		?>
	</div>
</div>
<style type="text/css">
	.radio label, .checkbox label { padding-left: 0; }
</style>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_setting_checkout_cod_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>