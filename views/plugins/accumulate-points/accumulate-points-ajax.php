<?php
function tddq_ajax_config_save($ci, $model) {
    $result['status']     = 'error';
    $result['message']  = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $data = $ci->input->post();
        $option = tddq_config();
        if(isset($data['receiving_form'])) {
            $option['receiving_form'] = (int)$data['receiving_form'];
            $option['point_type'] = (int)$data['point_type'];
            $option['receiving_range'] = add_magic_quotes($data['receiving_range']);
            $option['receiving_conver'] = add_magic_quotes($data['receiving_conver']);
        }
        if(isset($data['point_conver'])) {
            $option['point_conver'] = (int)$data['point_conver'];
        }
        if(isset($data['pay_type'])) {
            $option['pay_type'] = add_magic_quotes($data['pay_type']);
        }
        update_option('tddq_congfig', $option);
        $result['status']     = 'success';
        $result['message']  = 'Cập nhật dữ liệu thành công.';
    }
    echo json_encode($result);
}
register_ajax_admin('tddq_ajax_config_save');
function tddq_ajax_checkout_check($ci, $model) {
    $result['status']     = 'error';
    $result['message']  = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $point = (int)$ci->input->post('point');
        $error = tddq_checkout_check(
            $point,
            $ci->data['user']->id,
            $ci->cart->total()
        );
        if($error === true) {
            $result['status']     = 'success';
            $result['message']  = 'Cập nhật dữ liệu thành công.';
        }
        else {
            $result['message']  = notice('error', $error->get_error_message());
        }
    }
    echo json_encode($result);
}
register_ajax('tddq_ajax_checkout_check');
function tddq_ajax_customer_point_plus($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $point      = (int)$ci->input->post('point');
        $id         = (int)$ci->input->post('customer_id');
        $content    = removeHtmlTags($ci->input->post('note'));
        $customer = get_user($id);
        if(have_posts($customer)) {
            $account_point = (int)get_user_meta($customer->id, 'tddq_point', true);
            $account_point = $account_point + $point;
            update_user_meta($customer->id, 'tddq_point', $account_point);
            insert_tddq_history([
                'user_id' => $customer->id,
                'point'   => $point,
                'content' => 'Bạn đã nhận được điểm thưởng (+ '.$point.' điểm ) từ quản trị viên. '.$content
            ]);
            $result['status']     = 'success';
            $result['message']  = 'Cập nhật dữ liệu thành công.';
        }
    }
    echo json_encode($result);
}
register_ajax_admin('tddq_ajax_customer_point_plus');
function tddq_ajax_customer_point_out($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $point      = (int)$ci->input->post('point');
        $id         = (int)$ci->input->post('customer_id');
        $content    = removeHtmlTags($ci->input->post('note'));
        $customer = get_user($id);
        if(have_posts($customer)) {
            $account_point = (int)get_user_meta($customer->id, 'tddq_point', true);
            if($point < $account_point) {
                $account_point = $account_point - $point;
                update_user_meta($customer->id, 'tddq_point', $account_point);
                insert_tddq_history([
                    'user_id' => $customer->id,
                    'point'   => -$point,
                    'content' => 'Bạn đã rút điểm thưởng (- '.$point.' điểm ) từ quản trị viên. '.$content
                ]);
                $result['status']     = 'success';
                $result['message']  = 'Cập nhật dữ liệu thành công.';
            }
            else {
                $result['message']  = 'Số điểm rút lớn hơn số điểm khách hàng hiện có.';
            }
        }
    }
    echo json_encode($result);
}
register_ajax_admin('tddq_ajax_customer_point_out');
function tddq_ajax_account_withdrawal($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $point      = (int)$ci->input->post('point');
        $user       = get_user_current();
        $content    = removeHtmlTags($ci->input->post('content'));
        $bank       = removeHtmlTags($ci->input->post('bank'));
        if(have_posts($user)) {
            $tddq_config = tddq_config();
            $account_point = (int)get_user_meta($user->id, 'tddq_point', true);
            if($point < $account_point) {
                insert_tddq_history([
                    'user_id' => $user->id,
                    'point'   => $point,
                    'type'    => 'withdrawal',
                    'bank'    => $bank,
                    'content' => $content,
                    'status'  => 'wait',
                    'point_conver'  => $tddq_config['point_conver']
                ]);
                $result['status']   = 'success';
                $result['message']  = 'Yêu cầu rút điểm đã được gửi.';
            }
            else {
                $result['message']  = 'Số điểm rút lớn hơn số điểm bạn hiện có.';
            }
        }
    }
    echo json_encode($result);
}
register_ajax_login('tddq_ajax_account_withdrawal');
function tddq_ajax_account_withdrawal_success($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $id         = (int)$ci->input->post('id');
        $withdrawal = get_tddq_history($id);
        if(have_posts($withdrawal)) {
            $account_point = (int)get_user_meta($withdrawal->user_id, 'tddq_point', true);
            if($withdrawal->point < $account_point) {
                $account_point = $account_point - $withdrawal->point;
                update_user_meta($withdrawal->user_id, 'tddq_point', $account_point);
                $withdrawal->status = 'success';
                insert_tddq_history((array)$withdrawal);
                insert_tddq_history([
                    'user_id' => $withdrawal->user_id,
                    'point'   => -$withdrawal->point,
                    'content' => 'Bạn đã rút điểm thưởng (- '.$withdrawal->point.' điểm ) từ yêu cầu rút điểm thưởng.'
                ]);
                $result['status']           = 'success';
                $result['message']          = 'Cập nhật dữ liệu thành công.';
                $result['account_point']    = $account_point;
            }
            else {
                $result['message']  = 'Số điểm rút lớn hơn số điểm khách hàng hiện có.';
            }
        }
    }
    echo json_encode($result);
}
register_ajax_admin('tddq_ajax_account_withdrawal_success');
function tddq_ajax_account_withdrawal_cancel($ci, $model) {
    $result['status']    = 'error';
    $result['message']   = 'Cập nhật không thành công.';
    if($ci->input->post()) {
        $id         = (int)$ci->input->post('id');
        $withdrawal = get_tddq_history($id);
        if(have_posts($withdrawal)) {
            $withdrawal->status = 'cancel';
            insert_tddq_history((array)$withdrawal);
            $result['status']           = 'success';
            $result['message']          = 'Cập nhật dữ liệu thành công.';
        }
    }
    echo json_encode($result);
}
register_ajax_admin('tddq_ajax_account_withdrawal_cancel');
