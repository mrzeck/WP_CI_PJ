
<?php
    $cms_status = get_option('cms_status', 'public');

    $cms_password = get_option('cms_password', '');

    $cms_close_title = get_option('cms_close_title', '');

    $cms_close_content = get_option('cms_close_content', '');
?>
<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Trạng thái website:</label>
                
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    
                    <div class="checkbox">
                        <label style="padding-left:0;"> <input type="radio" name="cms_status" value="public" class="icheck" <?php echo ($cms_status == 'public')?'checked':'';?>> Công khai </label>
                    </div>

                    <div class="checkbox">
                        <label style="padding-left:0;"> <input type="radio" name="cms_status" value="close" class="icheck" <?php echo ($cms_status == 'close')?'checked':'';?>> Bảo trì / Đóng </label>
                    </div>

                        <div class="checkbox">
                        <label style="padding-left:0;"> <input type="radio" name="cms_status" value="close-home" class="icheck" <?php echo ($cms_status == 'close-home')?'checked':'';?>> Bảo trì / Đóng trang chủ </label>
                    </div>

                    <div class="checkbox">
                        <label style="padding-left:0;"> <input type="radio" name="cms_status" value="password" class="icheck" <?php echo ($cms_status == 'password')?'checked':'';?>> Truy cập bằng mật khẩu </label>
                    </div>
                    
                </div>
                
            </div>

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Password:</label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" name="cms_password" class="form-control" value="<?php echo $cms_password;?>">
                </div>
            </div>

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Tiêu đề (close):</label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" name="cms_close_title" class="form-control" value="<?php echo $cms_close_title;?>">
                </div>
            </div>

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Lý do (close):</label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <textarea name="cms_close_content" class="form-control"><?php echo $cms_close_content;?></textarea>
                </div>
            </div>

        </div>
    </div>
</div>