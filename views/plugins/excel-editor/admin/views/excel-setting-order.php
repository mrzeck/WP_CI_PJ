<form action="" id="excel_form__order">
    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Các trường dữ liệu</h4>

            <?php $row = excel_order_row(); ?>

            <div class="row">
                <div class="form-group col-md-1">
                    <label for="">Bật/tắt</label>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Tiêu đề</label>
                </div>
                <div class="form-group col-md-1">
                    <label for="">Thứ tự</label>
                </div>
            </div>

            <?php foreach ($row as $key => $label) { $item = $config['order_row'][$key]; ?>
            <div class="row">
                <div class="form-group col-md-1">
                    <label><input name="order_row[<?php echo $key;?>][us]" type="checkbox" class="icheck" value="<?php echo $key;?>" <?php echo ($item['us'] == true)?'checked':'';?>>
                </div>
                <div class="form-group col-md-4">
                    <input name="order_row[<?php echo $key;?>][label]" type="text" class="form-control" value="<?php echo $item['label'];?>">
                </div>
                <div class="form-group col-md-1">
                    <input name="order_row[<?php echo $key;?>][order]" type="number" class="form-control" value="<?php echo $item['order'];?>">
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="box">
        <div class="box-content text-right" style="padding:10px 15px;">
            <button type="submit" class="btn btn-green">Lưu</button>
        </div>
    </div>
</form>

<script>
    $(document).on('submit', '#excel_form__order', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'excel_ajax_config_save';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            show_message(data.message, data.status);
        });

        return false;
    });
</script>