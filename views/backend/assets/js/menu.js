var body = $(document);

var id = 0;
$(function() {
	init_table();
});

$(document).bind('DOMNodeInserted', function(e) {
	$('.delete').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn có muốn xóa menu này ? ( thao tác này không thể khôi phục )',
		callback:function ( event ) {
			var del_id = event.data.originalObject.attr('data-id');

			if(del_id == null || del_id.length == 0) {
				show_message('Không có dữ liệu nào được xóa ?', 'error');
			}
			else {
				
				var data ={
					'action' : 'ajax_menu_del',
					'data'   : del_id
				};

	            $jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');
	            
	            $jqxhr.done(function( data ) {
				    show_message(data.message, data.type);
					if(data.type == 'success') { location.reload(); }
				});
			}
        },
	});
});

body.on('submit', '#form-menu-group', function(event) {

	var name = $(this).find('input[name="name"]').val();

	if(name.length == 0) {
		show_message('Không được bỏ trống tên menu', 'error');
	}

	data = {
		'action' 		: 'ajax_group_add',
		'object_type' 	: 'menu',
		'name' 			: name,
	}
	$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

	$jqxhr.done(function( data ) {
        show_message(data.message, data.type);
        if(data.type == 'success') window.location.reload();
    });
	return false;
});

body.on('keyup', 'input[name="search"]', function(){
	$this 	= $(this);
	var object 			= $(this).attr('data-object');
	var object_type 	= $(this).attr('data-object-type');
	var key 			= $this.val();
	var box 			= $('#'+object_type+'_search');

	if(key.length >= 2) {

		box.find('.loading').show();

		var data = {
			'action'	: 'ajax_menu_item_search',
			object 		: object,
			object_type : object_type,
			key    		: key,
		}

		$jqxhr = $.post(base+'/ajax', data, function(){}, 'html');

		$jqxhr.done(function(data){
			box.find('.result_search').html(data);
			checkbox_style();
			box.find('.loading').hide();
		});

	} else {
		box.find('.result_search').html('');
	}
	return false;
});

body.on('click','button.btn-add-field', function() {
	$this = $(this);
	$type = $(this).attr('data-type');

	return menu_add_item($type, $this);
});

body.on('click','.icon-delete', function() {

	$this 	= $(this);

	var data = {
		'action': 'ajax_menu_item_del',
		id 	: $this.attr('href'),
	}
	$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

	$jqxhr.done(function(data){
		if(data.type == 'success') {
			$('#sortMenu').html(data.field_html);
			init_table();
		}
		show_message(data.message, data.type);
	});
	return false;
});

body.on('click', '.icon-edit', function(){

	id 	= $(this).attr('href');

	var data = {
		'action': 'ajax_menu_item_edit',
		id 	: id,
	}

	$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

	$jqxhr.done(function(data){
		if(data.type == 'success') {
			$('#modal-edit-menu #form-menu-edit .result').html(data.data);
			// $.fancybox.open({
			// 	src  : '#modal-edit-menu',
			// 	type : 'inline',
			// 	opts : {
			// 		afterShow : function( instance, current ) {
			// 			console.info( 'done!' );
			// 		}
			// 	}
			// });

		}
	});

	return false;
});

body.on('submit', '#form-menu-edit', function(){

	$this = $(this);

	var data = $this.serializeArray();

	data.push({
		'name'	:'id',
		'value' : id
	});
	data.push({
		'name'	:'action',
		'value' : 'ajax_menu_item_save'
	});

	$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

	$jqxhr.done(function(data){
		show_message(data.message, data.type);
		if(data.type == 'success') {
			$.fancybox.close();
		}
	});

	return false;
});

$('input.menu-position').on('ifClicked', function(event){
    var data = {
    	action		:'ajax_menu_position',
    	position 	: $(this).val(),
    	id   		: $(this).attr('data-id'),
    }

    console.log(data);

    $jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

	$jqxhr.done(function(data){
		show_message(data.message, data.type);
	});
	return false;
});

$(document).on('click', '.fancybox-close-small, .btn-close', function() {
    $.fancybox.close();
});


function menu_add_item(type, $this) {
	var input = [];

	var object_type = null;

	if($type != 'link') {
		$this.closest('.panel-body').find('input[type="checkbox"]:checked').each(function() {
			input.push($(this).val());
			object_type = $(this).attr('name');
		});
	}
	else
	{
		input = {
			'link':$('#link_box input[name="url"]').val(),
			'name':$('#link_box input[name="title"]').val(),
		}
		object_type = 'link';
	}

	//loading
	load = $(this).parent().find('.loading');
	load.show();

	var data = {
		'action': 'ajax_menu_add',
		menu_id 	: $this.attr('menu-id'),
		data 		: input,
		type    	: object_type,
		object_type : type,
	}

	$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

	$jqxhr.done(function(data){
		show_message(data.message, data.type);
		if(data.type == 'success')
		{
			data.fields.forEach(function(item){
				$('.sortable').prepend(item);
			});
			if($type != 'link') {
				$this.closest('.panel-body').find('input[type="checkbox"]:checked').iCheck('uncheck');
			}

			load.hide();
		}
	});

	return false;
}

function init_table() {
	return $('ol.sortable').nestedSortable({
      	forcePlaceholderSize: true,
	    handle: 'div',
	    helper: 'clone',
	    items: 'li',
	    opacity: .6,
	    placeholder: 'placeholder',
	    revert: 250,
	    tabSize: 25,
	    tolerance: 'pointer',
	    toleranceElement: '> div',
	    maxLevels: 5,
	    isTree: true,
	    expandOnHover: 700,
	    startCollapsed: false,
	    relocate: function(){
	        menu_sort($(this).nestedSortable('toHierarchy', {startDepthCount: 0}), $(this));
	    }
    });
}

function menu_sort($order, $element) {

	$id = $element.parent().attr('data-id');

	$('#ajax_loader').css( 'display', 'block' );

	var data = {
		'action': 'ajax_menu_sort',
		id 		: $id,
		data 	: $order
	}

	$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

	$jqxhr.done(function(data){
		$('#ajax_loader').css( 'display', 'none' );
		show_message(data.message, data.type);
	});

	return false;
}