<?php $gallerys = gets_gallery( array( 'where' => array('object_id' => $object->id, 'object_type' => 'products')) );?>

<div class="box-image-featured">
	<a href="<?php echo get_img_link($object->image);?>" data-fancybox="product-thumb">
		<?php get_img($object->image, '', array('class' => 'product-image-feature'));?>
	</a>
</div>

<div class="product-box-slide">
	<div class="product-thumb-horizontal" id="sliderproduct">
		<ul id="list-product-thumb" class="owl-carousel">
			<li class="product-thumb">
		        <a href="<?php echo get_img_link($object->image);?>" data-fancybox='product-thumb' data-image="<?php echo get_img_link($object->image);?>" class="zoomGalleryActive">
		            <?php get_img($object->image, '', array(), 'medium');?>
		        </a>
		    </li>
			<?php foreach ($gallerys as $key => $image): ?>
			<li class="product-thumb">
		        <a href="<?php echo get_img_link($image->value);?>" data-fancybox='product-thumb' data-image="<?php echo get_img_link($image->value);?>">
		            <?php get_img($image->value, '', array(), 'medium');?>
		        </a>
		    </li>
			<?php endforeach ?>
		</ul>
	</div>
</div>

<div class="clearfix"></div>

<style type="text/css" media="screen">
	img {
	    max-width: 100%;
	}
</style>

<script type="text/javascript">
	$(function(){

		zoomConfig 	= { responsive: true, gallery:'sliderproduct', scrollZoom : true };

		zoomImage 	= $('.box-image-featured');

		zoomGallery = $('#list-product-thumb .product-thumb a');

		$("#list-product-thumb").owlCarousel({
	        items:4,
	        margin:10,
	        loop:true,
	        autoplay:true,
	        autoplayTimeout:2000,
	        autoplayHoverPause:true,
	        responsive:{
	            0   :{ items:4 }, 400   :{ items:4 }, 600   :{ items:4 }, 1000:{ items:4 }
	        }
	    });

		zoomGallery.hover(function() {

			if( $(window).width() > 740 ) {
				//Remove
				$('.zoomContainer').remove();

				zoomImage.removeData('elevateZoom');

				zoomGallery.removeClass('zoomGalleryActive');


				//Add
				zoomImage.find('.product-image-feature').attr('src', $(this).attr('data-image'));

				zoomImage.data('zoom-image', $(this).attr('data-image'));

				zoomImage.elevateZoom(zoomConfig);

				$(this).addClass('zoomGalleryActive');
			}
			
		})
	});
</script>