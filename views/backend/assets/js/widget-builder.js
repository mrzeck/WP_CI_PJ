var widget_id, widget_key, sidebar, widget_position;

var WidgetBulderHandler = function () {

    $(document)
        // .on('mouseover', '.js_widget_builder',                  this.WidgetActionMouseover)
        // .on('mouseout', '.js_widget_builder',                   this.WidgetActionMouseout)
        .on('click', '.js_widget_builder .js_widget_edit',      this.WidgetActionClick)
        .on('click', '.js_widget_builder .js_widget_delete',    this.WidgetActionDelete)
        .on('click', '.js_widget-builder-edit_close',           this.WidgetActionClose)
        .on('click', '.js_widget_builder_content__add_top',     this.WidgetActionAddTop)
        .on('click', '.js_widget_builder_content__add_bottom',  this.WidgetActionAddBottom)
        .on('click', '.js_widget_builder_empty',                this.WidgetActionAddEmpty)
        .on('click', '.js_btn_widget__add',                     this.WidgetCmsAdd)
        .on('submit', '#widget-builder-edit__form',             this.WidgetActionSave)
        .on('click', '#widget_builder_btn__download',           this.WidgetActionServiceLoad)
        .on('click', '.widget-builder-edit .widget-cat-item',   this.WidgetActionServiceLoadCategory)
        .on('click', '.widget-builder-edit .wg-install',        this.download)
        .on('click', '#widget_builder_btn__load',               this.WidgetCmsLoad)
    
        
};
// WidgetBulderHandler.prototype.WidgetActionMouseover = function (e) {

//     widget_id = $(this).attr('data-id');

//     widget_key = $(this).attr('data-key');

//     item = $('.js_' + widget_key + '_' + widget_id);

//     $('.js_' + widget_key + '_' + widget_id).addClass('js_widget_builder_active');
// }

// WidgetBulderHandler.prototype.WidgetActionMouseout = function (e) {

//     widget_id = $(this).attr('data-id');

//     widget_key = $(this).attr('data-key');

//     item = $('.js_' + widget_key + '_' + widget_id);

//     $('.js_' + widget_key + '_' + widget_id).removeClass('js_widget_builder_active');
// }

WidgetBulderHandler.prototype.WidgetActionClick = function (e) {

    widget = $(this).closest('.js_widget_builder');

    widget_id = widget.attr('data-id');

    widget_key = widget.attr('data-key');

    item = $('.js_' + widget_key + '_' + widget_id);

    $('html, body').animate({ scrollTop: item.offset().top - 150 }, 500);

    $('.js_widget_builder').removeClass('js_widget_builder_active');

    $('.js_' + widget_key + '_' + widget_id).addClass('js_widget_builder_active');

    widget_builder_editer_toggle();

    var data = {
        'action': 'widget_builder_ajax_form_edit',

        'widget_id': widget_id,

        'widget_key': widget_key,
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        // show_message(data.message, data.type);

        if (data.type == 'success') {

            $('.widget-builder-edit__body').html(data.data);

            $('#widget_builder_btn__submit').show();

            $('#widget_builder_btn__download').hide();

            $('#widget_builder_btn__load').hide();

            widget_builder_action_load();
        }
    });
}

WidgetBulderHandler.prototype.WidgetActionDelete = function (e) {

    item = $(this).closest('.js_widget_builder');

    widget_id = item.attr('data-id');

    var data = {
        'action': 'ajax_widget_del',

        'id'    : widget_id,
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            item.remove();

            widget_builder_editer_toggle('hide');
        }
    });

    return false;
}

WidgetBulderHandler.prototype.WidgetActionSave  = function (e) {

    var data = $(this).serializeJSON();

    data.id = widget_id;

    data.action = 'ajax_widget_save';

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            $jqxhr = $.post(base + '/ajax', { 'action': 'widget_builder_ajax_review', 'id': widget_id }, function (data) { }, 'json');

            $jqxhr.done(function (data) {

                if (data.type == 'success') {

                    $('.js_' + widget_key + '_' + widget_id).replaceWith(data.data);

                    $('.js_' + widget_key + '_' + widget_id).addClass('js_widget_builder_active');

                    widget_builder_action_add_button($('.js_' + widget_key + '_' + widget_id));
                }
            });

        }
    });

    return false;
}

WidgetBulderHandler.prototype.WidgetActionClose = function (e) {

    $('.js_' + widget_key + '_' + widget_id).removeClass('js_widget_builder_active');

    widget_builder_editer_toggle('hide');

    return false;
}

WidgetBulderHandler.prototype.WidgetCmsLoad = function (e) {

    var data = {
        'action': 'widget_builder_ajax_theme',
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            $('.widget-builder-edit__body').html(data.data);

            $('#widget_builder_btn__submit').hide();

            $('#widget_builder_btn__download').show();

            $('#widget_builder_btn__load').hide();

            widget_builder_editer_toggle('show');
        }
    });

    return false;
}

