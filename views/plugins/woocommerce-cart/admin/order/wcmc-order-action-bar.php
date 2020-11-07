<?php
function action_bar_plugin_order_button ( $module ) {

	$ci =& get_instance();

	if($ci->template->class == 'plugins' && $ci->input->get('page') == 'woocommerce_order') {
            
        echo '<div class="pull-right">'; do_action('action_bar_plugin_order_right', $module); echo '</div>';
    }
}

add_action( 'action_bar_before', 'action_bar_plugin_order_button', 10 );

function action_bar_plugin_order_right ( $module ) {

    $ci =& get_instance();

    if($ci->input->get('view') == 'create' || $ci->input->get('view') == 'edit') {

	    ?><button name="save" class="btn-icon btn-green" form="order_save__form"><?php echo admin_button_icon('save');?> Lưu</button><?php
    }
}


add_action( 'action_bar_plugin_order_right', 'action_bar_plugin_order_right', 10 );