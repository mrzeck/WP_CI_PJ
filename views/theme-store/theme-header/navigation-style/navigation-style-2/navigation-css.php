<?php
    $nav_bg_color         = ( get_option('nav_bg_color') ) 			? 'background-color:'.get_option('nav_bg_color').';':'';
	$nav_text_color       = ( get_option('nav_text_color') ) 		? 'color:'.get_option('nav_text_color').';':'';
	$nav_padding          = ( get_option('nav_padding') ) 			? 'padding:'.get_option('nav_padding').';':'';
	$nav_bg_color_hover   = ( get_option('nav_bg_color_hover') ) 	? 'background-color:'.get_option('nav_bg_color_hover').';':'';
	$nav_text_color_hover = ( get_option('nav_text_color_hover') ) 	? 'color:'.get_option('nav_text_color_hover').';':'';

	$navsub_bg_color   = ( get_option('navsub_bg_color') ) 	? 'background-color:'.get_option('navsub_bg_color').';':'';
	$navsub_text_color = ( get_option('navsub_text_color') ) 	? 'color:'.get_option('navsub_text_color').';':'';

	$navsub_bg_color_hover   = ( get_option('navsub_bg_color_hover') ) 	? 'background-color:'.get_option('navsub_bg_color_hover').';':'';
	$navsub_text_color_hover = ( get_option('navsub_text_color_hover') ) 	? 'color:'.get_option('navsub_text_color_hover').';':'';

    $nav_font_weight = ( get_option('nav_font') ) ? get_option('nav_font') : get_option('text_font');

    //NAVIGATION DỌC
    $nav_vh_text_color 	= ( get_option('nav_vh_text_color') ) ? 'color:'.get_option('nav_vh_text_color', '#fff').';':'color:#fff;';
	$nav_vh_bg 			= ( get_option('nav_vh_bg') ) ? 'background-color:'.get_option('nav_vh_bg', '#e22b00').';':'background-color:#e22b00;';
	$nav_vh 			= $nav_vh_text_color.$nav_vh_bg;


	$nav_v_bg               = ( get_option('nav_v_bg') ) ? 'background-color:'.get_option('nav_v_bg', '#fff').';':'background-color:#fff;';
	$nav_v_bg_hover         = ( get_option('nav_v_bg_hover') ) ? 'background-color:'.get_option('nav_v_bg_hover', '#fff').';':'background-color:#fff;';
	$nav_v_text_color       = ( get_option('nav_v_text_color') ) ? 'color:'.get_option('nav_v_text_color', '#000').';':'color:#000;';
	$nav_v_text_color_hover = ( get_option('nav_v_text_color_hover') ) ? 'color:'.get_option('nav_v_text_color_hover', '#ff5c00').';':'color:#ff5c00;';
	$nav_v 					= $nav_v_bg.$nav_v_text_color;
	$nav_v_hover 			= $nav_v_bg_hover.$nav_v_text_color_hover;
