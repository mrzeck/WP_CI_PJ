<div class="wg_pr_btn wg_pr_btn_top_right wg_pr_btn_style_1" id="widget_related_products_btn">
	<div class="prev"><i class="fal fa-chevron-left"></i></div>
	<div class="next"><i class="fal fa-chevron-right"></i></div>
</div>
<div id="widget_related_products" class="owl-carousel">
	<?php foreach ($related_products as $key => $val): ?>
		<?php echo wcmc_get_template('loop/item_product', array('val' =>$val));?>
	<?php endforeach ?>
</div>
<script defer>
	$(document).ready(function(){
		var ol_related = $("#widget_related_products").owlCarousel({
			items:'<?php echo $columns;?>',
			margin:10,
			loop:true,
			autoplay:true,
			autoplayTimeout:2000,
			autoplayHoverPause:true,
			responsive:{
				0	:{ items:2 }, 400	:{ items:2 }, 600	:{ items:3 }, 1000:{ items:'<?php echo $columns;?>' }
			}
		});

		$('#widget_related_products_btn '+'.next').click(function() {
			ol_related.trigger('next.owl.carousel', [1000]);
		})
		$('#widget_related_products_btn '+' .prev').click(function() {
			ol_related.trigger('prev.owl.carousel', [1000]);
		});
	});
</script>