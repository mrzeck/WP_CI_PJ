<?php do_action( 'before_products_index' );?>
<?php
$layout 		= get_theme_layout();

if(isset($layout['banner'])) {
	if($layout['banner'] == 'in-content') $this->template->render_include('banner');
	$url='san-pham';
}
else {
	if(isset($category) && have_posts($category)) {
		$url = $category->slug;
		$name = $category->name;
		 $thuonghieu = get_metadata( 'products_categories', $category->id, 'thuonghieu', true );
		 $khoanggia = get_metadata( 'products_categories', $category->id, 'khoanggia', true );
		 ?>
		 <style type="text/css" media="screen">
		 	ul.wcmc-filter-list.wcmc-filter-list-thuonghieu li, ul.wcmc-filter-list.wcmc-filter-list-thuonghieu li{display: none;}
		 	ul.wcmc-filter-list.wcmc-filter-price_ul li, ul.wcmc-filter-list.wcmc-filter-price_ul li{display: none;}
		 </style>
		 <?php if (empty($khoanggia)): ?>
		 	<style>
		 		.wcmc-filter-price{display: none;}
		 	</style>
		 <?php endif ?>
		 <?php if (empty($thuonghieu)): ?>
		 	<style>
		 		.wcmc-filter-thuonghieu{display: none;}
		 	</style>
		 <?php endif ?>
		 <?php if (is_array($thuonghieu) && count($thuonghieu) !=0): ?>
			 <?php foreach ($thuonghieu as $key => $value): ?>
			 	<style>
			 		ul.wcmc-filter-list.wcmc-filter-list-thuonghieu li.id_<?=$value?>{display: block;}
			 	</style>
			 <?php endforeach ?>
		<?php endif ?>

		<?php if (is_array($khoanggia) && count($khoanggia) !=0): ?>
		 <?php foreach ($khoanggia as $key => $val): ?>
		 		<style>
		 			ul.wcmc-filter-list.wcmc-filter-price_ul li.price_<?=$val?>, ul.wcmc-filter-list.wcmc-filter-price_ul li.price_<?=$val?>{display: block;}
		 		</style>
		 <?php endforeach ?>
		<?php endif ?>
	<?php }else{
		$name = __('Sản phẩm');
		$url='san-pham';
	}


	$breadcrumb = theme_get_breadcrumb();
	echo Breadcrumb($breadcrumb);?>
	<?php if (isset($category)){ ?>
		<div class="banner product-slider-horizontal">
			<?php $gallerys = get_gallery(array(), null, $category->id, 'products_categories');?>
			<?php if (count((array)$gallerys) > 1){ ?>
			<div class="box-content" style="">
			<!--
				Thêm class sau để thay đổi vị trí: wg_pr_btn_top_right, wg_pr_btn_center
				Đổi class wg_pr_btn_style_1 thành wg_pr_btn_style_radius để sử dụng button tròn.
			-->

			<div class="wg_pr_btn wg_pr_btn_style_radius" id="wiget_product_btn_gallery">
				<div class="prev"><i class="fal fa-chevron-left"></i></div>
				<div class="next"><i class="fal fa-chevron-right"></i></div>
			</div>
			<div id="widget_product_gallery" class="owl-carousel">
				<?php foreach ($gallerys as $key => $val): ?>
					<?php $title = get_metadata('gallerys', $val->id, 'title', true); ?>
					<div class="item">
						<div class="img1">
							<div class="inner_img">
								<?php get_img($val->value,$title) ?>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>
		<script defer> 
			$(function(){
				var config = {
					items 				:3,
					margin				:10,
					autoplayTimeout		:6000,
					smartSpeed 			:2000,
					loop				:true, autoplay:true, autoplayHoverPause:true,
					responsive 			:{ 0	:{ items:3 } } 
				}
				var ol = $("#widget_product_gallery").owlCarousel(config);
				$('#wiget_product_btn_gallery '+'.next').click(function() {
					ol.trigger('next.owl.carousel', [1000]);
				})
				$('#wiget_product_btn_gallery '+' .prev').click(function() {
					ol.trigger('prev.owl.carousel', [1000]);
				});
			});
		</script>
	<?php }else{ ?>
		<?php if (count((array)$gallerys) == 1){ ?>
			<div class="banner">
				<?php foreach ($gallerys as $key => $val): ?>
					<?php $title = get_metadata('gallerys', $val->id, 'title', true); ?>
					<div class="item">
						<div class="img1">
							<div class="inner_img">
								<?php get_img($val->value,$title,array('style','width:100%')) ?>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		<?php } ?>
	<?php } ?>
	</div>	
	<style>
		.product-slider-horizontal .item .img1 {
			position: relative;
			display: block;
			width: 100%;
			padding-top: 50%;
			height: inherit;
			border-radius: 0 !important;
			overflow: hidden;
		}
		.product-slider-horizontal .item .img1 .inner_img{    position: absolute;top: 5px;left: 5px;width: calc(100% - 10px);height: calc(100% - 10px);align-items: center;justify-content: center;overflow: hidden;}
		.product-slider-horizontal .item .img1 .inner_img img{min-height: 100%;min-width: 100%;position: relative;left: 50%;top: 50%;transform: translateY(-50%) translateX(-50%);display: inherit;transition: all .5s ease-in-out;}
		.product-slider-horizontal .banner .item .img1 {padding-top:25%;}
	</style>
<?php } ?>

<style>
	h1.header { text-align:left;}
	.btn-breadcrumb a.btn.btn-default {
		color: #000; line-height: 37px;
	}
	.btn-breadcrumb span:first-child a { padding-left:0;}
</style>
<?php
}
?>
<div class="product_index_right">
	<div class="box-content">
	<?php dynamic_sidebar('sidebar-main');?>
	</div>
