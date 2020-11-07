<div class="text-center page-404">
	<div class="col-md-7">
		<h1>Trang không được tìm thấy</h1>
		<br />
		<p>Thật tiếc! Trang của bạn yêu cầu không tồn tại.</p>
		<p>Trang bạn đang tìm kiếm có thể đã bị xóa, chuyển đi, thay đổi link hoặc chưa bao giờ tồn tại.</p>
		<br />
		<br />
		<div class="row">
			<div class="item col-md-6">
				<i class="icon icon-1"></i>
				<div>
					<a href="<?php echo base_url();?>">Trang chủ</a>
					<span>Giúp bạn trả lời mọi thứ</span>
				</div>
			</div>
			<div class="item col-md-6">
				<i class="icon icon-3"></i>
				<div>
					<a href="<?php echo get_url('lien-he');?>">Liên hệ với chúng tôi</a>
					<span>Hotline tư vấn dịch vụ: <a href="tel:<?php echo get_option('contact_phone');?>"><?php echo get_option('contact_phone');?></a></span>
				</div>
			</div>
		</div>
		<br />
		<br />
	</div>
	<div class="col-md-4 text-center">
		<a href="<?php echo base_url();?>">
			<?php get_img('404.gif');?>
		</a>
	</div>
</div>

<style>
	.page-404 h1 { font-weight:bold; font-size:40px; }
	.page-404 p { font-size: 16px; font-weight: 400; color: #707070; }
	.page-404 .item {
		border-right: solid 1px #ebebeb;
		display: flex;
    	align-items: flex-start;
		text-align:left;
	}
	.page-404 .item .icon {
		width: 20px; height: 20px; margin-right: 10px; flex-shrink: 0; margin-top: 3px;
	}
	.page-404 .item .icon.icon-1 {
		background: url('<?php echo get_img_link('404_network.png');?>') no-repeat center center;
	}
	.page-404 .item .icon.icon-2 {
		background: url('<?php echo get_img_link('404_money.png');?>') no-repeat center center;
	}
	.page-404 .item .icon.icon-3 {
		background: url('<?php echo get_img_link('404_phone.png');?>') no-repeat center center;
	}
	.page-404 .item a {
		font-size: 18px;
		font-weight: 700;
		color: #454545;
		margin-bottom: 10px;
	}
	.page-404 .item span {
		font-size: 14px;
		font-weight: 400;
		color: #707070;
		display: block;
	}
</style>