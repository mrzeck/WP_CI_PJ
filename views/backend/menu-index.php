<?= $this->template->render_include('action_bar');?>

<?php if(isset($menu) && have_posts($menu)) {?>
<div class="col-md-4 menu">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php foreach ($this->list_object as $obj_key => $obj) { ?>
        <div class="panel panel-default">
            <!-- title data item -->
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$obj_key;?>_box"><?=$obj['label'];?></a>
                </h4>
            </div>
            <!-- /title data item -->
            <div id="<?=$obj_key;?>_box" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">
                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#<?=$obj_key;?>_all" role="tab" data-toggle="tab">Tất cả</a></li>
                            <li><a href="#<?=$obj_key;?>_search" role="tab" data-toggle="tab">Tìm kiếm</a></li>
                        </ul>
                        <!-- /Nav tabs -->

                        <div class="tab-content">
                            <!-- danh sách dữ liệu -->
                            <div role="tabpanel" class="tab-pane active" id="<?=$obj_key;?>_all">
                                <?php if(have_posts($obj['data'])){?>
                                    <?php foreach ($obj['data'] as $key => $val): ?>
                                        <?php
                                            $id     = $val->id;
                                            $value  = (isset($val->title))?$val->title:$val->name;
                                        ?>
                                        <div class="checkbox">
                                            <label> <input name="<?=$obj['type'];?>" type="checkbox" value="<?=$id;?>" class="icheck"> &nbsp;<?= $value ;?> </label>
                                        </div>
                                    <?php endforeach ?>
                                <?php } ?>
                            </div>
                            <!-- tìm kiếm -->
                            <div role="tabpanel" class="tab-pane" id="<?=$obj_key;?>_search">
                                <div class="form-group">
                                    <div class="col-sm-10" style="padding:0;">
                                        <input name="search" data-object="<?= $obj['type'];?>" data-object-type="<?=$obj_key;?>" type="text" class="form-control" placeholder="Tìm kiếm">
                                    </div>
                                    <label class="col-sm-2 control-label">
                                        <div class="loading"></div>
                                    </label>
                                </div>
                                <div class="result_search"></div>
                            </div>
                        </div>
                    </div>
                    <div style="padding:10px 0;">
                        <button class="btn-add-field btn btn-blue pull-left" menu-id="<?= $menu->id;?>" data-type="<?=$obj_key;?>">Thêm vào menu</button>
                        <div class="loading pull-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- đường link -->
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#link_box">Liên kết</a>
                </h4>
            </div>
            <div id="link_box" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Url</label>
                            <div class="col-sm-9">
                                <input name="url" type="url" class="form-control" value="" placeholder="http://">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tiêu đề</label>
                            <div class="col-sm-9">
                                <input name="title" type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div style="padding:10px 0;">
                        <button class="btn-add-field btn btn-blue pull-left" menu-id="<?= $menu->id;?>" data-type="link">Thêm vào menu</button>
                        <div class="loading pull-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /link -->
    </div>
</div>

<div class="col-md-5 col-show">
    <div class="box">
        <div class="header"><h3 class="pull-left"><?= $menu->name;?></h3></div>
        <div class="box-content">
            <div class="col-md-12">
                <?php admin_loading_icon('ajax_loader');?>
                <label>Cấu trúc menu</label>
                <div id="sortMenu" class="sort-menu" data-id="<?php echo $menu->id;?>">
                    <ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded">
                        <?php foreach ($items as $key => $val): ?>
                        <li id="menuItem_<?= $val->id;?>" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">
                            <?= $this->load->view($this->template->name.'/include/loop/menu_item_content',array('val' =>$val), true);?>
                            <?= created_admin_tree_menu($val->child);?>
                        </li>
                        <?php endforeach ?>
                    </ol>
                </div>
                <label>Vị Trí Hiển Thị</label>
                <?php foreach ($this->navigation as $key => $value): ?>
                    <div class="checkbox">
                        <label>
                            <input class="icheck menu-position" type="checkbox" value="<?= $key; ?>" data-id="<?= $menu->id;?>" <?php echo (in_array($key, $relationships) !== false)?'checked':'';?>> <?= $value; ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="col-md-3 col-hide">
    <div class="box">
        <div class="header"><h3 class="pull-left">Cập Nhật Menu</h3></div>
        <div class="box-content" id="modal-edit-menu">
            <form id="form-menu-edit">
                <div class="result"></div>
                <button type="button" class="btn btn-default btn-close">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
    <div class="clearfix"> </div>
</div>

<?php } ?>

<!-- popup thêm menu -->
<div style="display: none; padding:10px; min-width: 250px;" id="hidden-content">
    <?php $input[] = array('field' => 'name', 'label' => 'Tên menu', 'value'=>'','type' => 'text'); ?>
    <?php foreach ($input as $key => $field): ?>
        <div class="col-md-12"> <h4>THÊM MENU</h4> </div>
        <form id="form-menu-group">
            <?= _form($field);?>
            <div class="clearfix"></div>
            <div class="text-right">
                <button class="btn-icon btn-green add-fast"><i class="fa fa-save"></i>Lưu</button>
            </div>
        </form>
    <?php endforeach ?>
</div>

<style>
    .col-hide { 
        top: 0px;
        position: -webkit-sticky;
        position: sticky;
    }
    .col-hide .box { position: fixed; width: 20%; }
</style>
<script defer>
    $(function() {
        $('.menu .panel-collapse').first().addClass('in');
        $('#list_menu').change(function () {
            window.location ="<?= current_url();?>?id="+$(this).val()+""; 
            return true;
        });
    });
</script>