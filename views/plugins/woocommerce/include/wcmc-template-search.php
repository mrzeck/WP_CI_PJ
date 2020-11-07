<?php
/** PRODUCT-SEARCH ******************************************************************/

if ( ! function_exists( 'woocommerce_product_search' ) ) {

	function woocommerce_product_search( $objects, $type, $keyword ) {

		$ci =& get_instance();

		if( $type == 'products' ) {

			$args = [
				'where' 	 => ['public' => 1, 'trash' => 0],
				'where_like' => ['title' => [$keyword]],
			];

			if($ci->input->get('category') != null && $ci->input->get('category') != 0) {

				$category = removeHtmlTags($ci->input->get('category'));

				$args['where_category'] = $category;

				$args['join'] = true;
			}

			$args = apply_filters('woocommerce_product_search_args', $args);

			$objects = gets_product($args);

			$ci->template->set_layout('template-sidebar-none');
		}

		return $objects;
		
	}
	add_filter( 'get_search_data','woocommerce_product_search', 3, 3 );
}


if ( ! function_exists( 'woocommerce_product_search_html' ) ) {

	function woocommerce_product_search_html( $objects, $type, $keyword ) {

		$ci =& get_instance();

		if( $type == 'products' ) {

			if(have_posts($objects)) {

				if(is_object($objects)) {

					$objects = array('objects' => array($objects) );
				}
				else {
					$objects = array('objects' => $objects );
				}
			}
			
			wcmc_get_template( 'product_search', $objects );
		}
	}
	add_action( 'get_search_view','woocommerce_product_search_html', 3, 3 );
}