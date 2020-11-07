<?php
	$object_id 		= (isset($object))?$object->id:0;
	$object_module 	= $module;
	if($module == 'post') $object_module .= '_'.$ci->post_type;
	if($module == 'post_categories') $object_module .= '_'.$ci->cate_type;
?>
<div class="object-gallery-action" style="overflow: hidden;">
    <div class="pull-right">
		<button id="object-gallery-btn-save" 	data-object-id="<?= $object_id;?>" data-object-module="<?= $object_module;?>" data-edit="0" class="btn-icon btn-green" type="button"><?php echo admin_button_icon('save');?>Lưu</button>
		<button id="object-gallery-btn-del" 	data-object-id="<?= $object_id;?>" data-object-module="<?= $object_module;?>" data-edit="0" class="btn-icon btn-red del-img" type="button"><?php echo admin_button_icon('delete');?>Xóa</button>
		<button id="object-gallery-btn-cancel" 	data-object-id="<?= $object_id;?>" data-object-module="<?= $object_module;?>" data-edit="0" class="btn-icon btn-white" type="button"><?php echo admin_button_icon('cancel');?>Hủy</button>
	</div>
</div>

