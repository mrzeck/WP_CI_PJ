<?php
if ( ! function_exists( 'ajax_user_role_save' ) ) {

    function ajax_user_role_save( $ci, $model ) {
		
		$result['status']  = 'error';

		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data             = $ci->input->post();
			
			$role_name        = removeHtmlTags($data['role_name']);
			
			$role             = get_role($data['role_name']);

			$capabilities_old = $role->capabilities;

			if( !empty($data['capabilities']) ) {

				$capabilities_up  = $data['capabilities'];

				foreach ( $capabilities_up as $key => $value ) {

					if( !isset($capabilities_old[$key]) ) {
						$role->add_cap($key);
					}
					else unset( $capabilities_old[$key] );
				}
			}

			if( have_posts($capabilities_old)) {

				foreach ($capabilities_old as $key => $value ) {
					$role->remove_cap($key);
				}
			}


			$result['status']  = 'success';

			$result['message'] = __('Lưu dữ liệu thành công');

		}

		echo json_encode($result);   

    }

    register_ajax_admin('ajax_user_role_save');
}

if ( ! function_exists( 'ajax_user_role_add' ) ) {

    function ajax_user_role_add( $ci, $model ) {
		
		$result['status']  = 'error';

		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data             = $ci->input->post();
			
			$role_key        = removeHtmlTags($data['key']);

			$role_name        = removeHtmlTags($data['label']);
			
			$role             = get_role($role_key);

			if(!have_posts($role)) {

				add_role( $role_key, $role_name );

				$result['status']  = 'success';

				$result['message'] = __('Lưu dữ liệu thành công');

			}
			else {

				$result['message'] = __('Nhóm quyền này đã tồn tại.');
			}
		}

		echo json_encode($result);   

    }

    register_ajax_admin('ajax_user_role_add');
}


if ( ! function_exists( 'ajax_my_role_load_capbilitie' ) ) {

    function ajax_my_role_load_capbilitie( $ci, $model ) {
		
		$result['status']  = 'error';

		$result['message'] = __('Load dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$role_name = removeHtmlTags($ci->input->post('role_name'));

			$user_id = removeHtmlTags($ci->input->post('user_id'));

			$role_name_current 		= user_role( $user_id );

    		$role_name_current 		=  array_pop( $role_name_current );

			if( is_super_admin() ) {
            
				$role_name_default = 'root';
			}
			else $role_name_default = 'administrator';

			$role_all 		= get_role( $role_name_default )->capabilities;

			$role_default   = get_role( $role_name )->capabilities;

			$role_current 	= get_role_caps( $user_id );

			$role_label 	= user_role_editor_label();

			$role_group     = user_role_editor_group();

			if($role_name_default == 'administrator') {

				foreach ($role_group as &$role_group_value) {

					foreach ($role_group_value['capbilities'] as $key => $cap) {
						
						if(empty($role_all[$cap])) unset($role_group_value['capbilities'][$key]);
					}
				}
			}

			if($role_name_current != $role_name ) {

				$role_current = $role_default;
			}

			ob_start();

			foreach ( $role_group as $key => $value): if(!have_posts($value['capbilities'])) continue; ?>
				<div class="col-md-12">
					<h5><?php echo $value['label'];?></h5>
					<?php foreach ($value['capbilities'] as $capbilitie): ?>
						<?php if(!isset($role_all[$capbilitie])) continue; ?>
						<div class="col-md-6">
							<div class="checkbox">
								<label> <input type="checkbox" class="<?php echo (!empty($role_default[$capbilitie]))?'icheckdisable':'icheck';?>" name="capabilities[<?php echo $capbilitie;?>]" value="1" <?php echo (!empty($role_current[$capbilitie]))?'checked':'';?>> <?php echo $role_label[$capbilitie];?> </label>
							</div>
						</div>
					<?php endforeach ?>
					
					<div class="clearfix"></div>
					<hr />
				</div>
			<?php endforeach;

			$content = ob_get_contents();

			ob_clean();

			ob_end_flush();

			$result['content']  = $content;

			$result['status']  = 'success';

			$result['message'] = __('Lưu dữ liệu thành công');

		}

		echo json_encode($result);   

    }

    register_ajax_admin('ajax_my_role_load_capbilitie');
}

if ( ! function_exists( 'ajax_my_role_save' ) ) {

    function ajax_my_role_save( $ci, $model ) {
		
		$result['status']  = 'error';

		$result['message'] = __('Lưu dữ liệu không thành công');
		
		if( $ci->input->post() ) {

			$data             = $ci->input->post();

			$role_name = removeHtmlTags($data['role_name']);
			
			$user_id = (int)$data['user_id'];

			$user_edit = get_user_by('id', $user_id );

			if( !have_posts($user_edit) ) {

				$result['message'] = __('User không chính xác.');
				
				echo json_encode($result);  

				return false;
			}

			$user_current = get_user_current();

			if( $user_current->id != $user_edit->id && $user_edit->username == 'root') {

				$result['message'] = __('Bạn không có quyền thay đổi quyền hạn của thành viên này.');
				
				echo json_encode($result);  

				return false;
			}

			if( current_user_can('user_edit') ) {

				$result['message'] = __('Bạn không có quyền thay đổi quyền hạn của thành viên.');
				
				echo json_encode($result);  

				return false;
			}

			$capabilities_up = array();

			if( !empty($data['capabilities'])) $capabilities_up  = $data['capabilities'];

			$capabilities_up[$role_name] = 1;

			update_user_meta( $user_edit->id, 'capabilities', $capabilities_up );

			$result['status']  = 'success';

			$result['message'] = __('Lưu dữ liệu thành công');

		}

		echo json_encode($result);   

    }

    register_ajax_admin('ajax_my_role_save');
}