<?php
    $setting        = get_rating_star_setting();

    $setting_form   = $setting['form'];

    $setting_color  = $setting['color'];
?>
<form id="rating_star_setting_form">
    <div class="col-md-12 col-lg-8">

        <div class="box">
            <div class="header"> <h2>Duyệt đánh giá sản phẩm</h2> </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-6">
                        <?php  $input = array('field' => 'rating_star_setting[has_approving]', 'type'	=> 'switch', 'label' => '', 'note' => 'Cấu hình cho phép chủ cửa hàng duyệt các đánh giá sản phẩm trước khi cho hiển thị.'); ?>
                        <?php echo _form($input, $setting['has_approving']);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="header"> <h2>Màu Sắc</h2> </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12">
                        <?php  $input = array('field' => 'rating_star_setting[color][star][form]', 'type'	=> 'color', 'label' => 'Màu biểu tượng sao form đánh giá'); ?>
                        <?php echo _form($input, $setting_color['star']['form']);?>
                    </div>
                    <br />
                    <div class="col-md-12">
                        <?php  $input = array('field' => 'rating_star_setting[color][star][detail]', 'type'	=> 'color', 'label' => 'Màu biểu tượng sao chi tiết sản phẩm'); ?>
                        <?php echo _form($input, $setting_color['star']['detail']);?>
                    </div>
                    <br />
                    <div class="col-md-12">
                        <?php  $input = array('field' => 'rating_star_setting[color][star][object]', 'type'	=> 'color', 'label' => 'Màu biểu tượng sao item sản phẩm'); ?>
                        <?php echo _form($input, $setting_color['star']['object']);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="header"> <h2>Cấu hình form đánh giá</h2> </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-6">
                        <?php  $input = array(
                            'field' => 'rating_star_setting[form][email][label]',
                            'type' => 'text', 
                            'label' => 'Địa chỉ email người đánh giá'
                        ); ?>
                        <?php echo _form($input, $setting_form['email']['label']);?>
                    </div>
                    <div class="col-md-6">
                        <?php  $input = array(
                            'field' => 'rating_star_setting[form][email][required]',
                            'type' => 'select', 
                            'label' => 'Loại',
                            'options' => [
                                'required'      => 'Bắt buộc',
                                'unrequired'    => 'Tùy chọn',
                                'hiden'         => 'Ẩn',
                            ]
                        ); ?>
                        <?php echo _form($input, $setting_form['email']['required']);?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <?php  $input = array(
                            'field' => 'rating_star_setting[form][title][label]',
                            'type' => 'text', 
                            'label' => 'Tiêu đề đánh giá'
                        ); ?>
                        <?php echo _form($input, $setting_form['title']['label']);?>
                    </div>
                    <div class="col-md-6">
                        <?php  $input = array(
                            'field' => 'rating_star_setting[form][title][required]',
                            'type' => 'select', 
                            'label' => 'Loại',
                            'options' => [
                                'required'      => 'Bắt buộc',
                                'unrequired'    => 'Tùy chọn',
                                'hiden'         => 'Ẩn',
                            ]
                        ); ?>
                        <?php echo _form($input, $setting_form['title']['required']);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
	$(function(){
        
        $('#rating_star_setting_form').submit(function(){
            
            var form = $(this);

			var data   = form.serializeJSON();

			data.action = 'ajax_rating_star_setting_save';

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.type);

				if( data.type == 'success' ) {
				}
			});

			return false;
        });
    });
</script>