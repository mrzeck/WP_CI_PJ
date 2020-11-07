<?php
function action_bar_button ( $module )
{
	$ci =& get_instance();

	$bar_url        = URL_ADMIN.'/'.$ci->data['ajax'].'/';

	$btn['add']   = '<a href="'.$bar_url.'add'.$ci->url_type.'" class="btn-icon btn-green">'.admin_button_icon('add').' Thêm Mới</a>';

	$btn['save']   = '<button name="save" class="btn-icon btn-green">'.admin_button_icon('save').' Lưu</button>';

	$btn['back']   = '<a href="'.$bar_url.$ci->url_type.'" class="btn-icon btn-blue">'.admin_button_icon('back').' Quay lại</a>';

	$btn['trash']  = '<button class="btn-icon btn-red trash" data-table="'.$module.'">'.admin_button_icon('delete').' Xóa Tạm</button>';

	$btn['del']    = '<button class="btn-icon btn-red delete" data-table="'.$module.'">'.admin_button_icon('delete').' Xóa Vĩnh Viễn</button>';

	$btn['undo']   = '<button class="btn-icon btn-blue undo" data-table="'.$module.'">'.admin_button_icon('undo').' Phục hồi</button>';

	return $btn;
}


/**==================================================================================================
 * ACTION BAR PAGE
 * ==================================================================================================
 */
function action_bar_page_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'page') {
            
        echo '<div class="pull-left">'; do_action('action_bar_page_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_page_right', $module); echo '</div>';

    }
}

function action_bar_page_button_right ( $module )
{

	$ci =& get_instance();

	$btn = action_bar_button( $module );

	if($ci->template->is_page('page_index')) {
            
        if( $ci->input->get('status') == 'trash' ) {

            echo $btn['undo']; 

            if( current_user_can('delete_pages') )  echo $btn['del']; 

            echo $btn['back'];
        }
        else {
            
            if( current_user_can('delete_pages') ) echo $btn['trash'];

            if( current_user_can('add_pages') ) echo $btn['add'];
        }
    }

    if($ci->template->is_page('page_add')) { echo $btn['save']; echo $btn['back']; }

    if($ci->template->is_page('page_edit')) { echo $btn['save']; echo $btn['add']; echo $btn['back']; }

}


add_action( 'action_bar_before', 'action_bar_page_button', 10 );

add_action( 'action_bar_page_right', 'action_bar_page_button_right', 10 );


/**==================================================================================================
 * ACTION BAR POST CATEGORY
 * ==================================================================================================
 */
function action_bar_category_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'post_categories') {
            
        echo '<div class="pull-left">';

        	do_action('action_bar_cate_left', $module);

        	if( $ci->cate_type != null )
        		do_action('action_bar_cate_'.$ci->cate_type.'_left', $module);

        echo '</div>';

        echo '<div class="pull-right">';
        
        	do_action('action_bar_cate_right', $module);

        	if( $ci->cate_type != null )
        		do_action('action_bar_cate_'.$ci->cate_type.'_right', $module);

        echo '</div>';

    }
}


function action_bar_post_categories_button_right ( $module )
{

	$ci =& get_instance();

    $cate_type = get_cate_type( $ci->cate_type );

	$btn = action_bar_button( $module );

	if($ci->template->is_page('post_categories_index')) {
        if( !empty($cate_type['capibilitie']['delete'])  && current_user_can( $cate_type['capibilitie']['delete']) ) echo $btn['del'];
        echo $btn['add'];
    }

    if($ci->template->is_page('post_categories_add')) { echo $btn['save']; echo $btn['back']; }

    if($ci->template->is_page('post_categories_edit')) { echo $btn['save']; echo $btn['add']; echo $btn['back']; }

}

add_action( 'action_bar_before', 'action_bar_category_button', 10 );

add_action( 'action_bar_cate_right', 'action_bar_post_categories_button_right', 10 );


/**==================================================================================================
 * ACTION BAR POST
 * ==================================================================================================
 */
function action_bar_post_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'post') {
            
        echo '<div class="pull-left">';

        	do_action('action_bar_post_left', $module);

        	if( $ci->cate_type != null )
        		do_action(' '.$ci->cate_type.'_left', $module);

        echo '</div>';

        echo '<div class="pull-right">';
        
        	do_action('action_bar_post_right', $module);

        	if( $ci->cate_type != null )
        		do_action('action_bar_post_'.$ci->cate_type.'_right', $module);

        echo '</div>';

    }
}


