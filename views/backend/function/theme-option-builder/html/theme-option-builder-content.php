<div class="theme-option-builder">
    
    <a class="theme-option-builder-toggle" href="#"><i class="fal fa-cog fa-spin"></i></a>

    <a class="theme-option-builder-close" href="#"><i class="fal fa-times"></i></a>

    <form class="theme-option-builder__form" id="theme-option-builder__form">
        <div class="theme-option-builder-content">
            <h4 class="text-uppercase mb-0">Theme Customizer</h4>
            <small>Customize &amp; Preview in Real Time</small>
            <hr/>

            <div class="option-cms row">
                <div class="col-md-4">
                    <div class="row">
                    <?php 
                        $input = ['field' => 'cms_widget_builder', 'label' => 'Bật/Tắt widget builder', 'type' => 'switch'];
                        echo _form($input, get_option($input['field'], 0));
                    ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                    <?php 
                        $input = ['field' => 'cms_minify_css', 'label' => 'Bật/Tắt minify css', 'type' => 'switch'];
                        echo _form($input, get_option($input['field'], 0));
                    ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                    <?php 
                        $input = ['field' => 'cms_minify_js', 'label' => 'Bật/Tắt minify js', 'type' => 'switch'];
                        echo _form($input, get_option($input['field'], 0));
                    ?>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="option-theme">
                <!-- Nav tabs -->
                <div class="option-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $i = key($ci->theme_option['group']) ;?>
                        <?php if(isset($_COOKIE["of_current_opt"])) $i = $_COOKIE["of_current_opt"];?>
                        <?php foreach ($ci->theme_option['group'] as $key => $value) { if(isset($value['root']) && $value['root'] == true && !is_super_admin()) continue; ?>
                        <li class="<?= ($i == $key)?'active':'';?>"><a href="#<?= $key;?>" aria-controls="<?= $key;?>" role="tab" data-toggle="tab"><?= $value['icon'];?><span><?= $value['label'];?></span></a></li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Tab panes -->
                <div class="option-tab-content tab-content">
                    <?php foreach ($ci->theme_option['group'] as $key => $value) { ?>
                    <div role="tabpanel" class="tab-pane <?= ($i == $key)?'active':'';?>" id="<?= $key;?>">
                        <div class="header"><h4><?= $value['label'];?></h4></div>
                        <?php foreach ($ci->theme_option['option'] as $k => $field) {
                            if($field['group'] == $key) {
                                echo _form($field, get_option($field['field']));
                                unset($ci->theme_option['option'][$k]);
                            }
                        } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>

    <div class="theme-option-builder-button">
        <button id="theme-option-builder_btn__submit" form="theme-option-builder__form" class="btn">SAVE</button>
    </div>
</div>

<style>
    .theme-option-builder {
        width: 550px;
        right: -550px;
        padding: 0;
        margin-bottom:30px;
        background-color: #FFF;
        z-index: 9999;
        position: fixed;
        top: 0;
        bottom: 0;
        height: 100vh;
        -webkit-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -o-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -moz-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        backface-visibility: hidden;
        border-left: 1px solid rgba(0,0,0,.05);
        -webkit-box-shadow: 0 15px 30px 0 rgba(0,0,0,.11), 0 5px 15px 0 rgba(0,0,0,.08);
        box-shadow: 0 15px 30px 0 rgba(0,0,0,.11), 0 5px 15px 0 rgba(0,0,0,.08);
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-option-builder .theme-option-builder-toggle {
        background: #7367F0;
        color: #FFF;
        display: block;
        -webkit-box-shadow: -3px 0 8px rgba(0,0,0,.1);
        box-shadow: -3px 0 8px rgba(0,0,0,.1);
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
        position: absolute;
        top: 50%;
        width: 38px;
        height: 38px;
        left: -39px;
        text-align: center;
        line-height: 40px;
        cursor: pointer;
    }
    .theme-option-builder.open {
        right: 0;
    }
    .theme-option-builder-close {
        position: absolute;
        right: 30px;
        top: 20px;
        padding: 7px;
        width: auto;
        z-index: 10;
        color: #626262;
    }
    .theme-option-builder-content {
        position: relative;
        height: 100%;
        max-height:90vh;
        overflow-y: auto!important;
        overflow-anchor: none;
        -ms-overflow-style: none;
        touch-action: auto;
        -ms-touch-action: auto;
        padding:20px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-option-builder-content h4 {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        font-weight: 500;
        line-height: 1.2;
        color: #2C2C2C;
        margin-bottom:10px;
    }
    .theme-option-builder-content small {
        font-size: smaller;
        font-weight: 400;
        line-height: 1.45;
        color: #626262;
    }
    .theme-option-builder-content .header {
        background-color: #fff;
        padding: 10px 10px 10px 10px;
        overflow: hidden;
        text-transform: uppercase;
        border-bottom: 1px dashed #ccc;
    }
    .theme-option-builder-content .nav-tabs>li>a {
        font-size:11px;
        padding: 8px;
        border-radius: 0;
        color:#626262;
    }
    .theme-option-builder-content .nav-tabs>li>a span {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-option-builder-content .nav-tabs>li>a i{
        padding-right:5px;
    }
    .theme-option-builder-content .tab-content>.tab-pane .box { padding:0; }

    .theme-option-builder-content .header h2, .theme-option-builder-content .header h3, .theme-option-builder-content .header h4 {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        margin-bottom: 0px;
        font-weight: 500;
        line-height: 1.2;
        color: #2C2C2C;
        text-transform: uppercase;
        font-size:18px;
    }
    .theme-option-builder-content p {
        font-size:12px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-option-builder-content label {
        font-size:10px;
    }
    .theme-option-builder-content .form-control {
        -webkit-appearance: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        -webkit-transition: -webkit-box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        -o-transition: box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        transition: box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        -moz-appearance: none;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .theme-option-builder-content [class*=col-md-] {
        padding-left:5px; padding-right:5px;
    }
    .theme-option-builder-content .row {
        margin-left:-5px; margin-right:-5px;
    }
    .theme-option-builder-content .col-md-3 { width:50%; }

    .theme-option-builder-button {
        display: block;
        background: rgb(255, 255, 255);
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 50;
        padding: 10px 23px 10px 23px;
        margin-bottom: 0;
    }
    .theme-option-builder-button .btn {
        width: 100%;
        display: inline-flex;
        text-transform: capitalize;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        font-family: avenir-next-medium, arial;
        transition: all 0.3s ease-out 0s;
        white-space: nowrap;
        border-radius: 3px;
        cursor: pointer;
        position: relative;
        z-index: 10;
        margin-top: 0px;
        margin-bottom: 10px;
        height: 40px;
        line-height: 40px;
        padding: 0px 20px;
        background-color: rgb(255, 51, 51);
        color: rgb(255, 255, 255);
    }

    .colorpicker {
        z-index: 9999;
    }

    @media(max-width:500px) {
        .theme-option-builder { display:none; }
    }
</style>
<!-- button switch -->
<style>
    .toggleWrapper {
        position: relative;
    }
    .toggleWrapper .checkbox {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
    }
    .toggleWrapper .button {
        position: relative;
        top: 50%;
        width: 74px;
        height: 29px;
        overflow: hidden;
    }
    .toggleWrapper .button .knobs,
    .toggleWrapper .button .layer {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .toggleWrapper .button .layer {
        width: 100%;
        background-color: #fcebeb;
        transition: 0.3s ease all;
        border-radius: 2px;
        z-index: 1;
    }
    .toggleWrapper .button .knobs {
        z-index: 2;
    }
    .toggleWrapper #button-17 .knobs:before,
    .toggleWrapper #button-17 .knobs:before,
    .toggleWrapper #button-17 .knobs span,
    .toggleWrapper #button-17 .knobs span {
        content: 'NO';
        position: absolute;
        top: 4px;
        left: 32px;
        width: 50%;
        height: 21px;
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        line-height: 1;
        padding: 6px 4px;
    }
    .toggleWrapper #button-17 .knobs:before,
    .toggleWrapper #button-17 .knobs:before {
        transition: 0.3s ease all, left 0.5s cubic-bezier(0.18, 0.89, 0.35, 1.15);
        z-index: 2;
    }
    .toggleWrapper #button-17 .knobs span,
    .toggleWrapper #button-17 .knobs span {
        background-color: #F44336;
        border-radius: 2px;
        transition: 0.3s ease all, left 0.3s cubic-bezier(0.18, 0.89, 0.35, 1.15);
        z-index: 1;
    }
    .toggleWrapper #button-17 .checkbox:checked + .knobs:before,
    .toggleWrapper #button-17 .checkbox:checked + .knobs:before {
        content: 'YES';
        left: 4px;
    }
    .toggleWrapper #button-17 .checkbox:checked + .knobs span,
    .toggleWrapper #button-17 .checkbox:checked + .knobs span {
        left: 4px;
        background-color: #03A9F4;
    }
    .toggleWrapper #button-17 .checkbox:checked ~ .layer,
    .toggleWrapper #button-17 .checkbox:checked ~ .layer {
        background-color: #ebf7fc;
    }
</style>
<script defer >
    $(function(){
        $('.theme-option-builder .theme-option-builder-toggle, .theme-option-builder-close').click(function () {
            $('.theme-option-builder').toggleClass('open');
            return false;
        });

        //lưu cookie khi click vào các tap theme-option
        $('.option-theme a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $key = $(this).attr('aria-controls');
            setCookie('of_current_opt',$key,7);
        });

        var ThemeOptionBulderHandler = function () {
            $(document)
                .on('change', 'input.switch',   this.ClickMinify)
                .on('submit', '#theme-option-builder__form',  this.OptionActionSave)
        };

        ThemeOptionBulderHandler.prototype.ClickMinify  = function (e) {

            var cms_minify = $(this).closest('.toggleWrapper').find('input[name=cms_minify_css]');

            if(typeof cms_minify.val() != 'undefined') {

                var data = {
                    action: 'ajax_skd_theme_option_minify',
                    type  : 'css',
                    value : cms_minify.val(),
                }
            }

            var cms_minify = $(this).closest('.toggleWrapper').find('input[name=cms_minify_js]');

            if(typeof cms_minify.val() != 'undefined') {

                var data = {
                    action: 'ajax_skd_theme_option_minify',
                    type  : 'js',
                    value : cms_minify.val(),
                }
            }

            $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

            $jqxhr.done(function (data) {});

            return false;
        }

        ThemeOptionBulderHandler.prototype.OptionActionSave  = function (e) {

            var data = $(this).serializeJSON();

            data.action = 'ajax_skd_theme_option_builder_save';

            $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

            $jqxhr.done(function (data) {

                if (data.type == 'success') {

                    show_message(data.message, data.type);
                }
            });

            return false;
        }

        new ThemeOptionBulderHandler();

    })
</script>