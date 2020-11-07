<li id="menuItem_<?= $val->id;?>" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">
	<?= $ci->load->view($ci->template->name.'/include/loop/menu_item_content',array('val' =>$val), true);?>
</li>