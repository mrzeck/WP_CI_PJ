<?php
$category_row_count        = get_option('category_row_count');
$category_row_count_tablet = get_option('category_row_count_tablet');
$category_row_count_mobile = get_option('category_row_count_mobile');

$col['lg'] = 12/$category_row_count;
$col['md'] = 12/$category_row_count;
$col['sm'] = 12/$category_row_count_tablet;
$col['xs'] = 12/$category_row_count_mobile;

$col = 'col-xs-'.$col['xs'].' col-sm-'.$col['sm'].' col-md-'.$col['md'].' col-lg-'.$col['lg'].'';
?>


<div class="container" >
	<div class="box-content" style="margin:10px 0">
		<div class="product_index_right">
			<div class="box-content">
				<?php dynamic_sidebar('sidebar-main');?>
			</div>
		</div>
		<div class="product_index_left">
			<h1 class="header text-left" style="text-align:left">kẾT QUẢ TÌM KIẾM</h1>
			<div class="product-slider-horizontal" style="margin-top: 10px;">
				<div class="list-item-product">
					<?php foreach ($objects as $key => $val): ?>
						<div class="<?php echo $col;?>">
							<?php echo plugin_get_include('woocommerce', 'template/loop/item_product', array('val' =>$val));?>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>

		<?php do_action( 'after_products_index' );?>

	</div>
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