?>
<style>
    .navigation {
        <?php echo $nav_bg_color;?>
        text-align: left;
        padding-top: 5px;
        -webkit-box-shadow: 0px 5px 10px 0px rgba(84,84,84,0.15);
        -moz-box-shadow: 0px 5px 10px 0px rgba(84,84,84,0.15);
        box-shadow: 0px 5px 10px 0px rgba(84,84,84,0.15);
        padding: 0;
        box-shadow: none;
    }
    .navigation .navbar { <?php echo $nav_bg_color;?> }
    .navigation .navbar-collapse { padding:0; }
    .navigation .navbar-nav>li{padding:15px 0;}
    .navigation .navbar-nav>li>a {
        font-family: <?php echo $nav_font_weight;?>;
        font-weight: <?php echo get_option('nav_font_weight');?>;
        font-size: <?php echo get_option('nav_font_size');?>px;
        <?php echo $nav_text_color;?>
        <?php echo $nav_padding;?>
        border-left: 1px solid #ddd;cursor: pointer;
    }
    .navigation .navbar-nav>li:first-child>a{border:0;}
    .navigation .navbar-nav>li>a:focus, .navigation .navbar-nav>li>a:hover, .navigation .navbar-nav>li.active a { 
        <?php echo $nav_bg_color_hover;?>
        <?php echo $nav_text_color_hover;?>
    }
    .navigation .navbar-nav>li>a:after {
        <?php echo (get_option('nav_text_color_hover'))?'background-color:'.get_option('nav_text_color_hover').';':'';?>
        display: none;
    }

    .navigation .navbar-nav .dropdown-menu>li>a, .navigation .navbar-nav .dropdown-menu>li.open>a {
        <?php echo $navsub_bg_color;?>
        <?php echo $navsub_text_color;?>
    }
    .navigation .navbar-nav .dropdown-menu>li a:hover, .navigation .navbar-nav .dropdown-menu>.open>a:focus, .navigation .navbar-nav .dropdown-menu>.open>a:hover {
        <?php echo $navsub_bg_color_hover;?>
        <?php echo $navsub_text_color_hover;?>
    }
    .navigation>.container>div {
        position: relative;
    }
    #section-vertical-menu {
        position: relative; z-index: 52; display: block !important;
    }
    .navigation .vetical-menu{position:relative;float:left;width:20%;padding:0;}
    .navigation  .horizontal-menu{position:relative;float:left;width:80%;padding:0;}

    #section-vertical-menu .vetical-menu__header {
        cursor: pointer;
        margin: 0;
        padding: 0 15px;
        height:50px; line-height:50px;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 600;box-shadow:0 0 4px 0 rgba(0,0,0,.2);
        <?php echo $nav_vh;?>
    }
    #section-vertical-menu .vetical-menu__header i {
        content: "";
        display: block;
        height: 2px;
        position: absolute;
        -webkit-transform: rotate(0);
        transform: rotate(0);
        width: 15px;top: 17px;font-size: 1.3em;font-weight: bold;
    }
    #section-vertical-menu .vetical-menu__header .vetical-menu__header-text {
        padding-left: 25px;
    }
    #section-vertical-menu .bg-vertical.active {
        display: block;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 51;
    }
    .vetical-menu { padding: 0;width: 20%;box-shadow: 2px 0 4px 0 rgba(0,0,0,.2);float: left; }
    .vetical-slider { padding: 0;position: relative;width: 80%;float: left; }
    .vetical-slider [class*="col-md-"]{ padding-left: 0; }
    .vetical-menu__content { display: none; }
    .vetical-menu__content.active {     display: block;
        position: absolute;
        width: 100%;
        z-index: 99;
    }

    .vertical-menu-category {
        border: 1px solid #dadada;
    }
    .vertical-menu-category__nav {
        <?php echo $nav_v;?>
        list-style: none; margin:0;
    }

    .vertical-menu-category__nav .nav-item {
        float: none;
        border-top: 1px solid rgba(0,0,0,0.15);
        position: relative;
    }
    .vertical-menu-category__nav>.nav-item { padding-left: 37px; }
    .vertical-menu-category__nav>.nav-item  .megamenu {
        position:absolute;z-index: 999;left:100.5% !important;top:0px;height:380px;
         <?php echo $navsub_bg_color;?>
        <?php echo $navsub_text_color;?>
        box-shadow: 0 2px 1px 0 rgba(0,0,0,.25);display: none;opacity: 0;transition: all .5s ease-in-out;
    }
    .vertical-menu-category__nav>.nav-item:hover  .megamenu{display: block;opacity: 1;}
    .vertical-menu-category__nav>.nav-item  .megamenu ul{list-style-type: none;padding:10px;}
    .vertical-menu-category__nav>.nav-item .megamenu ul ul{padding:0;}
    .vertical-menu-category__nav>.nav-item  .megamenu ul li .title{position:relative;width:100%;display: block;border-bottom:1px solid #cdcdcd;}
    .vertical-menu-category__nav>.nav-item  .megamenu ul li .title a{text-transform:uppercase;}
    .vertical-menu-category__nav>.nav-item  .megamenu ul li a{display: block;width:100%;cursor: pointer;}
    .vertical-menu-category__nav .nav-item:first-child {
        border-top: 0px solid rgba(0,0,0,0.15);
    }
    .vertical-menu-category__nav .nav-item .icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 43px;
        height: 43px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .vertical-menu-category__nav .nav-item a.nav-link {
        position: relative;
        display: block;
        overflow: hidden;
        white-space: nowrap;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;cursor: pointer;
        padding: 3px 0px;
        padding-right: 30px;
        border: none;
        <?php echo $nav_v;?>
    }
    .vertical-menu-category__nav .nav-item i {
        position: absolute;
        top: 0;
        right: 0;
        width: 30px;
        line-height: 30px;
        text-align: center;
    }

    .vertical-menu-category__nav .nav-item>.dropdown-menu {
        position: absolute;
        left: calc(100% + 15px);
        top: 0;
        padding: 0px;
        min-width: 230px;
        border-radius: 0;
        box-shadow: 0 0 15px -5px rgba(0,0,0,0.4);
        border: 1px solid #ccc;
        <?php echo $nav_v;?>
    }
    .vertical-menu-category__nav .nav-item>.dropdown-menu {
        left: calc(100%) !important;
        border: 0px;margin-top: 0;
    }
    .vertical-menu-category__nav .nav-item>.dropdown-menu a.nav-link {
        padding: 11.5px 15px;
        padding-right: 30px;
    }
    
    .vertical-menu-category__nav .nav-item:hover,
    .vertical-menu-category__nav .nav-item:hover>a.nav-link,
    .vertical-menu-category__nav .nav-item a.nav-link:hover {
        <?php echo $nav_v_hover;?>
    }
    .vertical-menu-category__nav .nav-item:hover>.dropdown-menu {
        display: block;
    }


    @media(max-width:1199px){
        .navigation .horizontal-menu .navbar-center{display: flex;}
        .navigation .horizontal-menu .navbar-center li{flex:1;    padding: 5px 0;}
        .navigation .vetical-menu,.vetical-menu{width:25%;}
        .navigation .horizontal-menu,.vetical-slider{width:75%;}
    }
</style>