<?php
	$category_row_count        = get_option('category_row_count');

	$category_row_count_tablet = get_option('category_row_count_tablet');
	
	$category_row_count_mobile = get_option('category_row_count_mobile');

	$col['lg'] = ( $category_row_count != 5) ? 12/$category_row_count : 15;

	$col['md'] = ( $category_row_count != 5) ? 12/$category_row_count : 15;

	$col['sm'] = ( $category_row_count_tablet != 5) ? 12/$category_row_count_tablet : 15;

	$col['xs'] = ( $category_row_count_mobile != 5) ? 12/$category_row_count_mobile : 15;

	$col = 'col-xs-'.$col['xs'].' col-sm-'.$col['sm'].' col-md-'.$col['md'].' col-lg-'.$col['lg'].'';
?>
<div class="list-item-product">
	<div class="">
		<?php foreach ($objects as $key => $val): ?>
			<div class="<?php echo $col;?>">
			<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
			</div>
		<?php endforeach ?>
	</div>
</div>