function action_bar_post_button_right ( $module )
{

	$ci =& get_instance();

	$btn = action_bar_button( $module );

    $post_type = get_post_type( $ci->post_type );

	if($ci->template->is_page('post_index')) {

        if( $ci->input->get('status') == 'trash' ) {

            echo $btn['undo']; 
            
            if( !empty($post_type['capibilitie']['delete'])  && current_user_can( $post_type['capibilitie']['delete']) ) echo $btn['del']; 
            
            echo $btn['back'];

            if( !empty($post_type['capibilitie']['add']) && current_user_can( $post_type['capibilitie']['add']) ) echo $btn['add'];
        }
        else {
        	if( !empty($post_type['capibilitie']['delete'])  && current_user_can( $post_type['capibilitie']['delete']) ) echo $btn['trash'];
            
            if( !empty($post_type['capibilitie']['add']) && current_user_can( $post_type['capibilitie']['add']) ) echo $btn['add'];
        }
    }

    if($ci->template->is_page('post_add')) { echo $btn['save']; echo $btn['back']; }

    if($ci->template->is_page('post_edit')) { 
        if( !empty($post_type['capibilitie']['edit']) && current_user_can( $post_type['capibilitie']['edit']) ) echo $btn['save'];
        if( !empty($post_type['capibilitie']['add']) && current_user_can( $post_type['capibilitie']['add']) ) echo $btn['add'];
        echo $btn['back'];
    }

}

add_action( 'action_bar_before', 'action_bar_post_button', 10 );

add_action( 'action_bar_post_right', 'action_bar_post_button_right', 10 );


/**==================================================================================================
 * ACTION BAR THEME
 * ==================================================================================================
 */
function action_bar_theme_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'theme') {
            
        echo '<div class="pull-left">'; do_action('action_bar_theme_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_theme_right', $module); echo '</div>';

    }
}

function action_bar_theme_button_left ( $module )
{

	$ci =& get_instance();

    if($ci->template->is_page('theme_editor')) { echo '<div class="breadcrumbs"></div>'; }

}

function action_bar_theme_button_right ( $module )
{

	$ci =& get_instance();

    if($ci->template->is_page('theme_editor')) { echo '<div class="search"> <input type="search" placeholder="Find a file.." /> </div>'; }

    if($ci->template->is_page('theme_option')) { echo '<button type="button" class="btn-icon btn-green" id="item-data-save">'.admin_button_icon('save').'Lưu</button>'; }
}


add_action( 'action_bar_before', 'action_bar_theme_button', 10 );

add_action( 'action_bar_theme_left', 'action_bar_theme_button_left', 10 );

add_action( 'action_bar_theme_right', 'action_bar_theme_button_right', 10 );

/**==================================================================================================
 * ACTION BAR SYSTEM THEME
 * ==================================================================================================
 */
function action_bar_system_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->is_page('home_system')) {
            
        echo '<div class="pull-left">'; do_action('action_bar_system_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_system_right', $module); echo '</div>';

    }
}

function action_bar_system_button_right ( $module )
{

	$ci =& get_instance();

    echo '<button type="submit" class="btn-icon btn-green" form="system_form">'.admin_button_icon('save').'Lưu</button>';
}

add_action( 'action_bar_before', 'action_bar_system_button', 10 );

add_action( 'action_bar_system_right', 'action_bar_system_button_right', 10 );


/**==================================================================================================
 * ACTION BAR WIDGET THEME
 * ==================================================================================================
 */
function action_bar_widgets_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'widgets') {
            
        echo '<div class="pull-left">'; do_action('action_bar_widgets_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_widgets_right', $module); echo '</div>';

    }
}

function action_bar_widgets_button_right ( $module )
{

	$ci =& get_instance();

    if($ci->template->is_page('widgets_index')) { echo '<button class="btn-icon btn-blue" id="service-widget">'.admin_button_icon('add').' Thêm widget</button>'; }

}

add_action( 'action_bar_before', 'action_bar_widgets_button', 10 );

add_action( 'action_bar_widgets_right', 'action_bar_widgets_button_right', 10 );


/**==================================================================================================
 * ACTION BAR MENU
 * ==================================================================================================
 */
function action_bar_menu_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'menu') {
            
        echo '<div class="pull-left">'; do_action('action_bar_menu_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_menu_right', $module); echo '</div>';

    }
}


