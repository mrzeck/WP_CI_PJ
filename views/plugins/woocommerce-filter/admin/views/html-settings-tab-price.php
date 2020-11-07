<?php
	$filter_price = get_option('wcmc_filter_price_option', serialize(array()));
	if (is_array($filter_price)) {
		$filter_price=serialize($filter_price);
	}
	$filter_price = unserialize( $filter_price );
?>

<div class="" id="wcmc-filter-price">
	<table class="table table-bordered" cellspacing="0">
		<thead>
			<tr>
				<th>Min Price</th>
				<th>Max Price</th>
				<th>Label</th>
				<th></th>
			</tr>
		</thead>
		<tbody class="wcmc-filter-price-tbody ui-sortable">

			
				<?php if( have_posts($filter_price) ) {
					foreach ($filter_price as $key => $value) { ?>
					<tr class="wcmc-filter-price__item">
						<td><input type="number" class="form-control" value="<?php echo $value['min_price'];?>" 	name="filter_price[<?php echo $key;?>][min_price]"></td>
						<td><input type="number" class="form-control" value="<?php echo $value['max_price'];?>" 	name="filter_price[<?php echo $key;?>][max_price]"></td>
						<td><input type="text" class="form-control"   value="<?php echo $value['label'];?>" 	name="filter_price[<?php echo $key;?>][label]"></td>
						<td class="sort">
							<button class="btn-delete btn-icon btn-red">Xóa</button>
						</td>
					</tr>
					<?php }
				} else { ?>
					<tr class="wcmc-filter-price__item">
						<td><input type="number" class="form-control" value="" 	name="filter_price[0][min_price]"></td>
						<td><input type="number" class="form-control" value="" 	name="filter_price[0][max_price]"></td>
						<td><input type="text" class="form-control" value="" 	name="filter_price[0][label]"></td>
						<td class="sort">
							<button class="btn-delete btn-icon btn-red">Xóa</button>
						</td>
					</tr>
				<?php } ?>
			
			
		</tbody>
		<tfoot>
			<tr>
				<th colspan="7">
					<a href="#" class="add btn-white btn">+ Thêm khoản giá</a>
					<button type="submit" class="btn-icon btn-green" id="item-data-save"><i class="fas fa-save"></i>Lưu</button>
				</th>
			</tr>
		</tfoot>
	</table>
</div>


<script type="text/javascript">
	jQuery(function() {
		$('#wcmc-filter-price').on( 'click', 'a.add', function(){

			var size = jQuery('#wcmc-filter-price').find('tbody .wcmc-filter-price__item').length;

			jQuery('<tr class="wcmc-filter-price__item">\
					<td><input type="number" class="form-control" name="filter_price[' + size + '][min_price]"></td>\
					<td><input type="number" class="form-control" name="filter_price[' + size + '][max_price]"></td>\
					<td><input type="text" 	 class="form-control" name="filter_price[' + size + '][label]"></td>\
					<td class="sort"> <button class="btn-delete btn-icon btn-red">Xóa</button> </td>\
				</tr>').appendTo('#wcmc-filter-price table tbody');

			return false;
		});

		$('#wcmc-filter-price').on( 'click', 'button.btn-delete', function(){
			$(this).closest('tr.wcmc-filter-price__item').remove();
			return false;
		});


		$('#mainform').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_filter_ajax_price_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {

	  			show_message(data.message, data.type);
			});

			return false;

		});
	});
</script>