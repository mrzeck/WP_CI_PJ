<div class="td-search-background"></div>
<div class="td-search-wrap-mob">
	<div class="td-drop-down-search" aria-labelledby="td-header-search-button">
		<form method="get" class="td-search-form" action="search">
			<!-- close button -->
			<div class="td-search-close">
				<a href="#" data-wpel-link="internal"><i class="fal fa-times td-icon-close-mobile"></i></a>
			</div>
			<div role="search" class="td-search-input">
				<span>Tìm kiếm</span>
				<input id="td-header-search-mob" type="text" value="" name="keyword" autocomplete="off">
				<input type="hidden" value="products" name="type">
			</div>
		</form>
		<div id="td-aj-search-mob"></div>
		<div class="result-msg"><a href="<?php echo base_url('search?keyword=&type=products');?>">Xem tất cả các kết quả</a></div>
	</div>
</div>


<div class="td-menu-background"></div>

<div id="td-mobile-nav" style="min-height: 755px;">
    <div class="td-mobile-container">
        <!-- mobile menu top section -->
        <div class="td-menu-socials-wrap">
            <!-- socials -->
            <div class="td-menu-socials">
		        <span class="td-social-icon-wrap">
		            <a target="_blank" href="" title="Facebook" data-wpel-link="external" rel="nofollow">
		                <i class="td-icon-font td-icon-facebook"></i>
		            </a>
		        </span>
		        <span class="td-social-icon-wrap">
		            <a target="_blank" href="" title="Youtube" data-wpel-link="external" rel="nofollow">
		                <i class="td-icon-font td-icon-youtube"></i>
		            </a>
		        </span>
		    </div>
            <!-- close button -->
            <div class="td-mobile-close">
                <a href="#" data-wpel-link="internal"><i class="fal fa-times td-icon-close-mobile"></i></a>
            </div>
        </div>

        <!-- login section -->
        
        <!-- menu section -->
        <div class="td-mobile-content">
        	<ul class="menu-mobile-content">
				<div class="panelvmenu">
				<?= cle_nav_menu(array( 'theme_location' => 'main-mobile', 'walker' => 'store_mobile_nav_menu'));?>
				</div>
			</ul>
        </div>
    </div>
    <!-- register/login section -->
</div>



<div id="td-search-wrap-dest" style="display: none;">
	<div class="search">
		<form class="navbar-form form-search" action="search?type=products" method="get" role="search" style="margin:0;padding:0;">
			<div class="form-group" style="margin:0;padding:0;">
				<input class="form-control search-field" type="text" value="" name="keyword" placeholder="Tìm kiếm" id="searchInput">
				<input type="hidden" value="post" name="type">
			</div>
			<button type="submit" class="btn btn-search btn-default" style="width:50px;"><i class="fa fa-search" aria-hidden="true"></i></button>
		</form>
	</div>
</div>