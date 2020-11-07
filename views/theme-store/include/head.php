<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="">width=device-width, initial-scale=1 -->

    <?php $detect = new Mobile_Detect; ?>
    <?php 
    if ( $detect->isMobile() ) {
        if( $detect->isMobile() && !$detect->isTablet() ){?>
            <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php }else{?>
            <meta name="viewport" content="width=992, initial-scale=1">
        <?php }

    }else{?>
       <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php } ?>


    <base href="<?= base_url()?>">
    <?php $icon = (!empty(get_option('seo_favicon'))) ? get_option('logo_header') : get_option('seo_favicon'); ?>
    <link rel="icon" href="<?= get_img_link($icon);?>" sizes="16x16" type="image/png">
    <?php do_action('cle_header');?>
    <script src="<?=$this->template->get_assets();?>js/jquery-3.2.1.min.js"></script>
    <script type='text/javascript' defer>
        /* <![CDATA[ */
        domain  = '<?= base_url();?>';
        base    = '<?= base_url().URL_ADMIN;?>';
        /* ]]> */
    </script>
</head>