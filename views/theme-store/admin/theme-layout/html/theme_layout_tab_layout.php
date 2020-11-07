<?php
    $layout_list                = theme_layout_list();
    $layout_page                = get_option('layout_page',              'layout-full-width-banner');
    $layout_post                = get_option('layout_post',              'layout-sidebar-right-banner-2');
    $layout_post_category       = get_option('layout_post_category',     'layout-sidebar-right-banner-2');
    $layout_products_category   = get_option('layout_products_category', 'layout-sidebar-right-banner-2');
?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Page Layout</h2></div>
        <div class="box-content">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'page';
                $layout_active = $layout_page;
                include 'theme_layout_item.php';
            } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Post Category Layout</h2></div>
        <div class="box-content">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'post-category';
                $layout_active = $layout_post_category;
                include 'theme_layout_item.php';
            } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Post Layout</h2></div>
        <div class="box-content">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'post';
                $layout_active = $layout_post;
                include 'theme_layout_item.php';
            } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php if(class_exists('woocommerce')) {?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>PRODUCTS CATEGORY</h2></div>
        <div class="box-content">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'products-category';
                $layout_active = $layout_products_category;
                include 'theme_layout_item.php';
            } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php } ?>

