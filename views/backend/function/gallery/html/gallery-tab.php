<div class="col-md-12 object-gallery-items">
	<ul id="js_object_gallery_sort" style="list-style: none;">
		<?php foreach ($items as $key => $val): ?>
			<?php get_template_file('function/gallery/html/object_gallery_item',array('val'=>$val));?>
		<?php endforeach ?>
		<a href='#' class="col-xs-6 col-sm-3 col-md-3 add_gallery_item_box" id="js_object_gallery_btn_add_item">
			<span class="add_gallery_item_icon"><i class="fal fa-plus"></i></span>
		</a>
	</ul>
</div>


<div class="modal fade" id="modal_add_object_gallery_item">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="object-gallery-form">
					<?php get_template_file('function/gallery/html/gallery-form');?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-icon btn btn-white" data-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
				<button id="object-gallery-btn-save" data-object-id="<?= $object_id;?>" data-object-module="<?= $object_module;?>" data-edit="0" class="btn-icon btn-green" type="button"><?php echo admin_button_icon('save');?>Lưu</button>
			</div>
		</div>
	</div>
</div>