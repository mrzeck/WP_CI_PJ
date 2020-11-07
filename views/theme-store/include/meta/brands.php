<?php  
/*thương hiệu*/
register_cate_type( 'brand_categories', 'products', array(
        'labels' => array(
            'name' => 'Thương hiệu',
            'singular_name' => 'Thương hiệu',
        ),
        'parent' => false,
        'show_in_nav_menus' => true,
    )
);
add_filter( 'manage_categories_brand_categories_columns', 'brand_categories_colum');
add_action( 'manage_categories_brand_categories_custom_column', 'custom_brand_categories_colum',10,2);

function brand_categories_colum( $columns ) {

    unset($columns['name']);
    unset($columns['title']);
    unset($columns['action']);
    unset($columns['public']);
    unset($columns['order']);
    $columns['title2'] = 'Tiêu đề';

    $columns['tieu_de'] = 'URL';
    $columns['order'] = 'THỨ TỰ';
    $columns['public'] = 'HIỂN THỊ';
    $columns['action'] = 'HÀNH ĐỘNG';
    
    
    return $columns;
}

function custom_brand_categories_colum( $column_name, $item ) {

    switch ( $column_name ) {
        case 'tieu_de':
            echo 'thuong-hieu/'.$item->slug;
        break;
        case 'title2':
        echo '<h3>'.$item->name.'</h3>';
        ?>
        <div class="action-hide">
            <span>ID : <?=$item->id;  ?></span> |
            <a href="thuong-hieu/<?= $item->slug ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php break;

    }
}
/*end thương hiệu*/
?>