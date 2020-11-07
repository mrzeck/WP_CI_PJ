<?php
if(!function_exists('widget_builder_ajax_sidebar_load')) {

	function widget_builder_ajax_sidebar_load($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= removeHtmlTags($ci->input->post('sidebar'));
            
            $model->settable('widget');

            $widgets = $model->gets_where(array('sidebar_id' => $id));

			ob_start();
			foreach ($widgets as $key => $item) {
				?>
				<li id="menuItem_<?php echo $item->id;?>" class="js_widget_item" style="display: list-item;" data-id="<?php echo $item->id;?>" data-key="<?php echo $item->widget_id;?>" draggable="false">
					<div class="widget_sidebar">
						<div class="action text-right">
							<a href="<?php echo $item->id;?>" class="icon-edit" draggable="false"><i class="fas fa-wrench"></i></a> &nbsp;&nbsp;
							<a href="<?php echo $item->id;?>" class="icon-copy" draggable="false"><i class="fal fa-clone"></i></a> &nbsp;&nbsp;
							<a href="<?php echo $item->id;?>" class="icon-delete" draggable="false"><i class="fal fa-trash-alt"></i></a>
						</div>
						<div class="title">
							<h3 class="widget-name"><?php echo $item->name;?></h3>
							<p style="margin:0" class="widget-key"><?php echo $item->widget_id;?></p>
						</div>
					</div>
				</li>
				<?php
			}
			
			$result['data'] = ob_get_contents();

			ob_end_clean();

			$result['message'] 	= 'Load dữ liệu thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_sidebar_load');
}

