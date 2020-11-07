<?php
    $point = (int)get_user_meta($user->id, 'tddq_point', true);

    $history_limit = 10;

    $args = [
        'where' => [
            'user_id' => $user->id,
            'type'  => 'history',
        ]
    ];

    $total = count_tddq_history($args);

    //Phân trang
    $url        = base_url('tai-khoan/accumulate-points?paging={paging}');

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

    <h4 class="user-header-title mg-t-20">LỊCH SỬ ĐIỂM THƯỞNG</h4>

    <div class="box pd-0">
        <div class="box-content account-history">
            
            <table class="">
                <thead>
                    <tr>
                        <th>Điểm thưởng</th>
                        <th>Thời gian</th>
                        <th>Nội dung</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historys as $key => $item) { ?>
                    
                    <tr>
                        <?php if($item->point>0) {?>
                        <td><span>+<?php echo $item->point;?></span></td>
                        <?php } else { ?>
                        <td><span style="color:red"><?php echo $item->point;?></span></td>
                        <?php } ?>
                        <td><?php echo $item->created;?></td>
                        <td><?php echo $item->content;?></td>
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
        padding: 10px 15px;
        color: #242424;
        vertical-align: top;
        min-width: 110px;
        font-size:12px;
    }
</style>