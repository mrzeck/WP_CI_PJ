<?php
function ajax_rating_star_save($ci, $model) {
        $result['type'] = 'error';
        $result['message'] = 'Lưu dữ liệu không thành công.';
        if($ci->input->post()) {
                $data = $ci->input->post();
                $setting_form = get_rating_star_setting()['form'];
                $has_approving = get_rating_star_setting()['has_approving'];
                if($setting_form['name']['required'] != 'hiden') {
                        $rating['name']        = removeHtmlTags($data['rating_star_name']);
                        if($setting_form['name']['required'] == 'required' && empty($rating['name'])) {
                                $result['message'] = 'Không được để trống tên của bạn.';
                                echo json_encode($result);
                                return false;
                        }
                }
                if($setting_form['email']['required'] != 'hiden') {
                        $rating['email']       = removeHtmlTags($data['rating_star_email']);
                        if($setting_form['email']['required'] == 'required' && empty($rating['email'])) {
                                $result['message'] = 'Không được để trống email của bạn.';
                                echo json_encode($result);
                                return false;
                        }
                }
                if($setting_form['title']['required'] != 'hiden') {
                        $rating['title']       = removeHtmlTags($data['rating_star_title']);
                        if($setting_form['title']['required'] == 'required' && empty($rating['title'])) {
                                $result['message'] = 'Không được để trống tiêu đề của bạn.';
                                echo json_encode($result);
                                return false;
                        }
                }
                $rating['message']     = removeHtmlTags($data['rating_star_message']);
                if(empty($rating['message'])) {
                        $result['message'] = 'Không được để trống nhận xét của bạn.';
                        echo json_encode($result);
                        return false;
                }
                $rating['star']       = removeHtmlTags($data['rating']);
                $rating['object_id']   = removeHtmlTags($data['object_id']);
                $rating['object_type'] = removeHtmlTags($data['object_type']);
                if($has_approving == 1) $rating['status'] = 'hiden';
                $id = insert_rating_star($rating);
                if(!is_skd_error($id)) {
                        if($has_approving == 0) {
                                $rating_star_product = get_metadata($rating['object_type'], $rating['object_id'], 'rating_star', true );
                                if(!have_posts($rating_star_product)) {
                                        $rating_star_product = array(
                                                'count' => 0,
                                                'star'  => 0
                                        );
                                }
                                $rating_star_product['count'] += 1;
                                $rating_star_product['star']  += $rating['star'];
                                update_metadata($rating['object_type'], $rating['object_id'], 'rating_star', $rating_star_product);
                        }
                        $result['type'] = 'success';
                        $result['message'] = 'Cám ơn bạn đã đanh giá sản phẩm này.';
                }
        }
        echo json_encode($result);
}
register_ajax('ajax_rating_star_save');
function ajax_rating_star_setting_save($ci, $model) {
        $result['type'] = 'error';
        $result['message'] = 'Lưu dữ liệu không thành công.';
        if($ci->input->post()) {
                $data = $ci->input->post('rating_star_setting');
                $rating['has_approving']        = removeHtmlTags($data['has_approving']);
                $rating['form']                 = add_magic_quotes($data['form']);
                $rating['color']                = add_magic_quotes($data['color']);
                update_option('rating_star_setting', $rating );
                $result['message']      = 'Cập nhật dữ liệu thành công';
                $result['type']         = 'success';
        }
        echo json_encode($result);
}
register_ajax_admin('ajax_rating_star_setting_save');
function ajax_rating_star_status_save($ci, $model) {
        $result['type'] = 'error';
        $result['message'] = 'Lưu dữ liệu không thành công.';
        if($ci->input->post()) {
                $id = (int)$ci->input->post('id');
                $rating_star = get_rating_star($id);
                if(have_posts($rating_star)) {
                        $status = $rating_star->status;
                        if($status == 'public') {
                                $rating_star->status = 'hiden';
                                $result['status']               = '<span class="label label-danger">Ẩn</span>';
                                $result['status_label']         = 'Hiển thị';
                                $rating_star_product = get_metadata('product', $rating_star->object_id, 'rating_star', true );
                                if(!have_posts($rating_star_product)) {
                                        $rating_star_product = array(
                                                'count' => 0,
                                                'star'  => 0
                                        );
                                }
                                $rating_star_product['count'] -= 1;
                                $rating_star_product['star']  -= $rating_star->star;
                                update_metadata('product', $rating_star->object_id, 'rating_star', $rating_star_product);
                        }
                        if($status == 'hiden') {
                                $rating_star->status = 'public';
                                $result['status']               = '<span class="label label-success">Hiển thị</span>';
                                $result['status_label']         = 'Ẩn';
                                $rating_star_product = get_metadata('product', $rating_star->object_id, 'rating_star', true );
                                if(!have_posts($rating_star_product)) {
                                        $rating_star_product = array(
                                                'count' => 0,
                                                'star'  => 0
                                        );
                                }
                                $rating_star_product['count'] += 1;
                                $rating_star_product['star']  += $rating_star->star;
                                update_metadata('product', $rating_star->object_id, 'rating_star', $rating_star_product);
                        }
                        insert_rating_star((array)$rating_star);
                        $result['message']      = 'Cập nhật dữ liệu thành công';
                        $result['type']         = 'success';
                }
        }
        echo json_encode($result);
}
register_ajax_admin('ajax_rating_star_status_save');
function ajax_rating_star_delete($ci, $model) {
        $result['type'] = 'error';
        $result['message'] = 'Lưu dữ liệu không thành công.';
        if($ci->input->post()) {
                $id = (int)$ci->input->post('id');
                $rating_star = get_rating_star($id);
                if(have_posts($rating_star)) {
                        if( delete_rating_star($id) != 0 ){
                                $result['message']      = 'Cập nhật dữ liệu thành công';
                                $result['type']         = 'success';
                        }
                }
        }
        echo json_encode($result);
}
register_ajax_admin('ajax_rating_star_delete');
