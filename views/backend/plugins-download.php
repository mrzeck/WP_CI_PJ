<?php $this->template->render_include('action_bar');?>
<?php $listPlugin = $this->plugins['active'];?>
<?php if(have_posts($plugins)) { ?>
<div class="col-md-12">
    <div class="box">
        <div class="header"> <h2>Danh sách plugin</h2> </div>
        <!-- .box-content -->
        <div class="box-content">
			<?php foreach ($plugins as $key => $plugin): ?>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="pl-item">
						<div style="overflow:hidden;">
							<div class="img"><?php get_img($plugin->image);?></div>
							<div class="title">
								<h3 style="margin-bottom: 10px; font-size: 12px;height:22px;"><?php echo $plugin->title;?></h3>
								<p style="height: 90px; overflow: hidden; color:#999;"><?php echo removeHtmlTags($plugin->excerpt);?></p>
							</div>
						</div>
						<div class="action" style="float:none;text-align:right;width:100%;">
							<?php if( !isset($listPlugin[$plugin->folder]) ) { ?>
							<button type="button" class="btn btn-green pl-install button" data-url="<?php echo $plugin->id;?>">Download</button>
							<?php } else if( $listPlugin[$plugin->folder] != 1 ) { ?>
							<a href="<?php echo URL_ADMIN.'/plugins/active/'.$plugin->folder;?>" class="btn btn-blue" style="width:100%" data-url="<?php echo $plugin->id;?>">Kích hoạt</a>
							<a href="<?php echo URL_ADMIN.'/plugins/remove/'.$plugin->folder;?>" class="btn btn-red" style="width:100%" data-url="<?php echo $plugin->id;?>">Xóa</a>
							<?php } else { ?>
							<a href="<?php echo URL_ADMIN.'/plugins/active/'.$plugin->folder;?>" class="btn btn-white" data-url="<?php echo $plugin->id;?>">Ngưng kích hoạt</a>
							<?php } ?>
						</div>
						<div class="info">
							<div class="author"><b>Phiên bản:</b> <?php echo $plugin->version;?></div>
							<div class="version"><b>Tác giả:</b> <?php echo $plugin->author;?></div>
						</div>
					</div>
				</div>
			<?php endforeach ?>
        </div>
        <!-- /.box-content -->
    </div>
</div>
<?php } ?>