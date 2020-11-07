<?php
include 'function/roles.php';

include 'function/update.php';

include 'function/assets.php';

include 'function/action-bar-button.php';

include 'function/menu.php';

include 'function/navigation.php';

include 'function/ajax-action.php';

include 'function/widget-dashboard.php';

include 'function/custom-table.php';

include 'function/taxonomy.php';

include 'function/gallery/gallerys.php';

include 'function/system/system.php';

include 'function/tinymce/tinymce.php';

include 'function/cache/cache.php';

include 'function/audit-log/audit-log.php';

include 'function/widget-builder/widget-builder.php';

include 'function/theme-option-builder/theme-option-builder.php';

include 'user/user.php';

function custom_style() {
    ?>
    <style>
        .CodeMirror-fullscreen { height: auto !important; z-index: 999!important; } .cm-tag { color: #21a500; } .cm-qualifier { color: #c00cd6; }
        .datepicker > div { display: block; }
        .tox .tox-tbtn--bespoke .tox-tbtn__select-label { width:60px; }
    </style><?php
}

add_action('admin_footer', 'custom_style');


//**************** GALLERY OPTION *********************/
//
function gallery_option_default() {

    $input = array('field' => 'title',   'label' => 'Tiêu đề', 'value'=>'','type' => 'text');

    add_option_gallery( 'image', 'gallery', $input );
    $input = array('field' => 'url',   'label' => 'Link liên kết', 'value'=>'','type' => 'text');

    add_option_gallery( 'image', 'gallery', $input );

    $args = array(
        'id'        => 'title',
    	'input' 	=> array('field' => 'title',   'label' => 'Tiêu đề (alt)', 'value'=>'','type' => 'text'),
    	'object'	=> 'page',
    	'position'  => 1,
    );

    add_option_gallery_object( $args );

    $args = array(
        'id'        => 'title',
    	'input' 	=> array('field' => 'title',   'label' => 'Tiêu đề (alt)', 'value'=>'','type' => 'text'),
    	'object'	=> 'post',
    	'type'		=> 'post',
    	'position'  => 1,
    );

    add_option_gallery_object( $args );

    $args = array(
        'id'        => 'title',
        'input'     => array('field' => 'title',   'label' => 'Tiêu đề (alt)', 'value'=>'','type' => 'text'),
        'object'    => 'products',
        'position'  => 1,
    );

    add_option_gallery_object( $args );

    $args = array(
        'id'        => 'title',
        'input'     => array('field' => 'title',   'label' => 'Tiêu đề (alt)', 'value'=>'','type' => 'text'),
        'object'    => 'products_categories',
        'position'  => 1,
    );

    add_option_gallery_object( $args );

    // remove_option_gallery_object( 'title', 'post', 'post' );
    // remove_option_gallery_object( 'title', 'page', 'post' );
}

add_action( 'init', 'gallery_option_default' );

add_menu_option( 'icon', array(
	'field' => 'icon',
	'label' => 'Icon',
	'type' => 'image'
));

