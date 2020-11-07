<?php
class widget_product_style_1 extends widget {
	function __construct() {
		parent::__construct('widget_product_style_1', 'Sản phẩm (style 1)');
		add_action('theme_custom_css', array( $this, 'css'), 10);
	}
	function form( $left = array(), $right = array()) {
		$left[] = array('field' => 'title_image', 'label' =>'Hình ảnh Tiêu đề', 'type' => 'image');
		$left[] = array('field' => 'pr_cate_id', 		'label' =>'Danh mục sản phẩm', 'type' => 'product_categories','after' => '<div class="col-md-4"><label>Danh mục sản phẩm</label><div class="group">', 'before'=> '</div></div>');
		$left[] = array('field' => 'pr_display_type', 	'label' =>'Kiểu Hiển Thị', 'type' => 'select', 'options' => array('Sản phẩm chạy ngang', 'Danh sách sản phẩm'),'after' => '<div class="col-md-4"><label>Kiểu Hiển Thị</label><div class="group">', 'before'=> '</div></div>');

		$product_status = array(
	    	'0' 	=> 'Sản phẩm mới',
	    	'1' 	=> 'Sản phẩm yêu thích',
	    	'2' 	=> 'Sản phẩm bán chạy',
	    	'3' 	=> 'Sản phẩm nổi bật',
	    	'4' 	=> 'Sản phẩm khuyến mãi',
		);
		$left[] = array('field' => 'pr_status', 	'label' =>'Loại sản phẩm', 'type' => 'select', 'options' => $product_status,'after' => '<div class="col-md-4"><label>Loại sản phẩm</label><div class="group">', 'before'=> '</div></div><div class="clearfix"></div>');

		$left[] = array('field' => 'pr_margin', 		'label' =>'K/c giữa sản phẩm', 'type' => 'number', 'value' => 10,'after' => '<div class="col-md-3"><label>K/c giữa sản phẩm</label><div class="group">', 'before'=> '</div></div>');

		$left[] = array('field' => 'limit', 			'label' =>'Số sản phẩm lấy ra', 				'type' => 'number', 'value' => 10, 'after' => '<div class="col-md-3"><label>Số sản phẩm lấy ra</label><div class="group">', 'before'=> '</div></div>');

		$left[] = array('field' => 'time', 			'label' =>'Thời gian tự động chạy', 'type' => 'number', 'value' => 2,'after' => '<div class="col-md-3"><label>Thời gian tự động chạy</label><div class="group">', 'before'=> '</div></div>');
		$left[] = array('field' => 'speed', 			'label' =>'Thời gian h/t chạy', 'type' => 'number', 'value' => 3,'after' => '<div class="col-md-3"><label>Thời gian h/t chạy</label><div class="group">', 'before'=> '</div></div> <div class="clearfix"></div>');

		$left[] = array('field' => 'pr_per_row', 		'label' =>'Số sản phẩm trên 1 hàng', 			'type' => 'col', 'value' => 4, 'args' => array('min'=>1, 'max' => 6));
        $left[] = array('field' => 'pr_per_row_tablet','label' =>'Số sản phẩm trên 1 hàng - tablet', 	'type' => 'col', 'value' => 3, 'args' => array('min'=>1, 'max' => 6));
		$left[] = array('field' => 'pr_per_row_mobile','label' =>'Số sản phẩm trên 1 hàng - mobile', 	'type' => 'col', 'value' => 2, 'args' => array('min'=>1, 'max' => 6));
		
		$right[] = array('field' => 'title_sales', 'label' =>'Tiêu đề khuyến mãi', 'type' => 'text');
		$right[] = array('field' => 'title_sales_image', 'label' =>'Hình ảnh Tiêu đề khuyến mãi', 'type' => 'image');
		$right[] = array('field' => 'box', 'label' =>'Box', 'type' => 'wg_box');
		// $right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>13, 'args' => array('max' => 13));
        // $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));
		// $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>13, 'args' => array('max' => 13));
		
		$right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color');
		$right[] = array('field' => 'bg_image', 'label' =>'Hình nền', 'type' => 'image');
		$right[] = array('field' => 'box_size', 'label' =>'', 'type' => 'size_box');
		parent::form($left, $right);
	}
	function widget($option) {
		//xử lý option
		$model              = get_model('products');
		$where              = array('public' => 1, 'trash' => 0);
		$params              = array('orderby' => 'order, created desc');
		if($option->limit > 0) $params['limit'] = $option->limit;
		if($option->pr_status == 1) $where['status1'] = 1;
		if($option->pr_status == 2) $where['status2'] = 1;
		if($option->pr_status == 3) $where['status3'] = 1;
		if($option->pr_status == 4) $where['price_sale <>'] = 0;
		$args = array( 'where'  => $where, 'params' => $params );
        //lấy tất cả dữ liệu
        if($option->pr_cate_id != 0) {
			$args['where_category'] = wcmc_get_category( array( 'where' => array( 'id' => $option->pr_cate_id ) ) );
        }
        $products = gets_product($args);
        $box = $this->container_box('widget_product product-slider-horizontal', $option);
        echo $box['before'];
        if (get_option('general_public_km')!=1){ ?>
	        <?php if($option->title_image != ''){?>
	        	<div class="header-title" style="padding-botom:2%">
	        		<?=get_img($option->title_image ,$this->name ) ?>
	        	</div>
	        <?php }else{ ?>
	        	<div class="header-title" style="padding-botom:2%">
	        		<h2 class="header"><?= $this->name;?></h2>
	        	</div>
	        <?php } ?>
        <?php }else{ ?>
        	<?php if($option->title_sales_image != ''){?>
	        	<div class="header-title" style="padding-botom:2%">
	        		<?=get_img($option->title_sales_image ,$option->title_sales ) ?>
	        	</div>
	        <?php }else{ ?>
	        	<div class="header-title" style="padding-botom:2%">
	        		<h2 class="header"><?= $option->title_sales;?></h2>
	        	</div>
	        <?php } ?>
        <?php } ?>
        <?php //BÀI VIẾT CHẠY NGANG
        if($option->pr_display_type == 0) $this->display_horizontal($products, $option, $args);
        //BÀI VIẾT DANH SÁCH
        if($option->pr_display_type == 1) $this->display_list($products, $option, $args);
        echo $box['after'];
	}
	function display_horizontal($products, $option, $args) {
		?>
		<div class="box-content" style="padding: 0 10px">
			<!--
				Thêm class sau để thay đổi vị trí: wg_pr_btn_top_right, wg_pr_btn_center
				Đổi class wg_pr_btn_style_1 thành wg_pr_btn_style_radius để sử dụng button tròn.
			-->
			<div class="wg_pr_btn wg_pr_btn_style_radius" id="wiget_product_btn_<?= $this->id;?>">
				<div class="prev"><i class="fal fa-chevron-left"></i></div>
				<div class="next"><i class="fal fa-chevron-right"></i></div>
			</div>
			<div id="widget_product_<?= $this->id;?>" class="owl-carousel">
				<?php foreach ($products as $key => $val): ?>
					<?php echo wcmc_get_template('loop/item_product2', array('val' =>$val));?>
				<?php endforeach ?>
			</div>
		</div>
		<script defer> 
			$(function(){
				var config = {
					items 				:<?= $option->pr_per_row;?>,
					margin				:<?= $option->pr_margin;?>,
					autoplayTimeout		:<?= $option->time*1000;?>,
					smartSpeed 			:<?= $option->speed*1000;?>,
					loop				:true, autoplay:true, autoplayHoverPause:true,
					responsive 			:{ 0	:{ items:2 },  599	:{ items:<?= $option->pr_per_row_mobile;?> },   768	:{ items:<?= $option->pr_per_row_tablet;?> },   1200:{ items:<?= $option->pr_per_row;?> } }
				}
				var ol = $("#widget_product_<?= $this->id;?>").owlCarousel(config);
				$('#wiget_product_btn_<?= $this->id;?> '+'.next').click(function() {
			    	ol.trigger('next.owl.carousel', [1000]);
				})
				$('#wiget_product_btn_<?= $this->id;?> '+' .prev').click(function() {
				    ol.trigger('prev.owl.carousel', [1000]);
				});
			});
		</script>
        <?php
	}
	function display_list($wg_products, $option, $args) {
		$option->pr_per_row_mobile = ($option->pr_per_row_mobile == 5)?15:(12/$option->pr_per_row_mobile);
		$option->pr_per_row_tablet = ($option->pr_per_row_tablet == 5)?15:(12/$option->pr_per_row_tablet);
		$option->pr_per_row        = ($option->pr_per_row == 5)?15:(12/$option->pr_per_row);
		?>
		<div class="box-content">
			<div id="widget_product_<?= $this->id;?>" class="list-item-product row">
				<?php foreach ($wg_products as $key => $val): ?>
					<div class="col-xs-<?php echo $option->pr_per_row_mobile;?> col-sm-<?php echo $option->pr_per_row_tablet;?> col-md-<?php echo $option->pr_per_row;?> col-lg-<?php echo $option->pr_per_row;?>">
						<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
					</div>
				<?php endforeach ?>
			</div>
		</div>
		<?php
	}
    function css() { include_once('assets/product-style-1.css'); }
}
if( class_exists('woocommerce') ) {
	register_widget('widget_product_style_1');
}
else {
	add_action('admin_notices', function() {
		echo notice('error', 'Để sử dụng widget <b>widget_product_style_1</b> vui lòng cài đặt plugin woocommerce');
	});
}