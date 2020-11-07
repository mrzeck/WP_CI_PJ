<?php
    $point = (int)get_user_meta($user->id, 'tddq_point', true);

    $history_limit = 10;

    $args = [
        'where' => [
            'user_id' => $user->id,
        ]
    ];

    $total = count_affiliate_history($args);

    //Phân trang
    $url        = base_url('tai-khoan/accumulate-points-withdrawal?paging={paging}');

    $pagination = pagination($total, $url, $history_limit);

    $params = array(
        'limit'  => $history_limit,
        'start'  => $pagination->getoffset(),
        'orderby'=> 'order, created desc',
    );
    
    $args['params'] = $params;

    $historys = affiliate_history($args);
?>

<script>
   function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    show_message('Copy thành công.','success');
    $temp.remove();
}
</script>
<div class="col-md-12 tddq-my-account">

    <!-- <h4 class="user-header-title">QUẢN LÝ ĐIỂM THƯỞNG CỦA BẠN</h4> -->

    <!-- <div class="box">
        <div class="box-content account-point text-center">
            <div>
                <img src="https://salt.tikicdn.com/desktop/img/account/tiki-xu.svg" alt="">
                <span class="big"> <?php echo number_format($point);?></span>
            </div>
            <p>Bạn có <span><?php echo number_format($point);?></span> điểm trong tài khoản của bạn.</p>
        </div>
    </div> -->

    <h4 class="user-header-title mg-t-20">TẠO LIÊN KẾT CHIA SẺ </h4>
    
    <form action="" id="affiliate__form" autocomplete="off">
        <div class="box">
            <div class="box-content account-withdrawal-form text-left">
                <div>
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Link cần tạo:</label>
                        <div class="col-sm-9">
                            <input type="url" name="link" class="form-control" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Mở đầu mã </br>(cao nhất 5 kí tự):</label>
                        <div class="col-sm-3">
                            <input type="text" name="code" class="form-control" maxlength = "5" value="" required >
                        </div>
                    </div>

                   
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label"></label>

                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary">Tạo liên kết</button>
                        </div>
                    </div>
                        
                    
                </div>
            </div>
        </div>
    </form>

    <h4 class="user-header-title mg-t-20">Danh sách link chia sẻ</h4>

    <div class="box pd-0">
        <div class="box-content account-history">
            
            <table class="">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Thể loại</th>
                        <th>Thời gian</th>
                        
                        <th>Lấy link chia sẻ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historys as $key => $item) { ?>
                    
                    <tr class="account-history account-history-sttatus-<?php echo $item->status;?>">
                        <td><span><?php echo get_img($item->image,$item->title,array('style'=>'height:50px;'));?></span></td>
                        <td><a href="<?=$item->url; ?>?cookie-id='<?php echo $item->cookie;?>'"><?php echo $item->title;?></a></td>
                        <td>
                            <?=$item->type ?>                             
                        </td>
                        <td><?php echo $item->created;?></td>
                        <td>
                        	<p id="input<?=$item->id?>" class='hidden'><?=$item->url; ?>?cookie-id=<?php echo $item->cookie;?></p>
                        	<button onclick="copyToClipboard('#input<?=$item->id?>')">Copy link</button>
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
    .jq-icon-success{font-size:16px;}
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
    $(document).on('submit', '#affiliate__form', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'tddq_ajax_affiliate';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            if (typeof $.toast !== "undefined") {
                show_message(data.message, data.status);
            }
            else {
                alert(data.message);
            }

            if(data.status == 'success') {
                $('#affiliate__form').trigger("reset");
                setTimeout(function(){  window.location.reload(); }, 3000);
               

            }
        });

        return false;
    });
</script>
