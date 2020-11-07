<?php

/* chèn hành động */
// function update_menu_after_admin_edit($args) {
// 	$ci 	= $args['ci'];
// 	$model 	= $args['model'];
// 	$id 	= $args['id'];
// 	$object = $model->get_where(array('id' => $id));

// 	if($ci->data['module'] == 'post_categories') {
// 		$model->settable('menu');
// 		$menu = $model->gets_where(array('type' => 'categories', 'object_id' => $id));
// 		if(have_posts($menu)) {
// 			foreach ($menu as $key => $value) {
// 				$data = array();
// 				$data['slug'] = $object->slug;
// 				if($value->edit == 0) $data['name'] = $object->name;
// 				$model->update_where($data, array('id' => $value->id));
// 			}
// 		}
// 	}

// 	if($ci->data['module'] == 'page') {
// 		$model->settable('menu');
// 		$menu = $model->gets_where(array('type' => 'page', 'object_id' => $id));
// 		if(have_posts($menu)) {
// 			foreach ($menu as $key => $value) {
// 				$data = array();
// 				$data['slug'] = $object->slug;
// 				if($value->edit == 0) $data['name'] = $object->title;
// 				$model->update_where($data, array('id' => $value->id));
// 			}
// 		}
// 	}
// }

// add_action('after_admin_edit', 'update_menu_after_admin_edit');

