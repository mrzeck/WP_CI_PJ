<div class="products-detail">
	<?php
	/**
	 * woocommerce_products_detail_before hook.
	 *
	 * @woocommerce_products_detail_breadcrumb - 10 - Tạo breadcrumb
	 */
	do_action( 'woocommerce_products_detail_before', $object );
	?>
	<div class="row">
		<div class="col-md-7" id="surround">
			<?php
			/**
			 * woocommerce_products_detail_slider hook.
			 *
			 * @hooked woocommerce_product_slider_vertical - 10 - Slider ảnh sản phẩm
			 */
			do_action( 'woocommerce_products_detail_slider', $object );
			?>
		</div>
		<div class="col-md-5">
			<h1 class="title-head"><?= $object->title;?></h1>
			<?php
			/**
			 * woocommerce_products_detail_info hook.
			 *
			 * @hooked woocommerce_product_price 		- 10 - hiển thị giá sản phẩm
			 * @hooked woocommerce_product_description  - 20 - hiển thụ mô tả sản phẩm
			 * @hooked woocommerce_detail_social        - 30 - hiển thị chia sẻ sản phẩm
			 * @plugin woocommerce_cart - @hook woocommerce_product_add_cart - 40 - Hiển thị button add cart
			 */
			do_action( 'woocommerce_products_detail_info', $object );
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-9"> 
			<?php
			/**
			 * woocommerce_products_detail_tabs hook.
			 *
			 * @hooked woocommerce_detail_display_tabs 		- 10 - Hiển thị tabs thông tin sản phẩm
			 * @hooked woocommerce_output_related_products  - 20 - Hiển thị sản phẩm liên quan
			 */
			do_action( 'woocommerce_products_detail_tabs', $object );
			?> </div>
		<div class="col-md-3">
			<?php
			/**
			 * woocommerce_products_detail_sidebar hook.
			 *
			 * @hooked woocommerce_viewed_products_sidebar - 10 - Sidebar sản phẩm
			 */
			do_action( 'woocommerce_products_detail_sidebar', $object );
			?>
		</div>
	</div>
	<?php
	/**
	 * woocommerce_products_detail_after hook.
	 */
	do_action( 'woocommerce_products_detail_after', $object );
	?>
</div>