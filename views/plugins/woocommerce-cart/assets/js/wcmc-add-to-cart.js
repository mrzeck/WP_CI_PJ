$( function( $ ) {

	if( typeof $('.wcm-box-order').html() != 'undefined' ) {
		wcmc_update_order_review();
	}

	var product_id = 0;
	/**
	 * AddToCartHandler class.
	 */
	var AddToCartHandler = function() {
		$( document )
			.on( 'click', '.wcmc-product-cart-options .options .item', this.onAttribute )
			.on( 'mouseover', '.wcmc-product-cart-options .options .option-type__swatch', this.upOptionLabel )
			.on( 'click', '.wcmc-product-cart-options .options .option-type__radio', this.upDataProduct )
			.on( 'click', '.wcmc_add_to_cart', this.onAddToCart )
			.on( 'click', '.wcmc_add_to_cart_now', this.onAddToCartNow )

	};

	/**================================================================
	 * [ACTION EVENT]
	 * ================================================================
	 */
	AddToCartHandler.prototype.onAttribute = function( e ) {

		$(this).closest('.options').find('label').removeClass('active');

		$(this).closest('label').addClass('active');

		img = $(this).closest('label').find('.item').attr('data-image');
		
		if( img != '' && typeof img != 'undefined' ) {
            $('.box-image-featured img').attr('src', img);
            $('.zoomWindowContainer .zoomWindow').css('background-image','url("'+img+'")');
            $('#sliderproduct a').removeClass('active');
        }

	};

	AddToCartHandler.prototype.upOptionLabel = function( e ) {

		$(this).closest('.wcmc-product-cart-options').find('.option-type__selected-option').text( $(this).attr('data-label') );

	};


	//event khi chọn các option nâng cao
	AddToCartHandler.prototype.upDataProduct = function( e ) {

        var data_product_options    = $('#wcmc-form-options').attr('data-product-option');

        if( typeof data_product_options == 'undefined') {
        	AddToCartHandler.prototype.upDataProduct_V1($(this));
        }
        else {

        	i = $(this).parent();

	        group   = i.attr('data-group');

	        id      = i.attr('data-id');
	        
	        data_product_options    = JSON.parse(data_product_options);

	        var data_product_variations    	= JSON.parse($('#wcmc-form-options').attr('data-product-variations'));

	        var data        = $( ':input', $('.wcm-box-options')).serializeJSON();


	        if( countProperties(data_product_options) != 0) {

	            icheck = false;

	            $('.wcmc-product-cart-options .options label').each(function(){
	                if( $(this).attr('data-group') != group ) {
	                    $(this).addClass('option-type__disabled');
	                } else {
	                    $(this).removeClass('option-type__disabled');
	                }
	            });

	            for(var g in data_product_options[group][id]) {

	                for(var t in data_product_options[group][id][g]) {

	                    $('.option-type__'+g+'_'+data_product_options[group][id][g][t]).removeClass('option-type__disabled');

	                    if($('.option-type__'+g+'_'+data_product_options[group][id][g][t]).hasClass('active') == true ) {
	                        icheck = true
	                    }
	                }
	            }

	            if( icheck == false ) {

	                $('.wcmc-product-cart-options .options label').each(function(){
	                    if( $(this).attr('data-group') != group ) {
	                        $(this).removeClass('active');
	                        $(this).find('input').prop('checked', false);
	                    }
	                });

	            }

	            count_option_item = countProperties(data.option);

				check_option_item = true;

				for( var k in data_product_variations ) {
					console.log(count_option_item);
					console.log(countProperties(data_product_variations[k].items));
					if( countProperties(data_product_variations[k].items) != count_option_item ) {
						check_option_item = false; break;
					}
				}

				if( check_option_item == true ) {

					data.product_id =  $(this).attr('data-id');
				
					data.action     =  'wcmc_ajax_product_variations';

					$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			  		$jqxhr.done(function( data ) {

					    if(data.type == 'success') {

					    	$('.wcmc-price-detail').html(data.data);
					    }
					});

				}
	        }

	    }
	};

	AddToCartHandler.prototype.upDataProduct_V1 = function( t ) {

		var data        = $( ':input', $('.wcm-box-options')).serializeJSON();

		data.product_id =  t.attr('data-id');
		
		data.action     =  'wcmc_ajax_product_variations';

		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

  		$jqxhr.done(function( data ) {

		    if(data.type == 'success') {

		    	$('.wcmc-price-detail').html(data.data);
		    }
		});
	};

	//event khi click vào nút đặt hàng
	AddToCartHandler.prototype.onAddToCart = function( e ) {

		$('.wcmc-alert-product').html('');

		var data        = $( ':input', $('.wcm-box-options')).serializeJSON();

		data.product_id =  $(this).attr('data-id');

		data.qty 		=  $('#quantity').val();

		if( typeof data.qty == 'undefined' ) data.qty = 1;

		data.action     =  'wcmc_ajax_cart_add';

		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

  		$jqxhr.done(function( data ) {

  			$('.wcmc-alert-product').html(data.data);

		    if(data.type == 'success') {
		    	$('.wcmc-total-items').html(data.total_items);
		    }
		});

		return false;
	};

	AddToCartHandler.prototype.onAddToCartNow = function( e ) {

		$('.wcmc-alert-product').html('');

		var data        = $( ':input', $('.wcm-box-options')).serializeJSON();

		data.product_id =  $(this).attr('data-id');

		data.qty 		=  $('#quantity').val();

		if( typeof data.qty == 'undefined' ) data.qty = 1;

		data.action     =  'wcmc_ajax_cart_add';

		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

  		$jqxhr.done(function( data ) {

  			$('.wcmc-alert-product').html(data.data);

		    if(data.type == 'success') {

		    	$('.wcmc-total-items').html(data.total_items);

		    	window.location = 'gio-hang';
		    }
		});

		return false;
	};
	/**
	 * Init AddToCartHandler.
	 */
	var wcmc_addtocart = new AddToCartHandler();
});

