<?php
function add_dashboard_admin_widget() {

	if( is_super_admin() ) {
		add_dashboard_widget('site_about', 'Chức năng', 'dashboard_about', array('col' => 12));
	}
	add_dashboard_widget('adv_cle', 'Liên Kết Quảng Cáo', 'dashboard_adv_cle', array('col' => 3));
    add_dashboard_widget('dashboard_new_post', 'BÀI VIẾT MỚI NHẤT', 'dashboard_new_post', array('col' => 6));    
    add_dashboard_widget('dashboard_calendar', 'Lịch', 'dashboard_calendar', array('col' => 3));
}

function dashboard_site_info() {

	$model = get_model('home', 'backend');

	$col = 4;

	$post     = count_post();

	$page     = count_page();

	$category = count_post_category();

	if( class_exists('woocommerce')) {

		$col = 3;

		$product = count_product();
	}
	?>
	<div class="row" style="margin-bottom: 20px;">
		<div class="col-xs-6 col-md-<?php echo $col;?>">
			<div class="box-info">
				<i class="fal fa-passport"></i>
				<h4><?php echo $post;?></h4>
				<p>Tổng bài viết</p>
				
			</div>
		</div>
		<div class="col-xs-6 col-md-<?php echo $col;?>">
			<div class="box-info box-info-category">
				<i class="fal fa-newspaper"></i>
				<h4><?php echo $category;?></h4>
				<p>Tổng danh mục</p>
			</div>
		</div>
		<div class="col-xs-6 col-md-<?php echo $col;?>">
			<div class="box-info box-info-page">
				<i class="fal fa-book"></i>
				<h4><?php echo $page;?></h4>
				<p>Tổng trang nội dung</p>
			</div>
		</div>
		<?php if( class_exists('woocommerce')) { ?>
		<div class="col-xs-6 col-md-<?php echo $col;?>">
			<div class="box-info box-info-product">
				<i class="fab fa-product-hunt"></i>
				<h4><?php echo $product;?></h4>
				<p>Tổng sản phẩm</p>
			</div>
		</div>
		<?php } ?>
	</div>

	<style type="text/css">
		.box-info {
			padding: 20px; color: #424242; font-weight: 400;
			/*background-image: -webkit-linear-gradient(left, #4facfe 0%, #00f2fe 100%);
			background-image: -o-linear-gradient(left, #4facfe 0%, #00f2fe 100%);
			background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);*/
			border-radius: 5px;
			text-align: center;
		}

		.box-info h4 { font-size: 30px; }
		.box-info i { font-size: 30px; }
		/*.box-info-category { background-image: linear-gradient(to right, #43e97b 0%, #38f9d7 100%); }*/
		/*.box-info-page { background-image: linear-gradient(to top, #cd9cf2 0%, #f6f3ff 100%); }*/
		/*.box-info-product { background-image: linear-gradient(to top, #ff0844 0%, #ffb199 100%); }*/
	</style>
	<?php
}

function dashboard_new_post( $widget ) {

	$posts = gets_post(array('params' => array('limit' => 10)));

    ?>
    <div class="box">
		<div class="header"> <h2><?= $widget['title'];?></h2> </div>
		<div class="box-content">
			<table class="table table-bordered">
				<tbody>
					<?php foreach ($posts as $item): ?>
					<tr>
						<td><?php echo $item->title;?></td>
						<td><span style="border-radius: 30px; background-color: #ccc; padding:2px 10px;"><?php echo date('H:i', strtotime($item->created));?></span></td>
						<td><?php echo date('d/m/Y', strtotime($item->created));?></td>
					</tr>
					<?php endforeach ?>
					
				</tbody>
			</table>
		</div>
	</div>
    
    <?php
}

function dashboard_adv_cle( $widget ) {
	?>
	<div class="box">
		<div class="header"> <h2><?= $widget['title'];?></h2> </div>
		<div class="box-content">
			<div class="col-md-12">
			</div>
		</div>
	</div>
	<?php
}

function dashboard_calendar( $widget ) {
	?>
	<div class="box">
		<div class="header"> <h2><?= $widget['title'];?></h2> </div>
		<div class="box-content">
			<div class="datepicker-here" data-language='vi'></div>
		</div>
	</div>
	<style type="text/css">
		.datepicker-inline { width: 100%!important; }
		.datepicker-inline .datepicker { border:0; width: 100%; }
	</style>
	<?php
}

add_action('cle_dashboard_setup', 'add_dashboard_admin_widget');