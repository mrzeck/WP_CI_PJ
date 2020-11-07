<?php
function wcmc_field_product_category( $form ) {

	$ci =& get_instance();

	$form['param']['parent'] 		= true;

	$form['param']['slug'] 			= 'name';

	$form['param']['redirect'] 	= URL_ADMIN.'/products/products_categories';

	$redirect = '';

    if((int)$ci->input->get('category') != 0) $redirect .= '?category='.$ci->input->get('category');

    $form['param']['redirect'] 	.= $redirect;

	$form['right']['category'] =  'Danh mục';

	foreach ($ci->language['language_list'] as $key => $name) {

		if($key == $ci->language['default']) $rules = 'trim|required'; else $rules = 'trim';

		$param = array('group' => 'info', 'lang'=> $key, 	'field' => $key.'[name]', 'label' => 'Tiêu đề', 'type'	=> 'text', 'note' 	=> 'Tiêu đề bài viết được lấy làm thẻ H1', 'rules' => $rules);
		
		$form['field'] = form_add_field( $form['field'], $param, 'excerpt');
	}

	$form['field']['parent_id'] =  array('group' => 'category', 'field' => 'parent_id', 	'label' => 'Danh mục cha', 'value'=>$ci->input->get('category'), 'type' => 'select', 'rules' => 'trim');
	
	$form['right'] 				= form_add_group($form['right'], 'category', 'Danh mục', 'media');
	
	$form = form_remove_field('title', $form);

	if($ci->template->method == 'index') {

		$form = form_remove_group('seo,theme', $form);

		$form = form_remove_field('excerpt,content', $form);
	}

	$remove_group = 'media,theme';

	$remove_field = 'content,excerpt';

	template_support_action($remove_group, $remove_field, $form, 'products_categories');

	return $form;
}

function wcmc_field_product( $form ) {

	$ci =& get_instance();

	$form['param']['slug'] 			= 'title';

	$form['param']['redirect'] 		= URL_ADMIN.'/products';

	$redirect = '?page='.(($ci->input->get('page') != '')?$ci->input->get('page'):1);

	$form['param']['redirect'] 		.= $redirect;

	$form['right']['category'] 		=  'Phân loại';

	$form['field']['code'] 			=  array('group' => 'category', 'field' => 'code', 	'label' => 'Mã sản phẩm', 'value'=>'', 'type' => 'text', 'note' => 'Nhập mã sản phẩm (SKU) nếu có.');

	$form['field']['category_id'] 	=  array('group' => 'category', 'field' => 'category_id', 	'label' => 'Danh mục', 	'value'=>$ci->input->get('category'), 'type' => 'checkbox', 'rules' => '');
	
	// if( version_compare( get_option('wcmc_database_version'), '1.3') >= 0 ) {
	// 	$form['field']['supplier_id'] 	=  array('group' => 'category', 'field' => 'supplier_id', 	'label' => 'Nhà sản xuất', 	'value'=>$ci->input->get('supplier_id'), 'type' => 'select', 'rules' => '', 'options' => gets_suppliers_option() );
	// }

	if( version_compare( cms_info('version'), '2.5.4') >= 0 ) {
		
		$form['field']['category_id'] 	=  array(
			'group' => 'category', 
			'field' => 'category_id', 	
			'label' => 'Danh mục', 	
			'value' => $ci->input->get('category'), 
			'type'  => 'popover',
			'module' => 'products_categories' 
		);
	}

	// if( version_compare( cms_info('version'), '3.0.0') >= 0 && version_compare( get_option('wcmc_database_version'), '1.3') >= 0 ) {

	// 	$form['field']['supplier_id'] 	=  array(
	// 		'group' 	=> 'category', 
	// 		'field' 	=> 'supplier_id', 	
	// 		'label' 	=> 'Nhà sản xuất', 	
	// 		'value' 	=> $ci->input->get('supplier_id'), 
	// 		'type'  	=> 'popover',
	// 		'module' 	=> 'supplier_categories',
	// 		'multiple'  => false,
	// 		'options'   => gets_suppliers_option()
	// 	);
	// }
	
	$form['right'] 					= form_add_group($form['right'], 'category', 'Danh mục', 'media');

	foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {

        if( $taxonomy_value['post_type'] == 'products' ) {

        	$form['field']['taxonomy_'.$taxonomy_key] 	=  array('group' => 'taxonomies', 'field' => 'taxonomy['.$taxonomy_key.']', 	'label' => $taxonomy_value['labels']['name'], 	'value'=> '', 'type' => 'checkbox', 'rules' => '');
			
			if( version_compare( cms_info('version'), '2.5.4') >= 0 ) {
					
				$form['field']['taxonomy_'.$taxonomy_key]['type'] = 'popover';
				$form['field']['taxonomy_'.$taxonomy_key]['module'] = 'post_categories';
			}

        	$form['right'] 		= form_add_group( $form['right'], 'taxonomies', 'Chuyên Mục', 'media');
        }
    }

	$form['right']['price'] 		=  'Giá';
	
	$form['field']['price'] 	=  array('group' => 'price', 'field' => 'price', 	'label' => 'Giá', 	'value'=>0, 'type' => 'text', 'rules' => 'trim');
	
	$form['field']['price_sale'] 	=  array('group' => 'price', 'field' => 'price_sale', 	'label' => 'Giá khuyến mãi', 	'value'=>0, 'type' => 'text', 'rules' => 'trim');
	
	$form['right'] 		= form_add_group($form['right'], 'price', 'Giá', 'media');
	
	$remove_group = 'theme';

	template_support_action( $remove_group, '', $form, 'products');

	return $form;

	// $form = apply_filters("manage_product_input", $form);
}

function wcmc_manager_field() {
	add_filter('manage_products_input', 'wcmc_field_product', 1);
	add_filter('manage_products_categories_input', 'wcmc_field_product_category', 1);
}

add_action('init', 'wcmc_manager_field');

function wcmc_input_popover_products_categories_search($object, $keyword) {

	$object = wcmc_gets_category([
		'where_like' => [ 'name' => array($keyword), ]
	]);

	return $object;
}

add_filter('input_popover_products_categories_search', 'wcmc_input_popover_products_categories_search', 10, 2);

function wcmc_input_popover_supplier_categories_search($object, $keyword) {

	$object = gets_suppliers([
		'where_like' => [ 'name' => array($keyword), ]
	]);

	return $object;
}

add_filter('input_popover_supplier_categories_search', 'wcmc_input_popover_supplier_categories_search', 10, 2);