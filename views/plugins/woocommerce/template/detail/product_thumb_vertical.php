<?php $gallerys = gets_gallery( array( 'where' => array('object_id' => $object->id, 'object_type' => 'products')) );?>

<div class="row">
	<div class="col-md-push-2 col-lg-10 col-md-10 col-sm-10 col-xs-12 box-image-featured">
		<a href="<?php echo get_img_link($object->image);?>" data-fancybox="product-thumb">
			<?php get_img($object->image, '', array('class' => 'product-image-feature'));?>
		</a>
	</div>

	<div class="col-md-pull-10 col-lg-2 col-md-2 col-sm-2 col-xs-12 product-box-slide">
		<div class="product-thumb-vertical" id="sliderproduct" tyle="display:none">
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
</div>

<div class="clearfix"></div>

<style type="text/css" media="screen">
	#sliderproduct {
	    position: relative;
	}
	img {
	    max-width: 100%;
	}
</style>

<script type="text/javascript">
	$(function(){


		zoomConfig 	= { responsive: true, gallery:'sliderproduct', scrollZoom : true };

		zoomImage 	= $('.box-image-featured');

		zoomGallery = $('#list-product-thumb .product-thumb a');

		product_slider_vertical();

		$( window ).resize(function() {
			product_slider_vertical();
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

	function product_slider_vertical() {

		console.log($(window).width());

		if( $(window).width() > 740 ) {

			$(".product-image-feature").elevateZoom(zoomConfig);
		}
		else {

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
		}
	}

</script>