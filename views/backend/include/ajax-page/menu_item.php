<?php
function creat_tree_menu($ci, $trees = array()) {
  if(count($trees)) {
    echo '<ol class="mjs-nestedSortable-branch mjs-nestedSortable-expanded">';
    foreach ($trees as $key => $item) {
      echo '<li id="menuItem_'.$item->id.'" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">';
      echo $ci->load->view($ci->template->name.'/include/loop/menu_item_content',array('val' =>$item), true);
      if(have_posts($item->child))
        creat_tree_menu($ci, $item->child);
      echo '</li>';
    }
    echo "</ol>";
  }
}
?>

<ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded">
  <?php foreach ($items as $key => $val): ?>
    <li id="menuItem_<?= $val->id;?>" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">
      <?= $this->load->view($this->template->name.'/include/loop/menu_item_content',array('val' =>$val), true);?>
      <?= creat_tree_menu($ci, $val->child);?>
    </li>
  <?php endforeach ?>
</ol>