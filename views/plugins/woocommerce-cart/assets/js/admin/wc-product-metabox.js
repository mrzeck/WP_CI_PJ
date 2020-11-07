var body = $(document);

var prefix = 'wcmc_ajax_';

$(document).ready(function() {

    $('.attribute_values').select2();

    if( isset($('#result-attributes-items').html()) ) wcmc_attributes_load();
    if( isset($('#result-variations-items').html()) ) wcmc_variations_load();
});

/**
 * [ATTRIBUTE]
 */
body.on('click', '#add-group-attributes .save-attributes', function() {

	var id = $('#add-group-attributes').find('select#wcmc_option_id').val();

	var data = {
		'action' : prefix+'attribute_add',
		id : id,
		object_id : $('#wcmc_metabox').attr('data-object-id'),
	};

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

  	$jqxhr.done(function( data ) {
	    show_message(data.message, data.type);
	    if(data.type == 'success') {
	    	$('#result-attributes-items').append(data.data);
	    	$('.attribute_values').select2();
	    }
	});

	return false;
});

body.on('click', '#add-group-attributes .save-attributes-all', function () {

	var panel = $('.tab-pane.active .panel');

	var data = $(':input', panel.find('.panel-body')).serializeJSON();

	data.action = prefix + 'attribute_add_all';

	data.object_id = $('#wcmc_metabox').attr('data-object-id');

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {

		show_message(data.message, data.type);

		if (data.type == 'success') {

			$('#result-attributes-items').append(data.data);

			$('.attribute_values').select2();
		}

	});

	return false;
});

body.on('change', 'select.attribute_values', function () {

	$this = $(this);

	var id = $('#wcmc_metabox').attr('data-object-id');

	var panel = $('.tab-pane.active .panel');

	var data = $(':input', panel.find('.panel-body')).serializeJSON();

	data.action = prefix + 'attribute_save';

	data.object_id = id;

	data.session_id = $('#wcmc_metabox').attr('data-session-id');

	$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

	$jqxhr.done(function (data) {

		if (data.type == 'success') {

			if (id == 0) {

				$('#wcmc_metabox').attr('data-session-id', data.session_id);

				$('#wcmc_metabox').find('input[name="wcmc_metabox_session_id"]').val(data.session_id);

			}

			wcmc_variations_load();
		}
		else {
			show_message(data.message, data.type);
		}
	});

	return false;
});
 
body.on('click', '.save-group-attributes', function() {

	$this = $(this);

	var id = $('#wcmc_metabox').attr('data-object-id');

	var panel = $('.tab-pane.active .panel');

	var data = $( ':input', panel.find('.panel-body') ).serializeJSON();

	data.action 	= prefix+'attribute_save';
	data.object_id 	= id;
	data.session_id = $('#wcmc_metabox').attr('data-session-id');

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

    $jqxhr.done(function( data ) {
	    show_message(data.message, data.type);
	    if(data.type == 'success') {
	    	if(id == 0) {
	    		$('#wcmc_metabox').attr('data-session-id', data.session_id);
	    		$('#wcmc_metabox').find('input[name="wcmc_metabox_session_id"]').val(data.session_id);
	    	}
	    	wcmc_variations_load();
	    }
	});

	return false;
});



var del_attributes_id = 0;

body.on('click', '.attribute-del', function(event)  {
  	del_attributes_id   = $(this).attr('data-id');
  	return false;
});


var del_variations_id = 0;

body.on('click', '.variations-del', function(event)  {
  	del_variations_id   = $(this).attr('data-id');
  	return false;
});

