<div class="col-md-12 tddq-my-account">
    <h4 class="user-header-title mg-t-20">YÊU CẦU RÚT ĐIỂM THƯỞNG</h4>
    <div class="box pd-0">
        <div class="box-content account-history">
            <table class="">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Điểm hiện có</th>
                        <th>Rút</th>
                        <th>Thời gian</th>
                        <th>Nội dung</th>
                        <th>Ngân hàng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historys as $key => $item) { $customer = get_user($item->user_id); ?>
                    <tr class="js_tddq__item <?php echo ($item->status == 'success')?'js_tddq__item_success':'';?>" data-id="<?php echo $item->id;?>">
                        <td>
                            <strong><?php echo $customer->firstname.' '.$customer->lastname;?></strong>
                            <p><?php echo $customer->email;?></p>
                        </td>
                        <td class="js_account_point"><?php echo get_user_meta($customer->id, 'tddq_point', true);?></td>
                        <td>
                            <strong><span><?php echo number_format($item->point);?> điểm</span></strong>
                            <p><span style="color:red"><?php echo number_format($item->point*$item->point_conver);?>đ</span></p>
                        </td>
                        <td><?php echo $item->created;?></td>
                        <td><?php echo $item->content;?></td>
                        <td style="white-space: pre-line"><?php echo $item->bank;?></td>
                        <td class="js_account_status"><?php echo tddq_history_status($item->status)['label'];?></td>
                        <td>   
                            <?php if($item->status == 'wait') {?>
                            <button class="btn btn-green js_tddq_success">Đã chuyển</button>
                            <button class="btn btn-red js_tddq_remove">Hủy yêu cầu</button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- paging -->
            <div class="paging">
                <div class="pull-right"><?= (isset($pagination)&&is_object($pagination))?$pagination->html():'';?></div>
            </div>
            <!-- paging -->
        </div>
    </div>
</div>
<style>
    .mg-t-10 { margin-top:10px; }
    .mg-t-20 { margin-top:20px; }
    .pd-0 { padding:0px!important; }
    .tddq-my-account .box {
        border:1px dashed #ccc;
        padding:10px; 
    }
    .tddq-my-account span {
        color: #41d67e;
        font-weight: 700;
        margin-left: 4px;
        font-size: 13px;
        vertical-align: middle;
    }
    .tddq-my-account .account-history table {
        width:100%;
    }
    .tddq-my-account .account-history table tr th{
        border:0;
        border-bottom:1px solid #f4f4f4;
    }
    .tddq-my-account .account-history table tr th, .tddq-my-account .account-history table tr td{
        border:0;
        border-bottom:1px solid #f4f4f4;
        padding:10px;
    }
    .tddq-my-account .account-history table tr td {
        position: relative;
        display: table-cell;
        padding: 20px 15px;
        color: #242424;
        vertical-align: top;
        min-width: 110px;
        font-size:12px;
    }
    .tddq-my-account .account-history table tr.js_tddq__item_success td.js_account_status { color:green; }
    .tddq-my-account .account-history table tr.js_tddq__item_success td.js_account_cancel { color:red; }
</style>
<script>
    $('.js_tddq_success').bootstrap_confirm_delete({
        heading:'Xác nhận hoàn thành',
        message:'Bạn xác nhận đã chuyển khoản thành công hoàn thành yêu cầu này ?',
        callback:function ( event ) {
            var button = event.data.originalObject;
            var box = button.closest('.js_tddq__item');
            var id = box.attr('data-id');
            var data = {
                'action' : 'tddq_ajax_account_withdrawal_success',
                'id' : id
            };
            $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');
            $jqxhr.done(function( data ) {
                show_message(data.message, data.status);
                if(data.status == 'success') {
                    box.addClass('js_tddq__item_success');
                    box.find('.js_account_point').html(data.account_point);
                    box.find('.js_account_status').html('Hoàn thành');
                    box.find('.js_tddq_success').remove();
                    box.find('.js_tddq_remove').remove();
                }
            });
        },
    });
    $('.js_tddq_remove').bootstrap_confirm_delete({
        heading:'Xác nhận hủy yêu cầu',
        message:'Bạn xác nhận hủy yêu cầu chuyển khoản ?',
        callback:function ( event ) {
            var button = event.data.originalObject;
            var box = button.closest('.js_tddq__item');
            var id = box.attr('data-id');
            var data = {
                'action' : 'tddq_ajax_account_withdrawal_cancel',
                'id' : id
            };
            $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');
            $jqxhr.done(function( data ) {
                show_message(data.message, data.status);
                if(data.status == 'success') {
                    box.addClass('js_tddq__item_cancel');
                    box.find('.js_account_status').html('Đã hủy');
                    box.find('.js_tddq_success').remove();
                    box.find('.js_tddq_remove').remove();
                }
            });
        },
    });
</script>