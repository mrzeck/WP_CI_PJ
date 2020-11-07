<?php foreach ($_listAttribute as $key_attribute => $group_attributes): ?>

	<?php $attributes = woocommerce_options_item_gets($group_attributes->id);?>

	<?php foreach ($attributes as $op): ?>
    <label class="wcmc-label-attribute <?php echo woocommerce_filter_taxonomy_chose($op->id, 'attribute-'.$group_attributes->slug);?>">
        <a href="<?php echo wcmc_filter_get_url( 'attribute-'.$group_attributes->slug, $op->id);?>" class="box-option-<?php echo $group_attributes->option_type;?>" class="wcmc-filter-list_link" style="background-color: <?php echo $op->value;?>"><?php echo $op->title;?></a>
    </label>
    <?php endforeach ?>
<?php endforeach ?>

