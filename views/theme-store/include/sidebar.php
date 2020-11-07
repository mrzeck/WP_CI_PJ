<?php
$layout_setting   = get_theme_layout_setting();

if( is_page('post_index')) {

    $sidebar_post_new = $layout_setting['sidebar']['new'];

    $sidebar_post_hot = $layout_setting['sidebar']['hot'];

    $sidebar_post_sub = $layout_setting['sidebar']['sub'];

    $sidebar_post_sidebar = $layout_setting['sidebar']['sidebar'];
}

if( is_page('post_detail')) {

    $sidebar_post_new = $layout_setting['sidebar']['new'];

    $sidebar_post_hot = $layout_setting['sidebar']['hot'];

    $sidebar_post_related = $layout_setting['sidebar']['related'];

    $sidebar_post_sub = $layout_setting['sidebar']['sub'];

    $sidebar_post_sidebar = $layout_setting['sidebar']['sidebar'];
}

 $args = array( 
    'where'  => array('post_type' => 'post'),
    'params' => array('orderby' => 'order, created desc', 'limit' => 5),
);

if(isset($sidebar_post_new) && $sidebar_post_new['toggle'] == 1) {

    $args['params']['limit'] = (int)$sidebar_post_new['limit'];

    if($sidebar_post_new['data'] == 'post-category-current' && isset($category) && have_posts($category)) {
        $args['where_category'] = $category;
    }
    else if(is_numeric($sidebar_post_new['data'])) {
        $args['where_category'] = gets_post_category($sidebar_post_new['data']);
    }

    $post = gets_post($args);

    ?>
    <div class="widget_box_post_sidebar">
        <div class="header-title"><h3 class="header"><a href=""><?php echo $sidebar_post_new['title'];?></a></h3></div>
        <?php foreach ($post as $key => $val):
        $this->template->render_include('loop/item_post_sidebar',array('val' => $val));
        endforeach ?>
    </div>
    <?php
}

if(isset($sidebar_post_hot) && $sidebar_post_hot['toggle'] == 1) {

    $args['params']['limit'] = (int)$sidebar_post_hot['limit'];

    $args['where']['status'] = 1;

    if($sidebar_post_hot['data'] == 'post-category-current' && isset($category) && have_posts($category)) {
        $args['where_category'] = $category;
    }
    else if(is_numeric($sidebar_post_hot['data'])) {
        $args['where_category'] = gets_post_category(array( 'where' => array('id' => $sidebar_post_hot['data']) ) );
    }

    $post = gets_post($args);
    if(have_posts($post)){
    ?>
    <div class="widget_box_post_sidebar">
        <div class="header-title"><h3 class="header"><a href=""><?php echo $sidebar_post_hot['title'];?></a></h3></div>
        <?php foreach ($post as $key => $val):
        $this->template->render_include('loop/item_post_sidebar',array('val' => $val));
        endforeach ?>
    </div>
    <?php
    }
}

if(isset($sidebar_post_related) && $sidebar_post_related['toggle'] == 1 && have_posts($object)) {

    if(isset($args['where']['status'])) unset($args['where']['status']);

    $args['params']['limit'] = (int)$sidebar_post_related['limit'];

    $args['related'] = $object->id;

    $post = gets_post($args);

    if(have_posts($post)){
    ?>
    <div class="widget_box_post_sidebar">
        <div class="header-title"><h3 class="header"><a href=""><?php echo $sidebar_post_related['title'];?></a></h3></div>
        <?php foreach ($post as $key => $val):
        $this->template->render_include('loop/item_post_sidebar',array('val' => $val));
        endforeach ?>
    </div>
    <?php
    }
}

if(isset($sidebar_post_sub) && $sidebar_post_sub['toggle'] == 1) {

    if(isset($args['where']['status']))
        unset($args['where']['status']);

    $args['params']['limit'] = (int)$sidebar_post_sub['limit'];

    if($sidebar_post_sub['status'] == 'hot')
        $args['where']['status'] = 1;
    

    if($sidebar_post_sub['data'] == 'post-category-current' && isset($category) && have_posts($category)) {

        $where_category = $category->id;

    }
    else if(is_numeric($sidebar_post_sub['data'])) {

        $where_category = $sidebar_post_sub['data'];
    }

    if(isset($where_category)) {

        $categories_sub = gets_post_category( array( 'where' => array( 'parent_id' => $where_category ) ) );

        foreach ($categories_sub as $sub):
            $args['where_category'] = $sub;
            $post = gets_post($args);

            if(!have_posts($post)) continue;
        ?>
        <div class="widget_box_post_sidebar">
            <div class="header-title"><h3 class="header"><a href=""><?php echo $sub->name ;?></a></h3></div>
            <?php foreach ($post as $key => $val):
            $this->template->render_include('loop/item_post_sidebar',array('val' => $val));
            endforeach ?>
        </div>
        <?php endforeach;
    }
}

if(isset($sidebar_post_sidebar) && $sidebar_post_sidebar['toggle'] == 1) {
    dynamic_sidebar($sidebar_post_sidebar['data']);
}
?>