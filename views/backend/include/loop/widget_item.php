<li id="menuItem_<?= $val->id;?>" class="js_widget_item" style="display: list-item;" data-id="<?= $val->id;?>" data-key="<?= $val->widget_id;?>">
  	<div class="widget_sidebar">
  		<div class="action text-right">
	    	<a href="<?= $val->id;?>" class="icon-edit"><i class="fas fa-wrench"></i></a> &nbsp;&nbsp;
	    	<a href="<?= $val->id;?>" class="icon-copy"><i class="fal fa-clone"></i></a> &nbsp;&nbsp;
	    	<a href="<?= $val->id;?>" class="icon-delete"><i class="fal fa-trash-alt"></i></a>
	    </div>
	    <div class="title">
	    	<h3 class="widget-name"><?php if($val->name != $val->widget_name) { echo $val->widget_name.'</b>&nbsp;:&nbsp;'.$val->name; } else { echo $val->widget_name; } ?></h3>
	    	<p style="margin:0" class="widget-key"><?php echo $val->widget_id;?></p>
	    </div>
  	</div>
</li>