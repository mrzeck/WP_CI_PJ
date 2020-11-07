<?php
/**
* libraries phân trang website
*/
class paging
{
    private $_config = array(

        'current_page'  => 1, // trang hiện tại

        'total_rows'    => 1, // tổng số rows

        'total_page'    => 1, // tổng số trang

        'number'        => 20, //số rows trên 1 page

        'offset'        => 0, // vi trí bắt đầu

        'url'           => '',

        'url_first'     => '',

        'range'         => 5,

        'min'           => 0,

        'max'           => 0,
    );

    public function __construct($config = array()) {
        foreach ($config as $key => $val){

            if (isset($this->_config[$key])){

                $this->_config[$key] = $val;
            }
        }

        if ($this->_config['number'] < 0){

            $this->_config['number'] = 0;
        }

        if(is_array($this->_config['total_rows']) || $this->_config['total_rows'] == 0) $this->_config['total_rows'] = 1;

        $this->_config['total_page'] = ceil($this->_config['total_rows'] / $this->_config['number']);


        if (!$this->_config['total_page']){

            $this->_config['total_page'] = 1;
        }

        if ($this->_config['current_page'] < 1){

            $this->_config['current_page'] = 1;
        }
         
        if ($this->_config['current_page'] > $this->_config['total_page']){

            $this->_config['current_page'] = $this->_config['total_page'];
        }

        $this->_config['offset'] = ($this->_config['current_page'] - 1) * $this->_config['number'];

        $middle = ceil($this->_config['range'] / 2);

        if ($this->_config['total_page'] < $this->_config['range']){

            $this->_config['min'] = 1;

            $this->_config['max'] = $this->_config['total_page'];
        }
        else
        {
            $this->_config['min'] = $this->_config['current_page'] - $middle + 1;
             
            $this->_config['max'] = $this->_config['current_page'] + $middle - 1;
             
            if ($this->_config['min'] < 1){

                $this->_config['min'] = 1;

                $this->_config['max'] = $this->_config['range'];
            }
            else if ($this->_config['max'] > $this->_config['total_page'])
            {
                $this->_config['max'] = $this->_config['total_page'];

                $this->_config['min'] = $this->_config['total_page'] - $this->_config['range'] + 1;
            }
        }
    }

    public function getoffset()
    {
        return $this->_config['number']*$this->_config['current_page']-$this->_config['number'];
    }

    public function get_current_page()
    {
        return $this->_config['current_page'];
    }

    public function get_total_page()
    {
        return $this->_config['total_page'];
    }

    private function __link($page) {

        if ($page <= 1 && $this->_config['url_first']){

            return $this->_config['url_first'];
        }

        if( strpos($this->_config['url'], '{paging}') !== false ) {
            return str_replace('{paging}', $page, $this->_config['url']);
        }
        else {
            return str_replace('{page}', $page, $this->_config['url']);
        }
    }

    public function html()
    {  
        if( is_admin() )
            return $this->html_backend();
        else
            return $this->html_fontend();
    }

    public function html_backend() {

        if($this->_config['total_rows'] <= $this->_config['number']) return;

        $html =     apply_filters('admin_pagination_start', '<nav><ul class="pagination">');

        // Nút prev và first
        if ($this->_config['current_page'] > 1)
        {
            $html .= apply_filters('admin_pagination_first', '<li><a href="'.$this->__link('1').'" aria-label="Previous"><i class="fal fa-chevron-double-left"></i></a></li>', $this->__link('1') );
            $html .= apply_filters('admin_pagination_prev', '<li><a href="'.$this->__link($this->_config['current_page']-1).'">Prev</a></li>', $this->__link($this->_config['current_page']-1) );
        }

        //nút prev và first disabled
        if ($this->_config['current_page'] == 1)
        {  
            $html .= apply_filters('admin_pagination_first_disabled', '<li class="disabled"><span aria-hidden="true"><i class="fal fa-chevron-double-left"></i></span></li>');
            $html .= apply_filters('admin_pagination_prev_disabled',  '<li class="disabled"><span aria-hidden="true">Prev</span></li>');
        }
         
        // lặp trong khoảng cách giữa min và max để hiển thị các nút
        for ($i = $this->_config['min']; $i <= $this->_config['max']; $i++)
        {
            // Trang hiện tại
            if ($this->_config['current_page'] == $i){
                $html .= apply_filters('admin_pagination_item_active', '<li class="active"><span>'.$i.'</span></li>', $i);
            }
            else{
                $html .= apply_filters('admin_pagination_item', '<li><a href="'.$this->__link($i).'">'.$i.'</a></li>', $this->__link($i), $i);
            }
        }

        // Nút last và next
        if ($this->_config['current_page'] < $this->_config['total_page'])
        {
            $html .= apply_filters('admin_pagination_next', '<li><a href="'.$this->__link($this->_config['current_page'] + 1).'">Next</a></li>', $this->__link($this->_config['current_page'] + 1) );
            $html .= apply_filters('admin_pagination_last', '<li><a href="'.$this->__link($this->_config['total_page']).'" aria-label="Next"><span aria-hidden="true"><i class="fal fa-chevron-double-right"></i></span></a></li>', $this->__link($this->_config['total_page']) );
        }

        $html .=    apply_filters('admin_pagination_end', '</ul></nav>');

        return $html;
    }

    public function html_fontend() {

        if($this->_config['total_rows'] <= $this->_config['number']) return;

        $html =     apply_filters('pagination_start', '<nav><ul class="pagination">');

        // Nút prev và first
        if ($this->_config['current_page'] > 1)
        {
            $html .= apply_filters('pagination_first', '<li><a href="'.$this->__link('1').'" aria-label="Previous"><i class="fal fa-chevron-double-left"></i></a></li>', $this );
            $html .= apply_filters('pagination_prev', '<li><a href="'.$this->__link($this->_config['current_page']-1).'">Prev</a></li>', $this );
        }

        //nút prev và first disabled
        if ($this->_config['current_page'] == 1)
        {  
            $html .= apply_filters('pagination_first_disabled', '<li class="disabled"><span aria-hidden="true"><i class="fal fa-chevron-double-left"></i></span></li>');
            $html .= apply_filters('pagination_prev_disabled',  '<li class="disabled"><span aria-hidden="true">Prev</span></li>');
        }
         
        // lặp trong khoảng cách giữa min và max để hiển thị các nút
        for ($i = $this->_config['min']; $i <= $this->_config['max']; $i++)
        {
            // Trang hiện tại
            if ($this->_config['current_page'] == $i){
                $html .= apply_filters('pagination_item_active', '<li class="active"><span>'.$i.'</span></li>', $this, $i);
            }
            else{
                $html .= apply_filters('pagination_item', '<li><a href="'.$this->__link($i).'">'.$i.'</a></li>', $this, $i);
            }
        }

        // Nút last và next
        if ($this->_config['current_page'] < $this->_config['total_page'])
        {
            $html .= apply_filters('pagination_next', '<li><a href="'.$this->__link($this->_config['current_page'] + 1).'">Next</a></li>', $this );
            $html .= apply_filters('pagination_last', '<li><a href="'.$this->__link($this->_config['total_page']).'" aria-label="Next"><i class="fal fa-chevron-double-right"></i></a></li>', $this );
        }

        $html .=    apply_filters('pagination_end', '</ul></nav>');

        return $html;
    }
}