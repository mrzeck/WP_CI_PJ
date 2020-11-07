<?php
require_once FCPATH.'views/plugins/excel-editor/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Skd_Excel_Editor {

    public $spreadsheet;

    public $sheet = array();

    public $filename = '';

    public $path = 'uploads';

    public function __construct() {
        $this->spreadsheet = new Spreadsheet();
    }

    function get_excel_characters() {

        $excel_characters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $excel_characters_more = array();
        foreach ($excel_characters as $char_one) {
            foreach ($excel_characters as $char_two) {
                $excel_characters_more[] = $char_one.$char_two;
            }
        }

        $excel_characters = array_merge($excel_characters, $excel_characters_more);

        return $excel_characters;
    }

    function create_sheet( $title ) {
        return $this->sheet[] = new Skd_Excel_Sheet( $title );
    }

    function set_filename( $filename ) {
        $this->filename = $filename;
    }

    function set_path($path) {
        $this->path = $path;
    }

    function get_filename() {
        return $this->filename;
    }

    function get_path() {
        return $this->path;
    }

    function write() {

        $excel_characters = $this->get_excel_characters();

        $alignment['horizontal'] = array(
            'right' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            'left' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'center' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        );

        $alignment['vertical'] = array(
            'top' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'center' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        );

        foreach ($this->sheet as $sheet_key => $sheet_data) {

            if($sheet_key != 0) $this->spreadsheet->createSheet();

            $sheet = $this->spreadsheet->setActiveSheetIndex($sheet_key);

            $sheet->setTitle($sheet_data->get_title());

            $sheet_header = $sheet_data->get_header();

            $sheet->getDefaultRowDimension()->setRowHeight(20);

            //Header
            if( have_posts($sheet_header) ) {

                foreach ($sheet_header as $header_title => $header_format) {

                    if(isset($header_format['mergeCells']) ) {

                        $cell = $header_format['mergeCells'][0];

                        $sheet->mergeCells($header_format['mergeCells'][0].':'.$header_format['mergeCells'][1]);
                    }
                    else {

                        $cell = $header_format['cell'];
                    }

                    $sheet->setCellValue($cell, $header_title);

                    $styleArray = $sheet_data->get_header_style();

                    if(isset($header_format['style'])) $styleArray = $header_format['style'];

                    if(isset($styleArray['style']['alignment']['horizontal'])) {
                        
                        $styleArray['style']['alignment']['horizontal'] = $alignment['horizontal'][$styleArray['style']['alignment']['horizontal']];
                    }

                    if(isset($styleArray['style']['alignment']['vertical'])) {
                        
                        $styleArray['style']['alignment']['vertical'] = $alignment['vertical'][$styleArray['style']['alignment']['vertical']];
                    }

                    $sheet->getStyle($cell)->applyFromArray($styleArray);

                    $sheet->getColumnDimension($cell)->setAutoSize(true);
                }
            }
            
            $sheet_body = $sheet_data->get_body();


            //body
            if( have_posts($sheet_body) ) {

                $cell_row_char = $sheet_data->get_body_cell_begin()[0];

                $cell_row_char_index = array_search($cell_row_char, $excel_characters); //giá trị chữ

                $cell_row_num     = $sheet_data->get_body_cell_begin()[1]; // giá trị số

                foreach ($sheet_body as $key_item => $item) {

                    $_cell_char_index = $cell_row_char_index;

                    foreach ($item as $item_KEY => $item_data) {

                        $item_style = $sheet_data->get_body_style();

                        if( isset($item_data['style']) ) $item_style = $item_data['style'];

                        $cell = $excel_characters[$_cell_char_index].$cell_row_num;

                        $sheet->setCellValue($cell, $item_data['title']);

                        $sheet->getColumnDimension($excel_characters[$_cell_char_index])->setAutoSize(true);

                        $sheet->getStyle($cell)->applyFromArray($item_style);

                        if( isset($item_data['type']) && isset($item_data['type_format']) ) {

                            if($item_data['type'] == 'number') {
                                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode($item_data['type_format']);
                            }
                        }

                        $_cell_char_index++;
                    }

                    $cell_row_num++;
                }
            }
        }

    }

    function write_save() {

        $this->write();

        $this->spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($this->spreadsheet);

        ob_end_clean();

        $writer->save($this->path.'/'.$this->filename.'.xlsx');
    }

    function write_download() {

        $this->write();

        $this->spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($this->spreadsheet);

        ob_end_clean();

        header('Content-type: application/vnd.ms-excel');

        header('Content-Disposition: attachment; filename="'.$this->filename.'.xlsx"');
        
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        
    }
}