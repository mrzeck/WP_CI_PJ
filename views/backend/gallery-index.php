<?php $this->template->render_include('action_bar');?>
<?php if(have_posts($gallerys)) {?>
<?php $gallery_active = ((int)$this->input->get('id') != 0)?(int)$this->input->get('id'):$gallerys[0]->id;?>
<div class="gallery">
	<div class="col-xs-12 col-md-3" style="padding-left: 0">
		<div class="gallery-list">
			<a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox="" data-src="#hidden-content"><i class="fal fa-plus"></i>Thêm Thể Loại</a>
			<ul class="root-list">
				<?php foreach ($gallerys as $key => $val): ?>
					<li class="">
						<a href="<?php echo admin_url('gallery?id='.$val->id);?>" data-id="<?php echo $val->id;?>" class="group-gallery <?=($val->id == $gallery_active)?'active':'';?>">
							<label><i class="fas fa-folder"></i> <span class="gl-number"><?php echo count_gallery(['where' =>['group_id' => $val->id]]);?></span></label>
							<span><?= $val->name;?></span>
						</a>
						<?php if( current_user_can('delete_gallery') ) { ?>
							<button class="btn-icon btn-red delete" data-id="<?php echo $val->id;?>" type="button"><i class="fal fa-trash"></i></button>
						<?php } ?>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	<div class="col-xs-12 col-md-6 gallery-item">
		<div class="gallery-item__action">
			<button class="gallery-check-all" data-id="<?= $object->id;?>" type="button"><i class="fas fa-check-circle"></i></button>
			<button class="gallery-del-img disabled-item" data-id="<?= $object->id;?>" type="button"><i class="fa fa-trash"></i></button>
		</div>
		<!-- loading -->
		<?php admin_loading_icon();?>
		<ul id="js_object_gallery_sort">
			<?php foreach ($gallery_item as $key => $item): ?>
			<?php $this->template->render_include('loop/gallery_item', array('val' => $item));?>
			<?php endforeach ?>
		</ul>
	</div>

	<div class="col-xs-12 col-md-3 gallery-form" style="padding:0;">
		<div class="gallery-item__action" style="margin-bottom:0;">
		</div>
		<form id="form-gallery" data-add="<?= $object->id;?>" data-edit="0">

			<div class="img-thumbnail js_gallery_review" style="display: block;">
				<div class="camera-container iframe-btn" data-fancybox="iframe" data-id="value" href="<?php echo base_url();?>scripts/rpsfmng/filemanager/dialog.php?type=2&amp;subfolder=&amp;editor=mce_0&amp;field_id=value&amp;callback=gallery_responsive_filemanager_callback">
					<i class="fas fa-file-edit"></i>
				</div>
				<div class="camera-container-link">
					<i class="fal fa-link"></i>
					
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Liên kết">
						<div class="input-group-addon">Xong</div>
					</div>
					
				</div>
				<!-- loading -->
				<?php admin_loading_icon();?>
			</div>

			<?= $this->template->render_include('ajax-page/gallery_form', $this->data);?>
			
			<div class="clearfix"></div>
			
			<hr />

			<div class="box-content text-right">
				<button type="submit" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
				<button type="reset" class="btn-icon btn-default">Hủy</button>
			</div>
		</form>

	</div>
</div>

<?php } else {?>
<div class="col-md-5 box-empty">
  	<h2>Bạn chưa tạo bất kỳ gallery nào</h2>
  	<h4>Bạn có thể tạo gallery để có thể tải lên hình ảnh, video hay các tài liệu khác!</h4>
  	<a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox="" data-src="#hidden-content"><i class="fa fa-plus-square"></i>Thêm Gallery</a>
</div>

<div class="col-md-7"><img src="//cdn.shopify.com/s/assets/admin/empty-states-fresh/emptystate-files-45034fe342d1a46109a82c6b91b6e46b99efd5580585721a2f57a784860f49ff.svg" alt="Emptystate files"></div>
<style type="text/css">
	.box-empty { margin-top: 50px; }
	.box-empty h2 { font-size: 30px; font-weight: bold; }
	.box-empty h4 { font-size: 18px; line-height: 2.8rem;  font-weight: 400; color: #637381; }
</style>
<?php }?>


<!-- popup thêm menu -->
<div style="display: none; padding:10px; min-width: 250px;" id="hidden-content">
    <?php $input[] = array('field' => 'name',   'label' => 'Tên Gallery', 'value'=>'','type' => 'text'); ?>
    <div class="col-md-12"> <h4>THÊM GALLERY</h4> </div>
    <form id="form-gallery-group">
        <?php foreach ($input as $key => $field): ?>
        <?= _form($field);?>
        <?php endforeach ?>
        <div class="col-md-12 text-right">
            <button class="btn-icon btn-blue add-fast"><i class="fa fa-plus-square"></i>Thêm menu</button>
        </div>
    </form>
</div>