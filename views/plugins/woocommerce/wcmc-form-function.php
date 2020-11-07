<?php
if(!function_exists('_form_product_categories')) {

    function _form_product_categories($param, $value = '') {

        $output     = '';

        $ci         = &get_instance();

        $options    = wcmc_gets_category( array('mutilevel' => 'option') );

        $options[0] = 'chọn danh mục';

        $output     .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');

        return $output;
    }
}

if(!function_exists('_form_product_suppliers')) {

    function _form_product_suppliers($param, $value = '') {

        $output     = '';

        $ci         = &get_instance();

        $options    = gets_suppliers_option();

        $output     .= form_dropdown($param->field, $options, set_value($param->field, $value), ' class="form-control '.$param->class.'" id="'.$param->id.'"');

        return $output;
    }
}