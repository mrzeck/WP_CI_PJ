<?php 
require 'src/vendor/autoload.php';
require 'src/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require 'src/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
if( is_admin() ) {
    function register_admin_nav_excel( ) {
        register_admin_subnav('products', 'Nhập Excel', 'products_excel', 'plugins?page=products_excel', array('callback' => 'product_excel_callback'));
        // register_admin_subnav('woocommerce', 'Xuất Excel Đơn Hàng', 'woocommerce_excel', 'plugins?page=woocommerce_excel', array('callback' => 'woocommerce_excel_callback'));
    }
    add_action( 'init', 'register_admin_nav_excel' );
    function product_excel_callback() {
        $ci =& get_instance();
        if( $ci->input->get('del') != null ) {
            $path = str_replace('views/theme-store/excel', '', __DIR__);
            $file = $path.removeHtmlTags(urldecode( $ci->input->get('del') ));
            if( file_exists($file)) {
                unlink( $file );
            }
            redirect('admin/plugins?page=products_excel');
        }
        if( $ci->input->post('add') )       product_excel_nhap( $ci->input->post() );
        if( $ci->input->post('export') )    product_excel_xuat( $ci->input->post() );
        if( $ci->input->post('update') )    product_excel_capnhat( $ci->input->post() );
        include 'html-excel.php';
    }
    function product_excel_nhap( $post ) {
        $ci =& get_instance();
        include 'PHPExcel.php';
        $path = str_replace('views/theme-store/include/excel', 'uploads/source/', __DIR__);
        $file = $path.process_file( $ci->input->post('excel') );
        //Tiến hành xác thực file
        $objFile = PHPExcel_IOFactory::identify($file);
        $objData = PHPExcel_IOFactory::createReader($objFile);
        //Chỉ đọc dữ liệu
        $objData->setReadDataOnly(true);
        // Load dữ liệu sang dạng đối tượng
        $objPHPExcel = $objData->load($file);
        //Lấy ra số trang sử dụng phương thức getSheetCount();
        // Lấy Ra tên trang sử dụng getSheetNames();
        //Chọn trang cần truy xuất
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        //Lấy ra số dòng cuối cùng
        $Totalrow = $sheet->getHighestRow();
        //Lấy ra tên cột cuối cùng
        $LastColumn = $sheet->getHighestColumn();
        //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
        //Tạo mảng chứa dữ liệu
        $data = [];
        //Tiến hành lặp qua từng ô dữ liệu
        //----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
        for ($i = 2; $i <= $Totalrow; $i++) {
            //----Lặp cột
            for ($j = 0; $j < $TotalCol; $j++) {
                // Tiến hành lấy giá trị của từng ô đổ vào mảng
                $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
            }
        }
        $outsite = array();
        //Hiển thị mảng dữ liệu
        foreach ($data as $pr) {
            $outsite['relationships'] = array();
            $pr[0] = str_replace(',', '', trim($pr[0])); //Tên SP
            $pr[1] = str_replace(',', '', trim($pr[1])); //ảnh đại diện
            $pr[2] = (int)str_replace(',', '', trim($pr[2])); //mã danh mục
            $pr[3] = str_replace(',', '', trim($pr[3])); //tóm tắt
            $pr[4] = str_replace(',', '', trim($pr[4])); //giá khuyến mãi
            // $pr[5] = str_replace(',', '', trim($pr[5])); //ảnh đại diện
            $pr[5] = str_replace(',', '', trim($pr[5])); //giá
            // $pr[7] = str_replace(',', '', trim($pr[7])); //giá khuyến mãi
            $pr[6] = (int)trim($pr[6]); //số lượng
            $product = array(
                'title'     => $pr[0],
                'excerpt'   => $pr[3],
                // 'content'   => $pr[4],
                'image'     => process_file($pr[1]),
                'price'     => $pr[4],
                'price_sale'=> $pr[5],
            );
            if( $pr[2] != 0 ) $outsite['relationships'][] = $pr[2];
            $result = insert_pr( $product, $outsite );
            if( isset($result['id']) && $result['id'] != 0 ) {
                // update_metadata( 'woocommerce', $result['id'], '_code', $pr[1] );
                update_metadata( 'woocommerce', $result['id'], '_stock', $pr[6] );
                $ci->template->set_message(notice( 'success', 'Sản phẩm <b>'.$pr[0].'</b> đã được thêm'), array('name' => $result['id']));
            }
        }
    }
    function product_excel_xuat( $post ) {
        $model = get_model('products');
        $args = array( 'where' => array('public' => 1) );
        if( isset($post['category']) && (int)$post['category'] != 0 ) {

            $args['where_category'] = wcmc_gets_category(  array( 'where' => array( 'id' => (int)$post['category'] ) ) );
        }
        $products = gets_product( $args );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Tên Sản Phẩm');
        $sheet->setCellValue('B1', 'Ảnh đại diện');
        $sheet->setCellValue('C1', 'ID Danh Mục');
        $sheet->setCellValue('D1', 'Tóm Tắt');
        $sheet->setCellValue('E1', 'Giá');
        $sheet->setCellValue('F1', 'Giá Khuyến Mãi');
        $sheet->setCellValue('G1', 'Số Lượng');
        
        $numRow = 2;
        foreach ($products as $pr) {
            $sheet->setCellValue('A' . $numRow, $pr->title );
            $sheet->setCellValue('B' . $numRow, $pr->image );
            $categories = $model->gets_relationship_join_categories($pr->id, 'products', 'products_categories');
            $cate_name  = '';
            $cate_id    = '';
            foreach ($categories as $key => $value) {
                $cate_name  .= sprintf('%s, ', $value->name);
                $cate_id    .= sprintf('%s, ', $value->id);
            }
            $cate_name  = trim($cate_name,', ');
            $cate_id    = trim($cate_id,', ');
            // $sheet->setCellValue('C' . $numRow, $cate_name );
            $sheet->setCellValue('C' . $numRow, $cate_id );
            $sheet->setCellValue('D' . $numRow, $pr->excerpt );
            $sheet->setCellValue('E' . $numRow, $pr->price );
            $sheet->setCellValue('F' . $numRow, $pr->price_sale );
            $sheet->setCellValue('G' . $numRow, get_metadata( 'woocommerce', $pr->id, '_stock', true ) );
            $numRow++;
        }
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $writer = new Xlsx($spreadsheet);
        $writer->save('uploads/excel/san-pham-'.date('d-m-Y-H-i-s').'.xlsx');
    }
    function product_excel_capnhat( $post ) {
        $ci =& get_instance();
        include 'PHPExcel.php';
        $path = str_replace('views/theme-store/include/excel', 'uploads/source/', __DIR__);
        $file = $path.process_file( $ci->input->post('excel_update') );
        //Tiến hành xác thực file
        $objFile = PHPExcel_IOFactory::identify($file);
        $objData = PHPExcel_IOFactory::createReader($objFile);
        //Chỉ đọc dữ liệu
        $objData->setReadDataOnly(true);
        // Load dữ liệu sang dạng đối tượng
        $objPHPExcel = $objData->load($file);
        //Lấy ra số trang sử dụng phương thức getSheetCount();
        // Lấy Ra tên trang sử dụng getSheetNames();
        //Chọn trang cần truy xuất
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        //Lấy ra số dòng cuối cùng
        $Totalrow = $sheet->getHighestRow();
        //Lấy ra tên cột cuối cùng
        $LastColumn = $sheet->getHighestColumn();
        //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
        //Tạo mảng chứa dữ liệu
        $data = [];
        //Tiến hành lặp qua từng ô dữ liệu
        //----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
        for ($i = 2; $i <= $Totalrow; $i++) {
            //----Lặp cột
            for ($j = 0; $j < $TotalCol; $j++) {
                // Tiến hành lấy giá trị của từng ô đổ vào mảng
                $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
            }
        }
        $outsite = array();
        //Hiển thị mảng dữ liệu
        foreach ($data as $pr) {
             $outsite['relationships'] = array();
            $pr[0] = str_replace(',', '', trim($pr[0])); //Tên SP
            $pr[1] = str_replace(',', '', trim($pr[1])); //ảnh đại diện
            $pr[2] = (int)str_replace(',', '', trim($pr[2])); //mã danh mục
            $pr[3] = str_replace(',', '', trim($pr[3])); //tóm tắt
            $pr[4] = str_replace(',', '', trim($pr[4])); //giá khuyến mãi
            // $pr[5] = str_replace(',', '', trim($pr[5])); //ảnh đại diện
            $pr[5] = str_replace(',', '', trim($pr[5])); //giá
            // $pr[7] = str_replace(',', '', trim($pr[7])); //giá khuyến mãi
            $pr[6] = (int)trim($pr[6]); //số lượng
            $product = array(
                'title'     => $pr[0],
                'excerpt'   => $pr[3],
                // 'content'   => $pr[4],
                'image'     => process_file($pr[1]),
                'price'     => $pr[4],
                'price_sale'=> $pr[5],
            );
            if( $pr[2] != 0 ) $outsite['relationships'][] = $pr[2];
            $result = insert_pr( $product, $outsite );
            if( isset($result['id']) && $result['id'] != 0 ) {
                // update_metadata( 'woocommerce', $result['id'], '_code', $pr[1] );
                update_metadata( 'woocommerce', $result['id'], '_stock', $pr[6] );
                $ci->template->set_message(notice( 'success', 'Sản phẩm <b>'.$pr[0].'</b> đã được cập nhật'), array('name' => $result['id']));
            }

            
        }
    }
    //
    function woocommerce_excel_callback() {
        $ci =& get_instance();
        if( $ci->input->get('del') != null ) {
            $path = str_replace('views/theme-store/excel', '', __DIR__);
            $file = $path.removeHtmlTags(urldecode( $ci->input->get('del') ));
            if( file_exists($file)) {
                unlink( $file );
            }
            redirect('admin/plugins?page=woocommerce_excel');
        }
        if( $ci->input->post('export') ) {
            if( $ci->input->post('date_start') != '' ) {
                $start = removeHtmlTags($ci->input->post('date_start')).' 00:00:00';
                $start = date('Y-m-d h:i:s', strtotime($start));
                $where['created >='] = $start;
            }
            if( $ci->input->post('date_end') != '' ) {
                $end = removeHtmlTags($ci->input->post('date_end')).' 23:59:59';
                $end = date('Y-m-d h:i:s', strtotime($end));
                $where['created <='] = $end;
            }
            $orders = wcmc_gets_order( array( 'where' => $where ) );
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Mã Đơn Hàng');
            $sheet->setCellValue('B1', 'Ngày Tạo');
            $sheet->setCellValue('C1', 'Tên Khách Hàng');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Điện Thoại');
            $sheet->setCellValue('F1', 'Địa Chỉ');
            $sheet->setCellValue('G1', 'Tình Trạng');
            $sheet->setCellValue('H1', 'Tổng Tiền');
            // $sheet->setCellValue('I1', 'Giá Khuyến Mãi');
            // $sheet->setCellValue('J1', 'Số Lượng');
            $numRow = 2;
            foreach ($orders as $order) {
                $sheet->setCellValue('A' . $numRow, $order->code );
                $sheet->setCellValue('B' . $numRow, $order->created );
                $sheet->setCellValue('C' . $numRow, $order->billing_fullname );
                $sheet->setCellValue('D' . $numRow, $order->billing_email );
                $sheet->setCellValue('E' . $numRow, $order->billing_phone );
                $sheet->setCellValue('F' . $numRow, $order->billing_address );
                $sheet->setCellValue('G' . $numRow, woocommerce_order_status_label($order->status) );
                $sheet->setCellValue('H' . $numRow, $order->total );
                // $sheet->setCellValue('I' . $numRow, $pr->price_sale );
                // $sheet->setCellValue('J' . $numRow, get_metadata( 'woocommerce', $pr->id, '_stock', true ) );
                $numRow++;
            }
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            // $sheet->getColumnDimension('I')->setAutoSize(true);
            // $sheet->getColumnDimension('J')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);
            $writer->save('uploads/excel/donhang/don-hang-'.date('d-m-Y-H-i-s').'.xlsx');
        }
        include 'html-woocommerce-excel.php';
    }
}
if( !function_exists('insert_pr') ) {
    function insert_pr( $postarr = array(), $outsite = array() ) {
        $ci =& get_instance();
        $model      = get_model('products');
        $model->settable('products');
        $defaults = array(
            'title'     => '',
            'content'   => '',
            'excerpt'   => '',
            'image'     => '',
            'price'     => 0,
        );
        $postarr = array_merge($defaults, $postarr);
        $object = array();
        if( isset($postarr['id']) ) {
            $postarr['id'] = (int)removeHtmlTags($postarr['id']);
            $object = get_post( $postarr['id'] );
            if( have_posts($object) ) {
                $postarr['title']   = (empty($postarr['title'])) ? $object->title : $postarr['title'];
            }
        }
        $ci->data['module']   = 'products';
        $ci->data['param']    = array('slug' => 'title');
        $postarr[$ci->language['default']]['title']     = removeHtmlTags($postarr['title']); unset($postarr['title']);
        $postarr[$ci->language['default']]['content']   = $postarr['content']; unset($postarr['content']);
        $postarr[$ci->language['default']]['excerpt']   = $postarr['excerpt']; unset($postarr['excerpt']);
        $rules  = $ci->form_gets_field( array('class' => 'products') );
        if( !have_posts($object) )
            return $ci->_form_add($rules, $postarr, $outsite);
        else {
            return $ci->_form_edit($rules, $postarr, $object->id, $outsite);
        }
    }
}
function ajax_product_xuatexcel( $ci, $model ) {
    $result['type'] = 'success';
    if( $ci->input->post() ) {
        $args = $ci->input->post('value');
        $args = @unserialize($args);
        if( have_posts($args) ) {
            foreach ($args as $key => &$value) {
                if( !is_string($value) ) {
                    foreach ($value as $k => &$v) {
                        if( is_string($v)) $v = removeHtmlTags($v);
                    }
                }
                else $value = removeHtmlTags($value);
            }
        }
        $products = gets_product($args);
        if( have_posts($products) ) {
            $model = get_model('products');
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'ID Sản Phẩm');
            $sheet->setCellValue('B1', 'Tên Sản Phẩm');
            $sheet->setCellValue('C1', 'Mã SP');
            $sheet->setCellValue('D1', 'Danh Mục');
            $sheet->setCellValue('E1', 'ID Danh Mục');
            $sheet->setCellValue('F1', 'Tóm Tắt');
            $sheet->setCellValue('G1', 'Ảnh Đại Diện');
            $sheet->setCellValue('H1', 'Giá');
            $sheet->setCellValue('I1', 'Giá Khuyến Mãi');
            $sheet->setCellValue('J1', 'Số Lượng');
            $numRow = 2;
            foreach ($products as $pr) {
                $sheet->setCellValue('A' . $numRow, $pr->id );
                $sheet->setCellValue('B' . $numRow, $pr->title );
                $sheet->setCellValue('C' . $numRow, get_metadata( 'woocommerce', $pr->id, '_code', true ) );
                $categories = $model->gets_relationship_join_categories($pr->id, 'products', 'products_categories');
                $cate_name  = '';
                $cate_id    = '';
                foreach ($categories as $key => $value) {
                    $cate_name  .= sprintf('%s, ', $value->name);
                    $cate_id    .= sprintf('%s, ', $value->id);
                }
                $cate_name  = trim($cate_name,', ');
                $cate_id    = trim($cate_id,', ');
                $sheet->setCellValue('D' . $numRow, $cate_name );
                $sheet->setCellValue('E' . $numRow, $cate_id );
                $sheet->setCellValue('F' . $numRow, $pr->excerpt );
                $sheet->setCellValue('G' . $numRow, $pr->image );
                $sheet->setCellValue('H' . $numRow, $pr->price );
                $sheet->setCellValue('I' . $numRow, $pr->price_sale );
                $sheet->setCellValue('J' . $numRow, get_metadata( 'woocommerce', $pr->id, '_stock', true ) );
                $numRow++;
            }
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);
            $url = 'uploads/excel/san-pham-'.date('d-m-Y-H-i-s').'.xlsx';
            $writer->save($url);
            $result['message'] = $url;
        }
        else {
            $result['message'] = 'Không có sản phẩm nào để xuất';
            $result['type'] = 'error';
        }
    }
    echo json_encode($result);
}
register_ajax('ajax_product_xuatexcel');