$(function() {

	var FbmHandler = function() {
		$( document )
			.on('click', '.btn-fbm-active', this.onClickActive)
			.on('change','.fbm-send-message input, .fbm-send-message select', this.onSendMessageChange)
			.on('submit','form[name="fbm-send-message"]', this.onSettingSave)
			.on('submit','form[name="fbm-tab"]', this.onSettingSave)
	};

	FbmHandler.prototype.onClickActive = function(e) {

		var name 	= $(this).attr('data-id');

		data = {
			'action' 		: 'ajax_fbm_active',
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
	FbmHandler.prototype.onSendMessageChange = function(e) {

		data = $( ':input', $('.fbm-send-message')).serializeJSON();

		console.log(data);

		if( data.fbm_title.length > 0 ) $('#fbm_box .fbm-chat-title span').text(data.fbm_title)
		if( data.fbm_color_text.length > 0 ) $('#fbm_box .fbm-chat-title').css('color', data.fbm_color_text);
		if( data.fbm_color_bg.length > 0 ) $('#fbm_box .fbm-chat-header').css('background-color', data.fbm_color_bg);

		if( data.fbm_position.length > 0 ) 		{

			$('#fbm_box').removeClass('box-fbm-chat-left');
			$('#fbm_box').removeClass('box-fbm-chat-right');
			$('#fbm_box').addClass('box-fbm-chat-'+data.fbm_position);
		}

	};

	FbmHandler.prototype.onSettingSave = function(e) {

		style = $(this).attr('name');

		data        = $( ':input', $(this)).serializeJSON();
		
		data.action = 'ajax_fbm_save_setting';

		data.style  = style;

		$('.ajax-load-qa').show();
		
		$jqxhr      = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

        	show_message(data.message, data.type);

        	$('.ajax-load-qa').hide();

    	});

		return false;
	};



	/**
	 * Init AddToCartHandler.
	 */
	new FbmHandler();
});