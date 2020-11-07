<div id="widget_related_products">
	<?php foreach ($related_products as $key => $val): ?>
		<div class="col-md-<?php echo ( $columns != 5) ? 12/$columns : 15;?>">
			<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
		</div>
	<?php endforeach ?>
</div>