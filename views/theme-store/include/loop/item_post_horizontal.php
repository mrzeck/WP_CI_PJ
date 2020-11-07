<div class="item item-post-horizontal wow animated fadeIn">
	<div class="img">
		<a href="<?= get_url($val->slug);?>"><?= get_img($val->image, $val->title);?></a>
	</div>
	<div class="title">
		<h3 class="header"><a href="<?= get_url($val->slug);?>"><?= $val->title;?></a></h3>
		<div class="description"><?= str_word_cut(removeHtmlTags($val->excerpt), 30);?></div>
	</div>
</div>