</div>
<div class="product_index_left">
	<?php if (!empty($name)){ ?>
		<h1 class="header text-left"><?= $name;?></h1>
	<?php }else{ ?>
		<h1 class="header text-left"><?= __('sảm phẩm');?></h1>
	<?php } ?>
	
<?php
	/**
	 * content_products_index
	 *
	 * @Hook  woocommerce_products_index - 10
	 */
	do_action( 'content_products_index' );
	?>

<?php do_action( 'after_products_index' );?>

</div>

<style>
	.product_index_left{position:relative;float:left;width:75%;}
	.product_index_right{position:relative;float:right;width:25%;padding-left: 15px;}
	.product_index_right .box-content{position:relative;background-color:#fff;display: block;padding:5px;}
	.product_index_right .box-content .sidebar-title h2,.product_index_right .box-content .sidebar-title h2{margin-top:0;font-size:17px;text-transform: uppercase;}
	@media(max-width:1199px){
		.product_index_right{width:30%;}
		.product_index_left{width:70%;}
		.product-slider-horizontal .list-item-product [class^="col-"]{width:25%;}
	}
	@media(max-width:767px){
		.product_index_right{width:100%;padding: 0;}
		.product_index_left{width:100%;padding: 0}
		.product_index_right{float:left;}
		.product-slider-horizontal .list-item-product [class^="col-"]{width:33.33%;}
	}
	@media(max-width:599px){
		.product-slider-horizontal .list-item-product [class^="col-"]{width:50%;}
	}

</style>
	<script>
		$(document).on('click touch','ul.wcmc-filter-list li a.wcmc-filter-list_link',function(){
			var url='<?=fullurl()?>';
			var href=$(this).attr('data-href');
			var data={
				url:url,
				href:href,
				action:'ajax_search_fillter'
			}
			$jqxhr = $.post(base+'/ajax', data, function(data) {}, 'json');
			$jqxhr.done(function( data ) {
				if( data.status == 'success' ) {

					location.href = data.data;
				}
			});

			return false;
		});
	</script>
