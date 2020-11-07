<?php
    $point = (int)get_user_meta($user->id, 'tddq_point', true);

    $history_limit = 10;

    $args = [
        'where' => [
            'user_id' => $user->id,
            'type'  => 'withdrawal',
        ]
    ];

    $total = count_tddq_history($args);

    //Phân trang
    $url        = base_url('tai-khoan/accumulate-points-withdrawal?paging={paging}');

    $pagination = pagination($total, $url, $history_limit);

    $params = array(
        'limit'  => $history_limit,
        'start'  => $pagination->getoffset(),
        'orderby'=> 'order, created desc',
    );
    
    $args['params'] = $params;

    $historys = gets_tddq_history($args);
?>
<div class="col-md-12 tddq-my-account">

    <h4 class="user-header-title">QUẢN LÝ ĐIỂM THƯỞNG CỦA BẠN</h4>

    <div class="box">
        <div class="box-content account-point text-center">
            <div>
                <img src="https://salt.tikicdn.com/desktop/img/account/tiki-xu.svg" alt="">
                <span class="big"> <?php echo number_format($point);?></span>
            </div>
            <p>Bạn có <span><?php echo number_format($point);?></span> điểm trong tài khoản của bạn.</p>
        </div>
    </div>

    <h4 class="user-header-title mg-t-20">TẠO YÊU CẦU RÚT TIỀN </h4>
    
    <form action="" id="account_withdrawal__form">
        <div class="box">
            <div class="box-content account-withdrawal-form text-left">
                <div>
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Số điểm cần rút:</label>
                        <div class="col-sm-9">
                            <input type="number" name="point" class="form-control" value="" required>
                            <p class="note">Số điểm thấp nhất 100 điểm.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Tài khoản nhận tiền:</label>
                        <div class="col-sm-9">
                            <textarea name="bank" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Ghi chú:</label>
                        <div class="col-sm-9">
                            <textarea name="content" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label"></label>

                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary">gửi yêu cầu</button>
                        </div>
                    </div>
                        
                    
                </div>
            </div>
        </div>
    </form>

    <h4 class="user-header-title mg-t-20">YÊU CẦU RÚT ĐIỂM THƯỞNG</h4>

    <div class="box pd-0">
        <div class="box-content account-history">
            
            <table class="">
                <thead>
                    <tr>
                        <th>Điểm rút</th>
                        <th>Số tiền</th>
                        <th>Thời gian</th>
                        <th>Nội dung</th>
                        <th>Ngân hàng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historys as $key => $item) { ?>
                    
                    <tr class="account-history-sttatus-<?php echo $item->status;?>">
                        <td><span><?php echo $item->point;?></span></td>
                        <td><span><?php echo number_format($item->point*$item->point_conver);?></span></td>
                        <td><?php echo $item->created;?></td>
                        <td><?php echo $item->content;?></td>
                        <td><?php echo $item->bank;?></td>
                        <td class="status"><?php echo tddq_history_status($item->status)['label'];?></td>
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

    .tddq-my-account .account-point>div {
        margin-bottom: 10px;
    }
    .tddq-my-account .account-point>div>img {
        vertical-align: middle;
        margin-right: 5px;
    }
    .tddq-my-account .account-point>div>span.big {
        color: #41d67e;
        font-weight: 700;
        margin-left: 4px;
        font-size: 36px;
        vertical-align: middle;
    }

    .tddq-my-account .account-withdrawal-form{ overflow:hidden;}
    .tddq-my-account .account-withdrawal-form .form-group{
        margin-bottom:10px;
        overflow:hidden;
    }
    .tddq-my-account .account-withdrawal-form .form-group p.note {
        margin-top:5px; font-size:12px; color:#c4c4c4;
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
        padding: 15px 15px;
        color: #242424;
        vertical-align: top;
        min-width: 110px;
        font-size:12px;
    }
    .tddq-my-account .account-history table tr.account-history-sttatus-success td.status {
        color:green; font-weight:500;
    }
    .tddq-my-account .account-history table tr.account-history-sttatus-cancel td.status {
        color:red; font-weight:500;
    }
</style>

<script>
    $(document).on('submit', '#account_withdrawal__form', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'tddq_ajax_account_withdrawal';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            if (typeof $.toast !== "undefined") {
                show_message(data.message, data.status);
            }
            else {
                alert(data.message);
            }

            if(data.status == 'success') {
                $('#account_withdrawal__form').trigger("reset");
            }
        });

        return false;
    });
</script>