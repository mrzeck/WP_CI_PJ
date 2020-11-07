<?php
class Skd_Excel_Sheet {

    public $title;

    public $header = array();

    public $header_style = array();

    public $body_cell_begin = array();

    public $body_data = array();

    public $body_style = array();

    public function __construct( $title )
    {
        $this->title = removeHtmlTags($title);

        $this->header_style = [
            'font' => [ 'size' => 12, ]
        ];

        $this->body_style = [
            'font' => [ 'size' => 12, ]
        ];

    }

    public function set_header( $header = array() )
    {
        $this->header = $header;
    }

    public function set_header_style( $header_style = array() )
    {
        $this->header_style = $header_style;
    }

    public function set_body( $cell_begin = array(), $data = array() )
    {
        $this->body_cell_begin = $cell_begin;

        $this->body_data = $data;
    }

    public function set_body_item( $item = array() )
    {
        $this->body_data[] = $item;
    }

    public function set_body_style( $body_style = array() )
    {
        $this->body_style = $body_style;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_header()
    {
        return $this->header;
    }

    public function get_header_style()
    {
        return $this->header_style;
    }

    public function get_body_cell_begin()
    {
        return $this->body_cell_begin;
    }

    public function get_body()
    {
        return $this->body_data;
    }

    public function get_body_style()
    {
        return $this->body_style;
    }
}