<form action="" id="tddq_form__receiving">
    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Hình thức nhận điểm</h4>

            <div class="checkbox">
                <label><input name="receiving_form" type="radio" value="1" <?php echo ($option['receiving_form'] == 1)?'checked':'';?>>Theo tổng số tiền khách đã chi tiêu</label>
            </div>

            <div class="checkbox">
                <label><input name="receiving_form" type="radio" value="2" <?php echo ($option['receiving_form'] == 2)?'checked':'';?>>Theo tổng số tiền đơn hàng</label>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Loại điểm</h4>

            <div class="checkbox">
                <label><input name="point_type" type="radio" value="1" <?php echo ($option['point_type'] == 1)?'checked':'';?>>Theo khoản giá</label>
            </div>

            <div class="checkbox">
                <label><input name="point_type" type="radio" value="2" <?php echo ($option['point_type'] == 2)?'checked':'';?>>Theo giá trị quy đổi</label>
            </div>
        </div>
    </div>
    
    <div class="box" id="receiving_point_type_range" style="display:<?php echo ($option['point_type'] == 1)?'block':'none';?>">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Khoản Tiền tính %</h4>
            <div id="tddq_setting_receiving__range__box">
            
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">từ</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">đến</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">% được nhận</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">#</label>
                    </div>
                </div>
                <div id="tddq_setting_receiving__range__content">
                    <?php if(!have_posts($option['receiving_range'])) {?>
                    <div class="row tddq_setting_receiving__range__item">
                        <div class="form-group col-md-3">
                            <input type="text" name="receiving_range[0][form]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" name="receiving_range[0][to]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" name="receiving_range[0][percent]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-3">
                            <button type="button" class="add btn btn-green">Thêm</button>
                        </div>
                    </div>
                    <?php } else {
                        foreach ($option['receiving_range'] as $key => $range) { ?>
                        <div class="row tddq_setting_receiving__range__item">
                            <div class="form-group col-md-3">
                                <input type="text" name="receiving_range[<?php echo $key;?>][form]" class="form-control" value="<?php echo $range['form'];?>" >
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" name="receiving_range[<?php echo $key;?>][to]" class="form-control" value="<?php echo $range['to'];?>" >
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" name="receiving_range[<?php echo $key;?>][percent]" class="form-control" value="<?php echo $range['percent'];?>" >
                            </div>
                            <div class="form-group col-md-3">
                                <?php if($key == 0) {?>
                                <button type="button" class="add btn btn-green">Thêm</button>
                                <?php } else { ?>
                                <button type="button" class="delete btn btn-red">Xóa</button>
                                <?php } ?>
                            </div>
                        </div>
                        <?php }
                    } ?>
                </div>
            
            </div> 
        </div>
    </div>

    <div class="box" id="receiving_point_type_conver" style="display:<?php echo ($option['point_type'] == 2)?'block':'none';?>">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Giá trị quy đổi</h4>
            <div id="tddq_setting_receiving__conver__box">
            
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">từ</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">đến</label>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">giá trị quy đổi</label>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">điểm quy đổi</label>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">#</label>
                    </div>
                </div>
                <div id="tddq_setting_receiving__conver__content">
                    <?php if(!have_posts($option['receiving_conver'])) {?>
                    <div class="row tddq_setting_receiving__conver__item">
                        <div class="form-group col-md-3">
                            <input type="text" name="receiving_conver[0][form]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" name="receiving_conver[0][to]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" name="receiving_conver[0][value]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" name="receiving_conver[0][point]" class="form-control" value="" >
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="add btn btn-green">Thêm</button>
                        </div>
                    </div>
                    <?php } else {
                        foreach ($option['receiving_conver'] as $key => $range) { ?>
                        <div class="row tddq_setting_receiving__conver__item">
                            <div class="form-group col-md-3">
                                <input type="text" name="receiving_conver[<?php echo $key;?>][form]" class="form-control" value="<?php echo $range['form'];?>" >
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" name="receiving_conver[<?php echo $key;?>][to]" class="form-control" value="<?php echo $range['to'];?>" >
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" name="receiving_conver[<?php echo $key;?>][value]" class="form-control" value="<?php echo $range['value'];?>" >
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" name="receiving_conver[<?php echo $key;?>][point]" class="form-control" value="<?php echo $range['point'];?>" >
                            </div>
                            <div class="form-group col-md-2">
                                <?php if($key == 0) {?>
                                <button type="button" class="add btn btn-green">Thêm</button>
                                <?php } else { ?>
                                <button type="button" class="delete btn btn-red">Xóa</button>
                                <?php } ?>
                            </div>
                        </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-content text-right" style="padding:10px 15px;">
            
            <button type="submit" class="btn btn-green">Lưu</button>
            
        </div>
    </div>
</form>
<script>
    $('input[name="point_type"]').change(function(){

        $('#receiving_point_type_range').hide();

        $('#receiving_point_type_conver').hide();

        if($(this).val() == 1) $('#receiving_point_type_range').show();

        if($(this).val() == 2) $('#receiving_point_type_conver').show();
    })

    var range = $('#tddq_setting_receiving__range__content');

    range.on( 'click', '.add', function(){

        var size = range.find('.tddq_setting_receiving__range__item').length;

        $('<div class="row tddq_setting_receiving__range__item">\
            <div class="form-group col-md-3">\
                <input type="text" name="receiving_range['+size+'][form]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-3">\
                <input type="text" name="receiving_range['+size+'][to]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-3">\
                <input type="text" name="receiving_range['+size+'][percent]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-3">\
                <button type="button" class="delete btn btn-red">Xóa</button>\
            </div>\
        </div>').appendTo('#tddq_setting_receiving__range__content');

        return false;
    });

    range.on( 'click', 'button.delete', function(){
        $(this).closest('.tddq_setting_receiving__range__item').remove();
        return false;
    });

    var conver = $('#tddq_setting_receiving__conver__content');

    conver.on( 'click', '.add', function(){

        var size = conver.find('.tddq_setting_receiving__conver__item').length;

        $('<div class="row tddq_setting_receiving__conver__item">\
            <div class="form-group col-md-3">\
                <input type="text" name="receiving_conver['+size+'][form]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-3">\
                <input type="text" name="receiving_conver['+size+'][to]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-2">\
                <input type="text" name="receiving_conver['+size+'][value]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-2">\
                <input type="text" name="receiving_conver['+size+'][point]" class="form-control" value="" >\
            </div>\
            <div class="form-group col-md-2">\
                <button type="button" class="delete btn btn-red">Xóa</button>\
            </div>\
        </div>').appendTo('#tddq_setting_receiving__conver__content');

        return false;
    });

    conver.on( 'click', 'button.delete', function(){
        $(this).closest('.tddq_setting_receiving__conver__item').remove();
        return false;
    });

    $(document).on('submit', '#tddq_form__receiving', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'tddq_ajax_config_save';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            show_message(data.message, data.status);
        });

        return false;
    });
</script>