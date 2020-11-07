<div class="panel-group">
	<div class="panel panel-default">
		<div class="panel-heading" role="tab">
			<h4 class="panel-title pull-left"><a><?= $val->name;?></a></h4>
			<a href="<?= $val->id;?>" class="pull-right btn btn-red  btn-xs icon-delete color-white" style="margin: 3px 3px;"><?php echo admin_button_icon('delete');?></a>
			<a href="<?= $val->id;?>" class="pull-right btn btn-blue btn-xs icon-edit color-white" style="margin: 3px 0;"><?php echo admin_button_icon('edit');?></a>
			<p class="pull-right" style="padding:5px 20px;margin:0;text-transform:capitalize;"> <?= $val->type;?></p>
		</div>
	</div>
</div>
