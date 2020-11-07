<head>
    <base href="<?= base_url();?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="shortcut icon" href="<?php echo get_img_link('admin-panel-icon.png');?>">

    <title>Admin - vitechcenter</title>
    
    <?= do_action('admin_header') ;?>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>

    <script>
        $( document ).ready(function() {
            domain      = '<?= base_url();?>'; 
            base        = '<?= base_url().URL_ADMIN;?>'; 
            path        = '<?= base_url().URL_ADMIN.'/'.$ajax;?>';
            cate_type   = '<?= $this->cate_type;?>'; 
            post_type   = '<?= $this->post_type;?>';
            url_type = '';
            if(cate_type.length > 0  && post_type.length > 0) 	url_type = '?cate_type='+cate_type+'&post_type='+post_type;
            if(cate_type.length > 0  && post_type.length == 0) 	url_type = '?cate_type='+cate_type;
            if(cate_type.length == 0 && post_type.length > 0) 	url_type = '?post_type='+post_type;
    });
    </script>

</head>
