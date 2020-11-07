<ul class="wcmc-filter-list wcmc-filter-price_ul">
	<?php foreach ($_listPrice as $key => $value): ?>

	<li class="wcmc-filter-list_item <?php echo woocommerce_filter_price_chose($key);?> price_<?=$key ?>">
        <a data-href="<?php echo wcmc_filter_get_url('price', $key);?>" class="wcmc-filter-list_link"><?php echo $value;?></a>
    </li>
	<?php endforeach ?>
</ul>

