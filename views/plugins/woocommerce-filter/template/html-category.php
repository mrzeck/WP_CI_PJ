<ul class="wcmc-filter-list  wcmc-filter-list-thuonghieu ">

	<?php foreach ($_listCategory as $key => $category): ?>
	<li class="wcmc-filter-list_item wcmc-filter-list_item_<?=$category->id ?> hidden  <?php echo woocommerce_filter_category_chose($category->id);?>">
        <a href="<?php echo wcmc_filter_get_url('category', $category->id);?>" class="wcmc-filter-list_link"><?php echo $category->name;?></a>
        <!-- <?php if( isset($category->child) && have_posts($category->child) ): ?>
		<ul class="wcmc-filter-list-child">
			<?php foreach ($category->child as $sub_category): ?>

			<li class="wcmc-filter-list_item wcmc-filter-list_item_<?=$sub_category->id ?> hidden <?php echo woocommerce_filter_category_chose($sub_category->id);?>">

				
		        <a href="<?php echo wcmc_filter_get_url('category', $sub_category->id);?>" class="wcmc-filter-list_link "><?php echo $sub_category->name;?></a>
		    </li>
			<?php endforeach ?>
		</ul>
		<?php endif;?> -->
    </li>
	<?php endforeach ?>
</ul>

<style>
	.wcmc-filter-list-thuonghieu  li{position:relative;width:12.41% !important;float:left;}
</style>