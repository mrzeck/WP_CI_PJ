<div id="<?php echo $widget->key;?>">
<?php
$after  ='<div class="col-md-8" style="padding:0;">';
$center = '</div><div class="col-md-4" style="padding:0">';
$before = '</div>';

$input_name = _form(array('field' => 'name', 'label'=> 'Tiêu đề', 'type' => 'text'), $widget->name);

if(function_exists($widget->after)) {
    $run = $widget->after;
    $run();
}

//không có left
if(!have_posts($widget->left) || !have_posts($widget->right)) {
    $after  ='<div class="col-md-12" style="padding:0;">';
    $center = '';
}

?>
<div class="header widget_header">
    <h2 class="pull-left"><?= $widget->name;?></h2>

    <div class="pull-right text-right">
        <button type="submit" name="save" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
        <button type="button" class="btn btn-default btn-close">Đóng</button>
    </div>
</div>
<?php
echo $after;

if(have_posts($widget->left)) {

    echo $input_name; $input_name = '';

    foreach ($widget->left as $key => $val) {
        echo _form($val, $widget->option[$val['field']]);
    }
}
echo $center;
if(have_posts($widget->right)) {
    echo $input_name;
    foreach ($widget->right as $key => $val) {
        echo _form($val, $widget->option[$val['field']]);
    }
}
echo $before;
//nếu function tồn tại
if(function_exists($widget->before)) {
    $run = $widget->before;
    $run();
}
elseif(method_exists($widget, $widget->before)) {
    $widget->{$widget->before}();
}
else echo $widget->before;
?>
</div>