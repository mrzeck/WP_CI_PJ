<?php
	if( !have_posts($active) ) {
		$active = array( 'fbm-send-message' => 0, 'fbm-live-chat' => 0, 'fbm-tab' => 0);
	}
?>

<div class="col-md-4">
	<div class="fbm-style">
		<div class="thumbie">
			<?php get_img(base_url().FBM_PATH.'/assets/images/fbm-send-message.png');?>
		</div>
		<div class="action text-center">
			<button type="button" class="btn-fbm-active button <?= ($active['fbm-send-message'] ==1)?'active':'';?>" data-id="fbm-send-message"><?= ($active['fbm-send-message'] ==1)?'Đang Sử Dụng':'Sử Dụng';?></button>
			<div class="fountainG">
				<div class="loading-item fountainG_1"></div>
				<div class="loading-item fountainG_2"></div>
				<div class="loading-item fountainG_3"></div>
				<div class="loading-item fountainG_4"></div>
				<div class="loading-item fountainG_5"></div>
				<div class="loading-item fountainG_6"></div>
				<div class="loading-item fountainG_7"></div>
				<div class="loading-item fountainG_8"></div>
			</div>
		</div>

	</div>
</div>

<div class="col-md-4">
	<div class="fbm-style">
		<div class="thumbie">
			<p>FACEBOOK FANPAGE TAB</p>
			<?php get_img(base_url().FBM_PATH.'/assets/images/fbm-tab.png');?>
		</div>
		<div class="action text-center">
			<button type="button" class="btn-fbm-active button <?= ($active['fbm-tab'] ==1)?'active':'';?>" data-id="fbm-tab"><?= ($active['fbm-tab'] ==1)?'Đang Sử Dụng':'Sử Dụng';?></button>
			<div class="fountainG">
				<div class="loading-item fountainG_1"></div>
				<div class="loading-item fountainG_2"></div>
				<div class="loading-item fountainG_3"></div>
				<div class="loading-item fountainG_4"></div>
				<div class="loading-item fountainG_5"></div>
				<div class="loading-item fountainG_6"></div>
				<div class="loading-item fountainG_7"></div>
				<div class="loading-item fountainG_8"></div>
			</div>
		</div>
	</div>
</div>