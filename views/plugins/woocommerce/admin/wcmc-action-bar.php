<?php
/**==================================================================================================
 * ACTION BAR POST CATEGORY
 * ==================================================================================================
 */
function action_bar_products_categories_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'products_categories') {
            
        echo '<div class="pull-left">'; do_action('action_bar_products_categories_left', $module);  echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_products_categories_right', $module); echo '</div>';
    }
}


function action_bar_products_categories_button_right ( $module )
{

	$ci =& get_instance();

	$btn = action_bar_button( $module );

	if($ci->template->is_page('products_categories_index')) { 
        if( current_user_can('wcmc_product_cate_delete') ) { echo $btn['del']; }
        if( current_user_can('wcmc_product_cate_edit') ) { echo $btn['add']; }
    }

    if($ci->template->is_page('products_categories_add')) { echo $btn['save']; echo $btn['back']; }

    if($ci->template->is_page('products_categories_edit')) { echo $btn['save']; echo $btn['add']; echo $btn['back']; }

}

add_action( 'action_bar_before', 'action_bar_products_categories_button', 10 );

add_action( 'action_bar_products_categories_right', 'action_bar_products_categories_button_right', 10 );


/**==================================================================================================
 * ACTION BAR PRODUCT
 * ==================================================================================================
 */
function action_bar_products_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'products') {
            
        echo '<div class="pull-left">'; do_action('action_bar_products_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_products_right', $module); echo '</div>';

    }
}


function action_bar_products_button_right ( $module )
{

	$ci =& get_instance();

	$btn = action_bar_button( $module );

	if($ci->template->is_page('products_index')) {
            
        if( $ci->input->get('status') == 'trash' ) {
            echo $btn['undo'];
            if( current_user_can('wcmc_product_delete') ) { echo $btn['del']; }
            echo $btn['back']; 
            if( current_user_can('wcmc_product_edit') ) { echo $btn['add']; }
        }
        else {
        	if( current_user_can('wcmc_product_delete') ) { echo $btn['trash']; }
            if( current_user_can('wcmc_product_edit') ) { echo $btn['add']; }
        }
    }

    if($ci->template->is_page('products_add')) {
        echo $btn['save'];
        echo $btn['back'];
    }

    if($ci->template->is_page('products_edit')) {
        echo $btn['save'];
        if( current_user_can('wcmc_product_edit') ) { echo $btn['add']; }
        echo $btn['back'];
    }

}

add_action( 'action_bar_before', 'action_bar_products_button', 10 );

add_action( 'action_bar_products_right', 'action_bar_products_button_right', 10 );

/**==================================================================================================
 * ACTION BAR SUPPLIERS
 * ==================================================================================================
 */
function action_bar_suppliers_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'plugins' && $ci->input->get('page') == 'suppliers') {
            
        echo '<div class="pull-left">'; do_action('action_bar_suppliers_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_suppliers_right', $module); echo '</div>';
    }
}

function action_bar_suppliers_button_right ( $module )
{

    $ci =& get_instance();
    
    $view = $ci->input->get('view');

    if($view == '') {
        ?>
        <a href="admin/plugins?page=suppliers&view=add" class="btn-icon btn-green"><i class="fal fa-plus"></i> Thêm Mới</a>
        <?php
    }

    if($view == 'add') {
        ?>
        <button name="save" class="btn-icon btn-green" form="wcmc-suppliers-form"><i class="fal fa-save"></i> Lưu</button>
        <?php
    }

    if($view == 'edit') {
        ?>
        <button name="save" class="btn-icon btn-green" form="wcmc-suppliers-form"><i class="fal fa-save"></i> Lưu</button>
        <?php
    }
}


add_action( 'action_bar_before', 'action_bar_suppliers_button', 10 );

add_action( 'action_bar_suppliers_right', 'action_bar_suppliers_button_right', 10 );