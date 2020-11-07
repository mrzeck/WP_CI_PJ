<!-- header content -->
<div class="header-content">
	<div class="container">
		<div class="row">
			<?php $dm=wcmc_gets_category_mutilevel_option() ;?>
			<div class=" text-<?php echo get_option('logo_position');?> logo">
				<?php if( $ci->template->is_home() ) {?>
					<h1 style="display: none"><?php echo get_option('general_label');?></h1>
				<?php } ?>
				<a href="<?php echo base_url();?>" title="<?php echo get_option('general_label');?>">
					<?php get_img(get_option('logo_header'), get_option('general_label'));?>
				</a>
			</div>
			<div class="search">
				<form class="navbar-form form-search" action="search" method="get" role="search" style="margin:0;padding:0;" autocomplete="off">
					<div class="form-search-left">
						<select name="category" id="category" class="form-control mdb-select md-form colorful-select dropdown-primary" style="width:100%;">
							<?php foreach ($dm as $key => $val):?>
								<option value="<?=$key ?>"><?=$val  ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-search-right">
						<div class="form-group" style="margin:0;padding:0;">
							<input class="form-control" type="text" value="" name="keyword" placeholder="Tìm kiếm..." id="searchInput">
							<?php if(class_exists('woocommerce')) { ?>
								<input type="hidden" name="type" value="products">
							<?php } ?>
						</div>
						<button type="submit" class="btn btn-search btn-default"><i class="fa fa-search" aria-hidden="true"></i></button>
					</div>
				</form>
			</div>
			<div class="sign_in">
				<div class="groupc"> 
					<a href="<?php echo login_url();?>"><i class="fas fa-sign-in-alt"></i><span>Đăng nhập</span></a> 
					<a href="<?php echo register_url();?>"><i class="fas fa-registered"></i> <span>Đăng ký</span></a> 
				</div>
			</div>
			<div class="cart-top">
				<a href="gio-hang" class="btn-cart-top">
					<div style="position:relative; display:inline-block;">
						<?php get_img(get_option('header_icon_cart'),'Giỏ hàng');?>
						<span class="wcmc-total-items"><?= $ci->cart->total_items();?></span>
					</div>
				</a>
			</div>
			<div class="hotline">
				<a class="btn-call-now " href="tel:<?=get_option('contact_phone') ?>">
					Hotline: </br>
					<span><?=get_option('contact_phone') ?></span>
				</a>
			</div>
			<div class="new">
				<div class="title">
					<div class="inner_title">
					<a href="<?=get_option('header_menu_chiase_link')  ?>">
					<div class="nd1">Kinh nghiệm hay</div>
					<div class="nd2">& Tin khuyến mãi</div>
					</a>
					<div class="box-content">
						
						<ul class="nav navbar-nav ">
							<?php echo cle_nav_menu(array( 'theme_location' => 'share-nav', 'walker' => 'store_bootstrap_nav_menu'));?>
						</ul>
					</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script>

		$('.new .title .inner_title').mouseover(function(){
				$(this).find('.box-content').addClass('open');
		});
		$('.new .title .inner_title').mouseleave(function(){
				$(this).find('.box-content').removeClass('open');
		});


		// $('.new .title .inner_title').mouseleave(function(event) {
		// 	$(this).find('box-content').removeClass('open');
		// });


</script>