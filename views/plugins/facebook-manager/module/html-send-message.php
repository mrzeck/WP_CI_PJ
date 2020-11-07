<div class="box-fbm-chat box-fbm-chat-<?php echo $setting['fbm_position'];?>" id="fbm_box">
    <div class="fbm-chat-header">
        <div class="fbm-chat-title" id="fbm_header">
            <i class="fab fa-facebook-f"></i>
            <span><?php echo $setting['fbm_title'];?></span>
        </div>
        <div class="fbm-chat-close" id="fbm_close">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <div class="fbm-chat-content" id="fbm_content" style="display: none">
        <?php if(empty(get_option('social_facebook'))) {?>
        <div class="alert error">Bạn chưa cấu hình liên kết facebook fanpage</div>
        <?php } else {?>
        <div data-href="<?php echo get_option('social_facebook');?>" class="fb-page" data-small-header="true"
            data-height="300" data-width="250" data-tabs="messages" data-adapt-container-width="false" data-hide-cover="false"
            data-show-facepile="false" data-show-posts="true">
        </div>
        <?php } ?>
        
    </div>
</div>