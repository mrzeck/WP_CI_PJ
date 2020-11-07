<?php
/**======================================================
 * ĐÁNH GIÁ SAO SẢN PHẨM
 * ======================================================
 */
add_filter( 'woocommerce_product_tabs', 'wc_product_tab_rating_star' );

function wc_product_tab_rating_star( $tabs ) {

	$tabs['danhgia_tab'] = array(
		'title' 	=> 'Đánh Giá',
		'callback' 	=> 'wc_product_tab_rating_star_content'
	);

	return $tabs;
}

function wc_product_tab_rating_star_content() {

	$ci =& get_instance();

	$object = $ci->data['object'];

	plugin_get_include(RATING_STAR_NAME, 'rating-star-form', array('object' => $object));
}

function product_rating_star_template_detail($object) {

	$rating_star = get_metadata('product', $object->id, 'rating_star', true );
	
	$total_star = (isset($rating_star['star'])) ? $rating_star['star'] : 0;

	$total_count = (isset($rating_star['count'])) ? $rating_star['count'] : 0;

	if( $total_count != 0 ) $total_star = round($total_star/$total_count);

	$setting_color = get_rating_star_setting()['color']['star'];
	?>
	<div class="bizweb-product-reviews-star" style="color: <?php echo $setting_color['detail'];?>; text-align: left; margin-bottom:10px;">
		<?php for( $i = 0; $i < $total_star; $i++ ) {?>
			<i class="fa fa-star" aria-hidden="true" style="color:<?php echo $setting_color['detail'];?>; font-weight: 999;"></i>&nbsp;
		<?php } ?>
		<?php for( $i = 0; $i < (5-$total_star); $i++ ) {?>
			<i class="far fa-star" aria-hidden="true" style="color:#ccc;"></i>&nbsp;
		<?php } ?>
		( <?php echo $total_count;?> Nhận xét )
	</div>
	<?php
}

add_action('woocommerce_products_detail_info', 'product_rating_star_template_detail', 4 );

function product_rating_star_template_object($object) {

	$rating_star = get_metadata('product', $object->id, 'rating_star', true );
	
	$total_star = (isset($rating_star['star'])) ? $rating_star['star'] : 0;

	$total_count = (isset($rating_star['count'])) ? $rating_star['count'] : 0;

	if( $total_count != 0 ) $total_star = round($total_star/$total_count);

	$setting_color = get_rating_star_setting()['color']['star'];
	
	product_rating_star_template($total_count, $total_star, $setting_color['object']);
}

add_action('product_object_info', 'product_rating_star_template_object', 15 );

function product_rating_star_template($total_count, $total_star, $color) {
	if( $total_count != 0 ) $total_star = round($total_star/$total_count);
	?>
	<div class="bizweb-product-reviews-star" style="color: <?php echo $color;?>;margin-bottom:0;font-size:12px">
		<?php if ( $total_count != 0 ): ?>
			<!-- <?php for( $i = 0; $i < $total_star; $i++ ) {?>
				<i class="fa fa-star" aria-hidden="true" style="color:<?php echo $color;?>; font-weight: 999;"></i>&nbsp;
			<?php } ?>
			<?php for( $i = 0; $i < (5-$total_star); $i++ ) {?>
				<i class="far fa-star" aria-hidden="true" style="color:#ccc;"></i>&nbsp;
			<?php } ?> -->
			<span  style="color: <?php echo $color;?>"><?=$total_star ?>/5</span> <i class="fa fa-star" aria-hidden="true" style="color:<?php echo $color;?>; font-weight: 999;"></i>&nbsp;<span style="color:#333"><?=$total_count ?> đánh giá</span> 
		<?php endif ?>
	</div>
	<?php
}
/**======================================================
 * ĐÁNH GIÁ SAO BÀI VIẾT
 * ======================================================
 */
function wc_post_tab_rating_star_content($content) {

	$ci =& get_instance();

	if(is_page('post_detail')) {

		$object = $ci->data['object'];

		if($object->post_type == 'post') {

			ob_start();
			
			plugin_get_include(RATING_STAR_NAME, 'rating-star-form', array('object' => $object));

			$content .= ob_get_contents();

			ob_clean();

			ob_end_flush();
		}
	}

	return $content;
}

add_filter('the_content', 'wc_post_tab_rating_star_content', 99 );