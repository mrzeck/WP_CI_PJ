var body = $(document);
$(function(){
	//Search dữ liệu input popover
	var popover_typingTimer;

	var popover_doneTypingInterval = 300; //time in ms, 5 second for example

	var popover_input = $('.input-popover-group .input-popover-search');

	var popover_search_result = [];

	var popover_result = [];

	//on keyup, start the countdown
	popover_input.on('keyup', function () {
		clearTimeout(popover_typingTimer);
		popover_typingTimer = setTimeout(popover_doneTyping($(this)), popover_doneTypingInterval);
	});

	//on keydown, clear the countdown 
	popover_input.on('keydown', function () {
		clearTimeout(popover_typingTimer);
	});

	popover_input.each(function(){

		id_popover_index = $(this).closest('.input-popover-group').attr('id');

		popover_result[id_popover_index] = $(this).closest('.input-popover-group').find('.popover__ul').html();
	});

	$(document).on('focus', '.input-popover-group .input-popover-search', function () {

		popover_doneTyping($(this));

		$('.popover-content').removeClass('popover-content--is-active');

		$(this).closest('.input-popover-group').find('.popover-content').addClass('popover-content--is-active');

		$(this).closest('.input-popover-group').find('.popover__ul li.option').first().addClass('is--select');

	});

	$(document).on('mouseover', '.input-popover-group .popover__ul li.option', function () {

		$('.input-popover-group .popover__ul li.option').removeClass('is--select');

	})

	//user is "finished typing," do something
	function popover_doneTyping(e) {

		ebox = e.closest('.input-popover-group');

		id_popover_index = ebox.attr('id');

		// console.log(popover_result[id_popover_index]);

		ebox.find('.popover__loading').show();
		ebox.find('.popover__ul').hide();

		cinput = [];
		
		ebox.find('input[type="checkbox"]').each(function(){
			cinput[$(this).val()] = $(this).val();
		});

		keyword = ebox.find('.input-popover-search').val();

		keyword = keyword.trim();

		if (keyword.length == 0) {

			ebox.find('.popover__loading').hide();
			ebox.find('.popover__ul').show();
			ebox.find('.popover__ul').html(popover_result[id_popover_index]);

			if (typeof cinput != 'undefined' && cinput.length == 0) {

				ebox.find('.popover__ul li.option').removeClass('option--is-active');

				popover_result[id_popover_index] = ebox.find('.popover__ul').html();
				
			} else {
				ebox.find('.popover__ul li.option').each(function () {
					key = $(this).attr('data-key');
					if (typeof cinput[key] != 'undefined' && cinput[key] == key) $(this).addClass('option--is-active');
				});
			}

			return false;
		}

		if (typeof popover_search_result[keyword] != 'undefined') {
			ebox.find('.popover__loading').hide();
			ebox.find('.popover__ul').show();
			ebox.find('.popover__ul').html(popover_search_result[keyword]);
		} else {

			data = {
				'keyword' : keyword,
				'select'  : cinput,
				'module'  : ebox.attr('data-module'),
				'key_type': ebox.attr('data-key-type'),
				'action'  : 'ajax_input_popover_search',
			};

			$jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

			$jqxhr.done(function (data) {

				ebox.find('.popover__loading').hide();
				ebox.find('.popover__ul').show();

				popover_search_result[keyword] = data.data;

				ebox.find('.popover__ul').html(data.data);
			});
		}
	}
    //đóng mở tab form
    body.on('click', '.btn-collapse', function(event) {

    	$event 	 = $(this);

        var data = {
            'action' : 'ajax_collapse',
            'id'     : $event.attr('id')
        }

    	$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'html');
    	
		$jqxhr.done(function( data ) {});
    });

    //upload boolean
	body.on('ifClicked', '.up-boolean', function(event) {

        var data = {};

        data.action = 'ajax_up_boolean';

        data.id 	= $(this).attr('data-id');

        data.table 	= $(this).attr('data-model');

        data.row 	= $(this).attr('data-row');

    	$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
			show_message(data.message, data.type);
		});
    });

    //upload datatable
    $('.edittable-dl-number').editable({
		type 	: 'number',
		params: function(params) {
	        // add additional params from data-attributes of trigger element
	        params.action = 'ajax_up_table';
	        params.table = $(this).editable().attr('data-table');
	        return params;
	    },
		url 	: base+'/ajax',
	});

	$('.edittable-dl-text').editable({
		type: 'text',
		params: function(params) {
	        // add additional params from data-attributes of trigger element
	        params.action = 'ajax_up_table';
	        params.table = $(this).editable().attr('data-table');
	        return params;
	    },
		url: base+'/ajax',
	});

    //kiểm tra khi click submit form add, edit
    body.on('click', '#form-input button[name="save"]', function() {
        //hiển thị loading
        $('#ajax_loader').show();
        //get form submit
        var $this = $('#form-input');
        //xử lý dữ liệu
        var datas = {};

        $this.find('input, textarea, select').each(function(index, el) {
			datas[$(this).attr('name')] = $(this).val();
		});

        datas['action'] = 'ajax_form_validation';
        datas['module'] = $this.attr('data-module');

        //tiển hành kiểm tra
        $jqxhr   = $.post(base+'/ajax'+url_type, datas, function(data) {}, 'json');

        $jqxhr.done(function( data ) {
		    if(data.type == 'error'){
		    	show_message(data.message, data.type);
                $('#ajax_loader').hide();
		    }
		    else
		    	$this.submit();
		});
		return false;
	});
	
	body.on('submit', '#form-input-category', function () {
		//hiển thị loading
		$('#ajax_loader').show();
		//get form submit
		var $this = $(this);
		//xử lý dữ liệu
		var datas = $(this).serializeJSON();
		
		datas.action = 'ajax_form_submit_category';

		datas.module = $this.attr('data-module');

		//tiển hành kiểm tra
		$jqxhr = $.post(base + '/ajax' + url_type, datas, function (data) { }, 'json');

		$jqxhr.done(function (data) {

			show_message(data.message, data.type);

			$('#ajax_loader').hide();

			if (data.type == 'success') {

				$this.find('input[type="text"]').val('');

				if (isset($this.find('select#parent_id').val())) {

					select = $this.find('select#parent_id').val();

					$this.find('select#parent_id').html(data.parent_id);

					$this.find('select#parent_id').val(select);
				}

				$('#form-action').html(data.item);

				checkbox_style();
			}
		});

		return false;
	});

    //cho vào thùng rác
    var trash_this = null;
    var trash_id   = null;
    body.on('click', '.trash', function(event)  {
    	trash_this = $(this);
    	trash_id = trash_this.attr('data-id');
    	if(!isset(trash_id)) {
    		trash_id = []; var i = 0;
	        $('.select:checked').each(function () {
	            trash_id[i++] = $(this).val();
	        });
    	}
        return false;
    });

    $('.trash').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn muốn xóa trường dữ liệu này ?',
		callback:function ( event ) {

			if(trash_id == null || trash_id.length == 0) {
				show_message('Không có dữ liệu nào được xóa ?', 'error');
			}
			else {
				var data ={
	                'action' : 'ajax_trash',
	                'data'   : trash_id,
	                'table'  : trash_this.attr('data-table'),
	            }
	            $jqxhr   = $.post(base+'/ajax'+url_type, data, function() {}, 'json');
				$jqxhr.done(function( data ) {
				    show_message(data.message, data.type);
				    if(data.type == 'success')
				    {
				    	if (typeof data.data != 'undefined') {
						  	var count = data.data.length;
					    	for (i = 0; i < count; i++) {
					    		$('.tr_'+data.data[i]).hide('fast').remove();
							}
						}
						else
						{
							var button = event.data.originalObject;
	            			button.closest( 'tr' ).remove();
						}
				    }

					if(data.type == 'reload') { location.reload();}
				});
			}
        },
	});

    //khôi phục đối tượng
	var undo_this = null;
	
	var undo_id	  = null;

	body.on('click', '.undo', function(event)  {

		undo_this = $(this);
		
		undo_id = []; var i = 0;

        $('.select:checked').each(function () {
            undo_id[i++] = $(this).val();
		});
		
        return false;
    });

    $('.undo').bootstrap_confirm_delete({
		heading:'Phục hồi',
		message:'Bạn chắc chắn muốn phục hồi dữ liệu đã chọn!',
		btn_ok_label:'Khôi Phục',
		btn_cancel_label:'Hủy',
		callback:function ( event ) {
			if(undo_id == null || undo_id.length == 0) {
				show_message('Không có dữ liệu nào được phục hồi ?', 'error');
			}
			else {
				var data ={
					'action' : 'ajax_undo',
	                'data'   : undo_id,
	                'table'  : undo_this.attr('data-table'),
	            };
	            $jqxhr   = $.post(base+'/ajax'+url_type, data, function() {}, 'json');
	            $jqxhr.done(function( data ) {
				    show_message(data.message, data.type);
				    if(data.type == 'success')
				    {
				    	if (typeof data.data != 'undefined') {
						  	var count = data.data.length;
					    	for (i = 0; i < count; i++) {
					    		$('.tr_'+data.data[i]).hide('fast').remove();
							}
						}
						else
						{
							var button = event.data.originalObject;
	            			button.closest( 'tr' ).remove();
						}
				    }

					if(data.type == 'reload') { location.reload(); }
				});
			}
        },
	});

    //xóa vĩnh viển đối tượng
	var del_this = null;

	var del_id	 = null;

    body.on('click', '.delete', function(event)  {
    	del_this = $(this);
    	del_id = del_this.attr('data-id');
    	if(!isset(del_id)) {
    		del_id = []; var i = 0;
	        $('.select:checked').each(function () {
	            del_id[i++] = $(this).val();
	        });
    	}
        return false;
    });

    $('.delete').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
		callback:function ( event ) {
			if(del_id == null || del_id.length == 0) {
				show_message('Không có dữ liệu nào được xóa ?', 'error');
			}
			else {
				var data ={
					'action' : 'ajax_delete',
	                'data'   : del_id,
	                'table'  : del_this.attr('data-table'),
				};
				
				$jqxhr   = $.post( base + '/ajax' + url_type, data, function() {}, 'json');
				
	            $jqxhr.done(function( data ) {

					show_message(data.message, data.type);
					
				    if(data.type == 'success')
				    {
				    	if (typeof data.data != 'undefined') {
						  	var count = data.data.length;
					    	for (i = 0; i < count; i++) {
					    		$('.tr_'+data.data[i]).hide('fast').remove();
							}
						}
						else
						{
							var button = event.data.originalObject;
	            			button.closest( 'tr' ).remove();
						}
				    }

					if(data.type == 'reload') { location.reload(); }
				});
			}
        },
	});
});

