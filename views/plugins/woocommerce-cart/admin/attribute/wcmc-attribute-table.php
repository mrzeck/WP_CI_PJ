<?php
//dùng cho toàn bộ table
class skd_woocomerce_attribute_list_table extends SKD_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'               => 'cb',
            'title'            => 'Tên',
            'option_type'      => 'Loại',
            'action'           => 'Thao tác',
        );

        $this->_column_headers = apply_filters( "manage_woocomerce_attribute_columns", $this->_column_headers );

        return $this->_column_headers;
    }

	function column_title($item, $column_name) {
	   ?>
	   <strong><a href="<?php echo admin_url('plugins?page=woocommerce_attributes&id='.$item->id);?>"><?php echo $item->title;?></a></strong>
	   <?php
	}
	
	function column_option_type($item, $column_name) {
		echo woocommerce_options_type_label($item->option_type);
	}

    function column_action($item, $column_name) {

		$url = admin_url('plugins?page=woocommerce_attributes&id='.$item->id);

		?>
		<a href="<?php echo $url.'&view=item';?>" class="btn btn-blue">Thuộc tính</a>

		<?php if( current_user_can('wcmc_attributes_edit') ) { ?>
			<a href="<?php echo $url;?>" class="btn btn-blue"><?php echo admin_button_icon('edit');?></a>
		<?php } ?>
		<?php if( current_user_can('wcmc_attributes_delete') ) { ?>
			<button class="btn btn-red delete" data-id="<?php echo $item->id;?>" data-table="wcmc_attribute"><?php echo admin_button_icon('delete');?></button>
		<?php } ?>
		<?php
    }

    function column_default( $item, $column_name ) {
        do_action( 'manage_woocommerce_attribute_custom_column', $column_name, $item );
    }
}

class skd_woocomerce_attribute_item_list_table extends SKD_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'     => 'cb',
            'title'  => 'Tên',
            'value'  => 'Giá trị',
            'order'  => 'Thứ tự',
            'action' => 'Thao tác',
        );

        $this->_column_headers = apply_filters( "manage_woocomerce_attribute_item_columns", $this->_column_headers );

        return $this->_column_headers;
    }

	function column_title($item, $column_name) {
	   ?>
	   <strong><a href="<?php echo admin_url('plugins?page=woocommerce_attributes&view=item&sub_id='.$item->id);?>"><?php echo $item->title;?></a></strong>
	   <?php
	}
	
	function column_value($item, $column_name) {

		echo woocommerce_attributes_item_type($item->value, $item->type);
    }
    
    function column_order($item, $column_name) {

		?>
        <a href="#" data-pk="<?php echo $item->id;?>" data-name="order" data-table="wcmc_options_item" class="edittable-dl-text editable editable-click"><?php echo $item->order;?></a>
        <?php
	}

    function column_action($item, $column_name) {

		$url = admin_url('plugins?page=woocommerce_attributes&view=item&sub_id='.$item->id);

		?>
		<a href="<?php echo $url;?>" class="btn btn-blue"><?php echo admin_button_icon('edit');?></a>
		<button class="btn btn-red delete" data-id="<?php echo $item->id;?>" data-table="wcmc_attribute_item"><?php echo admin_button_icon('delete');?></button>
		<?php
    }

    function column_default( $item, $column_name ) {
        do_action( 'manage_woocommerce_attribute_item_custom_column', $column_name, $item );
    }
}