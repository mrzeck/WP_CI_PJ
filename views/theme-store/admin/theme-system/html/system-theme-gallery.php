<?php
    $categories = get_cate_type();

    $posts      = get_post_type();

    $gallery_support = get_option('gallery_template_support', []);

    if(!is_array($gallery_support)) $gallery_support = [];

    $gallery_support['page'] = (isset($gallery_support['page'])) ? $gallery_support['page'] : 0;

    foreach ($categories as $key => $cate_type) {
        $gallery_support['category'][$cate_type] = (isset($gallery_support['category'][$cate_type])) ? $gallery_support['category'][$cate_type] : 0;
    }

    foreach ($posts as $key => $post_type) {
        $gallery_support['post'][$post_type] = (isset($gallery_support['post'][$post_type])) ? $gallery_support['post'][$post_type] : 0;
    }
?>
<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Trang nội dung</label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[page]', 'type' => 'switch'], $gallery_support['page']);?>
                </div>
            </div>
           

            <?php foreach ($categories as $key => $cate_type) { $cate = get_cate_type($cate_type); ?>
            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3"><?php echo $cate['labels']['name'];?></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[category]['.$cate_type.']', 'type' => 'switch'], $gallery_support['category'][$cate_type]);?>
                </div>
            </div>
            <?php } ?>


            <?php foreach ($posts as $key => $post_type) { $post = get_post_type($post_type); ?>
            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3"><?php echo $post['labels']['name'];?></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[post]['.$post_type.']', 'type' => 'switch'], $gallery_support['post'][$post_type]);?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>