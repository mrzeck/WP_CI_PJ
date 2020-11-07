<form action="" method="post" id="form-action">
    <div class="ui-layout customer-sections">
        <div class="col-md-12">
            <div class="box">
                <div class="box-content">
                    <section class="ui-layout__section" style="overflow: hidden;">
                    
                        <?php $ci->template->get_message();?>

                        <?php echo form_open();?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mật khẩu hiện tại</label>
                            <div class="col-sm-9">
                                <input name="old_password" type="password" class="form-control" placeholder="Mật khẩu củ">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mật khẩu mới</label>
                            <div class="col-sm-9">
                                <input name="new_password" type="password" class="form-control" placeholder="Mật khẩu mới">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Xác nhận mật khẩu</label>
                            <div class="col-sm-9">
                                <input name="re_new_password" type="password" class="form-control" placeholder="Xác nhận mật khẩu">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Lưu</button>
                            </div>
                        </div>

                    </section>

                </div>
            </div>
        </div>
    </div>
</form>