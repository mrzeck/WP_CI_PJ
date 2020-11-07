<?php
    $mail = new skd_mail();
?>
<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="col-xs-12 col-md-4">
            
            <div class="header"> <h2>SMTP configuration </h2> </div>
            <div class="box-content" style="padding:10px;">
                <div class="form-group">
                    <label for="input" class="control-label">Username (email):</label>
                    <input type="text" name="smtp-user" class="form-control" value="<?php echo get_option('smtp-user', $mail->user);?>" required="required">
                </div>

                <div class="form-group">
                    <label for="input" class="control-label">Password (Mã ứng dụng ):</label>
                    <input type="text" name="smtp-pass" class="form-control" value="<?php echo get_option('smtp-pass', $mail->pass);?>" required="required">
                </div>

                <div class="form-group">
                    <label for="input" class="control-label">Server:</label>
                    <input type="text" name="smtp-server" class="form-control" value="<?php echo get_option('smtp-server', $mail->host);?>" required="required">
                </div>

                <div class="form-group">
                    <label for="input" class="control-label">Port:</label>
                    <input type="text" name="smtp-port" class="form-control" value="<?php echo get_option('smtp-port', $mail->port);?>" required="required">
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-md-4">
            <div class="header"> <h2>TEST SEND EMAIL</h2> </div>
            <div class="box-content" style="padding:10px;">
                <div id="smtp_form_test">
                    <div id="ajax_loader" class="ajax-load-qa">&nbsp;</div>

                    <div class="form-group">
                        <label for="input" class="control-label">Từ email (From email):</label>
                        <input type="text" name="smtp-test-from" class="form-control" value="<?php echo get_option('contact_mail');?>">
                    </div>

                    <div class="form-group">
                        <label for="input" class="control-label">Đến email (To email):</label>
                        <input type="text" name="smtp-test-to" class="form-control" value="<?php echo get_option('contact_mail');?>">
                    </div>

                    <div class="form-group">
                        <label for="input" class="control-label">Tên người gửi (Name):</label>
                        <input type="text" name="smtp-test-name" class="form-control" value="<?php echo get_option('general_label');?>">
                    </div>

                    <div class="form-group">
                        <label for="input" class="control-label">Tiêu đề (subject):</label>
                        <input type="text" name="smtp-test-subject" class="form-control" value="Kiểm tra tính năng gửi email - <?php echo get_option('general_label');?>">
                    </div>

                    <div class="form-group">
                        <label for="input" class="control-label">Nội dung (content):</label>
                        <textarea name="smtp-test-content" class="form-control" rows="3" required="required">Đây là nội dung kiểm tra!</textarea>
                    </div>

                    <hr />
                    <div class="col-xs-12 box-content text-right">
                        <button type="button" id="smtp_btn_test" class="btn-icon btn-green"><?php echo admin_button_icon('save');?>Gửi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="header"> <h2>KẾT QUẢ TEST SEND EMAIL</h2> </div>
            <div class="box-content" style="padding:10px;">
                <div id="smtp_form_test_result">
                </div>
            </div>
        </div>
    </div>
</div>