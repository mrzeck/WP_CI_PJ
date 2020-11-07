$(function() {

	var CmiHandler = function() {
		$( document )
			.on('click', '.btn-cmi-active', this.onClickActive)
			.on('change','.cmi-style-1 input, .cmi-style-1 select', this.onStyle1Change)
			.on('keyup change','.cmi-style-2 input, .cmi-style-2 select', this.onStyle2Change)
			.on('keyup change','.cmi-style-3 input, .cmi-style-3 select', this.onStyle3Change)
			.on('submit','form[name="style-1"]', this.onStyleSave)
			.on('submit','form[name="style-2"]', this.onStyleSave)
			.on('submit','form[name="style-3"]', this.onStyleSave)
			.on('submit','form[name="style-4"]', this.onStyleSave)
	};

	CmiHandler.prototype.onClickActive = function(e) {

		var name 	= $(this).attr('data-id');

		data = {
			'action' 		: 'ajax_cmi_active_style',
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
        	show_message(data.message, data.type);
        	if(data.type == 'success') window.location.reload();
    	});

		return false;
	};

	//STYLE 1
	CmiHandler.prototype.onStyle1Change = function(e) {

		data = $( ':input', $('.cmi-style-1')).serializeJSON();

		if( data.cmi_enable_fb   == 0 ) $('.ppocta-ft-fix #messengerButton').css('display','none'); 
		if( data.cmi_enable_fb   == 1 ) $('.ppocta-ft-fix #messengerButton').css('display','inline-block');
		if( data.cmi_enable_zalo == 0 ) $('.ppocta-ft-fix #zaloButton').css('display','none'); 
		if( data.cmi_enable_zalo == 1 ) $('.ppocta-ft-fix #zaloButton').css('display','inline-block');

		if( data.cmi_enable_sms  == 0 ) $('.ppocta-ft-fix #registerNowButton').css('display','none'); 
		if( data.cmi_enable_sms  == 1 ) $('.ppocta-ft-fix #registerNowButton').css('display','inline-block');

		if( data.cmi_enable_call == 0 ) $('.ppocta-ft-fix #callNowButton').css('display','none'); 
		if( data.cmi_enable_call == 1 ) $('.ppocta-ft-fix #callNowButton').css('display','inline-block');
	};

	//STYLE 2
	CmiHandler.prototype.onStyle2Change = function(e) {
		data = $( ':input', $('.cmi-style-2')).serializeJSON();

		if( data.cmi_title_call.length > 0 ) 	$('.style-2-call a span').text(data.cmi_title_call)

		if( data.cmi_title_sms.length > 0 ) 	$('.style-2-sms a span').text(data.cmi_title_sms)

		if( data.cmi_title_contact.length > 0 ) $('.style-2-contact a span').text(data.cmi_title_contact)

		if( data.cmi_bg.length > 0 ) $('.box-style-2').css('background-color', data.cmi_bg);
	};

	//STYLE 3
	CmiHandler.prototype.onStyle3Change = function(e) {

		data = $( ':input', $('.cmi-style-3')).serializeJSON();

		if( data.cmi_color_icon.length 	  > 0 ) $('.quick-alo-ph-img-circle').css('background-color', data.cmi_color_icon);

		if( data.cmi_color_border1.length > 0 ) $('.quick-alo-ph-circle-fill').css('background-color', data.cmi_color_border1);

		if( data.cmi_color_border2.length > 0 ) $('.quick-alo-ph-circle').css('border-color', data.cmi_color_border2);

		if( data.cmi_bottom.length > 0 ) {

			$('.cmi-box-style-3').css("bottom", data.cmi_bottom+"px");
		}

		if( data.cmi_position.length > 0 ) {

			$('.cmi-box-style-3').removeClass('phone-right');

			$('.cmi-box-style-3').removeClass('phone-left');

			$('.cmi-box-style-3').addClass('phone-'+data.cmi_position);
		}
	};

	CmiHandler.prototype.onStyleSave = function(e) {

		style = $(this).attr('name');

		data        = $( ':input', $(this)).serializeJSON();

		data.action = 'ajax_cmi_save_style';

		data.style  = style;

		$('#ajax_item_save_loader').show();

		$jqxhr      = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

        	show_message(data.message, data.type);

			$('#ajax_item_save_loader').hide();
    	});

		return false;
	};

	/**
	 * Init AddToCartHandler.
	 */
	new CmiHandler();
});