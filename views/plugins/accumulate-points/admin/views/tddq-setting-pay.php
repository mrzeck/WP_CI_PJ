<form action="" id="tddq_form__pay">
    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Hình thức sử dụng điểm thưởng</h4>

            <div class="checkbox">
                <label><input name="pay_type[]" type="checkbox" value="1" <?php echo (tddq_pay_type_check(1))?'checked':'';?>>Thanh toán cho đơn hàng</label>
            </div>

            <div class="checkbox">
                <label><input name="pay_type[]" type="checkbox" value="2" <?php echo (tddq_pay_type_check(2))?'checked':'';?>>Đổi điểm thành tiền</label>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-content" style="padding:10px 15px;">
            <h4>Giá trị quy đổi điểm thưởng</h4>

            <div class="form-group">
                <label>1 điểm đổi thành</label>
                <input type="number" name="point_conver" class="form-control" value="<?php echo $option['point_conver'];?>">
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
    $(document).on('submit', '#tddq_form__pay', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'tddq_ajax_config_save';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            show_message(data.message, data.status);
        });

        return false;
    });
</script>