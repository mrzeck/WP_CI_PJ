<?php
function admin_user_profile( $user, $model )
{
	$ci =& get_instance();

	$user_current 	= get_user_current();
	$user_obj 		= $user;

	if( $user_current->id != $user_obj->id && !current_user_can('edit_users') ) {
		$ci->template->set_message( notice('error', __('Bạn không có quyền cập nhật thông tin thành viên.')), 'update_profile_role' );
		return false;
	}

	if( $ci->input->post() && have_posts($user_obj) )
	{

		$error      = array();
		
		$user_array = (array)$user_obj;
		
		$user_meta  = array();

		if ( !empty($ci->input->post('firstname')) ) {

			$user_array['firstname'] = removeHtmlTags( $ci->input->post('firstname') );
		}

		if ( !empty($ci->input->post('lastname')) ) {

			$user_array['lastname'] = removeHtmlTags( $ci->input->post('lastname') );
		}

		if ( !empty($ci->input->post('fullname')) ) {

			$user_meta['fullname'] = removeHtmlTags( $ci->input->post('fullname') );
		}

		if ( !empty($ci->input->post('phone')) ) {

			$user_array['phone'] = removeHtmlTags( $ci->input->post('phone') );
		}

		if ( !empty($ci->input->post('address')) ) {

			$user_meta['address'] = removeHtmlTags( $ci->input->post('address') );
		}

		if ( !empty($ci->input->post('birthday')) ) {

			$user_meta['birthday'] = removeHtmlTags( $ci->input->post('birthday') );
		}

		if ( !empty($ci->input->post('email')) ) {

			$user_array['email'] = removeHtmlTags( $ci->input->post('email') );
		}

		$error = apply_filters('admin_user_profile_errors', $error, $user_array, $user_meta );

		if( !is_skd_error($error) ) {

			$user_array = apply_filters( 'edit_user_update_profile', $user_array, $user_obj );

			$user_meta 	= apply_filters( 'edit_user_update_profile_meta', $user_meta, $user_obj );

			$error = update_user( $user_array );

			if( !is_skd_error($error) ) {

				if( have_posts($user_meta) ) {

					foreach ($user_meta as $user_meta_key => $user_meta_value) {

						if ( !empty( $user_meta_value ) ) update_user_meta( $user_obj->id, $user_meta_key, $user_meta_value );
					}
				}

				$user = get_user_by( 'id', $user_obj->id );

				do_action('edit_user_update_profile', $user_array, $user_meta );
			}

		}

		if( is_skd_error($error) ) {

			foreach ($error->errors as $error_key => $error_value) {

				$ci->template->set_message( notice('error', $error_value[0]), $error_key );
			}

		}
		else {
			$ci->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_profile_success' );
		}
	}

	include 'html/user-profile.php';
}

function admin_user_password( $user, $model )
{
	$ci =& get_instance();

	$user_current 	= get_user_current();

	$user_obj 		= $user;

	if( $user_current->id != $user_obj->id && !current_user_can('edit_users') ) {
		$ci->template->set_message( notice('error', __('Bạn không có quyền cập nhật thông tin thành viên.')), 'update_profile_role' );
		return false;
	}

	if( $ci->input->post() ) {

		$error = array();

		if( have_posts( $user_obj ) ) {

			$user_array = (array)$user_obj;

			$user_meta = array();

			$error = array();

			if ( empty($ci->input->post('old_password')) || generate_password($ci->input->post('old_password'), $user_obj->username, $user_obj->salt ) != $user_obj->password ) {

				$error = new SKD_Error( 'invalid_old_password', __('Mật khẩu củ không chính xác.'));
			}

			if ( !empty($ci->input->post('new_password')) ) {

				$user_array['password'] = removeHtmlTags( $ci->input->post('new_password') );
			}
			else {

				if( is_skd_error($error) ) $error->add( 'invalid_new_password', __('Mật khẩu mới không chính xác.') );
				else $error = new SKD_Error( 'invalid_new_password', __('Mật khẩu mới không chính xác.'));
			}

			if ( empty($ci->input->post('re_new_password')) ||  $ci->input->post('re_new_password') != $ci->input->post('new_password') ) {

				if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.') );
				else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.'));
			}

			$error = apply_filters('admin_user_password_errors', $error, $user_array, $user_obj );

			if( !is_skd_error($error) ) {

				$user_array = apply_filters( 'pre_update_password', $user_array, $user_obj );

				$error = update_user( $user_array );

				if( !is_skd_error($error) && have_posts($user_meta) ) {

					foreach ($user_meta as $user_meta_key => $user_meta_value) {

						if ( !empty( $user_meta_value ) ) update_user_meta( $user_obj->id, $user_meta_key, $user_meta_value );
					}

				}
			}

			if( is_skd_error($error) ) {

				foreach ($error->errors as $error_key => $error_value) {

					$ci->template->set_message( notice('error', $error_value[0]), $error_key );
				}

			}
			else {
				$ci->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_password_success' );
			}

		}
	}

	include 'html/user-password.php';
}