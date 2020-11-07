$(function() {

    var order_provisional = 0;

    var order_total  = 0;

    var order_detail = [];

    if(isset($('#order_amount').html())) wcmc_order_save_review();

    $('#order_product_items .order_product_list .order_product__item').each(function () { 

        product_id = $(this).find('input.line_item_id').val()+'_'+$(this).find('input.line_item_variation').val();

        order_detail[product_id] = product_id;

        console.log(order_detail);
    });

    $(document).on('click', '#box_order_search_product .popover__ul li.option', function(e) {

        item = JSON.parse($(this).attr('data-product'));

        product_id = item.id + '_' + item.variation;

        if (typeof order_detail != 'undefined' && order_detail.length == 0) {

            if (typeof order_detail[product_id] != 'undefined' && order_detail[product_id] == product_id) {

                $('.order_product__item_' + product_id + ' .order_product__item-quantity input').focus();

                return false;
            }
        }

        order_detail[product_id] = product_id;

        order_provisional += parseInt(item.price);

        pr = '<div class="order_product__item order_product__item_' + item.id + '_' + item.variation +'">';
        //input
        pr += '<div class="order_product__item-input">';
        pr += '<input name="line_items[' + product_id+'][productID]"      class="line_item_id" value="'+item.id+'"     type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productName]"    class="line_item_name" value="'+item.title+'"  type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productPrice]"   class="line_item_price" value="'+item.price+'"  type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productQuantity]"   class="line_item_quantity" value="1"  type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productVariation]"   class="line_item_variation" value="'+item.variation+'"  type="hidden">';
        pr += '</div>';
        //img
        pr += '<div class="order_product__item-img -left -item"><img src="'+item.image+'" class="img-responsive" alt="Image"></div>';
        //title
        pr += '<div class="order_product__item-name -left -item"><span> ' + item.title;

        if (isset(item.attr_name))
            pr += ' <small style="font-size:11px;color: #29bc94;" > '+ item.attr_name +'</small >';

        pr += '</span></div>';
        //price
        pr += '<div class="order_product__item-price -left -item"><span>' + item.price + '₫</span> </div>';
        //input quantity
        pr += '<div class="order_product__item-quantity -left -item"><input value="1" style="display:inline-block; text-align: center;min-width: 5em;width:0" id="line_items_0_.quantity" class="form-control" min="1" type="number"></div>';
        //input delete
        pr += '<div class="order_product__item-delete -right -item"><a><i class="fal fa-trash"></i></a></div>';
        //total
        pr += '<div class="order_product__item-total -right -item"><span>' + item.price + '</span>₫ </div>';
        pr += '</div>';

        $('.order_product_list').prepend(pr);

        wcmc_order_save_review();
    });

    $(document).on('change', '.order_product__item .order_product__item-quantity input', function (e) {

        item                = $(this).closest('.order_product__item');

        quantity_change     = $(this).val();

        quantity_unchage    = item.find('input.line_item_quantity').val();

        quantity_change = parseInt(quantity_change);

        quantity_unchage = parseInt(quantity_unchage);

        if(quantity_change == 0) {
            
            quantity_change = 1;

            $(this).val(quantity_change);
        }


        if(quantity_change != quantity_unchage) {

            price = item.find('input.line_item_price').val();

            order_provisional = order_provisional - (quantity_unchage * price);

            order_provisional = order_provisional + (quantity_change * price);

            item.find('.order_product__item-total span').html(formatNumber(quantity_change * price, '.', ','));

            item.find('input.line_item_quantity').val(quantity_change);
        }

        wcmc_order_save_review();
    });

    $(document).on('click', '.order_product__item .order_product__item-delete', function (e) {

        item = $(this).closest('.order_product__item');

        quantity = item.find('input.line_item_quantity').val();

        quantity = parseInt(quantity);

        if (quantity == 0) quantity = 1;

        price = item.find('input.line_item_price').val();

        order_provisional = order_provisional - (quantity * price);

        product_id = item.find('input.line_item_id').val() + '_' + item.find('input.line_item_variation').val();

        if (typeof order_detail[product_id] != 'undefined') {

            delete order_detail[product_id];
        }

        item.remove();

        wcmc_order_save_review();
    });

    //click customer
    $(document).on('click', '#order_customer_search .popover__ul li.option', function (e) {

        id = $(this).attr('data-key');

        var data = {
            action: 'wcmc_ajax_order_save_customer',
            id: id
        };

        $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

        $jqxhr.done(function (data) {

            if (data.type == 'success') {

                $('#order_customer_infomation_result').html(data.customer_review);

                $('#order_customer_infomation').show();

                $('#order_customer_search').hide();
            }
            else {
                show_message(data.message, data.type);
            }
        });

    });

    //Save
    $(document).on('submit', '#order_save__form', function (e) {

        var data = $(this).serializeJSON();

        data.action = 'wcmc_ajax_order_save_submit';

        $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

        $jqxhr.done(function (data) {

            show_message(data.message, data.type);

            if (data.type == 'success') {
            }
            else {
            }

        });

        return false;

    });
})

function formatNumber(nStr, decSeperate, groupSeperate) {
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    return x1 + x2;
}

function wcmc_order_save_review() {

    // $('.wcm-box-order').addClass('loading');

    var data = $('#order_save__form').serializeJSON();

    data.action = 'wcmc_ajax_order_save_review';

    $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

    $jqxhr.done(function (data) {

        if (data.type == 'success') {

            $('#order_amount').html(data.order_review);

            // $('.wcm-box-order').removeClass('loading');
        }

    });
}