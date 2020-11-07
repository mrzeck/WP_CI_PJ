<div class="wg-item">
	<div class="img">
		<a href="<?php echo $item->image;?>" data-fancybox="gallery-<?php echo $item->id;?>">
			<?php get_img($item->image);?>
		</a>
	</div>
	<div class="title">
		<h3 class="widget-name"><?php echo $item->title;?></h3>
		<p class="author"><?php echo $item->author;?></p>
		<div class="action">
			<button type="button" class="wg-install btn-green btn btn-block" data-url="<?php echo $item->id;?>"><i class="fal fa-cloud-download"></i> Download</button>
		</div>
	</div>
	<div class="wg-item__overlay"></div>
</div>