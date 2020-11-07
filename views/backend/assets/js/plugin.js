$(function() {
	var PluginHandler = function() {
		$( document )
			.on( 'click', '.pl-install', this.download )
	};

	/**
	 * [Download widget]
	 * @param {[type]} e [description]
	 */
	PluginHandler.prototype.download = function(e) {

		var name 	= $(this).attr('data-url');

		var button = $(this);

		if(name.length == 0) { show_message('Không được bỏ trống tên menu', 'error'); return false; }

		$(this).text('Đang download');

		data = {
			'action' 		: 'ajax_plugin_download',
			'name' 			: name,
		}

		console.log(data);

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

	        show_message(data.message, data.type);

	        if(data.type == 'success') {

	        	button.text('Đang cài đặt');

	        	setTimeout( function()  {
	        		PluginHandler.prototype.install( button );
	        	}, 500);

	        	
	        }
	    });

	    return false;
	}

	/**
	 * [Active Widget]
	 * @param {[type]} e [description]
	 */
	PluginHandler.prototype.install = function( button ) {

		var name 	= button.attr('data-url');

		data = {
			'action' 		: 'ajax_plugin_install',
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

	        show_message(data.message, data.type);

	        button.text('Đã cài đặt');
	    });

	    return false;
	}

	/**
	 * Init AddToCartHandler.
	 */
	new PluginHandler();
})