if(!function_exists('widget_builder_ajax_form_edit')) {

	function widget_builder_ajax_form_edit($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= removeHtmlTags($ci->input->post('widget_id'));

            $key 	= removeHtmlTags($ci->input->post('widget_key'));
            
            $model->settable('widget');

            $ci->data['widget'] = $model->get_where(array('id' => $id));

            if(have_posts($ci->data['widget'])) {

                $ci->data['widget']->options = unserialize($ci->data['widget']->options);
                
                $widget = $ci->template->get_widget($ci->data['widget']->widget_id);

                $widget->form();

                $widget->set_name($ci->data['widget']->name);

                $widget->get_option($ci->data['widget']->options);

                ob_start();

                include 'html/widget-form.php';
                
                $result['data'] = ob_get_contents();

                ob_end_clean();

                $result['message'] 	= 'Load dữ liệu thành công';

		        $result['type'] 	= 'success';
            }
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_form_edit');
}

if(!function_exists('widget_builder_ajax_review')) {

	function widget_builder_ajax_review($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$id 	= removeHtmlTags($ci->input->post('id'));

            $model->settable('widget');

            $widget_data = $model->get_where(array('id' => $id));

            $widget = $ci->template->get_widget($widget_data->widget_id);

			if( have_posts($widget) ) {

				$widget->form();

				$widget->id 	= $widget_data->id;

				$widget->name 	= $widget_data->name;

				$widget->get_option(unserialize($widget_data->options));

                $wg_option     = $widget->get_option_object();
                
                ob_start();

                $widget->widget($wg_option);
                
                $result['data'] = ob_get_contents();

                ob_end_clean();

                $result['message'] 	= 'Load dữ liệu thành công';

		        $result['type'] 	= 'success';
				
			}
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_review');
}

if(!function_exists('widget_builder_ajax_theme')) {

	function widget_builder_ajax_theme($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$model->settable('widget');

			ob_start();

			$widget_file = $ci->template->get_widget();

			foreach ($widget_file as $key => $val) { //show_r($val); ?>
			<div class="widget_item_nosidebar" style="overflow: inherit;" data-key="<?php echo $val->key;?>">
				<div class="header pull-left" style="border-bottom:0;">
					<h3 style="margin:0;" class="widget-name"><?php echo $val->name;?></h3>
					<p style="margin:0" class="widget-key"><?php echo $val->key;?></p>
				</div>

				<div class="header-action pull-right" style="padding:5px;">
					<a class="btn btn-green js_btn_widget__add"><i class="fa fa-plus"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php }
			
			$result['data'] = ob_get_contents();

			ob_end_clean();

			$result['data']  	.= '<div class="clearfix"></div>';

			$result['message'] 	= 'Load widget thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_theme');
}

if(!function_exists('widget_builder_ajax_add')) {

	function widget_builder_ajax_add($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$model->settable('widget');

			$widget_id 			= $ci->input->post('widget_add');

			$widget_parent 		= $ci->input->post('widget_parent');

			$widget_position 	= $ci->input->post('widget_position');

			$widget_parent 		= $model->get_where(['id' => $widget_parent]);

			if(!have_posts($widget_parent)) {

				$sidebar_id = $ci->input->post('sidebar_id');
			}
			else {

				$sidebar_id = $widget_parent->sidebar_id;
			}

			$widget             = $ci->template->get_widget($widget_id);

			$data['name']       = $widget->name;

			$data['template']   = $ci->data['template']->name;

			$data['widget_id']  = $widget_id;

			$data['sidebar_id'] = $sidebar_id;

			$data['options']    = serialize($widget->get_option());

			$id = $model->add($data);

			if($id) {

				$cache_id = 'sidebar_'.md5($sidebar_id.'_'.$ci->data['template']->name);

				delete_cache($cache_id);

				if(have_posts($widget_parent)) {

					$widget_sidebar = $model->gets_where(['sidebar_id' => $sidebar_id]);

					$order_check = false;

					$order = 0;

					foreach ($widget_sidebar as $key => $item) {
						
						if($item->id == $widget_parent->id && $order_check == false ) {

							$order_check = true;

							$order		= $item->order + 1;

							if($widget_position == 'top') {

								$model->update_where(['order' => $item->order], ['id' => $id]);

								$model->update_where(['order' => $order], ['id' => $item->id]);
							}
							else {
								$model->update_where(['order' => $order], ['id' => $id]);
							}
						}
						else if($order_check == true) {

							$order		= $order + 1;

							$model->update_where(['order' => $order], ['id' => $item->id]);
						}
					}

				}

				$result['id'] 	= $id;

				$result['key'] 	= $widget_id;

				$result['type'] 	= 'success';

				$result['message'] 	= 'Thêm widget thành công!';
			}

			$result['message'] 	= 'Load widget thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_add');
}

if(!function_exists('widget_builder_ajax_service')) {

	function widget_builder_ajax_service($ci, $model) {

		$result['message'] 	= 'Load dữ liệu không thành công';

		$result['type'] 	= 'error';

		if($ci->input->post()) {

			$model->settable('widget');

			$widgets 		= get_cache('widget_service_item');

			$categories 	= get_cache('widget_service_category');

			if( !have_posts($widgets) || !cache_exists('widget_service_item') ) {

				$widgets 	= $ci->service_api->gets_widget();

				if($widgets->status == 'success') {

					$widgets = $widgets->data;

					save_cache('widget_service_item', $widgets, 8*60*60 ); //Lưu cache trong 8h
				}
			}

			if(!have_posts($categories) || !cache_exists('widget_service_category') ) {

				$categories = $ci->service_api->gets_widget_category();

				if($categories->status == 'success') {

					$categories = $categories->data;

					save_cache('widget_service_category', $categories, 8*60*60 ); //Lưu cache trong 8h
				}
			}

			ob_start();

			include 'html/widget-service.php';
                
			$result['data'] = ob_get_contents();

			ob_end_clean();

			$result['data']  	.= '<div class="clearfix"></div>';

			$result['message'] 	= 'Load widget thành công';

			$result['type'] 	= 'success';
		}

		echo json_encode($result);
	}

	register_ajax_admin('widget_builder_ajax_service');
}