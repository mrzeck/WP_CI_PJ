<?php 
$nav_search_border_color = get_option('nav_search_border_color', 'red');
$nav_search_border_color = (!empty($nav_search_border_color)) ? $nav_search_border_color : '';
$nav_search_bg_color = get_option('nav_search_bg_color', '#fff');
$nav_search_bg_color = (!empty($nav_search_bg_color)) ? $nav_search_bg_color : '#fff';
$nav_search_btn_bg_color = get_option('nav_search_btn_bg_color', '#000');
$nav_search_btn_bg_color = (!empty($nav_search_btn_bg_color)) ? $nav_search_btn_bg_color : '#000';

$nav_search_bg_color_select = get_option('nav_search_bg_color_select', '#000');
$nav_search_bg_color_select = (!empty($nav_search_bg_color_select)) ? $nav_search_bg_color_select : '#000';


$nav_search_btn_txt_color = get_option('nav_search_btn_txt_color', '#fff');
$nav_search_btn_txt_color = (!empty($nav_search_btn_txt_color)) ? $nav_search_btn_txt_color : '#fff';


$nav_search_text_color_select = get_option('nav_search_text_color_select', '#fff');
$nav_search_text_color_select = (!empty($nav_search_text_color_select)) ? $nav_search_text_color_select : '#fff';
?>
<style type="text/css">
  header .header-content {
    padding: 0;
    <?php echo (get_option('header_bg_color'))?'background-color:'.get_option('header_bg_color').';':'';?>
    <?php echo (get_option('header_bg_image'))?'background-image:url(\''.get_img_link(get_option('header_bg_image')).'\');background-repeat:'.get_option('header_position_image').';':'';?>    
    min-width: 1200px; 
  }
  header .logo{position:relative;float:left;width:200px;}
  header .logo img { <?php echo (get_option('logo_height'))?'max-height:'.get_option('logo_height').'px;':'';?> }
  
  /* //search */
  .header-content .search { padding-top:13px;width: 40%; position:relative;float:left;}
  .header-content .search .form-search { 
    display: flex;
    border: 0;
  }
  .header-content .search .form-search .form-search-left{
    position: relative;float: left;width: 30%;
    border-radius: 5px;overflow: hidden;
    background-color:<?php echo $nav_search_bg_color_select;?>;
    color: <?php echo $nav_search_text_color_select;?>;
  }
  .header-content .search .form-search .form-search-right{
   border-radius: 5px;overflow: hidden;position: relative;float: left;width: calc(70% - 20px);margin-left: 20px;


   background-color:<?php echo $nav_search_btn_bg_color;?>;
   <?php if(!empty($nav_search_border_color)) {?>border:1px solid <?php echo $nav_search_border_color;?>;<?php } else {?>border:0px solid #000;<?php } ?>
 }
 .header-content .search .form-search  #category{font-size:13px; background-color:<?php echo $nav_search_bg_color_select;?>;border: 0;
   color: <?php echo $nav_search_text_color_select;?>;}
   .header-content .search .form-search  #category option{line-height:35px;}
   .header-content  .search .form-search .form-control { 
    background-color:<?php echo $nav_search_bg_color;?>; 
    height: 34px; 
    float:left; 
    width: calc(100% - 50px);
    border-radius:0;
    box-shadow: none;
    border: 1px solid <?php echo $nav_search_bg_color;?>; 
  }
  header .search .form-search .form-group .form-control{height:34px;}
  .header-content  .search .form-search .btn-search {
    width:34px; line-height:34px; height:34px;
    background-color:<?php echo $nav_search_btn_bg_color;?>;
    color:<?php echo $nav_search_btn_txt_color;?>;
    border:0px solid <?php echo $nav_search_btn_bg_color;?>;
  }
  /* end search */

  /* login */
  .sign_in{width:110px;position:relative;float:left;padding-left:10px;padding-top:9px;}
  .sign_in .groupc a{display: block;width:100%;color:#fff;text-align:left;line-height:1.5;}
  .sign_in .groupc a i{padding-right:5px;font-size:17px;width:25px;float:left;margin-top:3px;}
  .sign_in .groupc a span{line-height:1.5;}
  /* end login */

  /* cart */
   .header-content .cart-top{display: inline-table;float: left;width:70px;text-align:center;}
   .header-content .cart-top a{display: inline-table;float:none;}
  /* end cart */
header .header-content .hotline { float:left; margin:  0px; overflow:hidden; text-align:center;padding-top:7px;width:150px;padding-left:10px;}
header .header-content .hotline a{text-align:left;    letter-spacing: 1.2px;line-height: 1.3;}

header .header-content .hotline .hotline__title {
  float: right;
  padding-left:10px;
  padding-top:10px;
  color:red;
  font-weight: bold;
  text-align:left;
}
header .header-content .hotline .hotline__title p {
  margin:0;
  color:#000;
  line-height:19px;
  font-size:18px;
  text-transform: uppercase;
  letter-spacing: 2px;
}
header .header-content .new{position:relative;padding-top:10px;display: inline-table;float: right}
header .header-content .new a{}
header .header-content .new .title{text-align:right;}
header .header-content .new .title .inner_title{display: inline-table;text-align:left;}
header .header-content .new .nd1{color:#fff;line-height:1.3;font-weight:bold;font-size:1.2em;}
header .header-content .new .nd2{color:#fff;line-height:1.3;font-weight:bold;font-size:1em;}
header .header-content .new .box-content{display: none;height:0;position:absolute;background-color:#fff;z-index: 99;width:100%;padding:5px;max-width: 220px;box-shadow: 1px 1px 4px #cdcdcd}
header .header-content .new .box-content a{width:100%;color:#333;}
header .header-content .new .box-content li{width:100%;display: block;}
header .header-content .new .box-content li a{padding:5px;background-color:transparent !important;cursor: pointer}
header .header-content .new .box-content li a:hover{color:#288ad6;}
header .header-content .new .box-content.open{display: inline-table;height:100%;}
.btn-call-now span{line-height:1.8em;}
.btn-call-now{
  text-align: left;
  display:inline-block; 
  text-transform: uppercase;
  height:34px; 
  /* position:fixed;  */
  /* width:300px;  */
  bottom:10px; 
  background:transparent; 
/*   text-decoration:none; box-shadow:0 0 5px #ddd; 
  -webkit-box-shadow:0 0 5px #ddd; 
  -moz-box-shadow:0 0 5px #ddd;  */
  z-index:999999999;
  /*  left:0;  */
  color: #FFF;
  font-weight: 700;
  font-size: 1em;
  border-radius:25px; 
  padding:5px 0;

  -moz-animation-duration: 500ms;
  -moz-animation-name: calllink;
  -moz-animation-iteration-count: infinite;
  -moz-animation-direction: alternate;

  -webkit-animation-duration: 500ms;
  -webkit-animation-name: calllink;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-direction: alternate;

  animation-duration: 500ms;
  animation-name: calllink;
  animation-iteration-count: infinite;
  animation-direction: alternate;
}
.btn-call-now:hover{
  color:#fff;
}


@keyframes calllink {
  0%{ color:#eba11e; }
  50%{ color:#fff; }
  100%{ color:#ebfa48; }
}
@media screen and (max-width: 650px){
  .btn-call-now{
    width:auto; 
    font-size: 100%;
    bottom:0; 
  }
  .btn-call-now em { margin: 0; }
  .btn-call-now span { display: none; }
}
@media screen and (max-width: 320px){
  .btn-call-now{
    font-size: 90%; 
  }
}
@media(max-width:767px){
  .btn-call-now{display:none;}
}
@media(max-width:1199px){
  header .header-content{min-width:inherit;}
  header .header-content .new{display: none;}
  header .search .form-search .form-group .form-control{width:100%;}
  .header-content .search{width:45%;}
  .header-content .search .form-search .form-search-left{width:35%;}
  .header-content .search .form-search .form-search-right{width:calc(65% - 20px);}
}
</style>