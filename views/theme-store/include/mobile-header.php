<!-- MOBILE -->
<div class="header-mobile ">
	<div class="header-mobile-top">
		<div class="container">
			<div class="row">
				<div class="dk-dn">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo login_url();?>"><i class="fal fa-sign-out"></i> Đăng nhập</a></li>
						<li><a href="<?php echo register_url();?>"><i class="far fa-file-edit"></i> Đăng ký</a></li>
					</ul>
				</div>
				
			</div>

		</div>
	</div>
	<div class="container">
		<div class="row">
			
			<div class="col-xs-2 logo text-center" style="padding:0">
				<a href="<?php echo base_url();?>" title="<?php echo get_option('general_label');?>">
					<?php get_img(get_option('logo_header'), get_option('general_label'));?>
				</a>
			</div>
			<div class="col-xs-10 header-mobile-rgt">
				<div class="search">
					<form class="navbar-form form-search" action="search" method="get" role="search" style="margin:0;padding:0;">
						<div class="form-group" style="margin:0;padding:0;width: calc( 100% - 50px);float:left;">
							<input class="form-control search-field" type="text" value="" name="keyword" placeholder="<?php echo __('Tìm kiếm', 'theme_timkiem');?>" style="width: 100%;">
							<input type="hidden" value="products" name="type">
						</div>
						<button type="submit" class="btn btn-search btn-default" style="width:50px;float:left;"><i class="fa fa-search" aria-hidden="true"></i></button>
					</form>
				</div>
				<a href="gio-hang" class="btn-cart-mobile">
					<?php get_img(get_option('header_mobile_icon_cart'),'cart',array('style'=>'width:30px'));?>
					<span class="wcmc-total-items"><?= $this->cart->total_items();?></span>
				</a>
				<!-- <a class="js_btn-search-mobile btn-search-mobile"><i class="fal fa-search"></i></a> -->
				<div class=" js_btn-menu-mobile btn-menu-mobile" data-menu="#menu-mobile"> <i class="fa fa-bars"></i> </div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

</div>
<style>
	.header-mobile{padding-top:0;box-shadow:1px  1px #333;}
	.header-mobile .header-mobile-top{background-color:<?php echo (get_option('header_mobile_icon_menu'))? get_option('header_mobile_icon_menu'):'#000';?>;color:#fff;margin-bottom:10px;}
		.header-mobile .header-mobile-top a{color:#fff;}
  
	.dk-dn ul,.lang2 ul{margin:0;}
	.dk-dn,.lang2{float:right;}
  	.dk-dn li{display: inline-table;float:left;}
  	.lang2 li{display: inline-table;float:left;}
	/* #td-outer-wrap{width:600px;} */
	.header-mobile .btn-search-mobile{    font-size: 30px;padding-top: 2px;}
	.header-mobile-rgt .search{position: relative;float: left;width: calc(100% - 100px);margin:0;padding-top:8px;}

	.header-mobile-rgt .btn-menu-mobile,.header-mobile-rgt .btn-cart-mobile{position:relative;float:left;width:50px;text-align:right;padding-top:8px;}
	.header-mobile .btn-menu-mobile{font-size:30px;padding-top:10px}
	.header-mobile .logo img{    height: 50px;
    margin-top: -5px;
    margin-bottom: -5px;}
    .search .form-search .form-group .form-control{height:30px;}
    .header-mobile .search .btn{height:32px;background-color:#fff;color:#333;}
    .header-mobile .search .form-group,.header-mobile .search .btn{border:1px solid <?php echo (get_option('header_mobile_icon_menu'))? get_option('header_mobile_icon_menu'):'#000';?>;}
    .header-mobile .search .form-group{border-right:0;}
    .header-mobile .search .btn{border-left:0;}

   /*  .header-mobile .logo img{height:150px;max-height:inherit;}
    .header-mobile .btn-menu-mobile{font-size:40px;}
    .header-mobile .btn-search-mobile{font-size:50px;}
    .header-mobile .btn-cart-mobile img{width:50px !important}
    .header-mobile-rgt,.header-mobile .btn-menu-mobile{padding-top:50px;}
    .td-mobile-content .panelvmenu .list-group-item-vmenu{font-size: 30px;
    line-height: 80px;}
    .td-mobile-content .panelvmenu a.arrow-sub-vmenu i{    top: -80px;    height: 80px;line-height: 80px;} */
    .header-mobile {display: none;}
    @media(max-width:767px){
    	.header-mobile {display: block;}
    }
    @media(max-width:767px){
    	.header-mobile .logo img{height: 50px;}
    	.header-mobile-rgt{padding-top:5px}
    	.header-mobile-rgt .search,.header-mobile-rgt .btn-menu-mobile, .header-mobile-rgt .btn-cart-mobile{padding-top:0;}
    }
</style>
<script>
	$(document).ready(function($) {
		//Fixed menu scroll
		var nav_m = $('.header-mobile');

		var nav_m_p = nav_m.position();

		$(window).scroll(function() {
			if ($(this).scrollTop() > nav_m_p.top) {
				nav_m.addClass('fixed');
			} else {
				nav_m.removeClass('fixed');
			}
		});
	});
</script>