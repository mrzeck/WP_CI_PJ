<?php
if( empty($_SESSION['cms_version'])) {
	$_SESSION['cms_version'] 		= $ci->service_api->cms_version();
	$_SESSION['cms_version_time'] 	= time();
}
;?>
<div class="col-md-12">
    <div class="box">
        <div class="header"> <h2>Cập nhật website </h2> </div>
        <!-- .box-content -->
        <div class="box-content">
			<div class="col-md-12">
				<p>Kiểm tra lần cuối <?php echo date('d-m-Y H:i', $_SESSION['cms_version_time'] );?> </p>
				<?php admin_notices();?>
				<?php if ( version_compare( $_SESSION['cms_version'], cms_info('version') ) === 1 ): ?>
					<?php echo notice('warning', '<b>Quan Trọng:</b> trước khi cập nhật, hãy <b style="color:red">sao lưu dữ liệu và các tập tin</b> của bạn.');?>
					<h3>Phiên bản CMS mới đã săn sàng.</h3>
					<form action="<?php echo URL_ADMIN;?>/home/update_core?action=do-upgrade-core" method="post">
						<?php echo form_open(); ?>
						<input type="hidden" name="_submit_update_core" value="<?php echo $_SESSION['_submit_update_core'];?>">
						<button type="submit" class="btn btn-blue btn-icon" style="margin-bottom: 10px; padding:20px"><i class="fal fa-cloud-upload"></i> Cập nhật ngay bây giờ</button>
					</form>
					<p>Trong khi trang của bạn đang được cập nhật, nó sẽ ở chế độ bảo trì. Ngay khi cập nhật xong, trang của bạn sẽ trở lại bình thường.</p>
				<?php else: ?>
					<?php echo notice('success', 'CMS của bạn hiện đang là phiên bản mới nhất');?>
				<?php endif ?>
			</div>
        </div>
        <!-- /.box-content -->
    </div>
</div>