function action_bar_menu_button_left ( $module )
{

	$ci =& get_instance();

	$menus = $ci->data['menus'];

    if($ci->template->is_page('menu_index')) {
    	if( have_posts($menus) ): ?>
	    	<p style="display: inline">Chọn menu để chỉnh sửa </p>
	        <select name="list_menu" id="list_menu" class="form-control" style="display: inline;width:150px;">
	        <?php foreach ($menus as $key => $val): ?>
	            <option value="<?= $val->id;?>" <?=($val->id == $ci->input->get('id'))?'selected=selected':'';?>><?= $val->name;?></option>}
	        <?php endforeach ?>
	        </select>
       	<?php else: ?>
       		<p style="display: inline"> Chưa có menu nào </p>
            <a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox data-src="#hidden-content"><?php echo admin_button_icon('add');?>Thêm menu</a>
       	<?php endif;
    }
}

function action_bar_menu_button_right ( $module )
{

	$ci =& get_instance();

	$menu = $ci->data['menu'];

    if($ci->template->is_page('menu_index')) {
    	if( have_posts($menu)): ?>
    	<a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox data-src="#hidden-content"><?php echo admin_button_icon('add');?>Thêm menu</a>
        <button data-id="<?= $menu->id;?>" class="btn-red btn delete"><?php echo admin_button_icon('delete');?></button>
       	<?php endif;
    }
}


add_action( 'action_bar_before', 'action_bar_menu_button', 10 );

add_action( 'action_bar_menu_left', 'action_bar_menu_button_left', 10 );

add_action( 'action_bar_menu_right', 'action_bar_menu_button_right', 10 );

/**==================================================================================================
 * ACTION BAR PLUGINS
 * ==================================================================================================
 */
function action_bar_plugins_button ( $module )
{

	$ci =& get_instance();

	if($ci->template->class == 'plugins' && $ci->input->get('page') == '') {
            
        echo '<div class="pull-left">'; do_action('action_bar_plugins_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_plugins_right', $module); echo '</div>';
    }
}

function action_bar_plugins_button_right ( $module )
{

	$ci =& get_instance();

	$btn = action_bar_button( $module );

	if($ci->template->is_page('plugins_index')) : ?>
		<a href="<?php echo URL_ADMIN;?>/plugins/download" class="btn btn-green">PLUGIN</a>
        <a href="<?php echo URL_ADMIN;?>/plugins/license" class="btn btn-blue">API SECRET</a>
    <?php endif;

    if($ci->template->is_page('plugins_download')) : ?>
		<a href="<?php echo URL_ADMIN;?>/plugins/download" class="btn btn-green active">PLUGIN</a>
        <a href="<?php echo URL_ADMIN;?>/plugins/license" class="btn btn-blue">API SECRET</a>
    <?php endif;

    if($ci->template->is_page('plugins_license')) : ?>
		<a href="<?php echo URL_ADMIN;?>/plugins/download" class="btn btn-green">PLUGIN</a>
        <a href="<?php echo URL_ADMIN;?>/plugins/license" class="btn btn-blue active">API SECRET</a>
    <?php endif;
}


add_action( 'action_bar_before', 'action_bar_plugins_button', 10 );

add_action( 'action_bar_plugins_right', 'action_bar_plugins_button_right', 10 );


/**==================================================================================================
 * ACTION BAR USER
 * ==================================================================================================
 */
function action_bar_user_button ( $module ) {

    $ci =& get_instance();

    if($ci->template->class == 'user' && $ci->input->get('page') == '') {
            
        echo '<div class="pull-left">'; do_action('action_bar_user_left', $module); echo '</div>';

        echo '<div class="pull-right">'; do_action('action_bar_user_right', $module); echo '</div>';
    }
}

function action_bar_user_button_right ( $module ) {

    $ci =& get_instance();

    $bar_url        = URL_ADMIN.'/'.$ci->data['ajax'].'/';

    if($ci->template->is_page('user_index')) : ?>
        <?php if(current_user_can('create_users') ) { ?><a href="<?php echo $bar_url;?>add" class="btn-green btn-icon"><?php echo admin_button_icon('add');?> Thêm thành viên</a><?php } ?>
    <?php endif;

    if($ci->template->is_page('user_add')) : ?>
        <?php if(current_user_can('create_users') ) { ?><button type="submit" class="btn-green btn-icon"><?php echo admin_button_icon('add');?> Lưu thông tin</button><?php } ?>
    <?php endif;
}


add_action( 'action_bar_before', 'action_bar_user_button', 10 );

add_action( 'action_bar_user_right', 'action_bar_user_button_right', 10 );