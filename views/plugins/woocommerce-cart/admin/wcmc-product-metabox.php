<?php
/* TEMPLATE *********************************************************/
if( !function_exists('woocommerce_metabox_admin_form') ) {
	/**
	 * [woocommerce_metabox_product Tạo metabox woocommerce]
	 */
	function woocommerce_metabox_product() {
		$ci =& get_instance();
		add_meta_box(
			'woocommerce_metabox_product',
			'woocommerce',
			'woocommerce_metabox_product_callback',
			'products'
		);
	}

	add_action( 'init', 'woocommerce_metabox_product');

}

if( !function_exists('woocommerce_metabox_product_callback') ) {
	/**
	 * [woocommerce_metabox_product_callback hàm callback metabox ]
	 */
	function woocommerce_metabox_product_callback() {
		wcmc_get_include_cart('admin/views/metabox/html-product-metabox');
	}
}

if( !function_exists('woocommerce_metabox_tabs') ) {
	/**
	 * [woocommerce_metabox_tabs hàm tạo các tabs chức năng ]
	 */
	function woocommerce_metabox_tabs() {
		return apply_filters('woocommerce_metabox_tabs', array(
			//Tab các thuộc tính
			'wcmc_metabox_tab_attributes' => array(
				'label' => 'Các thuộc tính',
				'icon'	=> '',
				'callback' => 'wcmc_metabox_tab_attributes'),
			//Tab các biến thể
			'wcmc_metabox_tab_variations' => array(
				'label' => 'Các biến thể',
				'icon'	=> '',
				'callback' => 'wcmc_metabox_tab_variations',),
		));
	}
}

if( !function_exists('wcmc_metabox_tab_attributes') ) {
	/**
	 * [wcmc_metabox_tab_attributes hàm callback của tab thuộc tính]
	 */
	function wcmc_metabox_tab_attributes() {
		wcmc_get_include_cart('admin/views/metabox/html-product-metabox-tab-attributes');
	}
}

if( !function_exists('wcmc_metabox_tab_variations') ) {
	/**
	 * [wcmc_metabox_tab_attributes hàm callback của tab thuộc biến thể]
	 */
	function wcmc_metabox_tab_variations() {
		wcmc_get_include_cart('admin/views/metabox/html-product-metabox-tab-variations');
	}
}

if( !function_exists('wcmc_metabox_product_save') ) {
	/**
	 * [wcmc_metabox_product_save thực hiện khi save sản phẩm]
	 */
	function wcmc_metabox_product_save($product_id, $model) {

		$ci =& get_instance();

		if($ci->data['module'] == 'products') {

			$data = $ci->input->post();

			$currenttable 		= $model->gettable();

			$attribute 			= array();

			$attribute_value 	= array();

			if( isset($data['attribute_names']) && isset($data['attribute_values'])) {

				foreach ($data['attribute_names'] as $key => $option_id) {

					if( isset($data['attribute_values'][$option_id]) && have_posts($data['attribute_values'][$option_id]) ) {

						$option = get_attribute($option_id);

						$attribute['_op_'.$option_id]['name'] 	= $option->title;
						$attribute['_op_'.$option_id]['id'] 		= $option->id;

						foreach ($data['attribute_values'][$option_id] as $option_item_id) {

							$attribute_value['attribute_op_'.$option_id][] = $option_item_id;
						}

						unset($data['attribute_values'][$option_id]);
					}
				}

				$metabox_attr['_product_attributes'] 		= $attribute;

				$metabox_attr['_product_attributes_value'] 	= $attribute_value;

				//product attributes
				update_metadata( 'product', $product_id, 'attributes', $metabox_attr['_product_attributes'] );

				foreach ( $metabox_attr['_product_attributes_value'] as $meta_key => $meta_values) {

					$model->settable('relationships');

					$model->delete_where(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes' ));

					foreach ( $meta_values as $value ) {

						$model->add(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes', 'value' =>  $value ));
					
					}
				}

			}

			if(isset( $data['wcmc_variations_id'])) {

				$product = get_product($product_id);

				$session_id = $data['wcmc_metabox_session_id'];

				//add dữ liệu
				if($session_id != 0) {

					$model->settable('wcmc_session');

					$model->delete_where(array('session_id' => $session_id));

					//product variables
					$model->settable('wcmc_variations');

					foreach ($data['wcmc_variations_id'] as $variation_id) {

						insert_product([
							'id' 	 	=> $variation_id,
							'title'     => $product->title,
							'status' 	=> 'public',
							'parent_id' => $product_id,
						]);
					}
				}
				else {
					//product variables
					$model->settable('products');

					$model->update_where(array('status' => 'public', 'title' => $product->title ), array('parent_id' => $product_id, 'type' => 'variations'));
				}

				$metabox_temp  = array();

				$metabox_varia = array();

				$codes 		= $data['variable_code']; unset($data['variable_code']);

				$price 		= $data['variable_price']; unset($data['variable_price']);

				$price_sale = $data['variable_price_sale']; unset($data['variable_price_sale']);

				$image 		= $data['upload_image']; unset($data['upload_image']);

				if(have_posts($data)) {

					foreach ($data as $key => $list_item) {

						if(have_posts($list_item) && ( strpos($key, 'attribute_') !== false ) ) {
							
							$name = $key;

							foreach ($list_item as $object_id => $value) $metabox_temp[$object_id][$name] = $value;
						}
					}
				}

				foreach ($codes as $id => $code) {

					$product = [
						'id' 		 => $id, 
						'code' 		 => removeHtmlTags($code),
						'image'		 => process_file($image[$id]),
						'price' 	 => $price[$id],
						'price_sale' => $price_sale[$id],
					];

					insert_product($product);

					if(isset($metabox_temp[$id])) $metabox_varia[$id] = $metabox_temp[$id];
				}

				//product variables
				foreach ($metabox_varia as $object_id => $metadata) {

					if(have_posts($metadata)) {
						
						foreach ($metadata as $meta_key => $meta_value) {

							update_product_meta( $object_id, $meta_key, $meta_value );
						}
					}
				}
			}

			do_action('woocommerce_save_product_variation', $product_id, $data );
		}
	}

	add_action('save_object', 'wcmc_metabox_product_save', '', 2);
}


