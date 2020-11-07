<?php
$layout 		= get_theme_layout();

// $layout_setting = get_theme_layout_setting();

if(isset($layout['banner'])) {
	if($layout['banner'] == 'in-content') $this->template->render_include('banner');
}
else {
	$breadcrumb = theme_get_breadcrumb();
	?>
	<?php echo Breadcrumb($breadcrumb);?>
	<h1 class="header text-left"><?= $object->title;?></h1>
	<style>
		h1.header { text-align:left;}
		.btn-breadcrumb a.btn.btn-default {
			color: #000;    line-height: 37px;
		}
	</style>
	<?php
}
?>
<div class="object-detail">
	<?php if(have_posts($object)) {?>
		<div class="info" style="overflow: hidden;">
			<div class="pull-left">
				<div class="block"> <span class="post-time"> <i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($object->created));?></span> </div>
				<?php if( isset($category->name) ) : ?>
				<div class="block"> <span class="post-time"> <i class="fas fa-file-alt"></i> <?php echo $category->name;?></span> </div>
				<?php endif;?>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="excerpt"><?php echo $object->excerpt;?></div>
		<!-- content -->
		<div class="object-detail-content">
			<?php the_content();?>
		</div>
		
		<script src="https://sp.zalo.me/plugins/sdk.js"></script>
		<div class="td-post-sharing td-post-sharing-bottom td-with-like">
			<span class="td-post-share-title">Chia sẻ</span>
            <div class="td-default-sharing">
	            <a class="td-social-sharing-buttons td-social-facebook" href="http://www.facebook.com/sharer.php?u=<?= fullurl();?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="fab fa-facebook-f"></i><div class="td-social-but-text">Facebook</div></a>
	            <a class="td-social-sharing-buttons td-social-twitter" href="https://twitter.com/intent/tweet?text=<?php echo $object->title;?>&amp;url=<?= fullurl();?>"><i class="fab fa-twitter"></i><div class="td-social-but-text">Twitter</div></a>
	            <a class="td-social-sharing-buttons td-social-pinterest" href="http://pinterest.com/pin/create/button/?url=<?= fullurl();?>&amp;media=<?php echo get_img_link($object->image);?>&amp;description=<?php echo removeHtmlTags($object->excerpt);?>&amp;" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;"><i class="fab fa-pinterest-p"></i></a>
	            <a class="td-social-sharing-buttons td-social-zalo">
					<div class="zalo-share-button" data-href="<?= fullurl();?>" data-oaid="3986611713288256895" data-layout="4" data-color="blue" data-customize=true>
						<?php echo get_img( 'Zalo-Icon.png' ) ;?>
					</div>
				</a>
			</div>
			<div class="td-classic-sharing">
				<ul>
					<li class="td-classic-facebook">
						<iframe frameborder="0" src="http://www.facebook.com/plugins/like.php?href=<?= fullurl();?>&amp;layout=button_count&amp;show_faces=false&amp;width=105&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:105px; height:21px; background-color:transparent;"></iframe>
					</li>
					<li class="td-classic-twitter">
						<iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" style="position: static; visibility: visible; width: 60px; height: 20px;" title="Twitter Tweet Button" src="https://platform.twitter.com/widgets/tweet_button.2e9f365dae390394eb8d923cba8c5b11.en.html#dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=<?= fullurl();?>F&amp;size=m&amp;text=<?= $object->title;?>&amp;time=<?= time();?>&amp;type=share&amp;url=<?= fullurl();?>" data-url="<?= fullurl();?>"></iframe> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</li>
				</ul>
			</div>
		</div>

		<?php 
			$args = array(
				'where' => array('public' => 1, 'trash' => 0, 'post_type' => $object->post_type),
				'params' => array( 'limit' => 6 ),
				'related' => $object->id
			);

			// Get visble related products then sort them at random.
			$args['related_post'] = gets_post( $args );
		?>
		<div class="related_post">
			<h3 class="header text-left" style="text-align: left;">BÀI VIẾT LIÊN QUAN</h3>
			<div class="row">
				<?php foreach ($args['related_post'] as $key => $related): ?>
				<div class="col-md-6">
					<div class="item">
						<div class="img effect-hover-zoom">
							<a href="<?= get_url($related->slug);?>"><?php get_img($related->image, $related->title);?></a>
						</div>
						<div class="title">
							<h3><a href="<?= get_url($related->slug);?>"><?= $related->title;?></a></h3>
							<div class="description">
								<?php echo removeHtmlTags(str_word_cut($related->excerpt, 20));?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	<?php } ?>
</div>

<style type="text/css">
	.btn-breadcrumb .btn-default {
	    border: 0;
	    padding: 0 5px;
	}
	.btn-breadcrumb span { float: left; }
	.btn-breadcrumb .btn{ border:0; border-radius:0;background-color:transparent; }

	.td-post-sharing-bottom {
		border: 1px solid #ededed;
		padding: 10px 26px;
		margin-bottom: 40px;
		margin-top: 40px;
	}
	.td-post-share-title {
		font-weight: 700;
		font-size: 14px;
		position: relative;
		margin-right: 20px;
		vertical-align: middle;
	}
	.td-default-sharing {
		display: inline-block;
		vertical-align: middle;
	}
	.td-social-sharing-buttons {
		font-size: 11px;
		color: #fff;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		padding: 10px 13px 8px 13px;
		margin-right: 10px;
		height: 40px;
		min-width: 40px;
		text-align: center;
	}

	.td-social-sharing-buttons img { height:30px;}

	.td-post-sharing-bottom .td-social-sharing-buttons {
		-webkit-border-radius: 0;
		-moz-border-radius: 0;
		border-radius: 0;
		height: 32px;
		min-width: 32px;
		padding: 6px 9px 4px 9px;
	}

	[class*="td-icon-"] {
		line-height: 1;
		text-align: center;
		display: inline-block;
	}

	.td-social-facebook .td-icon-facebook {
		font-size: 14px;
		position: relative;
		top: 1px;
	}
	.td-social-but-text {
		display: inline-block;
		position: relative;
		top: -1px;
		line-height: 16px;
		padding-left: 10px;
		margin-left: 12px;
	}
	.td-social-facebook .td-social-but-text {
		border-left: 1px solid rgba(255, 255, 255, 0.1);
	}
	.td-classic-sharing {
		display: inline-block;
		vertical-align: middle;
	}

	.td-post-sharing a {
		display: inline-block;
		vertical-align: middle;
	}

	.td-social-zalo {
		padding-top:0!important;
	}

	.td-social-facebook {
		background-color: #516eab;
	}

	.td-social-twitter {
		background-color: #29c5f6;
	}
	.td-social-pinterest {
		background-color: #ca212a;
		margin-right: 0;
	}
	.td-classic-sharing ul {
		margin: 0 0 0 30px;
		height: 20px;
	}
	.td-classic-sharing li {
		display: inline-block;
		height: 20px;
		margin-left: 0;
	}

	.td-classic-sharing li img{
		height: 20px;
	}
</style>