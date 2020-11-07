<?php foreach ($_listTaxonomy as $key_taxonomy => $taxonomies): ?>
<?php
	$Mtaxonomy = get_cate_type($key_taxonomy); 
?>
<ul class="wcmc-filter-list">
	<?php foreach ($taxonomies as $taxonomy): ?>
	<li class="wcmc-filter-list_item <?php echo woocommerce_filter_taxonomy_chose($taxonomy->id, 'filter-'.$key_taxonomy);?>">
        <a href="<?php echo wcmc_filter_get_url('filter-'.$key_taxonomy, $taxonomy->id);?>" class="wcmc-filter-list_link" title='<?=$taxonomy->name ?>'><?= str_word_cut(removeHtmlTags($taxonomy->name), 3);?></a>
    </li>
	<?php endforeach ?>
</ul>
<?php endforeach ?>