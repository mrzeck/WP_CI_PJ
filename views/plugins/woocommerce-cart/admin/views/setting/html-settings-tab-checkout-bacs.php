<?php

$setting = get_option('_setting_checkout_bacs');

$default = array (
    'woocommerce_bacs_enabled' 	=> 0,
    'woocommerce_bacs_title' 	=> 'Chuyển khoản ngân hàng',
    'woocommerce_bacs_img' 		=> '',
    'bank' => array (
        array (
            'bacs_account_name' 	=> '',
            'bacs_account_number' 	=> '',
            'bacs_bank_name'		=> '',
            'bacs_bank_branch' 		=> '',
        ),
    )
);

if( have_posts($setting) ) $setting = array_merge($default, $setting);
else $setting = $default;
?>
<div class="clearfix"></div><br/ >
<div class="box">
	<div class="box-content">
		<?php
			$input = array(
				'field' => 'woocommerce_bacs_enabled', 'label'	=> 'Bật chuyển khoản ngân hàng', 'type'  => 'checkbox',
				'options' => 1, 'after' => '<div>', 'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Bật/Tắt</label></div>';
			echo '<div class="col-md-10">';
			echo _form($input, $setting['woocommerce_bacs_enabled']);
			echo '</div><div class="clearfix"></div>';



			$input = array(
				'field' => 'woocommerce_bacs_title', 'label'	=> 'Tiêu đề', 'type'  => 'title', 'after' => '<div>', 'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Tiêu đề</label></div>';
			echo '<div class="col-md-10">';
			echo _form($input, $setting['woocommerce_bacs_title']);
			echo '</div><div class="clearfix"></div>';

			$input = array(
				'field' => 'woocommerce_bacs_img', 'label'	=> 'Icon', 'type'  => 'image', 'after' => '<div>', 'before' => '</div>',
			);
			echo '<div class="col-md-2"><label for="">Icon</label></div>';
			echo '<div class="col-md-10">';
			echo _form($input, $setting['woocommerce_bacs_img']);
			echo '</div><div class="clearfix"></div>';
		?>

		<div class="col-md-2"><label for=""><?php echo __('Ngân Hàng');?></label></div>

		<div class="col-md-10" id="bacs_accounts">
			<table class="table table-bordered wcmc-setting-bacs" cellspacing="0">
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th><?php echo __('Tên tài khoản');?></th>
						<th><?php echo __('Số tài khoản');?></th>
						<th><?php echo __('Tên ngân hàng');?></th>
						<th><?php echo __('Chi nhánh');?></th>
						<th></th>
					</tr>
				</thead>
				<tbody class="accounts ui-sortable">
					<?php foreach ($setting['bank'] as $key => $account): ?>
					<tr class="account wcmc-setting-bacs__item">
						<td class="sort"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['bacs_account_name'];?>" name="bacs_account_name[<?php echo $key;?>]"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['bacs_account_number'];?>" name="bacs_account_number[<?php echo $key;?>]"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['bacs_bank_name'];?>" name="bacs_bank_name[<?php echo $key;?>]"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['bacs_bank_branch'];?>" name="bacs_bank_branch[<?php echo $key;?>]"></td>
						<td class="sort">
							<button class="btn-delete btn-icon btn-red">Xóa</button>
						</td>
					</tr>
					<?php endforeach ?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="7">
							<a href="#" class="add btn-white btn">+ Thêm tài khoản</a>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>



<div class="clearfix"></div>




<style type="text/css">
	.radio label, .checkbox label { padding-left: 0; }
</style>

<script type="text/javascript">
	$(function() {
		$('#bacs_accounts').on( 'click', 'a.add', function(){

			var size = jQuery('#bacs_accounts').find('tbody .account').length;

			jQuery('<tr class="account wcmc-setting-bacs__item">\
					<td class="sort"></td>\
					<td><input type="text" class="form-control" name="bacs_account_name[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_account_number[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_bank_name[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_bank_branch[' + size + ']" /></td>\
					<td class="sort">\
						<button class="btn-delete btn-icon btn-red">Xóa</button>\
					</td>\
				</tr>').appendTo('#bacs_accounts table tbody');

			return false;
		});

		$('#bacs_accounts').on( 'click', 'button.btn-delete', function(){
			$(this).closest('tr.wcmc-setting-bacs__item').remove();
			return false;
		});

		$('#mainform').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_setting_checkout_bacs_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>