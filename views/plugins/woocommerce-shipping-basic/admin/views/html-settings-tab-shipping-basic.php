<?php
$setting = get_option('_setting_shipping');

$default = array (
	'shipping_base_required' 	=> 0,
    'shipping' => array (
        array (
            'shipping_name' 	=> '',
            'shipping_price' 	=> '',
            'shipping_key' 		=> '',
        ),
    )
);

if( have_posts($setting) ) $setting = array_merge($default, $setting);
else $setting = $default;
?>
<div class="box">
	<div class="box-content">

		<?php
			echo '<div class="col-md-2"><label for="">Bắt buộc</label></div>';
			echo '<div class="col-md-6">';
			?>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="shipping_base_required" id="shipping_base_required" class="icheck " value="shipping_base_required" <?php echo($setting['shipping_base_required']==1)?'checked':'';?>>&nbsp;&nbsp;Bắt buộc khi đặt hàng
				</label>
			</div>
			<p style="color:#999;margin:5px 0 5px 0;">Khi đặt hàng bắt buộc phải chọn khu vực đặt hàng.</p>
			<?php
			echo '</div><div class="clearfix"></div>';
		?>
			
		</div>
		
		<div class="clearfix"></div>
		
		<div class="col-md-2"><label for=""><?php echo __('Khu vực');?></label></div>
		<div class="col-md-6" id="bacs_accounts">
			<table class="table table-bordered" cellspacing="0">
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th>Khu vực</th>
						<th>Key</th>
						<th>Số tiền</th>
					</tr>
				</thead>
				<tbody class="accounts ui-sortable">
					<?php foreach ($setting['shipping'] as $key => $account): ?>
					<tr class="account ui-sortable-handle shipping-basic__item">
						<td class="sort"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['shipping_name'];?>" name="shipping_name[<?= $key;?>]"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['shipping_key'];?>" name="shipping_key[<?= $key;?>]"></td>
						<td><input type="text" class="form-control" value="<?php echo $account['shipping_price'];?>" name="shipping_price[<?= $key;?>]"></td>
						<td class="sort">
							<button class="btn-delete btn-icon btn-red">Xóa</button>
						</td>
					</tr>
					<?php endforeach ?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="7">
							<a href="#" class="add btn-white btn">+ Thêm khu vực</a>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="clearfix"></div>
	</div>
</div>


<style type="text/css">
	.radio label, .checkbox label { padding-left: 0; }
</style>

<script type="text/javascript">
	$(function() {
		$('#bacs_accounts').on( 'click', 'a.add', function(){

			var size = $('#bacs_accounts').find('tbody .account').length;

			$('<tr class="account">\
					<td class="sort"></td>\
					<td><input type="text" class="form-control" name="shipping_name[' + size + ']"></td>\
					<td><input type="text" class="form-control" name="shipping_key[' + size + ']"></td>\
					<td><input type="text" class="form-control" name="shipping_price[' + size + ']"></td>\
					<td class="sort">\
						<button class="btn-delete btn-icon btn-red">Xóa</button>\
					</td>\
				</tr>').appendTo('#bacs_accounts table tbody');

			return false;
		});

		$('#bacs_accounts').on( 'click', 'button.btn-delete', function(){
			$(this).closest('tr.shipping-basic__item').remove();
			return false;
		});
	});
</script>