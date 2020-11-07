<div class="navigation">
	<div class="container">
		<div>
			<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 vertical-menu-home vetical-menu">
					<div id="section-vertical-menu">
						<div class="bg-vertical"></div>
						<h4 class="vetical-menu__header">
							<i class="fal fa-bars"></i>
							<span class="vetical-menu__header-text"><?php echo get_option('nav_vh_text', 'Danh mục sản phẩm');?></span>	
						</h4>
						<div class="vetical-menu__content">
							<div class="vertical-menu-category">
					            <ul class="vertical-menu-category__nav">
					            	<?php echo cle_nav_menu(array( 'theme_location' => 'main-vertical', 'walker' => 'store_mega_nav_menu'));?>
					            </ul>
					        </div>
						</div>
					</div>
				</div>
				<div class="col-md-9 horizontal-menu">
					<nav class="navbar" role="navigation">
				        <div class="collapse navbar-collapse" id="navbar-main" data-hover="dropdown" data-animations="fadeInUp fadeInRight fadeInUp fadeInLeft" aria-expanded="true">
				            <ul class="nav navbar-nav <?= get_option('nav_position');?>">
				                <?php echo cle_nav_menu(array( 'theme_location' => 'main-nav', 'walker' => 'store_bootstrap_nav_menu'));?>
				            </ul>
				        </div>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if( is_home() ) {?>
<section class="section-vertical">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4 vetical-menu">
		        <div class="vertical-menu-category">
		            <ul class="vertical-menu-category__nav">
		            	<?php echo cle_nav_menu(array( 'theme_location' => 'main-vertical', 'walker' => 'store_mega_nav_menu'));?>
		            </ul>
		        </div>
			</div>
			<div class="col-lg-9 col-md-8 vetical-slider">
				<?php dynamic_sidebar('home-slider');?>
			</div>
		</div>
	</div>
</section>
<?php } ?>