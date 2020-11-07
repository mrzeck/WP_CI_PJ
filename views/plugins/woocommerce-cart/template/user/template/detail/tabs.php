<?php
/**
 * woocommerce_product_tabs filters
 * @hook woocommerce_detail_tab_default filters
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if(have_posts($tabs)): ?>


<div role="tabpanel">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<?php foreach ( $tabs as $key => $tab ) : ?>
		<li class="<?= ($key == 'content')?'active':'';?>">
			<a href="#tab-<?= $key;?>" aria-controls="<?= $key;?>" role="tab" data-toggle="tab"><?= $tab['title'];?></a>
		</li>
		<?php endforeach; ?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<?php foreach ( $tabs as $key => $tab ) : ?>
		<div role="tabpanel" class="tab-pane <?= ($key == 'content')?'active':'';?>" id="tab-<?= $key;?>">
			<?php call_user_func( $tab['callback'], $key, $tab ) ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>


<?php endif;?>