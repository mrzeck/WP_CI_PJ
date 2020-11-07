<?php
	$banner_setting  = get_theme_layout_setting('banner');

	$breadcrumb = theme_get_breadcrumb();

	$image_banner = get_img_link(get_option('banner_img'));

	$name = '';

	if($this->template->method == 'index') {
		if(isset($category) && have_posts($category)) {
			$name = $category->name;
		}
		else if(is_page('products_index')) {
			$name = __('Sản phẩm');
		}
	}
	else {
		if(isset($object) && have_posts($object)) {
			$name = $object->title;
		}
	}
?>

<div class="box-bg-top" style="background:url(<?php echo $image_banner;?>);background-size: cover;">
	<div class="box-bg-title">
		<div class="title">
			<h1 class="header wow animated fadeInDown"><?= $name;?></h1>
			<div class="wow animated fadeInUp"><?php echo Breadcrumb($breadcrumb);?></div>
		</div>
	</div>
</div>

<style type="text/css">
	.box-bg-top {
		min-height: <?php echo $banner_setting['height'];?>px; position: relative; text-align: center;
		margin-bottom:10px;
	}
	.box-bg-top .box-bg-title {
	    background-color: rgba(0,0,0,0.5); text-align: center;
	    height: 100%; width: 100%;
	    position: absolute; left: 0; top: 0; color: #fff;
	}
	.box-bg-top .box-bg-title .title {
	    position: relative;  top: 50%; transform: translateY(-50%);
	}

	.box-bg-top .box-bg-title .title h1.header { text-align: center; font-size: 40px; color:#fff; }

	.box-bg-top .btn-breadcrumb>.btn{ border:0; border-radius:0;background-color:transparent;color:#fff; }

	.warper { padding-top: 0px;}

</style>