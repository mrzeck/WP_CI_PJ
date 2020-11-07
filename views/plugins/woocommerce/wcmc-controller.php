<?php
/** PRODUCT-INDEX ******************************************************************/
if ( ! function_exists( 'woocommerce_controllers_products_index' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function woocommerce_controllers_products_index( $slug = '' ) {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('products');

		$args = array();

		$url = base_url().'san-pham';

		$page 		= (int)$ci->input->get('page');

		$status     = (int)$ci->input->get('status');
		$order     = (int)$ci->input->get('order');
		$where 		= array('public' => 1, 'trash' => 0);

		$category 	= wcmc_get_category( array('where' => array('slug' => $slug) ) );

		$args = [];

		$ci->data['category']   = $category;

		/* Số dữ liệu trên 1 trang */
		$product_pr_page = (int)get_option('product_pr_page');

		$where = [];

		$where = apply_filters( 'woocommerce_controllers_index_where', $where );

		if(is_array($where) && $status >= 1 && $status <= 3) {

			$where['status'.$status] =  1;
		}
		if(is_array($where) &&  $status == 3) {
			$where['price_sale <>'] =  0;
			
		}
		if( have_posts($category) ) {

			/* Url phân trang */
			$url        = base_url().get_url($category->slug).'?page={page}';

			/* Lấy danh sách sản phẩm */
			$args['where_category'] = $category;
			$args['where'] = $where;
		}

		if($slug == '') {

			$url                      = base_url().'san-pham'.'?page={page}';

			$get = $ci->input->get();

			foreach ($get as $key => $value) {
				if( $key != 'page' ) $url .= '&'.removeHtmlTags($key).'='.removeHtmlTags($value);
			}

			$args['where']    = $where;
		}

		$args 					= apply_filters( 'woocommerce_controllers_index_args', $args );

		$total_rows 			= apply_filters( 'woocommerce_controllers_index_count', count_product( $args ) );

		if( $total_rows > 0 ) $ci->data['pagination'] = apply_filters( 'woocommerce_controllers_index_paging', pagination($total_rows, $url, $product_pr_page ) );
		else $ci->data['pagination'] = '';

		if(isset($ci->data['pagination']) && is_object($ci->data['pagination'])) {
			$args['params']         = array('limit' => $product_pr_page, 'start' => $ci->data['pagination']->getoffset(),'orderby' => 'products.order, products.created desc');
		}
		else {
			$args['params']         = array('orderby' => 'products.order, products.created desc');
		}
		/**
		 * @since 1.7.2
		 * filter woocommerce_controllers_index_params giúp bạn tùy chỉnh params trước khi lấy dữ liệu
		 */
		$args['params'] = apply_filters( 'woocommerce_controllers_index_params', $args['params'] );
		
		$ci->data['objects']    = apply_filters( 'woocommerce_controllers_index_objects', gets_product($args),  $args );
	}

	add_action('controllers_products_index', 'woocommerce_controllers_products_index', 10, 1);
}
/** PRODUCT-SUPPLIERS ***************************************************************/
if ( ! function_exists( 'wcmc_suppliers_frontend' ) ) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function wcmc_suppliers_frontend( $ci, $model ) {

		$lang = $ci->uri->segment('1');

		if( ($ci->language['default'] != $ci->language['current'] || $ci->uri->segment('1') ==  $ci->language['default']) && $lang == $ci->language['current'] ) {
			
			$slug = $ci->uri->segment('2');
		}
		else {
			
			$slug = $ci->uri->segment('1');
		}

		$slug = removeHtmlTags($slug);

		$suppliers = get_suppliers(['where' => array('slug' => $slug)]);

		$ci->data['objects'] = [];

		if(have_posts($suppliers)) {

			$model->settable('products');

			$url 	= base_url().$slug;

			$args 	= array();

			$page 		= (int)$ci->input->get('page');

			$where 		= array('public' => 1, 'trash' => 0, 'supplier_id' => $suppliers->id);

			$where 		= apply_filters( 'woocommerce_controllers_suppliers_where', $where );

			$args 		= array('where' => $where);

			$args 		= apply_filters( 'woocommerce_controllers_suppliers_args', $args );

			$total_rows = apply_filters( 'woocommerce_controllers_suppliers_count', count_product($args));

			$product_pr_page = (int)get_option('product_pr_page');

			if( $total_rows > 0 ) {

				$ci->data['pagination'] = apply_filters( 'woocommerce_controllers_suppliers_paging', pagination($total_rows, $url, $product_pr_page ) );
			}
			else {
				
				$ci->data['pagination'] = '';
			}

			if(isset($ci->data['pagination']) && is_object($ci->data['pagination'])) {

				$args['params']    = array('limit' => $product_pr_page, 'start' => $ci->data['pagination']->getoffset(),'orderby' => 'products.order, products.created desc');
			}
			else {
				
				$args['params']     = array('orderby' => 'products.order, products.created desc');
			}

			$args['params'] 		= apply_filters('woocommerce_controllers_suppliers_params', $args['params']);

			$ci->data['objects']    = apply_filters('woocommerce_controllers_suppliers_objects', gets_product($args), $args);
		}

		$ci->data['suppliers'] = $suppliers;

		$layout = apply_filters( 'woocommerce_controllers_suppliers_layout', 'template-full-width');

		$view 	= apply_filters( 'woocommerce_controllers_suppliers_view', 'products-index');

		$ci->template->set_layout($layout);

		$ci->template->set_view($view);

		$ci->template->render();
	}
}
/** PRODUCT-DETAIL ******************************************************************/
if ( ! function_exists( 'woocommerce_controllers_products_detail' ) ) {
	/**
	 * @Lấy data trang chi tiết sản phẩm
	 */
	function woocommerce_controllers_products_detail( $slug = '' ) {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('products'); 

		/* Get sản phẩm */
		$args = array(
			'where' => array('slug' => $slug, 'public' => 1, 'trash' => 0),
		);

		$ci->data['object'] 	= get_product($args);

		if( have_posts( $ci->data['object']) ) {

			/* Get danh sách categories của sản phẩm */
			$ci->data['categories'] 	= $model->gets_relationship_join_categories($ci->data['object']->id, 'products', 'products_categories');

			if(have_posts($ci->data['categories'])) {
				$ci->data['category']  	= wcmc_get_category( $ci->data['categories'][0]->id );
			}
		}
	}

	add_action('controllers_products_detail', 'woocommerce_controllers_products_detail', 10, 1);
}