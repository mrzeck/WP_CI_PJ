<?php
/** PRODUCT-DETAIL ******************************************************************/

if ( ! function_exists( 'woocommerce_product_style_header' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_product_style_header() {
		$ci =& get_instance();
		cle_register_style('wcmc-product-detail', WCMC_PATH.'assets/css/wcmc-style.css');
	}

	add_action('cle_enqueue_style', 		'woocommerce_product_style_header');
}

if ( ! function_exists( 'woocommerce_product_style_footer' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_product_style_footer() {
		$ci =& get_instance();
		cle_register_script('wcmc-product-detail', WCMC_PATH.'assets/js/wcmc-script.js');
	}

	add_action('cle_enqueue_script', 'woocommerce_product_style_footer');
}

if ( ! function_exists( 'woocommerce_product_slider_libary' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_product_slider_libary() {
		
		$ci =& get_instance();

		cle_register_script('elevatezoom-master',      WCMC_PATH.'assets/add-on/elevatezoom-master/jquery.elevateZoom-3.0.8.min.js','products');
	}

	add_action('cle_enqueue_script', 		'woocommerce_product_slider_libary');
}

if ( ! function_exists( 'woocommerce_products_detail' ) ) {
	/**
	 * @Hiển thị trang chi tiết sản phẩm
	 */
	function woocommerce_products_detail() {

		$ci =& get_instance();

		wcmc_get_template( 'product_detail' );
	}

	add_action('content_products_detail', 'woocommerce_products_detail', 10);
}

/** product breadcrumb  */
if ( ! function_exists( 'woocommerce_products_detail_breadcrumb' ) ) {
	/**
	 * @Hiển thị breadcrumb trang chi tiết sản phẩm
	 */
	function woocommerce_products_detail_breadcrumb() {

		$ci =& get_instance();

		wcmc_get_template( 'detail/breadcrumb' );
	}

	add_action('woocommerce_products_detail_before', 'woocommerce_products_detail_breadcrumb', 10);
}

/** product slider **/
if ( ! function_exists( 'woocommerce_product_slider' ) ) {
	/**
	 * @Hiển thi slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_product_slider_vertical() {

		$product_gallery      	= get_option('product_gallery', 'product_gallery_vertical' );

		if( $product_gallery == 'product_gallery_vertical' ) wcmc_get_template( 'detail/product_thumb_vertical' );
		else wcmc_get_template( 'detail/product_thumb_horizontal' );
	}

	add_action('woocommerce_products_detail_slider',	'woocommerce_product_slider_vertical', 10);
}

/** product price **/
if ( ! function_exists( 'woocommerce_product_price' ) ) {
	/**
	 * @Hiển Thị giá sản phẩm
	 */
	function woocommerce_product_price() {
		wcmc_get_template('detail/price');
	}

	add_action('woocommerce_products_detail_info', 'woocommerce_product_price', 10);
}

/** product description **/
if ( ! function_exists( 'woocommerce_product_description' ) ) {
	/**
	 * @Mô tả sản phẩm
	 */
	function woocommerce_product_description() {
		wcmc_get_template('detail/description');
	}

	add_action('woocommerce_products_detail_info', 		'woocommerce_product_description', 20);
}

/** product social **/
if ( ! function_exists( 'woocommerce_detail_social' ) ) {
	/**
	 * @chia sẻ mạng xã hội trong trang chi tiết sản phẩm
	 */
	function woocommerce_detail_social() {
		wcmc_get_template('detail/social-share-btn');
	}

	add_action('woocommerce_products_detail_info', 		'woocommerce_detail_social', 30);
}

/** product tabs **/
if ( ! function_exists( 'woocommerce_detail_display_tabs' ) ) {
	/**
	 * @hiển thị tabs
	 */
	function woocommerce_detail_display_tabs() {
		wcmc_get_template('detail/tabs');
	}

	add_action('woocommerce_products_detail_tabs', 		'woocommerce_detail_display_tabs', 10);
}

if ( ! function_exists( 'woocommerce_detail_tab_default' ) ) {
	/**
	 * @tab sản phẩm mặc định
	 */
	function woocommerce_detail_tab_default( $tabs ) {
		// thêm tab mới
		$tabs['content'] = array(
			'title' 	=> 'Nội dung Chi Tiết',
			'priority' 	=> 50,
			'callback' => 'woocommerce_detail_tab_content'
		);
		return $tabs;
	}

	add_filter('woocommerce_product_tabs', 	'woocommerce_detail_tab_default' );
}

if ( ! function_exists( 'woocommerce_detail_tab_content' ) ) {
	/**
	 * @callback tab chi tiết sản phẩm
	 * Hàm gọi tab
	 */
	function woocommerce_detail_tab_content() {

		do_action('woocommerce_detail_tab_content_before');

		wcmc_get_template('detail/tab-content');

		do_action('woocommerce_detail_tab_content_after');
	}
}

/** product related **/
if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	/**
	 * woocommerce_output_related_products sản phẩm liên quan
	 * @return [type] [description]
	 */
	function woocommerce_output_related_products() {

		$product_related      	= get_option('product_related', array( 'style' => 'slider', 'columns' => 4, 'posts_per_page' => 12, ) );

		$args = array(
			'posts_per_page' 	=> $product_related['posts_per_page'],
			'columns' 			=> $product_related['columns'],
			'orderby' 			=> 'rand',
		);

		woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
	}

	add_action('woocommerce_products_detail_tabs', 'woocommerce_output_related_products', 20);
}

if ( ! function_exists( 'woocommerce_related_products' ) ) {

	/**
	 * Output the related products.
	 */
	function woocommerce_related_products( $args = array() ) {

		$ci =& get_instance();

		if(!isset($ci->data['object'])) return;

		$defaults = array(
			'posts_per_page' => 10,
			'columns'        => 4,
			'orderby'        => 'rand',
			'order'          => 'desc',
		);

		$args = array_merge( $defaults, $args );

		$product_related = gets_product(
			array(
				'related' => $ci->data['object']->id,
				'params' => array('limit' => $args['posts_per_page']),
			)
		);

		// Get visble related products then sort them at random.
		$args['related_products'] = apply_filters( 'woocommerce_get_related_products',  $product_related );

		wcmc_get_template( 'detail/related' , $args);
	}
}

/** PRODUCT-VIEWED : sản phẩm đã xem ******************************************************************/
if ( ! function_exists( 'woocommerce_viewed_products_session' ) ) {

	function woocommerce_viewed_products_session(){

	    $ci =& get_instance();

	    if(!isset($ci->data['object']->id)) return;

	    if(!isset($_SESSION['viewed_product'])){  $_SESSION['viewed_product'] = array(); }

	    if(!isset($_SESSION['viewed_product'][$ci->data['object']->id])){ $_SESSION['viewed_product'][$ci->data['object']->id] = $ci->data['object']->id; }
	}

	add_action( 'controllers_products_detail','woocommerce_viewed_products_session', 20 );

}

if ( ! function_exists( 'woocommerce_viewed_products_sidebar' ) ) {

	function woocommerce_viewed_products_sidebar() {
		$ci =& get_instance();

		if(isset($_SESSION['viewed_product']) && have_posts($_SESSION['viewed_product'])) {

			$args['viewed_product'] = gets_product(
				array(
					'where_in' => array('field'=>'id', 'data' => $_SESSION['viewed_product']),
				)
			);

			if(have_posts($args['viewed_product'])) wcmc_get_template( 'widget/viewed_product', $args );
		}
	}

	add_action( 'woocommerce_products_detail_sidebar','woocommerce_viewed_products_sidebar', 10 );

}