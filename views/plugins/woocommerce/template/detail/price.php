<div class="wcmc-price-detail">
	<?php if($object->price == 0 && $object->price == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo _price_none();?></span></p>
	<?php } ?>

	<?php if($object->price != 0 && $object->price_sale != 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($object->price_sale);?><?php echo _price_currency();?></span></p>
	<p class="price-sale"><del id="product-detail-price-sale"><?php echo number_format($object->price);?><?php echo _price_currency();?></del></p>
	<?php } ?>

	<?php if($object->price != 0 && $object->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($object->price);?><?php echo _price_currency();?></span></p>
	<?php } ?>
</div>