<?php $font_family = get_font_family();?>

<div class="left-font">
    <h5> Thêm font size và font chữ</h5>
    <form id="fonts_form_save">
        <?php $ci->template->get_message();?>
        <div class="form-group">
            <div class="col-sm-12">
                <label class="col-sm-12 control-label"><i class="fal fa-text-width"></i> Font size</label>
                <input type="text" data-role="tagsinput" value="<?=$font_size?>" name="fontsize">
                <p style="color:#999;margin:5px 0 5px 0;">Thêm font size mới cho tinymce</p>
            </div>
            <div class="col-sm-12">
                <label class="col-sm-12 control-label"><i class="fal fa-font"></i> Thêm font ()</label>
                
                <div id="blog_font_family">
                    <div class="box-content" style="padding:10px 15px;">

                        <div id="blog_font_family__box">
                        
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="">Font Key</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="">Font Type</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="">Font Label</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="">Font Load</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="">#</label>
                                </div>
                            </div>
                            <div id="blog_font_family__content">
                                <?php if(!have_posts($font_family)) {?>
                                <div class="row blog_font_family__item">
                                    <div class="form-group col-md-4">
                                        <input type="text" name="font_family[0][key]" class="form-control" value="" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="font_family[0][type]" class="form-control" required>
                                            <option value="default">Mặc định</option>
                                            <option value="theme">Template</option>
                                            <option value="google">Google Font</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="text" name="font_family[0][label]" class="form-control" value="" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="button" class="add btn btn-green">Thêm</button>
                                    </div>
                                </div>
                                <?php } else {
                                    foreach ($font_family as $key => $font) { ?>
                                    <div class="row blog_font_family__item">
                                        <div class="form-group col-md-3">
                                            <input type="text" name="font_family[<?php echo $key;?>][key]" class="form-control" value="<?php echo $font['key'];?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <select name="font_family[<?php echo $key;?>][type]" class="form-control" required>
                                                <option value="default" <?php echo ($font['type'] == 'default')?'selected':'';?>>Mặc định</option>
                                                <option value="theme" <?php echo ($font['type'] == 'theme')?'selected':'';?>>Template</option>
                                                <option value="google" <?php echo ($font['type'] == 'google')?'selected':'';?>>Google Font</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="text" name="font_family[<?php echo $key;?>][label]" class="form-control" value="<?php echo $font['label'];?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" name="font_family[<?php echo $key;?>][load]" class="form-control" value="<?php echo $font['load'];?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <?php if($key == 0) {?>
                                            <button type="button" class="add btn btn-green">Thêm</button>
                                            <?php } else { ?>
                                            <button type="button" class="delete btn btn-red">Xóa</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php }
                                } ?>
                            </div>
                        
                        </div> 
                    </div>
                </div>

                <p style="color:#999;margin:5px 0 5px 0;">Chỉ có thể thêm các font ở trên google fonts ( chỉ nên lấy font tiếng việt). lấy font ở đây<a target="_blank" href="https://fonts.google.com/?subset=vietnamese"> đến google font</a></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 text-right">
                <button  class="btn-icon btn-green savefont"><?php echo admin_button_icon('save');?>Cập nhật</button>
            </div>
        </div>
    </form>
</div>

<script>

    var font_family = $('#blog_font_family__content');

    font_family.on( 'click', '.add', function() {

        var size = font_family.find('.blog_font_family__item').length;

        $('<div class="row blog_font_family__item">\
            <div class="form-group col-md-3">\
                <input type="text" name="font_family['+size+'][key]" class="form-control" value="" required>\
            </div>\
            <div class="form-group col-md-2">\
                <select name="font_family['+size+'][type]" class="form-control" required>\
                    <option value="default">Mặc định</option>\
                    <option value="theme">Template</option>\
                    <option value="google-font">Google Font</option>\
                </select>\
            </div>\
            <div class="form-group col-md-3">\
                <input type="text" name="font_family['+size+'][label]" class="form-control" value="" required>\
            </div>\
            <div class="form-group col-md-2">\
                <input type="text" name="font_family['+size+'][load]" class="form-control" value="">\
            </div>\
            <div class="form-group col-md-2">\
                <button type="button" class="delete btn btn-red">Xóa</button>\
            </div>\
        </div>').appendTo('#blog_font_family__content');

        return false;
    });

    font_family.on( 'click', 'button.delete', function(){
        $(this).closest('.blog_font_family__item').remove();
        return false;
    });

    $(document).on('submit', '#fonts_form_save', function() {

        var data 		= $(this).serializeJSON();

        data.action     =  'ajax_admin_tinymce_font';

        $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

        $jqxhr.done(function( data ) {
            show_message(data.message, data.status);
        });

        return false;
    });
</script>
