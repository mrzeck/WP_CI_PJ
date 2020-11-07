$(function() {
	$('.email-register-form').submit(function(){
		
		var form = $(this);

		var data = $(this).serialize();

		data.action = 'ajax_email_register';

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

			alert(data.message);

			if( data.type == 'success' ) {

				form.find('input[name="email"]').val('');
			}

		});

		return false;
	});
});