WidgetBulderHandler.prototype.WidgetCmsAdd = function (e) {

    var item = $(this).closest('.widget_item_nosidebar');

    var data = {

        'action': 'widget_builder_ajax_add',

        'widget_add': item.attr('data-key'),

        'widget_parent': widget_id,

        'widget_position': widget_position,
    }

    if (widget_id == 0) data.sidebar_id = widget_key;

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            if (data.type == 'success') {

                $jqxhr = $.post(base + '/ajax', { 'action': 'widget_builder_ajax_review', 'id': data.id }, function (data) { }, 'json');

                $jqxhr.done(function (res) {

                    if (res.type == 'success') {

                        if (widget_id != 0) {

                            if (widget_position == 'top') {
                                $(res.data).insertBefore('.js_' + widget_key + '_' + widget_id);
                            }
                            else {
                                $(res.data).insertAfter('.js_' + widget_key + '_' + widget_id);
                            }
                        }
                        else {
                            $(res.data).insertBefore('#js_widget_empty_' + widget_key);
                        }

                        widget_builder_action_add_button($('.js_' + data.key + '_' + data.id));

                        $('.js_' + data.key + '_' + data.id).trigger('click');
                    }
                });

            }
        }
    });

    return false;
}

WidgetBulderHandler.prototype.WidgetActionAddTop = function (e) {

    item        = $(this).closest('.js_widget_builder');

    widget_id   = item.attr('data-id');

    widget_key = item.attr('data-key');

    widget_position = 'top';

    WidgetBulderHandler.prototype.WidgetCmsLoad();

    return false;
}

WidgetBulderHandler.prototype.WidgetActionAddBottom = function (e) {

    item        = $(this).closest('.js_widget_builder');

    widget_id   = item.attr('data-id');

    widget_key = item.attr('data-key');

    widget_position = 'bottom';

    WidgetBulderHandler.prototype.WidgetCmsLoad();

    return false;
}

WidgetBulderHandler.prototype.WidgetActionAddEmpty = function (e) {

    widget_builder_editer_toggle();

    widget_id = 0;

    widget_key = $(this).attr('data-key');

    widget_position = 'bottom';

    WidgetBulderHandler.prototype.WidgetCmsLoad();

    return false;
}

WidgetBulderHandler.prototype.WidgetActionServiceLoad = function (e) {

    var data = {
        'action': 'widget_builder_ajax_service',
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            $('.widget-builder-edit__body').html(data.data);

            $('#widget_builder_btn__submit').hide();

            $('#widget_builder_btn__download').hide();

            $('#widget_builder_btn__load').show();

            widget_builder_editer_toggle('show');
        }
    });

    return false;
}

WidgetBulderHandler.prototype.WidgetActionServiceLoadCategory = function (e) {

    loading = $('.widget-builder-edit').find('.loading');

    loading.show();

    $('.widget-cat-item').parent().removeClass('active');

    $(this).parent().addClass('active');

    $jqxhr = $.post(base + '/ajax', { 'action': 'ajax_widget_service_category', 'cate': $(this).attr('data-id') }, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        loading.hide();

        $('#widget-service-kho-list__item').html(data.data);
    });

    return false;
}

WidgetBulderHandler.prototype.download = function (e) {

    var name = $(this).attr('data-url');

    var button = $(this);

    if (name.length == 0) { show_message('Không được bỏ trống tên menu', 'error'); return false; }

    $(this).text('Đang download');

    data = {
        'action': 'ajax_plugin_wg_download',
        'name': name,
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        show_message(data.message, data.type);

        if (data.type == 'success') {

            button.text('Đang cài đặt');

            widget_new = true;

            setTimeout(function () {
                WidgetBulderHandler.prototype.install(button);
            }, 500);
        }
    });

    return false;
}

WidgetBulderHandler.prototype.install = function (button) {

    var name = button.attr('data-url');

    data = {
        'action': 'ajax_plugin_wg_install',
        'name': name,
    }

    $jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

    $jqxhr.done(function (data) {

        show_message(data.message, data.type);

        button.text('Đã cài đặt');

        widget_new = true;
    });

    return false;
}

new WidgetBulderHandler();

function widget_builder_editer_toggle(toggle = 'show' ) {

    console.log(toggle);

    if (toggle == 'show') {
        
        $('.widget-builder-edit').addClass('is-show');

        $('.widget-builder-edit .widget-builder-edit__content').addClass('is-show');

        $('body').addClass('is-show');
    }
    else {

        $('.widget-builder-edit').removeClass('is-show');

        $('.widget-builder-edit .widget-builder-edit__content').removeClass('is-show');

        $('body').removeClass('is-show');

        $('.widget-builder-edit__body').html('');
    }
}

function widget_builder_action_load() {

    tinymce.remove();

    tinymce_load();

    tinyMCE.execCommand('mceAddControl', false, "content");

    load_image_review();

    rangeSlider();

    $(".select2-multiple").select2();

    $('.iframe-btn').fancybox({
        'type': 'iframe',
        animationEffect: "zoom",
        transitionEffect: "zoom-in-out",
    });
}

function widget_builder_action_add_button( element ) {

    element.append('<div class="clearfix"></div>');
    element.append('<div class="js_widget_builder_content__add_top"><i class="fas fa-plus-circle"></i></div>');
    element.append('<div class="js_widget_builder_content__add_bottom"><i class="fas fa-plus-circle"></i></div> ');
    element.append('<div class="js_widget_builder_action">\
        <label>'+ element.attr('data-key') +'</label>\
        <button type="button" class="btn btn-blue  js_widget_edit"><i class="fad fa-comment-alt-edit"></i></button>\
        <button type="button" class="btn btn-red    js_widget_delete"><i class="fad fa-trash"></i></button>\
        <button type="button" class="btn btn-green  js_widget_copy"><i class="fad fa-copy"></i></button>\
    </div><div class="_widget_border_box_top"></div><div class="_widget_border_box_left"></div><div class="_widget_border_box_right"></div><div class="_widget_border_box_bottom"></div>');
}

$('.js_widget_builder').each(function (index, e) {
    widget_builder_action_add_button($(this))
});