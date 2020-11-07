<form action="" method="post" id="wcmc-suppliers-form">
    <div class="ui-layout">
        <?php admin_loading_icon();?>
        <div class="col-md-12">
            <div class="ui-title-bar__group">
                <h1 class="ui-title-bar__title">Thêm nhà sản xuất</h1>
                <div class="ui-title-bar__action">
                    <?php do_action('admin_suppliers_action_bar_heading');?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box">
                    <div class="header">
                        <h3 class="pull-left">Thông Tin</h3>
                        <a class="pull-right btn-collapse" id="btn-info" data-toggle="collapse" data-target="#info"><i class="fal fa-minus-square"></i></a>
                    </div>
                    <div class="box-content collapse in" id="info">
                        <!-- tab language -->

                        <!-- hiển thị các field -->
                        <div class="box-content" style="margin:0;padding:0px;">

                            <div role="tabpanel" class="tab-pane active" id="lang_vi">
                                <?php foreach ($ci->language['language_list'] as $key => $label) { ?>
                                <div class="col-md-12" id="box_<?php echo $key;?>_title">
                                    <label for="<?php echo $key;?>[name]" class="control-label">Tên nhà sản xuất (<?php echo $label['label'];?>)</label>
                                    <div class="group">
                                        <input type="text" name="<?php echo $key;?>[name]" value="<?php echo (isset($object))?$object->lang[$key]['name']:'';?>" id="<?php echo $key;?>_name" class="form-control ">
                                        <p style="color:#999;margin:5px 0 5px 0;"></p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <?php if(isset($object) && have_posts($object)) {?>
                    <input type="hidden" name="id" class="form-control" value="<?php echo $object->id;?>">
                <?php } ?>

                <div class="box">
                    <div class="header">
                        <h3 class="pull-left">Thông tin liên hệ</h3>
                        <a class="pull-right btn-collapse" id="btn-seo" data-toggle="collapse" data-target="#seo"><i class="fal fa-minus-square"></i></a>
                    </div>
                    <div class="box-content collapse in" id="seo">
                        <div class="col-md-6" id="box_firstname">
                            <label for="firstname" class="control-label">Họ</label>
                            <div class="group">
                                <input type="text" name="firstname" value="<?php echo (isset($object))?$object->firstname:'';?>" id="firstname" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-6" id="box_lastname">
                            <label for="lastname" class="control-label">Tên</label>
                            <div class="group">
                                <input type="text" name="lastname" value="<?php echo (isset($object))?$object->lastname:'';?>" id="lastname" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_email">
                            <label for="email" class="control-label">Email</label>
                            <div class="group">
                                <input type="text" name="email" value="<?php echo (isset($object))?$object->email:'';?>" id="email" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_phone">
                            <label for="phone" class="control-label">Số điện thoại</label>
                            <div class="group">
                                <input type="text" name="phone" value="<?php echo (isset($object))?$object->phone:'';?>" id="phone" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_address">
                            <label for="address" class="control-label">Địa chỉ</label>
                            <div class="group">
                                <input type="text" name="address" value="<?php echo (isset($object))?$object->address:'';?>" id="address" class="form-control ">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="header">
                        <h3 class="pull-left">Media</h3>
                        <a class="pull-right btn-collapse" id="btn-media" data-toggle="collapse" data-target="#media"><i class="fal fa-minus-square"></i></a>
                    </div>
                    <div class="box-content collapse in" id="media">
                        <div class="col-md-12" id="box_image">
                            <label for="image" class="control-label">Ảnh đại diện</label>
                            <div class="group">
                                <div class="input-group image-group">
                                    <input type="images" name="image" value="<?php echo (isset($object))?$object->image:'';?>" id="image" class="form-control " >
                                    <span class="input-group-addon iframe-btn" data-fancybox="iframe" data-id="image" href="<?php echo base_url();?>/scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=image&amp;callback=responsive_filemanager_callback"><i class="fas fa-upload"></i></span>
                                </div>
                                <p style="color:#999;margin:5px 0 5px 0;"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="header">
                        <h3 class="pull-left">Seo</h3>
                        <a class="pull-right btn-collapse" id="btn-seo" data-toggle="collapse" data-target="#seo"><i class="fal fa-minus-square"></i></a>
                    </div>
                    <div class="box-content collapse in" id="seo">
                        <div class="col-md-12" id="box_slug">
                            <label for="slug" class="control-label">Slug</label>
                            <div class="group">
                                <input type="text" name="slug" value="<?php echo (isset($object))?$object->slug:'';?>" id="slug" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_seo_title">
                            <label for="seo_title" class="control-label">Meta title</label>
                            <div class="group">
                                <input type="text" name="seo_title" value="<?php echo (isset($object))?$object->seo_title:'';?>" id="seo_title" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_seo_keywords">
                            <label for="seo_keywords" class="control-label">Meta Keyword</label>
                            <div class="group">
                                <input type="text" name="seo_keywords" value="<?php echo (isset($object))?$object->seo_keywords:'';?>" id="seo_keywords" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12" id="box_seo_description">
                            <label for="seo_description" class="control-label">Meta Description</label>
                            <div class="group">
                                <textarea name="seo_description" cols="40" rows="5" type="textarea" id="seo_description" class="form-control "><?php echo (isset($object))?$object->seo_description:'';?></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function() {
        $('#wcmc-suppliers-form').submit(function() {

            $('.loading').show();

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_ajax_suppliers_save';

			$jqxhr   = $.post( base + '/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {

	  			show_message(data.message, data.status);

                $('.loading').hide();

                if(data.status == 'success') {
                    window.location.href = '<?php echo admin_url('plugins?page=suppliers');?>';
                }
			});

			return false;

		});
    })
</script>
