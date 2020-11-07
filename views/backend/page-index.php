<?php $this->template->render_include('action_bar');?>
<?php if(have_posts($objects) || $this->input->get('keyword') != null || $this->input->get('status') == 'trash' || $trash != 0) {?>
<div class="col-md-12">
    
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Trang nội dung</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_page_action_bar_heading');?>
        </div>
    </div>
    

    <div class="box"> 
        <!-- .box-content -->
        <div class="box-content">
            <!-- search box -->
            <?php $table_list->display_search();?>
            <!-- /search box -->

            <form method="post" id="form-action">
                <?php $table_list->display();?>
            </form>

            <!-- paging -->
            <div class="paging">
                <div class="pull-left" style="padding-top:20px;">Hiển thị <?= count($objects);?> trên tổng số <?= $total;?> kết quả</div>
                <div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
            </div>
            <!-- paging -->
        </div>
        <!-- /.box-content -->
    </div>
</div>
<?php } else if( $this->input->get('status') != 'trash' ) {?>

<div class="col-md-5 box-empty">
    <h2>Thêm trang vào website của bạn</h2>
    <h4>Viết tiêu đề và mô tả trang rõ ràng để cải thiện tối ưu hóa công cụ tìm kiếm (SEO) của bạn và giúp khách hàng tìm thấy trang web của bạn.</h4>
    <a href="<?php echo admin_url('page/add');?>" class="btn-icon btn-green hvr-sweep-to-right"><i class="fa fa-plus-circle"></i> Thêm Mới</a>
</div>

<div class="col-md-7"><img src="//cdn.shopify.com/s/assets/admin/empty-states-fresh/emptystate-pages-9fc4d1bc367cc2ce06e3404e37068eeaa8483fa736ea6c0e3bdc251807d1f76b.svg" alt="Emptystate pages"></div>

<style type="text/css">
    .box-empty { margin-top: 50px; }
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
