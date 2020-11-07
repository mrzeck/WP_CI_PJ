<?php foreach ($_listTaxonomy as $key_taxonomy => $taxonomies): ?>
<?php
	$Mtaxonomy = get_cate_type($key_taxonomy); 
?>

<ul class="wcmc-filter-list wcmc-filter-list-thuonghieu">
	<?php foreach ($taxonomies as $taxonomy): ?>

	<li class="wcmc-filter-list_item <?php echo woocommerce_filter_taxonomy_chose($taxonomy->id, 'filter-'.$key_taxonomy);?> id_<?=$taxonomy->id ?>" style="padding-bottom:5px;">
        <a data-href="<?php echo wcmc_filter_get_url('filter-'.$key_taxonomy, $taxonomy->id);?>" class="wcmc-filter-list_link" title='<?=$taxonomy->name ?>'>
        	<?=get_img($taxonomy->image,$taxonomy->name,array('style'=>'height:30px;')) ?></a>
    </li>
	<?php endforeach ?>
</ul>
<?php endforeach ?>