<?php
function theme_custom_css($tag_style = true) {

    $ci =& get_instance();

    ob_start();
    ?>
    <style type="text/css">
        html, body, p, span {
            line-height: 25px;
            font-family: <?php echo get_option('text_font');?>
        }
        body{background-color:<?php echo (get_option('general_background_color'))? get_option('general_background_color'):'#fff';?>;}
        .navigation.fixed{max-width:2000px;margin:0 auto !important;}
        .navigation.fixed{left:inherit;}
        .warper {  margin-top:0;     }
        .warper > .container{padding:0;}
        /** HEADING **/
        h1,h2,h3,h4,h5,h6, h1.header, h2.header, h3.header, .products-detail h1, .product-slider-horizontal .item .title h3, .product-slider-horizontal .item .title .item-pr-price .product-item-price {
            font-family: <?php echo get_option('header_font');?>
        }
        h1.header, h2.header, h3.header, .header-title h1.header, .header-title h2.header, .header-title h3.header { color:#000; font-weight: bold; text-transform:uppercase;}
        .header-title h1.header:before, .header-title h2.header:before, .header-title h3.header:before { border-bottom: 2px solid red; }
        .header-title img{width:100%;       }
        .header-title { border-bottom: 2px; text-align: center;position: relative; }
        .header-title .xemthem{position:absolute;top:2px;right:0;}
        /** HEADER **/
        .header-mobile {
            background-color:<?php echo (get_option('header_mobile_bg_color'))? get_option('header_mobile_bg_color'):'#fff';?>;
        }
        .header-mobile .logo img {
            height:50px;
        }
        .header-mobile .btn-menu-mobile {
            color:<?php echo (get_option('header_mobile_icon_menu'))? get_option('header_mobile_icon_menu'):'#000';?>;
        }
        .header-mobile a.btn-search-mobile {
            color:<?php echo (get_option('header_mobile_color_search'))? get_option('header_mobile_color_search'):'#97ca51';?>;
        }
        /** SIDEBAR HEADING **/
        .sidebar h1.header, .sidebar h2.header, .sidebar h3.header {
            text-align:left;
            text-transform: uppercase;
            padding:0;
        }
        .sidebar h1.header:after, .sidebar h2.header:after, .sidebar h3.header:after, .related_post h3.header:after {
            content:'';
            height:3px;
            width:50px;
            margin:10px 0;
            display:block;
            background-color:red;
        }
        .sidebar h1.header a, .sidebar h2.header a, .sidebar h3.header a {
            color:#000;
        }
        /** navigation */
        .fixed{ box-shadow: 0px 1px 1px rgba(165, 165, 165, 0.3); }
        /** POST */
        .widget_box_post .item { background-color: #fff; }
        .widget_box_post .item .img { height: inherit; }
        .widget_box_post .item .title { padding:5px ; }
        .widget_box_post .item .title .excerpt{height:52px;    line-height: 18px;}
        .widget_box_post .item .title h3 { font-weight: bold; font-size: 15px; margin:0;    height: 42px;    line-height: 20px;}
        .widget_box_post .item .img{position: relative;display: block;width: 100%;padding-top: 65%;height:inherit;    border-radius: 0; overflow: hidden;border:1px solid #cdcdcd;}
        .widget_box_post .item .img a{    position: absolute;top: 5px;left: 5px;width: calc(100% - 10px);height: calc(100% - 10px);display: flex;align-items: center;justify-content: center;overflow: hidden;}
        .widget_box_post .item .img a img{min-height: 100%;min-width: 100%;position: relative;left: 50%;top: 50%;transform: translateY(-50%) translateX(-50%);display: inherit;transition: all .5s ease-in-out;}
        /** breadcrumb */
        .breadcrumb { margin: 20px 0; }
        .breadcrumb .btn-group>.btn { border:0; border-radius: 0; }
        .btn-breadcrumb a.btn.btn-default {line-height: 37px; padding: 0 5px;}
        .btn-breadcrumb a span {line-height: 37px;}
        .btn-breadcrumb>span:first-child a { padding-left:0!important; }
        .products-breadcrumb {
            background-color:#F5F5F5;
            padding:10px 0;
            margin-bottom:15px;
        }
        /*product*/
        /* .product-slider-horizontal .item .title .bizweb-product-reviews-star{height:24px;} */
        .product-slider-horizontal .list-item-product [class^="col-"]{padding:0;}
        .product-slider-horizontal .item {padding:0;margin:0;}
        .product-slider-horizontal .list-item-product .item{margin:0;}
        .product-slider-horizontal .list-item-product .item{border:1px solid #cdcdcd;}
        .product-slider-horizontal .list-item-product .item .img picture{transition: all .5s ease-in-out}
        .product-slider-horizontal .list-item-product .item:hover .img picture{padding:10px}
        .product-slider-horizontal .item .product{ border-radius: 0 !important; overflow:hidden;}
        .product-slider-horizontal .item .img{position: relative;display: block;width: 100%;padding-top: 100%;height:inherit;    border-radius: 0 !important; overflow: hidden;}
        .product-slider-horizontal .item .title {height:100px;overflow:hidden;text-align:left;}
        .product-slider-horizontal .item .title h3{font-size:14px;       height: auto;margin-top:0;max-height:33px;overflow:hidden;font-weight:bold;text-align:left;line-height:1.2;height:initial;}
        .product-slider-horizontal .item .title .item-pr-price {text-align:left;line-height:1.2;margin:0}
        .product-slider-horizontal .item .title .item-pr-price .product-item-price{font-weight:500;color:#000;font-size:13px;margin:0;}

        .product-slider-horizontal .item .img picture{    position: absolute;top: 0;left: 0;width: 100%;height: 100%;align-items: center;justify-content: center;overflow: hidden;}
        .product-slider-horizontal .item .img picture img{min-height: 100%;min-width: 100%;position: relative;left: 50%;top: 50%;transform: translateY(-50%) translateX(-50%);display: inherit;transition: all .5s ease-in-out;}
        .product-slider-horizontal .item .title h3{    text-overflow: ellipsis;font-weight:bold;font-style: italic;}    

        .product-slider-horizontal .item .title .bizweb-product-reviews-star{text-align:left}
        .product-slider-horizontal .item .item_inner{border-radius: 0; overflow: hidden;}
        .product-slider-horizontal .item .item_inner:hover{     -webkit-box-shadow: 0px 3px 10px 2px rgba(0,0,0,0.61);-moz-box-shadow: 0px 3px 10px 2px rgba(0,0,0,0.61);box-shadow: 0px 3px 10px 2px rgba(0,0,0,0.61);}
        .product-slider-horizontal .wg_pr_btn .next, .product-slider-horizontal .wg_pr_btn .prev{font-size:40px;    line-height: 60px;background-color:transparent;
            text-shadow: 0px 4px 3px rgba(0,0,0,0.4),0px 8px 13px rgba(0,0,0,0.1),0px 18px 23px rgba(0,0,0,0.1);color:#000;opacity:.7;top:30%;}
            .product-slider-horizontal .wg_pr_btn .next{right:0;}
            .product-slider-horizontal .wg_pr_btn .prev{left:0;}
            .product-slider-horizontal .wg_pr_btn .next{right:0;}
            .product-slider-horizontal .wg_pr_btn .prev{left:0;}

            @media(max-width: 767px) {
             .widget_product_style_4 .product-slider-horizontal .wg_pr_btn .next{right:0;top:40%}
             .widget_product_style_4  .product-slider-horizontal .wg_pr_btn .prev{left:0;right:inherit;top:40%}        
         }
         
         /** footer */
         footer {
                width: 100%;
            padding:50px 0;
            <?php echo (get_option('footer_bg_color'))?'background-color:'.get_option('footer_bg_color').';':'';?>
            <?php echo (get_option('footer_bg_image'))?'background-image:url(\''.get_img_link(get_option('footer_bg_image')).'\');background-size:cover;':'';?>     
            <?php echo (get_option('footer_text_color'))?'color:'.get_option('footer_text_color').';':'';?>
        }
        footer a { <?php echo (get_option('footer_text_color'))?'color:'.get_option('footer_text_color').';':'';?> }
        footer .header-title { margin-bottom:10px; }
        footer h1.header, footer h2.header, footer h3.header, footer .header-title h1.header, footer .header-title h2.header, footer .header-title h3.header {
            <?php echo (get_option('footer_header_color'))?'color:'.get_option('footer_header_color').';':'';?>
            margin:0;font-size: 18px;
        }
        footer .header-title h1.header:before, footer .header-title h2.header:before, footer .header-title h3.header:before { display: none; }
        footer ul { list-style: none; }
        footer ul li { padding-bottom:5px; }

        .footer-top .container{padding:0;}
        .footer-bottom {
            width: 100%;
            display: <?php echo (get_option('footer_bottom_public'))?'block':'none';?>;
            background-color:<?php echo (get_option('footer_bottom_bg_color'))? get_option('footer_bottom_bg_color'):'#282828';?>;
            color:<?php echo (get_option('footer_bottom_text_color'))?get_option('footer_text_color'):'#fff';?>;
        }
        .footer-bottom p { margin-bottom: 0; }
        .owl-controls { display: none; }

        .container { width:1200px;}
        @media(min-width: 1250px) {
           /* .container { padding:0 5%;} */
           .td-menu-background{opacity:0;}
       }
       @media(max-width: 1199px){
            .container { width:100%;}
       }
       @media(max-width:767px){
        footer ul li{padding:0;}
       }
       @media(max-width:599px){
        footer .js_widget_builder{width:100%;position:relative;}
       }
       <?php do_action('theme_custom_css');?>
   </style>
   <?php do_action('theme_custom_css_no_tag');?>
   <?php
   $css = ob_get_contents();

   ob_end_clean();

   if($tag_style === false) {

    $css = str_replace('<style type="text/css">', '', $css);
    $css = str_replace('<style type=\'text/css\'>', '', $css);
    $css = str_replace('<style>', '', $css);
    $css = str_replace('</style>', '', $css);

    return $css;
}

echo $css;
}

if(get_option('cms_minify_css', 0) == 0) {
    add_action('cle_header', 'theme_custom_css');
}

//add script to header
function header_script() {
	echo get_option('header_script');
}

add_action('cle_header', 'header_script');
