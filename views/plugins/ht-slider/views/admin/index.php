<?php
    $model->settable('group');
    $sliders = $model->gets_where(array('object_type' => 'ht-slider'));
?>



<div class="col-md-12">

    <div class="box list-sliders">

        <!-- .box-content -->

        <div class="box-content" style="padding: 0;">

            <h2 class="header" style="margin-top: 0;">HT Slider</h2>

            <div class="box-list-sliders">

                <?php foreach ($sliders as $key => $value): ?>

                <a href="<?= HTSlider_URL;?>slider&slider=<?= $value->id;?>">

                    <div class="item tls-addnewslider">

                        <span class="tls-firstslideimage mini-transparent" style="background-size: inherit; background-repeat: repeat;;background-image:url(https://vignette.wikia.nocookie.net/animal-jam-clans-1/images/5/57/Transparent_Background.png) "></span>

                        <span class="tls-title-wrapper"><span class="tls-title"><?= $value->name;?></span></span>

                    </div>

                </a>

                <?php endforeach ?>

                <a href="javascript:;" data-fancybox="" data-src="#hidden-content">

                    <div class="item tls-addnewslider">

                        <span class="tls-new-icon-wrapper"><span class="slider_list_add_buttons add_new_slider_icon"></span></span>

                        <span class="tls-title-wrapper"><span class="tls-title">New Slider</span></span>

                    </div>

                </a>

            </div>

        </div>

        <!-- /.box-content -->

    </div>

</div>



<!-- popup thêm menu -->

<div style="display: none; padding:10px; min-width: 250px;" id="hidden-content">

    <?php $input[] = array('field' => 'name',   'label' => 'Tên Slider', 'value'=>'','type' => 'text'); ?>

    <?php $input[] = array('field' => 'options', 'label' => 'ID Slider', 'value'=>'','type' => 'text'); ?>

    <div class="col-md-12"> <h4>THÊM SLIDER</h4> </div>

    <form id="form-sliders-group">

        <?php foreach ($input as $key => $field): ?>

        <?= _form($field);?>

        <?php endforeach ?>

        <div class="col-md-12 text-right">

            <button class="btn-icon btn-blue add-fast"><i class="fa fa-plus-square"></i>Thêm</button>

        </div>

    </form>

</div>