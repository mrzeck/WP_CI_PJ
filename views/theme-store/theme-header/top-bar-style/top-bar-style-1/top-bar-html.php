<div class="top-bar" id="top-bar">
	<div class="container">
		<div class="row">
			<?php if (get_option('general_public_km')!=1){ ?>
				<?php if (get_option('top_bar_bg_image')!=null): ?>
					<?=get_img(get_option('top_bar_bg_image'),'topbar banner') ?>
				<?php endif ?>
			<?php }else{ ?>
				<?php if (get_option('top_bar_bg_image_km')!=null){ ?>
					<?=get_img(get_option('top_bar_bg_image_km'),'topbar banner') ?>
				<?php }else{ ?>
					<?php if (get_option('top_bar_bg_image')!=null): ?>
						<?=get_img(get_option('top_bar_bg_image'),'topbar banner') ?>
					<?php endif ?>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>