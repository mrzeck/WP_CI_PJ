<?php if( have_posts($rating_stars) ) {?>
<div class="col-md-12">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Danh sách đánh giá</h1>
        <div class="ui-title-bar__action"></div>
    </div>
    <div class="box">
        <!-- .box-content -->
        <div class="box-content">
            <table class="display table table-striped media-table">
                <thead>
                    <tr>
                        <th class="manage-column">Xếp hạng</th>
                        <th class="manage-column">Nội dung đánh giá</th>
                        <th class="manage-column">Ngày</th>
                        <th class="manage-column">Trạng thái</th>
                        <th class="manage-column">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rating_stars as $key => $item) { 
                        if($object_type == 'product') {

                            $product = get_product($item->object_id);

                            $label_type = 'sản phẩm';
                        }
                        else {
                            $product = get_post($item->object_id);

                            $label_type = 'bài viết';
                        }
                    ?>
                    <tr class="rating-star-item">
                        <td style="width:200px;">
                            <div class="bizweb-product-reviews-star" style="color: rgb(255, 190, 0); text-align: center;">
                                <?php for( $i = 0; $i < $item->star; $i++ ) {?>
                                    <i class="fa fa-star" aria-hidden="true" style="color:#FFBB03; font-weight: 999;"></i>&nbsp;
                                <?php } ?>
                                <?php for( $i = 0; $i < (5-$item->star); $i++ ) {?>
                                    <i class="far fa-star" aria-hidden="true" style="color:#ccc;"></i>&nbsp;
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <p><a href=""><?php echo $item->title;?></a></p>
                            <div class="message"><?php echo $item->message;?></div>
                            <p><b><?php echo $item->name;?> <?php echo (!empty($item->email)) ? '('.$item->email.')' : '';?></b> đánh giá <?php echo $label_type;?> <b><a href="<?php echo get_url($product->slug);?>" target="_blank" rel="noopener noreferrer"><?php echo $product->title;?></a></b></p>
                        </td>
                        <td style="width:150px;">
                            <p><?php echo $item->created;?></p>
                        </td>
                        <td>
                            <p class="-status"><span class="label label-<?php echo ($item->status == 'public') ? 'success' : 'danger';?>"><?php echo ($item->status == 'public') ? 'Hiển thị' : 'Ẩn';?></span> </p>
                        </td>
                        <td style="width:150px;">
                            <button class="btn-white btn rating-star__btn-status" data-id="<?php echo $item->id;?>"><?php echo ($item->status == 'public') ? 'Ẩn' : 'Hiển thị';?> </button>
                            <button class="btn-red btn rating-star__btn-delete" data-id="<?php echo $item->id;?>"><i class="fal fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                    
                </tbody>
            </table>
        </div>
        <!-- /.box-content -->
    </div>
</div>

<style>
    .rating-star-item .message {
        font-size: 13px;
        color: #111;
        position: relative;
        margin-top: 9px;
        padding-left: 15px;
        margin-bottom: 5px;
        font-weight: 300;
    }
    .rating-star-item .message:before {
        content: "";
        font-family: ap;
        position: absolute;
        top: 0px;
        left: 0px;
        bottom: 0px;
        width: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: rgba(0,0,0,0.1);
    }
</style>

<script type="text/javascript">
    $(function(){
        $('.rating-star__btn-status').click(function(){

            var ths = $(this);

            var item = $(this).closest('.rating-star-item');

            var id  = $(this).attr('data-id');

            var data = {
                action : 'ajax_rating_star_status_save',
                id     : id
            };

            $jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

            $jqxhr.done(function(data){

                if(data.type == 'success') {

                    item.find('.-status').html(data.status);

                    ths.text(data.status_label);
                }
            });

            return false;
        });

        $('.rating-star__btn-delete').bootstrap_confirm_delete({
            heading:'Xác nhận xóa',
            message:'Bạn muốn xóa trường dữ liệu này ?',
            callback:function ( event ) {

                var button = event.data.originalObject;

                var id = button.attr('data-id');

                if(id == null || id.length == 0) {
                    show_message('Không có dữ liệu nào được xóa ?', 'error');
                }
                else {

                    var data ={
                        'action' : 'ajax_rating_star_delete',
                        'id'   : id,
                    }

                    $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

                    $jqxhr.done(function( data ) {

                        show_message(data.message, data.type);

                        if(data.type == 'success') {

                            button.closest( 'tr' ).remove();
                        }
                    });
                }
            },
        });
    })
</script>
<?php } else {?>
<div class="col-md-5 box-empty">
  	<h2>CHƯA CÓ DỮ LIỆU ĐÁNH GIÁ</h2>
  	<h4>Khi người mua hàng kiểm định được chất lượng hàng hóa và dịch vụ trên website của bạn, họ sẽ tự tin thanh toán để mua về món đồ mà họ yêu thích và đã được kiểm chứng về chất lượng. Theo thống kê từ chúng tôi, khách hàng sẽ mua hàng nhiều hơn gấp 4 lần nếu website của bạn cài đặt ứng dụng Product Reviews</h4>
</div>
<div class="col-md-1 box-empty">
</div>
<div class="col-md-6"><img src="<?php echo RATING_STAR_PATH;?>/assets/images/icon-rating.png" style="max-width:100%"></div>
<style type="text/css">
  	.box-empty {
    	margin-top: 50px;
  	}
  	.box-empty h2 {
    	font-size: 30px;
    	font-weight: bold;
  	}
  	.box-empty h4 {
	    font-size: 18px;
	    line-height: 2.8rem;
	    font-weight: 400;
	    color: #637381;
  	}
</style>
<?php }?>