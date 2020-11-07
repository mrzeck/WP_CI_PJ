<div class="item wow animated fadeInDown">
	<div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 img">
		<a href="<?= get_url($val->slug);?>"><?= get_img($val->image, $val->title);?></a>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-8 col-lg-8 title">
		<div class="post-time">
			<i class="far fa-calendar"></i> <?= date("d/m/Y", strtotime($val->created));?>
		</div>
		<h3 class="header"><a href="<?= get_url($val->slug);?>"><?= $val->title;?></a></h3>
		<div class="description"><?= removeHtmlTags($val->excerpt);?></div>
		<!-- <a class="readmore btn effect-hover-guong clear-fix" href="<?= get_url($val->slug);?>" role="button">Xem tiếp <span class="fa fa-angle-double-right"></span></a> -->
		<a href="<?= get_url($val->slug);?>" class="btn-effect">
		  	<svg width="200" height="62">
			    <defs>
			        <linearGradient id="grad1">
			            <stop offset="0%" stop-color="#FF8282"/>
			            <stop offset="100%" stop-color="#E178ED" />
			        </linearGradient>
			    </defs>
		     	<rect x="5" y="5" rx="25" fill="none" stroke="url(#grad1)" width="190" height="50"></rect>
		  	</svg>
		    <span>Xem Thêm <i class="fa fa-angle-double-right"></i></span>
		</a>
	</div>
</div>
<div class="cleart"></div>