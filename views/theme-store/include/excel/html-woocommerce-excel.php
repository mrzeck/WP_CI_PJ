<div class="col-md-12">
	<?php echo $ci->template->get_message();?>
</div>

<div class="form col-md-4">
	<h4 class="header" style="color:#fff; padding:10px;">Xuất Đơn Hàng Ra File Excel</h4>
	<form method="post">
		<?php echo form_open(); ?>
		<?php
			$input = array( 'field' => 'date_start', 'label'	=> 'Từ Ngày', 'type'  => 'date' );
			echo _form( $input, '');

			$input = array( 'field' => 'date_end', 'label'	=> 'Đến Ngày', 'type'  => 'date' );
			echo _form( $input, '');
		?>
		<button type="submit" name="export" value="export" class="btn btn-success">Tạo File Đơn Hàng</button>
	</form>
</div>
<div class="form col-md-8">
	<h4 class="header" style="color:#fff; padding:10px;">Danh Sách File Download</h4>
	<div class="download">
	<?php
		foreach (glob('uploads/excel/donhang/*.xlsx') as $filename) {
		    ?>
		    <div class="row">
		    	<div class="col-md-7"> <a href="<?php echo base_url().$filename;?>" download><?php echo $filename;?></a> </div>
		    	<div class="col-md-5">
		    		<a href="<?php echo base_url().$filename;?>" download class="btn btn-blue">Download</a>
		    		<a href="admin/plugins?page=woocommerce_excel&del=<?php echo $filename;?>" style="color:red;" class="btn btn-red">Delete</a>
		    	</div>
		    </div>
		    <?php
		}
	?>
	</div>

	<style type="text/css">
		.download a { padding:10px;display: inline-block; }
	</style>
</div>


<style type="text/css">
	.page-content .page-body { padding-top: 10px; }
</style>