var body     	= $(document);

var id 			= 0;

var check 		= false;

var UserHandler = function() {
	$( document )
		.on( 'submit', '#user-form-login', this.login )
		.on( 'submit', '#form-check-pass', this.resetPassSecurity )
		.on( 'submit', '#form-reset-pass', this.resetPass )
};

UserHandler.prototype.login = function(e) {

	var data = {
		'action'   : 'ajax_user_login',
		'username' : $(this).find('input[name="username"]').val(),
		'password' : $(this).find('input[name="password"]').val(),
	};

	load = $(this).find('.loader');

	load.show();

	$('button[type="submit"]').hide();

	$jqxhr   = $.post(base+'/ajax',data, function(data) {}, 'json');

	$jqxhr.done(function( data ) {

      	show_message(data.message, data.type);

      	if(data.type == 'success') {

			$('.wcm-loading').show();

			setTimeout( function() {

				var redirect_to = getParameterByName('redirect_to');

				window.location = redirect_to;

			}, 1000 );
      		
      	}
      	else {

      		load.hide();

			$('button[type="submit"]').show();
      	}
    });

    return false;
}
UserHandler.prototype.resetPassSecurity = function(e) {

	var datas = {
		'action'   : 'ajax_reset_pass',
		'password' : $('#form-check-pass input[name="password"]').val(),
		'check'	   : check,
	}

	$jqxhr   = $.post(base+'/ajax', datas, function(data) {}, 'json');

	$jqxhr.done(function( data ) {

		if(data.type == 'success') {

			$('#form-check-pass').find('label').html('<span style="color:red">Mật khẩu mới</span>');

			$('#form-check-pass').find('input[name="password"]').val('');

			$('#form-check-pass').attr('id','form-reset-pass');

			check = true;
		}
		else {
			show_message(data.message, data.type);
		}
    });

    return false;
}
UserHandler.prototype.resetPass = function(e) {

	var datas = {
		'action'	: 'ajax_reset_pass',
		'password' 	: $('#form-reset-pass input[name="password"]').val(),
		'id'	   	: id,
		'check'	   	: check,
	}

	$jqxhr   = $.post(base+'/ajax', datas, function(data) {}, 'json');

	$jqxhr.done(function( data ) {

		show_message(data.message, data.type);

		if(data.type == 'success') {

			$('#modalreset').modal('hide');

			$('#form-reset-pass').find('label').html('Mật Khẩu của bạn');

			$('#form-reset-pass').find('input[name="password"]').val('');

			$('#form-reset-pass').attr('id','form-check-pass');

			check = false;

			id = 0;
		}
    });

    return false;
}

$(function() {
	new UserHandler();
});

body.on('click', '.btn-reset-pass', function(){
	id  = $(this).attr('href');
	$('#modalreset').modal('show');
	return false;
});

$('.user-trash').bootstrap_confirm_delete({

	heading:'Xác nhận xóa',

	message:'Bạn muốn xóa trường dữ liệu này ?',

	callback:function ( event ) {

		var del_id = event.data.originalObject.attr('data-id');

		$jqxhr   = $.post(base+'/ajax', { 'action' : 'ajax_user_trash', id : del_id }, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

		    show_message(data.message, data.type);

		    if(data.type == 'success') {

				var button = event.data.originalObject;

        		button.closest( 'tr' ).remove();
		    }
		});
    },
});

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}