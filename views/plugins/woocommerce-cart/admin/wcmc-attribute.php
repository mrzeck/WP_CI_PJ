<?php
include 'attribute/wcmc-attribute-table.php';

include 'attribute/wcmc-attribute-ajax.php';

function woocommerce_attributes($ci, $model) {
	
	$ci =& get_instance();

	$view 	= removeHtmlTags($ci->input->get('view'));

	$id 	= (int)$ci->input->get('id');

	$model->settable('wcmc_options');

	if($view == '') {

		if($ci->input->post()) {

			$data = $ci->input->post();

			if($id != 0) $data['id'] = $id;

			insert_attribute($data);
		}

		if( $id == 0 ) {

			$args = array(
                'items' => gets_attribute(),
                'table' => 'woocomerce_attribute',
                'model' => get_model('products'),
                'module'=> 'woocomerce_attribute',
            );

			$table_list = new skd_woocomerce_attribute_list_table($args);
			
			include 'views/attribute/html-attribute-add.php';
		}
		else {

			$object = get_attribute($id);

			if(have_posts($object)) {

                $object->{$ci->language['default'].'[title]'} = $object->title;

                if(count($ci->language['language_list']) > 1) {

					$model = get_model('home');
					
					$model->settable('language');

                    $languages = $model->gets_where(array('object_id' => $object->id, 'object_type' => 'woocomerce_attribute'));

                    foreach ($languages as $key => $lang) {

						$object->{$key.'[title]'} = $lang->title;
                    }
				}

                include 'views/attribute/html-attribute-edit.php';
            }
		}
	}

	if($view == 'item') {

        $attribute = get_attribute($id);

        $sub_id = (int)$ci->input->get('sub_id');

        if($ci->input->post()) {

            $data = $ci->input->post();
            
			$data['option_id'] = $attribute->id;

			$data['type'] = $attribute->option_type;

            if($sub_id != 0) $data['id'] = $sub_id;
            
			insert_attribute_item($data);
		}

		if( $sub_id == 0 ) {

            $args = array(
                'items' => gets_attribute_item(['attribute' => $attribute->id]),
                'table' => 'wcmc_attribute',
                'model' => get_model('products'),
                'module'=> 'wcmc_attribute',
            );

			$table_list = new skd_woocomerce_attribute_item_list_table($args);

            include 'views/attribute/html-attribute-item-add.php';
        }
        else {

            include 'views/attribute/html-attribute-item-edit.php';
        }
	}
}

function woocommerce_attributes_form() {

	$ci =& get_instance();

	foreach ($ci->language['language_list'] as $key => $label) { 

		$form_input[] = ['field' => $key.'[title]', 'label' => 'Tên tùy chọn', 'type' => 'text'];
    }

	$form_input[] = ['field' => 'option_type', 'label' => 'Loại options', 'value'=> 0, 'type' => 'select', 'options' => [
		'label' => 'Chữ (Label)',
		'color' => 'Màu (Color)',
		'image' => 'Hình ảnh (Image)'
    ]];
    
    //Phiên bản củ
    $form_input = apply_filters('form_gets_field_woocommerce_options', $form_input);

	return apply_filters('woocommerce_attributes_form', $form_input);
}

function woocommerce_attributes_item_form( $attribute ) {

	$ci =& get_instance();

	foreach ($ci->language['language_list'] as $key => $label) { 

		$form_input[] = ['field' => $key.'[title]', 'label' => 'Tên thuộc tính', 'type' => 'text'];
    }

    $form_input_value = ['field' => 'value', 'label' => 'Giá trị', 'value'=> '', 'type' => 'text'];
    
    if(have_posts($attribute)) {

		if($attribute->option_type == 'color') {

            $form_input_value['label'] = 'Màu';
            
			$form_input_value['type'] = 'color';
		}

		if($attribute->option_type == 'image') {

            $form_input_value['label'] = 'Hình ảnh';
            
			$form_input_value['type'] = 'image';
		}
    }
    
    $form_input[] = $form_input_value;

	//Phiên bản củ
    $form_input = apply_filters('form_gets_field_woocommerce_options_item', $form_input);

	return apply_filters('woocommerce_attributes_item_form', $form_input);
}