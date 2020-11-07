<div class="ui-layout customer-sections">
    <div class="col-md-12">
        <div class="ui-title-bar__group">
            <div class="ui-title-bar__action">
                <a class="btn btn-default" href="<?php echo admin_url('plugins?page=customers&view=detail&id='.$customer->id);?>">
                    <i class="fal fa-chevron-left"></i> Quay lại
                </a>
            </div>
            <h1 class="ui-title-bar__title"><?php echo $customer->firstname.' '.$customer->lastname;?></h1>
            <div class="ui-title-bar__action">
            </div>
        </div>
    </div>
</div>
<div class="ui-layout customer-sections">
    <div class="col-md-8">
        <div class="box">
            <div class="box-content">
                <div class="customer-profile">
                    <div class="customer-profile__avatar">
                        <?php get_img('https://yt3.ggpht.com/-tcGz0UiyfkE/AAAAAAAAAAI/AAAAAAAAAAA/XkN5ucCEyBg/w800-h800/photo.jpg');?>
                    </div>
                    <div class="customer-profile__name">
                        <h3><?php echo $customer->firstname.' '.$customer->lastname;?></h3>
                        <p><?php echo get_user_meta($customer->id, 'address', true);?></p>
                    </div>
                </div>
                <section class="ui-layout__section">
                    <div class="account-point text-center">
                        <div>
                            <img src="https://salt.tikicdn.com/desktop/img/account/tiki-xu.svg" alt="">
                            <span class="big"> <?php echo number_format($point);?></span>
                        </div>
                        <p>Khách hàng <?php echo $customer->firstname.' '.$customer->lastname;?> có <span><?php echo number_format($point);?></span> điểm trong tài khoản.</p>
                    </div>
                </section>
            </div>
        </div>
        <div class="box">
            <div class="box-content">
                <section class="ui-layout__section account-history">
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
                </section>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-content">
                <section class="ui-layout__section">
                    <header class="ui-layout__title"><h2>THAO TÁC</h2></header>
                </section>
                <section class="ui-layout__section">
                    <button type="button" data-fancybox data-src="#customer-point-plus" class="btn btn-green btn-block">Thêm điểm</button>
                    <button type="button" data-fancybox data-src="#customer-point-out" class="btn btn-red btn-block">Rút điểm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .ui-layout {
        box-sizing: border-box;
        max-width: 1100px;
        margin-right: auto;
        margin-left: auto;
        font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
    }
    .ui-layout .box {
        border-radius: 3px;
        -webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
        box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    }
    section.ui-layout__section {
        padding:20px;
    }
    section.ui-layout__section+section.ui-layout__section {
        border-top: 1px solid #dfe4e8;
    }
    section.ui-layout__section~section.ui-layout__section {
        border-top: 1px solid #ebeef0;
    }
    section.ui-layout__section header.ui-layout__title h2 {
        font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
        -webkit-box-flex: 1;
        -webkit-flex: 1 1 auto;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-width: 0;
        max-width: 100%;
        display:inline-block;
    }
    .customer-profile { padding:20px; overflow:hidden; }
    .customer-profile .customer-profile__avatar {
        float:left; width:60px; height:50px; border-radius:50%; overflow:hidden; padding-right:10px;
    }
    .customer-profile .customer-profile__avatar img{
        width:50px; height:50px; border-radius:50%;
    }
    .customer-profile .customer-profile__name {
        float:left; width:calc(100% - 60px);
    }
    .customer-profile .customer-profile__name h3 {
        font-size: 15px;
        font-weight: 600;
        line-height: 2.4rem;
        margin:0;
    }
    .customer-profile .customer-profile__name p {
        color: #637381;
    }
    .account-point>div {
        margin-bottom: 10px;
    }
    .account-point>div>img {
        vertical-align: middle;
        margin-right: 5px;
    }
    .account-point>div>span.big {
        color: #41d67e;
        font-weight: 700;
        margin-left: 4px;
        font-size: 36px;
        vertical-align: middle;
    }
    .account-history table {
        width:100%;
    }
    .account-history table tr th{
        border:0;
        border-bottom:1px solid #f4f4f4;
    }
    .account-history table tr th, .tddq-my-account .account-history table tr td{
        border:0;
        border-bottom:1px solid #f4f4f4;
        padding:10px;
    }
    .account-history table tr td {
        position: relative;
        display: table-cell;
        padding: 20px 15px;
        color: #242424;
        vertical-align: top;
        min-width: 110px;
        font-size:12px;
    }
</style>
<div style="display: none;" id="customer-point-plus" class="customer-point">
    <h2>Cập Nhật Thông Tin Liên Hệ</h2>
    <form action="" autocomplete="off" id="customer-point-plus__form">
        <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">
        <div class="group">
            <label for="">Điểm +</label>
            <input name="point" type="number" class="form-control" value="" required>
        </div>
        <div class="group">
            <label for="">Lý do (ghi chú)</label>
            <textarea name="note" class="form-control" rows="3" required></textarea>
        </div>
        <div class="ghtk-order-created__footer">
            <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
        </div>
    </form>
</div>
<div style="display: none;" id="customer-point-out" class="customer-point">
    <h2>Cập Nhật Thông Tin Liên Hệ</h2>
    <form action="" autocomplete="off" id="customer-point-out__form">
        <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">
        <div class="group">
            <label for="">Điểm +</label>
            <input name="point" type="number" class="form-control" value="" required>
        </div>
        <div class="group">
            <label for="">Lý do (ghi chú)</label>
            <textarea name="note" class="form-control" rows="3" required></textarea>
        </div>
        <div class="ghtk-order-created__footer">
            <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
        </div>
    </form>
</div>
<style>
    .fancybox-slide > * { padding:0; }
    .customer-point h2 {
        background-color:#2C3E50; color:#fff; margin:0; padding:10px;
        font-size:18px;
    }
    .customer-point form {
        padding:10px;
        overflow:hidden;
        min-width:500px;
        max-width:100%;
    }
    .customer-point form .group{
        margin-bottom:10px;
    }
</style>
<script>
        $(function() {
            $('#customer-point-plus__form').submit(function(){
                var data = $(this).serializeJSON();
                data.action = 'tddq_ajax_customer_point_plus';
                $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');
                $jqxhr.done(function( data ) {
                    show_message(data.message, data.status);
                    if(data.status == 'success') {
                        $.fancybox.close();
                        document.location.reload();
                    }
                });
                return false;
            });
            $('#customer-point-out__form').submit(function(){
                var data = $(this).serializeJSON();
                data.action = 'tddq_ajax_customer_point_out';
                $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');
                $jqxhr.done(function( data ) {
                    show_message(data.message, data.status);
                    if(data.status == 'success') {
                        $.fancybox.close();
                        document.location.reload();
                    }
                });
                return false;
            });
        });
    </script>