<?php
function excel_ajax_config_save($ci, $model) {

    $result['status']     = 'error';

    $result['message']  = 'Cập nhật không thành công.';

    if($ci->input->post()) {

        $data = $ci->input->post();

        $option = excel_config();

        if(isset($data['order_row'])) {

            foreach ($data['order_row'] as $key => &$row) {
                
                if(!isset($row['us'])) $row['us'] = false;
            }

            $option['order_row'] = add_magic_quotes($data['order_row']);
        }

        update_option('excel_congfig', $option);

        $result['status']     = 'success';

        $result['message']  = 'Cập nhật dữ liệu thành công.';
    }

    echo json_encode($result);
}

register_ajax_admin('excel_ajax_config_save');