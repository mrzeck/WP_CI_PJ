<?php
/** Lấy danh sách item của slider */
$model->settable('gallerys');

//Get all item
$items  = $model->gets_where(array('group_id' => $slider->id));

//Get item hiện tại
$item   = $model->get_where(array('id' => $id));

$option_item = ht_slider_item_options(array());

if(have_posts($item))
    $option_item = ht_slider_item_options($item->options);
?>

<!-- List item -->

<div class="col-md-12">
    <div class="box list-sliders">
        <!-- .box-content -->
        <div class="box-content" style="padding: 0;">
            <div class="box-list-sliders">
                <?php foreach ($items as $key => $value): ?>
                <?php
                    // $op = ht_slider_options($value->options);
                    $thumb = 'https://vignette.wikia.nocookie.net/animal-jam-clans-1/images/5/57/Transparent_Background.png';
                    // if(isset($op->type)) if($op->type == 'image') $thumb = get_img_link($value->value);
                ?>
                <a href="<?= HTSlider_URL;?>slider&slider=<?= $value->group_id;?>&id=<?= $value->id;?>">
                    <div class="item tls-addnewslider <?php echo ($id == $value->id)?'active':'';?>">
                        <span class="tls-firstslideimage mini-transparent" style="background-size: inherit; background-repeat: repeat;background-image:url('<?= $thumb;?>') "></span>
                        <span class="tls-title-wrapper"><span class="tls-title">#<?= $key + 1;?> slider</span></span>
                    </div>
                </a>
                <?php endforeach ?>

                <a href="#" data-id="<?= $slider->id;?>" id="add_slider_item">
                    <div class="item tls-addnewslider">
                        <span class="tls-new-icon-wrapper"><span class="slider_list_add_buttons add_new_slider_icon"></span></span>
                        <span class="tls-title-wrapper"><span class="tls-title">Add Item</span></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- /.box-content -->
    </div>
</div>
<!-- /List Item -->

<?php if(have_posts($item)) {?>

<?php ht_slider_libary();?>

<!-- Item Setting -->
<div class="col-md-12">
    <div class="box list-sliders">
        <!-- .box-content -->
        <div class="box-content" style="padding: 0;">
            <div style="overflow: hidden;padding:10px;">
                <div class="pull-right">
                    <button name="save" data-id="<?= $id;?>" class="btn-icon btn-green hvr-sweep-to-right" id="ht_slider_btn_save"><i class="fa fa-floppy-o"></i>Lưu</button>
                    <button name="del" data-id="<?= $id;?>" class="btn-icon btn-red hvr-sweep-to-right" id="ht_slider_btn_del"><i class="fa fa-floppy-o"></i>Xóa</button>
                </div>
            </div>
        </div>
        <!-- /.box-content -->
    </div>
</div>


<div class="col-md-12">
	<div role="tabpanel">
		<!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active" > <a href="#bg" role="tab" data-toggle="tab">Background</a> </li>
            <li class=""> <a href="#anima" role="anima" data-toggle="tab">Hiệu ứng</a> </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
        	<!-- Images -->
        	<div role="tabpanel" class="tab-pane active" id="bg">

                <div id="bg_type_image">
                    <?php $input = array('field' => 'value',   'label' => 'Chọn ảnh', 'value'=>$item->value,'type' => 'image'); ?>
                    <?= _form($input, $item->value);?>

                    <?php $input = array('field' => 'name',   'label' => 'Tiêu đề', 'value'=>$option_item->name,'type' => 'text'); ?>
                    <?= _form($input, $option_item->name);?>

                    <?php $input = array('field' => 'url',   'label' => 'Liên kết', 'value'=>$option_item->url,'type' => 'text'); ?>
                    <?= _form($input, $option_item->url);?>
                </div>

        	</div>



        	<!-- Animation -->

        	<div role="tabpanel" class="tab-pane" id="anima">

        		<div class="system">

        			<div class="system-tab"  style="padding:0;">

                        <ul class="nav nav-tabs" role="tablist" style="min-height: 416px;background-color: #f3f3f3;">

                            <li class="active"><a href="#Fade" data-toggle="tab">Flat Fade Transitions</a></li>

                            <li class=""><a href="#Zoom" data-toggle="tab">Flat Zoom Transitions</a></li>

                            <li class=""><a href="#Parallax" data-toggle="tab">Flat Parallax Transitions</a></li>

                            <li class=""><a href="#Slide" data-toggle="tab">Flat Slide Transitions</a></li>

                            <li class=""><a href="#Premium" data-toggle="tab">Premium Transitions</a></li>

                        </ul>

                    </div>

        		</div>



        		<section class="tool first" id="transitselector" style="float:left; width:25%;padding:0; min-height: 416px; position: relative; top:0;position: initial">

                    <div class="tooltext norightcorner long" id="mranim" style="cursor:pointer;display: none;">Fade</div>

                    <div class="transition-selectbox-holder">

                        <div class="transition-selectbox">

                            <div class="system-tab-content tab-content" style="float:none;">

                            	<?php $list_anim = slider_animation();?>

                                <?php foreach ($list_anim as $key => $data): ?>

                                    <div role="tabpanel" class="tab-pane <?= ($key == 'Fade')?'active':'';?>" id="<?= $key;?>">

                                    <ul>

                                        <li class="animchanger" data-anim="">Flat <?= $key;?> Transitions</li>

                                        <?php foreach ($data as $name => $value): ?>

                                        <li class="animchanger" data-anim="<?= $name;?>"><?= $value;?></li>

                                        <?php endforeach ?>

                                    </ul>

                                </div>

                                <?php endforeach ?>

                            </div>

                        </div>

                    </div>

                    <div class="clear"></div>

                </section>



                <div style="float:left; width:50%;">

                    <?php ht_slider_demo($option_item->data_slotamount, $option_item->data_masterspeed);?>

                    <div class="toolpad" style="padding-left: 20px;">

                        <p><strong>Hiệu ứng:</strong> <span id='resultanim'><?= $option_item->data_transition;?></span></p>

                        <p><strong>Số khe:</strong> <span id='resultslot'><?= $option_item->data_slotamount;?></span></p>

                        <p><strong>Thời gian hiệu ứng:</strong> <span id='resultspeed'><?= $option_item->data_masterspeed;?></span></p>

                    </div>

                </div>

        	</div>

        </div>

	</div>

</div>

<!-- /Item Setting -->

<?php } ?>





<style type="text/css" media="screen">

    .bg-settings-block {

        margin-bottom: 10px;

        line-height: 33px;

        color: #777;

        font-weight: 100;

    }

    .bg-settings-block label { font-weight: 100; font-size: 18px; }



    .bg-settings-detail-block { display: none; }

    .bg-settings-detail-block.active { display: block; }

    .transition-selectbox-holder {

        position: relative!important;

        top:0!important;

        opacity: 1!important;

        display: block!important;position: inherit!important;

        transform: none !important;

        padding: 0;

    }

    .transition-selectbox, .jspContainer, .jspPane {

        width: 100%!important;

    }

    .transition-selectbox {position: inherit!important;}

    .transition-selectbox-holder {

        border-radius:0;

        height: 414px;

        padding:10px;

    }

    .icheckbox_square-blue, .iradio_square-blue { background-color: transparent; }

    .toolpad { padding:20px 10px; border:none; overflow: hidden;}

    .toolpad .tool {

        width: 50%!important;

    }

    .toolcontroll { width: 34px; }

</style>