//check out
$(document).on('submit', '.woocommerce-checkout', function() {

	var data = $( ':input', $(this) ).serializeJSON();

	data.action 	= 'wcmc_ajax_checkout_save';

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

	$('.wcm-box-order').addClass('loading');

	$jqxhr.done(function( data ) {

		$('.wcm-box-order').removeClass('loading');

		$('.woocommerce-checkout .notice').remove();

		$('.woocommerce-checkout .toast').remove();

		$('.error_message').html('');

		$('.input_checkout').removeClass('error_show');

		$('.input_checkout').removeClass('error_input');

	    if(data.type == 'success') {

	    	window.location = data.url;
	    }
	    else {

			var count_error = 0;

			var notice = data.message;

			for (const [index, message] of Object.entries(notice)) {

				if ( typeof $('#error_' + index).html() != 'undefined') {

					count_error++;
					
					$('#error_' + index).closest('.input_checkout').addClass('error_input');

					$('#error_' + index).html(message);
				}
				else {
					$('.woocommerce-checkout').prepend(message);
				}
			}

			if (count_error > 0) {

				$('.input_checkout').addClass('error_show');
			}

			if (typeof $('.woocommerce-checkout .toast').html() != 'undefined') {
				$('html, body').animate({
					scrollTop: $(".woocommerce-checkout .toast").offset().top - 100
				}, 500);
			}
	    }

	});

	return false;
});

$('#billing_city').change(function () {

	var data = {
		province_id: $(this).val(),
		action: 'wcmc_ajax_load_districts'
	}

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {
		if (data.type == 'success') {
			$('#billing_districts').html(data.data);
			$('#billing_ward').html('<option value="">Chọn phường xã</option>');
			
			if($('input[name="show-form-shipping"]').prop('checked') == false) wcmc_update_order_review();
		}
	});

});

$('#billing_districts').change(function () {

	var data = {
		district_id: $(this).val(),
		action: 'wcmc_ajax_load_ward'
	}

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {

		if (data.type == 'success') {

			$('#billing_ward').html(data.data);

			if ($('input[name="show-form-shipping"]').prop('checked') == false) wcmc_update_order_review();
		}
	});

});

$('#shipping_city').change(function () {

	var data = {
		province_id: $(this).val(),
		action: 'wcmc_ajax_load_districts'
	}

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {

		if (data.type == 'success') {

			$('#shipping_districts').html(data.data);

			$('#shipping_ward').html('<option value="">Chọn phường xã</option>');

			if ($('input[name="show-form-shipping"]').prop('checked') == true) wcmc_update_order_review();

		}
	});

});

$('#shipping_districts').change(function () {

	var data = {
		district_id: $(this).val(),
		action: 'wcmc_ajax_load_ward'
	}

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {

		if (data.type == 'success') {

			$('#shipping_ward').html(data.data);

			if ($('input[name="show-form-shipping"]').prop('checked') == true) wcmc_update_order_review();
		}
	});

});

$(document).on('change', '.woocommerce-checkout input[name="shipping_type"]', function () {
	wcmc_update_order_review();
});

$(document).on('change', '.woocommerce-checkout input[name="show-form-shipping"]', function () {
	wcmc_update_order_review();
});

function wcmc_update_order_review( ) {

	$('.wcm-box-order').addClass('loading');
	
	var data = $('form[name="checkout"]').serializeJSON();

	data.action = 'wcmc_ajax_update_order_review';

	$jqxhr   = $.post( base + '/ajax' , data, function() {}, 'json');

	$jqxhr.done(function( data ) {

		if( data.type == 'success' ) {

			$('.wcm-box-order').html( data.order_review );

			$('.wcm-box-order').removeClass('loading');
		}

	});
}

function countProperties(obj) {

	var prop;

  	var propCount = 0;

  	for (prop in obj) {
    	propCount++;
  	}
  	return propCount;
}