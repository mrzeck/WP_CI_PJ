<div class="box product-slider-vertical products-viewed">
	<div class="title-header"><h3 class="header">Sản Phẩm Đã Xem</h3></div>
	<div class="box-content">
		<?php foreach ($viewed_product as $key => $val): ?>
			<?php wcmc_get_template('loop/item_product_vertical', array('val' => $val));?>
		<?php endforeach ?>
	</div>
</div>