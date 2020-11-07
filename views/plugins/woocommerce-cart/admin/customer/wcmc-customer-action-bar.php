<?php
function action_bar_plugin_customers_button ( $module ) {
	$ci =& get_instance();

	if($ci->template->class == 'plugins' && $ci->input->get('page') == 'customers') {
            
        echo '<div class="pull-right">'; do_action('action_bar_plugin_customers_right', $module); echo '</div>';
    }
}

add_action( 'action_bar_before', 'action_bar_plugin_customers_button', 10 );

function action_bar_plugin_customers_right ( $module ) {

    $ci =& get_instance();
    
    if($ci->input->get('view') == '' || $ci->input->get('view') == 'list') {
        if( current_user_can('customer_add') ) {
	        ?><a href="<?php echo admin_url('plugins?page=customers&view=created');?>" class="btn-icon btn-green"><?php echo admin_button_icon('add');?> Thêm mới</a><?php
        }
    }

    if($ci->input->get('view') == 'created') {

	    ?><button name="save" class="btn-icon btn-green" form="customer_form__created"><?php echo admin_button_icon('save');?> Lưu</button><?php
    }
}


add_action( 'action_bar_plugin_customers_right', 'action_bar_plugin_customers_right', 10 );