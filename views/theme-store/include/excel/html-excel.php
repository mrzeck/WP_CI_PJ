
<div class="col-md-12">
	<?php echo $ci->template->get_message();?>
</div>

<div class="form col-md-12">
	<h4 class="header" style="color:#fff; padding:10px;">Thêm Sản phẩm Bằng File Excel</h4>
	<form method="post">
		<?php echo form_open(); ?>
		<?php
			$input = array( 'field' => 'excel', 'label'	=> 'File Excel', 'type'  => 'file' );
			echo _form( $input, '');
		?>
		<button type="submit" name="add" value="add" class="btn btn-success">Thêm Sản Phẩm</button>
		<a href="uploads/file-mau-sanpham.xlsx" class="btn btn-blue" download>Downloan File Excel Mẫu</a>
	</form>
</div>

<!-- <div class="form col-md-4">
	<h4 class="header" style="color:#fff; padding:10px;">Xuất Sản Phẩm Ra File Excel</h4>
	<form method="post">
		<?php echo form_open(); ?>
		<?php
			$categories = wcmc_gets_category_mutilevel_option();

			$input = array( 'field' => 'category', 'label'	=> 'Danh Mục', 'type'  => 'select', 'options' => $categories );
			echo _form( $input, '');
		?>
		<button type="submit" name="export" value="export" class="btn btn-success">Tạo File Sản Phẩm</button>
	</form>
	<div class="download">
	<?php
		foreach (glob('uploads/excel/*.xlsx') as $filename) {
		    ?>
		    <div class="row">
		    	<div class="col-md-7"> <a href="<?php echo base_url().$filename;?>" download><?php echo $filename;?></a> </div>
		    	<div class="col-md-5">
		    		<a href="<?php echo base_url().$filename;?>" download class="btn btn-blue">Download</a>
		    		<a href="admin/plugins?page=products_excel&del=<?php echo $filename;?>" style="color:red;" class="btn btn-red">Delete</a>
		    	</div>
		    </div>
		    <?php
		}
	?>
	</div>

	<style type="text/css">
		.download a { padding:10px;display: inline-block; }
	</style>
</div> -->
<!-- 
<div class="form col-md-6">
	<h4 class="header" style="color:#fff; padding:10px;">Cập Nhật Sản Phẩm Bằng File Excel</h4>
	<form method="post">
		<?php echo form_open(); ?>
		<?php
			$input = array( 'field' => 'excel_update', 'label'	=> 'File Excel', 'type'  => 'file' );
			echo _form( $input, '');
		?>
		<button type="submit" name="update" value="update" class="btn btn-success">Cập Nhật Sản Phẩm</button>
	</form>
</div>
 -->


<style type="text/css">
	.page-content .page-body { padding-top: 10px; }
</style>
<script>
	
</script>