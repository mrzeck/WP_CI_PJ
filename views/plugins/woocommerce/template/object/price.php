<div class="item-pr-price">
 	<?php if(!empty($val->price_sale)) { ?>
    	<span class="product-item-price"><?= number_format($val->price_sale);?><?php echo _price_currency();?></span>
    	<del class="product-item-price-old"><?= number_format($val->price);?><?php echo _price_currency();?></del>
  	<?php } else if($val->price == 0) { ?>
    	<span class="product-item-price"><?php echo _price_none();?></span>
  	<?php } else {?>
    	<span class="product-item-price"><?= number_format($val->price);?><?php echo _price_currency();?></span>
  	<?php } ?>
</div>