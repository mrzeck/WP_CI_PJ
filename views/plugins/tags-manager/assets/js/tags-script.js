var popover_input_tags = $('.input-popover-group .input-popover-tags-search');

var popover_tags_search_result = [];

var popover_tags_result = [];

var timmer;

//on keyup, start the countdown
popover_input_tags.on('keyup', function (e) {
    popover_tags_doneTyping(e, $(this), 0);
});


popover_input_tags.each(function () {

    id_popover_index = $(this).closest('.input-popover-tags').attr('id');

    popover_tags_result[id_popover_index] = $(this).closest('.input-popover-tags').find('.popover__ul').html();
});

$(document).on('focus', '.input-popover-tags .input-popover-tags-search', function () {

    popover_tags_doneTyping($(this));

    $('.popover-content').removeClass('popover-content--is-active');

    $(this).closest('.input-popover-group').find('.popover-content').addClass('popover-content--is-active');

    $(this).closest('.input-popover-group').find('.popover__ul li.option').first().addClass('is--select');

});

$(document).on('click', '.input-popover-tags .popover__ul li.option-tag-add', function () {

    box = $(this).closest('.input-popover-tags');

    text = box.find('input.input-popover-tags-search').val();

    text = text.trim();

    if (text.length > 0) {

        name = box.attr('data-name');

        rm = false;

        $(box).find('input.inp-' + name +'').each(function (index, e) {
            
            if ($(this).val() == text) {

                $(this).closest('li.collection-list__li').remove();

                rm = true;

                return false;
            }
             
        });

        if (rm == false) {
            box.find('.collection-list').prepend(tags_popover_collection_item(name, text));
        }
    }

    box.find('input.input-popover-tags-search').val('');

    box.find('.popover-content').removeClass('popover-content--is-active');

    return false;
});

$(document).on('click', '.input-popover-tags .popover__ul li.option-tag-data', function () {

    box  = $(this).closest('.input-popover-tags');

    text = $(this).find('.item-tag__name span').text();

    text = text.trim();

    if (text.length > 0) {

        name = box.attr('data-name');

        rm = false;

        $(box).find('input.inp-' + name + '').each(function (index, e) {

            if ($(this).val() == text) {

                $(this).closest('li.collection-list__li').remove();

                rm = true;

                return false;
            }

        });

        if (rm == false) {
            box.find('.collection-list').prepend(tags_popover_collection_item(name, text));
        }
    }

    box.find('input.input-popover-tags-search').val('');

    box.find('.popover-content').removeClass('popover-content--is-active');

    return false;
});

$(document).on('keyup', '.input-popover-tags input.input-popover-tags-search', function (e) {

    if (e.keyCode == 13) {

        box = $(this).closest('.input-popover-tags');

        text = box.find('input.input-popover-tags-search').val();

        text = text.trim();

        if (text.length > 0) {

            name = box.attr('data-name');

            rm = false;

            $(box).find('input.inp-' + name + '').each(function (index, e) {

                if ($(this).val() == text) {

                    $(this).closest('li.collection-list__li').remove();

                    rm = true;

                    return false;
                }

            });

            if (rm == false) {
                box.find('.collection-list').prepend(tags_popover_collection_item(name, text));
            }
        }

        box.find('input.input-popover-tags-search').val('');

        box.find('.popover-content').removeClass('popover-content--is-active');
    }

    return false;
});

$(document).on('click', '.input-popover-tags .collection-list-delete', function () {

    $(this).closest('.collection-list__li').remove();

    return false;
});

function tags_popover_collection_item(name, text) {

    item = '<li class="collection-list__li"><input type="hidden" name="' + name + '[]" value="' + text + '" class="inp-'+name+'"><div class="collection-list__grid">';
    item += '<div class="collection-list__cell"><a href="">' + text + '</a></div>';
    item += '<div class="collection-list__cell"> <button class="ui-button collection-list-delete"> <i class="fal fa-times"></i> </button> </div> </div> </li>';

    return item;
}

function popover_tags_doneTyping(e, th, call) {

    if (typeof (call) == 'undefined') call = false;

    if (!call) {
        clearTimeout(timmer);
        timmer = setTimeout(function () {
            popover_tags_doneTyping(e, th, true);
        }, 300);
        return;
    }

    if (typeof (th) != 'undefined') {

        ebox = th.closest('.input-popover-tags');

        id_popover_index = ebox.attr('id');

        // console.log(popover_result[id_popover_index]);

        ebox.find('.popover__loading').show();
        ebox.find('.popover__ul').hide();

        cinput = [];

        ebox.find('input[type="checkbox"]').each(function () {
            cinput[$(this).val()] = $(this).val();
        });

        keyword = ebox.find('.input-popover-tags-search').val();

        keyword = keyword.trim();

        if (keyword.length == 0) {

            ebox.find('.popover__loading').hide();
            ebox.find('.popover__ul').show();
            ebox.find('.popover__ul').html(popover_tags_result[id_popover_index]);

            if (typeof cinput != 'undefined' && cinput.length == 0) {

                ebox.find('.popover__ul li.option').removeClass('option--is-active');

                popover_tags_result[id_popover_index] = ebox.find('.popover__ul').html();

            } else {
                ebox.find('.popover__ul li.option').each(function () {
                    key = $(this).attr('data-key');
                    if (typeof cinput[key] != 'undefined' && cinput[key] == key) $(this).addClass('option--is-active');
                });
            }

            return false;
        }

        if (typeof popover_tags_search_result[keyword] != 'undefined') {
            ebox.find('.popover__loading').hide();
            ebox.find('.popover__ul').show();
            ebox.find('.popover__ul').html(popover_tags_search_result[keyword]);
        }
        else {

            data = {
                'keyword': keyword,
                'select': cinput,
                'module': ebox.attr('data-module'),
                'key_type': ebox.attr('data-key-type'),
                'action': 'ajax_input_popover_search',
            };

            $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

            $jqxhr.done(function (data) {

                ebox.find('.popover__loading').hide();
                ebox.find('.popover__ul').show();

                popover_tags_search_result[keyword] = data.data;

                ebox.find('.popover__ul').html(data.data);
            });
        }
    }
}