body.on('DOMNodeInserted', function(e) {
	//xóa metabox các thuộc tính
    $('.attribute-del').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
		callback:function ( event ) {
	    	if(del_attributes_id == null || del_attributes_id.length == 0) {
	      		show_message('Không có dữ liệu nào được xóa ?', 'error');
	    	}
	    	else {

	      		var data ={
	          		'action' 		: prefix+'attribute_del',
	          		'data'   		: del_attributes_id,
	          		'product_id'   	: $('#wcmc_metabox').attr('data-object-id'),
	          		'session_id'	: $('#wcmc_metabox').attr('data-session-id'),
	      		};

	      		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

		      	$jqxhr.done(function( data ) {
		          	show_message(data.message, data.type);
		          	if(data.type == 'success')
		          	{
		          		var button = event.data.originalObject;
		                  	button.closest( '.panel' ).remove();
		          	}

		        	if(data.type == 'reload') { location.reload(); }
		      	});
	    	}
	  	},
	});

	//xóa metabox các biến thể
	$('.variations-del').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
		callback:function ( event ) {
	    	if(del_variations_id == null || del_variations_id.length == 0) {
	      		show_message('Không có dữ liệu nào được xóa ?', 'error');
	    	}
	    	else {

	      		var data ={
	          		'action' 		: prefix+'variations_del',
	          		'data'   		: del_variations_id,
	          		'product_id'   	: $('#wcmc_metabox').attr('data-object-id'),
	          		'session_id'	: $('#wcmc_metabox').attr('data-session-id'),
	      		};

	      		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

		      	$jqxhr.done(function( data ) {
		          	show_message(data.message, data.type);
		          	if(data.type == 'success')
		          	{
		          		var button = event.data.originalObject;
		                  	button.closest( '.panel' ).remove();
		          	}

		        	if(data.type == 'reload') { location.reload(); }
		      	});
	    	}
	  	},
	});
});

/**
 * [VARIATIONS]
 */
body.on('click', '#add-group-variations .save-variations', function() {

	var data = {
		'action' 	: prefix+'variations_add',
		id 			: $('#wcmc_metabox').attr('data-object-id'),
		session_id 	: $('#wcmc_metabox').attr('data-session-id'),
	};

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

    $jqxhr.done(function( data ) {
	    show_message(data.message, data.type);
	    if(data.type == 'success') {
	    	$('#result-variations-items').append(data.data);
	    }
	});

	return false;
});

function wcm_responsive_filemanager_callback(field_id) {

  var url = $('#'+field_id).val();

	if(url.length > 0) {
	    $('#'+field_id).closest('.form-group').find('.field-btn-img').attr('src',url);
	}
    parent.$.fancybox.close();
}

body.on('click', '.save-group-variations', function() {

	$this = $(this);

	var panel = $('.tab-pane.active .panel');

	var data = $( ':input', panel.find('.panel-body') ).serializeJSON();

	panel.find('.panel-heading select').each( function( index, element ) {
		var select = $( element );
		data[ select.attr( 'name' ) ] = select.val();
	});

	data.action 	= prefix+'variations_save';
	data.id 		= panel.attr('data-variations-id');
	data.object_id 	= $('#wcmc_metabox').attr('data-object-id');
	data.session_id = $('#wcmc_metabox').attr('data-session-id');

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

    $jqxhr.done(function( data ) {
	    show_message(data.message, data.type);
	});

	return false;
});

function wcmc_attributes_load() {
	var data = {};
	data.action 	= prefix+'attribute_load';
	data.object_id 	= $('#wcmc_metabox').attr('data-object-id');
	data.session_id = $('#wcmc_metabox').attr('data-session-id');

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

    $jqxhr.done(function( data ) {

	    if(data.type == 'success') {
	    	$('#result-attributes-items').html(data.data);
	    	$('.attribute_values').select2();
	    }
	});
}

function wcmc_variations_load() {
	var data = {};
	data.action     = prefix+'variations_load';
	data.object_id  = $('#wcmc_metabox').attr('data-object-id');
	data.session_id = $('#wcmc_metabox').attr('data-session-id');

	$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

    $jqxhr.done(function( data ) {
	    if(data.type == 'success') {
	    	$('#result-variations-items').html(data.data);
	    }
	});
}