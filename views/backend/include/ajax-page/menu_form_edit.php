<link rel="stylesheet" href="scripts/fancybox-3.0/jquery.fancybox.css">
<script type="text/javascript" src="scripts/fancybox-3.0/jquery.fancybox.js" charset="utf-8"></script>
<div class="col-md-12 form-group">
	<label>Tiêu đề</label>
	<input type="text" name="name" class="form-control" value="<?= $val->name;?>" required="required">
</div>
<!-- option all -->
<?php
$val->data 		= unserialize($val->data);
$option_all 	= get_menu_option();
$option_object 	= get_menu_option($val->type, $val->object_type);
$option 		= array_merge($option_all, $option_object);


if(have_posts($option)) {
	foreach ($option as $key => $value) {
		$v = isset($val->data[$key])?$val->data[$key]:'';
		echo _form($value, $v);
	}
}
?>


<?php if($val->type == 'link'){ ?>
<div class="col-md-12 form-group">
    <label>Url</label>
    <input type="text" name="url" class="form-control" value="<?= $val->slug;?>">
</div>
<?php } ?>

