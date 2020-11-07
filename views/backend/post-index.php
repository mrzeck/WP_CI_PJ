<?= $this->template->render_include('action_bar');?>
<?php if(have_posts($objects) || $this->input->get('category') != null ||$this->input->get('keyword') != null || $this->input->get('status') == 'trash' || $trash != 0) {?>
<div class="col-md-12">

    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title"><?= $this->post['labels']['name'];?></h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_post_'.$this->post_type.'_action_bar_heading');?>
        </div>
    </div>

    <div class="box">
        <!-- .box-content -->
        <div class="box-content">
            <!-- search box -->
            <?php $table_list->display_search();?>
            <!-- /search box -->

            <!-- paging -->
            <div class="paging">
                <div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
            </div>
            <!-- paging -->

            <form method="post" id="form-action" class="table-responsive">
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
<?php } else {?>
<div class="col-md-5 box-empty">
  	<h2>Viết một bài đăng ngay bây giờ</h2>
  	<h4>Bài viết trên blog là một cách tuyệt vời để xây dựng một cộng đồng xung quanh các sản phẩm và thương hiệu của bạn.</h4>
</div>
<div class="col-md-7"><img src="//cdn.shopify.com/s/assets/admin/empty-states-fresh/emptystate-blogposts-b900a3bb6a29febd000abba4ee9a9266df99e458c2dd89fb1f919fab0298029e.svg" alt="Emptystate pages"></div>
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