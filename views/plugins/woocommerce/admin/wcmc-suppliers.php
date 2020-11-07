<?php
if ( !function_exists( 'woocommerce_suppliers' ) ) {

	function woocommerce_suppliers() {

        $ci =& get_instance();

        $view = $ci->input->get('view');

        if($view == '') {

            $args = array(
                'items' => gets_suppliers(),
                'table' => 'suppliers',
                'model' => get_model('products'),
                'module'=> 'suppliers',
            );

            $table_list = new skd_suppliers_list_table($args);

            include 'views/suppliers/html-suppliers-index.php';
        }

        if($view == 'add') {

            include 'views/suppliers/html-suppliers-save.php';
        }

        if($view == 'edit') {

            $id = (int)$ci->input->get('id');

            $object = get_suppliers($id);

            if(have_posts($object)) {

                $object->lang[$ci->language['default']]['name'] = $object->name;

                if(count($ci->language['language_list']) > 1) {

                    $model = get_model('home');

                    $model->settable('language');

                    $languages = $model->gets_where(array('object_id' => $object->id, 'object_type' => 'suppliers'));

                    foreach ($languages as $key => $lang) {
                        
                        $object->lang[$lang->language]['name'] = $lang->name;
                    }
                }

                include 'views/suppliers/html-suppliers-save.php';
            }
        }
    }
}

if( !function_exists('wcmc_ajax_suppliers_save') ) {

	function wcmc_ajax_suppliers_save( $ci, $model ) {

        $result['status']  = 'error';
        
		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

            $data = $ci->input->post();
            
            $error = insert_suppliers($data);

            if ( is_skd_error($error) ) {

                $result['status']  = 'error';

                foreach ($error->errors as $key => $er) {
                    
                    $result['message'] = $er;
                }
            }
            else {

                $result['status']  = 'success';

			    $result['message'] = __('Lưu dữ liệu thành công.');

            }
		}

        echo json_encode($result);
	}

	register_ajax_admin('wcmc_ajax_suppliers_save');
}

if( !function_exists('wcmc_action_suppliers_delete') ) {

    function wcmc_action_suppliers_delete($res, $table, $id) {

        if(is_numeric($id)) {

            $res = delete_suppliers($id);
        }
        else if(have_posts($id)) {

            $res = delete_list_suppliers($id);
        }
        
        return $res;
    }

    add_filter('delete_object_suppliers', 'wcmc_action_suppliers_delete', 1, 3 );
}