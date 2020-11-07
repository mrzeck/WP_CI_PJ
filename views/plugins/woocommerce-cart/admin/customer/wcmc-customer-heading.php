<?php
/**
 * Edit infomation customer
 */
function customer_detail_header_action__editinfo($customer) {

    if( !current_user_can('customer_edit') ) return false;

    $fields = wcmc_customer_fields();

    ?>
    <a class="btn btn-default" data-fancybox data-src="#customer-edit" href="javascript:;">
        <i class="fal fa-user-edit"></i>  Sửa thông tin liên hệ
    </a>
    <div style="display: none;" id="customer-edit">
        <h2>Cập Nhật Thông Tin Liên Hệ</h2>
        <form action="" autocomplete="off" id="customer-edit__form">

            <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">

            <?php foreach ($fields as $key => $field) {

                $value = '';

                if(isset($customer->{$key})) $value = $customer->{$key};

                if(isset($field['metadata']) && $field['metadata'] == true) {
                    $value = get_user_meta($customer->id, $key, true);
                }

                echo _form($field, $value);
            } ?>

            <?php do_action('form_customer_edit', $customer);?>

            <div class="ghtk-order-created__footer">
                <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
            </div>
        </form>
    </div>

    <style>
        .fancybox-slide > * { padding:0; }
        #customer-edit { max-width:500px; }
        #customer-edit h2 {
            background-color:#2C3E50; color:#fff; margin:0; padding:10px;
            font-size:18px;
        }
        #customer-edit form {
            padding:10px;
            overflow:hidden;
        }
        #customer-edit form .group{
            margin-bottom:10px;
        }

        .select2-container { z-index:99999; width: 100%!important; }
    </style>

    <script>
        $(function() {
            $('#customer-edit__form').submit(function(){

                var data = $(this).serializeJSON();

                data.action = 'wcmc_ajax_customer_edit';

                $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

                $jqxhr.done(function( data ) {

                    show_message(data.message, data.type);

                    if(data.type == 'success') {

                        $.fancybox.close();
                    }

                });

                return false;
            });

            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                }
            });

            $('#city').select2();

            $('#districts').select2();

            $('#city').each(function (index, value) {

                var data = {
                    province_id : $(this).val(),
                    district_id : '<?php echo get_user_meta($customer->id, 'districts', true);?>',
                    action      : 'wcmc_ajax_load_districts'
                }

                $jqxhr   = $.post( base + '/ajax' , data, function() {}, 'json');

                $jqxhr.done(function( data ) {
                    if(data.type == 'success') {
                        $('#districts').html(data.data);
                    }
                });

            });

            $('#city').change(function() {

                var data = {
                    province_id : $(this).val(),
                    action: 'wcmc_ajax_load_districts'
                }

                $jqxhr   = $.post( base + '/ajax' , data, function() {}, 'json');

                $jqxhr.done(function( data ) {
                    if(data.type == 'success') {
                        $('#districts').html(data.data);
                    }
                });

            });
        });
    </script>
    <?php
}

add_action('customer_detail_header_action', 'customer_detail_header_action__editinfo');

/**
 * Action account customer
 */
function customer_detail_header_action__active($customer) {

    if( !current_user_can('customer_active') ) return false;

    if($customer->username == '') {
    ?>
    <a class="btn btn-default" data-fancybox data-src="#customer-active-account" href="javascript:;"> <i class="fal fa-user-check"></i>  Kich hoạt tài khoản</a>
    <div style="display: none;" id="customer-active-account">
        <h2>Kích hoạt tài khoản</h2>
        <form action="" autocomplete="off" id="customer-active-account__form">

            <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">

            <div class="col-md-12" id="box_username">
                <label for="username" class="control-label">Tài khoản đăng nhập</label>
                <div class="group">
                    <input type="text" name="username" value="" placeholder="Tên đăng nhập của tài khoản khách hàng." class="form-control " required>
                </div>
            </div>

            <div class="form-group col-md-6">
				<label for="">Mật khẩu</label>
				<input name="password" type="password" value="" class="form-control" placeholder="Nhập mật khẩu" required>
			</div>

            <div class="form-group col-md-6">
				<label for="">Nhập lại mật khẩu</label>
				<input name="re_password" type="password" value="" class="form-control" placeholder="Nhập lại mật khẩu" required>
			</div>

            <?php do_action('form_customer_active_account', $customer);?>

            <div class="ghtk-order-created__footer">
                <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
            </div>
        </form>
    </div>

    <style>
        #customer-active-account { max-width:500px; }
        #customer-active-account h2 {
            background-color:#2C3E50; color:#fff; margin:0; padding:10px;
            font-size:18px;
        }
        #customer-active-account form {
            padding:10px;
            overflow:hidden;
        }
        #customer-active-account form .group{
            margin-bottom:10px;
        }
    </style>

    <script>
        $(function() {
            $('#customer-active-account__form').submit(function(){

                var data = $(this).serializeJSON();

                data.action = 'wcmc_ajax_customer_active_account';

                $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

                $jqxhr.done(function( data ) {

                    show_message(data.message, data.type);

                    if(data.type == 'success') {

                        $.fancybox.close();
                    }

                });

                return false;
            });
        });
    </script>
    <?php
    }
}

add_action('customer_detail_header_action', 'customer_detail_header_action__active');

/**
 * Reset password account customer
 */
function customer_detail_header_action__reset_password($customer) {

    if( !current_user_can('customer_reset_password') ) return false;

    if($customer->username != '') {
    ?>
    <a class="btn btn-default" data-fancybox data-src="#customer-reset-password" href="javascript:;"><i class="fal fa-repeat"></i>  Đặt lại mật khẩu</a>
    <div style="display: none;" id="customer-reset-password">
        <h2>Đặt lại mật khẩu</h2>
        <form action="" autocomplete="off" id="customer-reset-password__form">

            <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">

            <div class="form-group col-md-6">
				<label for="">Mật khẩu</label>
				<input name="password" type="password" value="" class="form-control" placeholder="Nhập mật khẩu" required>
			</div>

            <div class="form-group col-md-6">
				<label for="">Nhập lại mật khẩu</label>
				<input name="re_password" type="password" value="" class="form-control" placeholder="Nhập lại mật khẩu" required>
			</div>

            <?php do_action('form_customer_active_account', $customer);?>

            <div class="ghtk-order-created__footer">
                <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
            </div>
        </form>
    </div>

    <style>
        #customer-reset-password { max-width:500px; }
        #customer-reset-password h2 {
            background-color:#2C3E50; color:#fff; margin:0; padding:10px;
            font-size:18px;
        }
        #customer-reset-password form {
            padding:10px;
            overflow:hidden;
        }
        #customer-reset-password form .group{
            margin-bottom:10px;
        }
    </style>

    <script>
        $(function() {
            $('#customer-reset-password__form').submit(function(){

                var data = $(this).serializeJSON();

                data.action = 'wcmc_ajax_customer_reset_password';

                $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

                $jqxhr.done(function( data ) {

                    show_message(data.message, data.type);

                    if(data.type == 'success') {

                        $.fancybox.close();
                    }

                });

                return false;
            });
        });
    </script>
    <?php
    }
}

add_action('customer_detail_header_action', 'customer_detail_header_action__reset_password');