<?php
    $seo_input = get_theme_seo_input();
?>
<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">

            <?php foreach ($seo_input as $key => $input) {
                echo _form($input, get_option($input['field']));
            } ?>
        </div>
    </div>
</div>