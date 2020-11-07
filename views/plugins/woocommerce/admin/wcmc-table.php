<?php

function set_product_table_colum() {

	$ci =& get_instance();

	$ci->col['product_category'] = array( );

	$ci->col['product'] = array( );
}

add_action('init', 'set_product_table_colum' );

//product_categories
class skd_product_category_list_table extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = [
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'status'    => 'Nổi bật',
            'order'     => 'Thứ Tự',
            'public'    => 'Hiển Thị',
            'action'    => 'Hành Động',
        ];
    
        $this->_column_headers = apply_filters( "manage_product_category_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_product_category_custom_column', $column_name, $item );
    }

    function column_title($item) {
        ?>
        <h3><?= str_repeat('|-----', (($item->level > 0)?($item->level - 1):0)).$item->name;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= get_url($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_status($item, $column_name, $module, $table) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$table.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url    = URL_ADMIN.'/'.$this->ci->data['ajax'].'/';
        $cate   = ($this->ci->cate_type != null)?'?cate_type='.$this->ci->cate_type:'';
        $class .= ' text-center';
        echo '<td class="'.$class.'">';
        if( current_user_can('wcmc_product_cate_delete') ) {
            echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'"><i class="fa fa-trash"></i></button>';
        }
        if( current_user_can('wcmc_product_cate_edit') ) {
            echo '<a href="'.$url.'edit/'.$item->slug.$cate.'" class="btn-blue btn"><i class="fas fa-pen-square"></i></a>';
        }
        echo "</td>";
    }

    function search_left() {
    }

    function search_right() {
        $module     = $this->ci->data['ajax'];
        $url        = URL_ADMIN.'/'.$module;
        ?>
        <form class="search-box" action="<?= $url ;?>">
            <?php echo _form(array( 'field' => 'keyword', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),'');?>
            <?php echo _form(array( 'field' => 'category', 'label' => '', 'type'  => 'product_categories', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),$this->ci->input->get('category'));?>      
            <label for="title" class="control-label"></label>
            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
        </form>
        <?php
    }
}

//product
class skd_product_list_table extends skd_object_list_table {

    function get_columns() {
        
        $this->_column_headers = [
            'cb'        => 'cb',
            'image'     => 'Hình',
            'title'     => 'Tiêu Đề',
            'categories'=> 'Chuyên mục',
            'price'     => 'Giá',
            'price_sale'=> 'Giá khuyến mãi',
            'public'    => 'Hiển thị',
            'status1'   => 'Yêu thích',
            'status2'   => 'Bán chạy',
            'status3'   => 'Nổi bật',
            'order'     => 'Thứ tự',
            'action'    => 'Hành Động',
        ];
        
        $this->_column_headers = apply_filters( "manage_product_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_product_custom_column', $column_name, $item );
    }

    function column_title($item) {
        ?>
        <h3><?= $item->title;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= get_url($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_categories($item) {
        $str = '';
        foreach ($item->categories as $key => $value) {
            $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/products/products_categories/edit/'.$value->slug, $value->name);
        }
        echo trim($str,', ');
    }

    function column_price($item, $column_name, $module, $table) {
        $str = number_format($item->price);
        echo '<a href="#" data-pk="'.$item->id.'" data-name="price" data-table="'.$table.'" class="edittable-dl-text" >'.$str.'</a>';
    }

    function column_price_sale($item, $column_name, $module, $table) {
        $str = number_format($item->price_sale);
        echo '<a href="#" data-pk="'.$item->id.'" data-name="price_sale" data-table="'.$table.'" class="edittable-dl-text" >'.$str.'</a>';
    }


    function column_status1($item, $column_name) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="products"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_status2($item, $column_name) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="products"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_status3($item, $column_name) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="products"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/'.$module.'/';

        $ci  = $this->ci;

        $status = $ci->input->get('status');

        $url_type = '?page='.(($ci->input->get('page') != '')?$ci->input->get('page'):1);

        $class .= ' text-center';

        echo '<td class="'.$class.'">';
        if($status == 'trash') {
            echo '<a href="'.$url.'untrash'.$url_type.'&id='.$item->id.'" class="btn-blue btn">'.admin_button_icon('undo').'</a>';
            if( current_user_can('wcmc_product_delete') ) {
                echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
        } else {
            echo '<a href="'.$url.'edit/'.$item->slug.$url_type.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
            if( current_user_can('wcmc_product_delete') ) {
                echo '<button class="btn-red btn trash" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
        }
        echo "</td>";
    }

    function search_right() {
        $module     = $this->ci->data['ajax'];
        $url        = URL_ADMIN.'/'.$module;
        ?>
        <form class="search-box" action="<?= $url ;?>">
            <?= _form(array( 'field' => 'keyword', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),'');?>
            <?= _form(array( 'field' => 'category', 'label' => '', 'type'  => 'product_categories', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),$this->ci->input->get('category'));?>      
            <label for="title" class="control-label"></label>
            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
        </form>
        <?php
    }
}

//suppliers
class skd_suppliers_list_table extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = [];

        $this->_column_headers['cb']        = 'cb';

        $this->_column_headers['name']      = 'Tên nhà sản xuất';

        $this->_column_headers['email']     = 'Email';

        $this->_column_headers['address']   = 'Địa chỉ';

        $this->_column_headers['action']   = 'Hành động';

        return apply_filters( "manage_suppliers_columns", $this->_column_headers );
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_suppliers_custom_column', $column_name, $item );
    }

    function column_name($item, $column_name, $module, $table) {
        echo $item->name;
    }

    function column_email($item, $column_name, $module, $table) {
        echo $item->email;
    }

    function column_address($item, $column_name, $module, $table) {
        echo $item->address;
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/'.$module.'/';

        $ci  = $this->ci;

        $status = $ci->input->get('status');

        $url_type = '?page='.(($ci->input->get('page') != '')?$ci->input->get('page'):1);

        $class .= ' text-center';

        echo '<td class="'.$class.'">';
        echo '<a href="'.admin_url('plugins?page=suppliers&view=edit&id='.$item->id).'" class="btn-blue btn"><i class="fas fa-pen-square"></i></a>';
        echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'"><i class="fa fa-trash"></i></button>';
        echo "</td>";
    }

    function search_right() {
 
    }
}