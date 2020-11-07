<h5 style="padding: 10px;"><i class="fal fa-cogs"></i> Cấu hình chung cho tinymce</h5>
<form class="" id="generalform" action="post" >
    <?php echo form_open();?>
    <div class="bg-img col-md-12">
        <div class="list-item">
            <ul>
                <?php foreach ($tiny_setting as $group_name => $value) :?>
                    <?php foreach ($value as $item) { ?>
                    <li class="a<?=$item?> <?php if(isset($get_tiny[$group_name])){echo (in_array($item, $get_tiny[$group_name]))?'i-checked': 'i-uncheck';} ?>">
                        <?php get_img('tinymce/'.$item.'.png');?> 
                        <input type="checkbox" <?php if(isset($get_tiny[$group_name])){echo (in_array($item, $get_tiny[$group_name]))?'checked': '';} ?>  value="<?=$item?>" name="showhidebutton[<?=$group_name?>][]" class="">
                    </li>
                    <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-sm-offset-3 col-sm-9 text-right">
            <button  class="btn-icon btn-green savesetting"><?php echo admin_button_icon('save');?>Cập nhật</button>
        </div>
    </div>
    <div class="clearfix"></div>
</form>
<h5 style="padding: 10px;"><i class="fal fa-cogs"></i> Cấu hình chung cho tinymce bản rút gọn</h5>
<form class="" id="generalform_shortcut" action="post" >
    <?php echo form_open();?>
    <div class="bg-img col-md-12">
        <div class="list-item">
            <ul>
                <?php foreach ($tiny_setting as $group_name => $value) :?>
                    <?php foreach ($value as $item) { ?>
                    <li class="a<?=$item?> <?php if(isset($get_tiny_shortcut[$group_name])){echo (in_array($item, $get_tiny_shortcut[$group_name]))?'i-checked': 'i-uncheck';} ?>">
                        <?php get_img('tinymce/'.$item.'.png');?> 
                        <input type="checkbox" <?php if(isset($get_tiny_shortcut[$group_name])){echo (in_array($item, $get_tiny_shortcut[$group_name]))?'checked': '';} ?>  value="<?=$item?>" name="showhidebutton_shortcut[<?=$group_name?>][]">
                    </li>
                    <?php } ?>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </ul>
        </div>
    </div>
    <div class="form-group col-md-12">
        <div class="col-sm-offset-3 col-sm-9 text-right">
            <button  class="btn-icon btn-green savesetting"><?php echo admin_button_icon('save');?>Cập nhật</button>
        </div>
    </div>
</form>

<script>	
    $(document).ready(function(){
        
        //thiết lập chung cho tinymce
        $('#generalform').submit(function(event){
            
            var arr = $(this).serializeJSON();

            var ark = $(this).serializeJSON().showhidebutton;

            if(!jQuery.isEmptyObject(ark)){

                var ak = Array.from(Object.keys(arr), k=>arr[k]);

                $jqxhr   = $.post(base+'/ajax', {'info':ark,'action':'ajax_admin_tinymce'}, function() {}, 'json');

                $('.tab-content').addClass('abc');
                $jqxhr.done(function( data ) {

                    if(data.status == 'success') {

                        show_message(data.message, data.status);

                        $('.tab-content').removeClass('abc');

                    }else{
                        show_message(data.message, data.status);
                        $('.tab-content:after').css('display','none');
                    }
                });
            }else{

                alert('Vui lòng chọn ít nhất 1 mục để hiển thị;');
            }
            
            return false;
        });

        $('#generalform_shortcut').submit(function(event){

            var arr = $(this).serializeJSON();

            var ark = $(this).serializeJSON().showhidebutton_shortcut;
        
            if(!jQuery.isEmptyObject(ark)){

                var ak = Array.from(Object.keys(arr), k=>arr[k]);

                $('.tab-content').addClass('abc');

                $jqxhr   = $.post(base+'/ajax', {'info':ark,'action':'ajax_admin_tinymce_shortcut'}, function() {}, 'json');

                $jqxhr.done(function( data ) {

                    if( data.status == 'success') {

                        $('.tab-content').removeClass('abc');

                        show_message(data.message, data.status);

                    }
                        
                });
            }else{
                alert('Vui lòng chọn ít nhất 1 mục để hiển thị;');
            }
            return false;
        });
    });
</script>