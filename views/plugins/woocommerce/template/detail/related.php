<?php
$product_related      	= get_option('product_related', array( 'style' => 'slider', 'columns' => 4, 'posts_per_page' => 12, ) );

if( $product_related['style'] != 'disabled' ) {

	if(have_posts($related_products)) { ?>
	<div class="box product-slider-horizontal products-related">
		<div class="title-header"><h3 class="header">Sản Phẩm Liên Quan</h3></div>
		<div class="box-content">
			<?php if( $product_related['style'] == 'slider' ) include 'related_slider.php'; ?>
			<?php if( $product_related['style'] == 'grid' )   include 'related_grid.php'; ?>
		</div>
	</div>
	<?php }
}
