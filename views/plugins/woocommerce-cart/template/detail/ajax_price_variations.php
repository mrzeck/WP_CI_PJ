<?php if(have_posts($wcmc_data)) {?>
	<?php if($wcmc_data->price == 0 && $wcmc_data->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo _price_none();?></span></p>
	<?php } ?>

	<?php if($wcmc_data->price != 0 && $wcmc_data->price_sale != 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($wcmc_data->price_sale);?><?php echo _price_currency();?></span></p>
	<p class="price-sale"><del id="product-detail-price-sale"><?php echo number_format($wcmc_data->price);?><?php echo _price_currency();?></del></p>
	<?php } ?>

	<?php if($wcmc_data->price != 0 && $wcmc_data->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($wcmc_data->price);?><?php echo _price_currency();?></span></p>
	<?php } ?>
<?php } ?>