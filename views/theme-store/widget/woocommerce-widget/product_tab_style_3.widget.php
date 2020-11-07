<?php
class widget_product_tab_style_3 extends widget {
	function __construct() {
		parent::__construct('widget_product_tab_style_3', 'Sản Phẩm Tab (Style 3 - ajax)');
	}
	function form($left = array(), $right = array()) {
		$left[] = array('field' => 'pr_cate_id', 		'label' =>'Danh mục sản phẩm', 'type' => 'select2-multiple', 'options' => wcmc_gets_category_mutilevel_option( ) );
		$left[] = array('field' => 'pr_display_type', 	'label' =>'Kiểu Hiển Thị', 'type' => 'select', 'options' => array('Slider (chạy)', 'List (danh sách)'));
		$right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
		$right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
		$right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
		$right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');
		parent::form( $left, $right );
	}
	function widget( $option ) {
		$categories = array();
		if( have_posts($option->pr_cate_id) ) {
        	$categories = wcmc_gets_category( array( 'where_in' => array(  'field' => 'id', 'data' => $option->pr_cate_id ) ) );
        }
		$box = $this->container_box('widget-categories-product-tab', $option);
		echo $box['before'];
		if($this->name != '') {?>
		<div class="title-header">
			<h2 class="header"><?= $this->name;?></h2>
		</div>
		<?php } ?>
		<div class="box-content text-center" style="background-color: #fff;">
			<div id="widget_categories_product_<?= $this->id;?>" style="text-align:left;">
				<?php if(have_posts($categories)){ ?>
					<ul class="w_c_pr_tab__list w_c_pr_tab__list_<?=$this->id?>">
					<?php foreach ($categories as $key => $val): ?>
						<?php if ($key==0){ ?>
							<li class="item"><a href="#" data-wg="<?php echo $this->id;?>" data-id="<?php echo $val->id;?>" class="active"><?= $val->name;?></a></h3></li>
						<?php }else{ ?>
							<li class="item"><a href="#" data-wg="<?php echo $this->id;?>" data-id="<?php echo $val->id;?>"><?= $val->name;?></a></h3></li>
						<?php } ?>
						
					<?php endforeach ?>
					</ul>
				<?php } ?>
			</div>
			<div class="product-slider-horizontal" style="position:relative;">
				<?php if(have_posts($categories[0])){
					$where              = array('public' => 1, 'trash' => 0);
					$params              = array('orderby' => 'order, created desc');
					$params['limit'] = 24;
					$args['where_category'] =$categories[0];
					$products = gets_product($args);
				?>
				<div class="loading" style="display:none;"><?php get_img('rolling.svg');?></div>
				<?php if($option->pr_display_type == 0) {?>
				<div class="wg_pr_btn wg_pr_btn_style_1" id="wiget_product_btn_<?= $this->id;?>">
					<div class="prev"><i class="fal fa-chevron-left"></i></div>
					<div class="next"><i class="fal fa-chevron-right"></i></div>
				</div>
				<?php } ?>
				<div id="widget_product_<?= $this->id;?>" class=" <?php echo ($option->pr_display_type == 1)?'':'owl-carousel';?> list-item-product">
					<?php if($option->pr_display_type == 0) $this->display_slider($products);?>
					<?php if($option->pr_display_type == 1) $this->display_list($products);?>
				</div>
				<?php } ?>
			</div>
		</div>
		<script type="text/javascript">
			$(function(){
				<?php if($option->pr_display_type == 0) {?>
				var config = {
					items 				:3,
					margin				:15,
					autoplayTimeout		:2000,
					smartSpeed 			:1000,
					loop				:true, autoplay:true, autoplayHoverPause:true,
					responsive 			:{ 0	:{ items:1 },  500	:{ items:3 },  1000:{ items:4 } }
				}
				var ol = $("#widget_product_<?= $this->id;?>").owlCarousel(config);
				$('#wiget_product_btn_<?= $this->id;?> '+'.next').click(function() {
			    	ol.trigger('next.owl.carousel', [1000]);
				});
				$('#wiget_product_btn_<?= $this->id;?> '+' .prev').click(function() {
				    ol.trigger('prev.owl.carousel', [1000]);
				});
				<?php } ?>
				var cate_id, wg_id, data_item = [];
				$('.w_c_pr_tab__list_<?=$this->id?> li a').click(function(){
					$('#widget_product_<?= $this->id;?>').trigger("destroy.owl.carousel");
					$('.w_c_pr_tab__list_<?=$this->id?> li a').removeClass('active');
					$(this).addClass('active');
					cate_id = $(this).attr('data-id');
					wg_id 	= $(this).attr('data-wg');
					$('.product-slider-horizontal .loading').show();
					$('#widget_product_'+wg_id).html('');
					if (typeof data_item[cate_id] != 'undefined') {
						$('#widget_product_'+wg_id).html(data_item[cate_id]);
						$('.product-slider-horizontal .loading').hide();
						<?php if($option->pr_display_type == 0) {?>
						ol = $("#widget_product_<?= $this->id;?>").owlCarousel(config);
						$('#wiget_product_btn_<?= $this->id;?> '+'.next').click(function() {
							ol.trigger('next.owl.carousel', [1000]);
						});
						$('#wiget_product_btn_<?= $this->id;?> '+' .prev').click(function() {
							ol.trigger('prev.owl.carousel', [1000]);
						});
						<?php } ?>
						return false;
					}
					var data = {
						action : 'widget_ajax_category_product_tab',
						cate_id: cate_id,
						type : '<?php echo ($option->pr_display_type == 0)?'slide':'list';?>'
					};
					$jqxhr   = $.post( base + '/ajax' , data, function() {}, 'json');
					$jqxhr.done(function( data ) {
						if( data.type == 'success' ) {
							$('#widget_product_'+wg_id).html(data.item);
							$('.product-slider-horizontal .loading').hide();
							<?php if($option->pr_display_type == 0) {?>
							ol = $("#widget_product_<?= $this->id;?>").owlCarousel(config);
							$('#wiget_product_btn_<?= $this->id;?> '+'.next').click(function() {
								ol.trigger('next.owl.carousel', [1000]);
							});
							$('#wiget_product_btn_<?= $this->id;?> '+' .prev').click(function() {
								ol.trigger('prev.owl.carousel', [1000]);
							});
							<?php } ?>
							data_item[cate_id] = data.item;
						}
					});
					return false;
				});
			})
		</script>
		<style type="text/css">
			.w_c_pr_tab__list { list-style:none; display:flex; }
			.w_c_pr_tab__list li {
				flex: 1;
				margin: 0;
				margin-right: 0;
				padding: 2px;
				font-size: 12px;
				text-align: center;
				display: flex;
				justify-content: center;
				align-items: center;
				min-height: 40px;
				color: #333;
				border-right: 1px solid #f3f3f3;max-width: 150px;text-transform: capitalize;display: table;
			}
			.w_c_pr_tab__list li a {
				display:table-cell;
				vertical-align: middle;
				width: 100%;text-align: center;
				color:#000;
			}
			.w_c_pr_tab__list li a.active {
				background-color: #cf1717;color: #fff;
			}
		.widget-categories-product-tab .title-header h2.header{text-align:left;    font-size: 22px;color: #cf1717;line-height: 30px;margin-bottom: 10px;font-weight: 600;}
		.widget-categories-product-tab .box-content .product-slider-horizontal 	 .list-item-product [class^="col-"]{padding:0;}
		</style>
		<?php echo $box['after'];
	}
	function display_list($products) {
		foreach ($products as $key => $val): ?>
		<div class=" product-index col-xs-4  col-md-2 col-lg-2" style="padding:0">
			<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
		</div>
		<style>
			@media(max-width:1199px) and (min-width:768px){
			.widget-categories-product-tab .product-index {width:20%;}
			}
			@media(max-width:599px){
				.widget-categories-product-tab .product-index {width:50%;}
			}
		</style>
		<?php endforeach;
	}
	function display_slider($products) {
		foreach ($products as $key => $val): ?>
			<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
		<?php endforeach;
	}
}
if( class_exists('woocommerce') ) {
	register_widget('widget_product_tab_style_3');
}
else {
	add_action('admin_notices', function() {
		echo notice('error', 'Để sử dụng widget <b>widget_product_tab_style_3</b> vui lòng cài đặt plugin woocommerce');
	});
}
if( !function_exists('widget_ajax_category_product_tab') ) {
	function widget_ajax_category_product_tab($ci, $model) {
		$ci =& get_instance();
		$result['type'] = 'error';
		$result['message'] = 'Lấy dữ liệu thất bại';
		if( $ci->input->post() ) {
			$pr_cate_id = (int)$ci->input->post('cate_id');
			$pr_cate_type = removeHtmlTags($ci->input->post('type'));
			$where              = array('public' => 1, 'trash' => 0);
			$params              = array('orderby' => 'order, created desc', 'limit' => 24);
			$args = array( 'where'  => $where, 'params' => $params );
			$args['where_category'] = wcmc_get_category( array( 'where' => array( 'id' => $pr_cate_id ) ) );
			$products = gets_product($args);
			$result['item'] = '';
			$wg = new widget_product_tab_style_3();
			ob_start();
			if($pr_cate_type == 'slide') $wg->display_slider($products);
			if($pr_cate_type == 'list') $wg->display_list($products);
			$result['item'] = ob_get_contents();
			ob_clean();
			ob_end_flush();
			$result['type'] = 'success';
		}
		echo json_encode( $result );
	}
	register_ajax( 'widget_ajax_category_product_tab' );
}