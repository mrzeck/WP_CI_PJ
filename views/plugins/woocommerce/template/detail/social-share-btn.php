<div class="social-block">
	<a href="javascript:;" onclick="window.open('http://www.facebook.com/sharer.php?u=<?= fullurl();?>', 'Chia sẽ sản phẩm này cho bạn bè', 'menubar=no,toolbar=no,resizable=no,scrollbars=no, width=600,height=455')" class="btn-socianetwork btn-facebook">
  		<?php echo get_img( base_url().WCMC_PATH.'assets/images/Facebook-Icon.png' ) ;?>
  	</a>

  	<a href="javascript:;" onclick="window.open('https://twitter.com/home?status=<?= fullurl();?>')" class="btn-socianetwork btn-twitter">
  		<?php echo get_img( base_url().WCMC_PATH.'assets/images/Twitter-Icon.png' ) ;?>
  	</a>

	<script src="https://sp.zalo.me/plugins/sdk.js"></script>
  	<div class="zalo-share-button" data-href="<?= fullurl();?>" data-oaid="3986611713288256895" data-layout="4" data-color="blue" data-customize=true>
  		<?php echo get_img( base_url().WCMC_PATH.'assets/images/Zalo-Icon.png' ) ;?>
  	</div>
	  
</div>