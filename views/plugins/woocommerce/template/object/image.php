<div class="img">
	<picture>
	    <source media="(min-width: 900px)" srcset="<?= get_img_link($val->image, 'source');?>">
	    <source media="(max-width: 480px)" srcset="<?= get_img_link($val->image, 'source');?>">
	    <?= get_img($val->image,$val->title, array(), 'medium');?>
	</picture>
</div>