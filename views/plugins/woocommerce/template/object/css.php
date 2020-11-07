<!-- woocommerce style custom -->
<?php
	$product_border_size      	= get_option('product_border_size');
	$product_border_style      	= get_option('product_border_style');
	$product_border_color      	= get_option('product_border_color');

	$border = $product_border_size.'px '.$product_border_style.' '.$product_border_color;
?>
<style type="text/css">
.woocommerce-pr-object { border:<?php echo $border;?>; }
.product-slider-horizontal .item .title .item-pr-price .product-item-price {
	<?php echo (get_option('product_price_color'))?'color:'.get_option('product_price_color').';':'';?>
}
</style>