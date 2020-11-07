<?= $this->template->render_include('action_bar');?>
<div class="col-md-12">
	<div class="box">
		<div class="header">
	      	<h2>Danh sách plugin</h2>
	    </div>
	    <div class="box-content">
	    	<?php if(isset($list_plugin) && have_posts($list_plugin)) { ?>
           		<table id="table-data" class="display table table-striped media-table table-hover">

			        <?php foreach ($list_plugin as $key => $plugin): ?>
            		<tr class="spacer tr_<?= $key;?>"></tr>
            		<tr class="tr_<?= $key;?>">
                		<td>
                    		<div class="media">
                        		<div class="media-body">
                            		<label>
		                            	<?php if($plugin->active == 1) {?>  <span style="color:green;"><i class="fa fa-check-circle" aria-hidden="true"></i></span><?php } ?>
                            			<?php echo $plugin->label;?>
                            		</label>
                            		<p>
                            			<span class="label label-danger">Phiên bản : <?= $plugin->version; ?></span>

                            		<!-- 	<?php if( version_compare( $plugin->version_new, $plugin->version ) === 1 && $plugin->version_new != 'error') {?>
                            			<span class="label label-success">Phiên bản mới : <?= $plugin->version_new; ?></span>
                            			<?php } ?> -->
                            		</p>
		                            
                        		</div>
                    		</div>
                		</td>
		                <td style="width: 800px;">
		                	<p><?= $plugin->description;?></p>
		                	<p style="color:#ccc; font-size: 13px;">Tác giả : <?= $plugin->author; ?></p>
		                </td>
		                <td>
		                	<p class="text-right">
                            
                              	<?php if($plugin->active == 1) {?>
                              		<a class="btn btn-white" href="<?= URL_ADMIN.'/'.$module.'/active/'.$plugin->name;?>" class="btn icon-edit">Ngưng Kích hoạt</a>
                              	<?php } else {?>
                            		<a class="btn btn-success" href="<?= URL_ADMIN.'/'.$module.'/active/'.$plugin->name;?>" class="btn icon-edit">Kích hoạt</a>
                            		<a class="btn btn-red" href="<?= URL_ADMIN.'/'.$module.'/remove/'.$plugin->name;?>" class="btn icon-delete">Xóa</a>
                            	<?php } ?>
                            </p>
		                </td>
            		</tr>
          			<?php endforeach ?>
	    		</table>
	    	<?php } else { echo notice('danger','Không có dữ liệu để hiển thị');}  ?>
	    </div>
	</div>
</div>