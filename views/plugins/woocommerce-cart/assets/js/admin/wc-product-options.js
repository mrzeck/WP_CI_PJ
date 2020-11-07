var body = $(document);

var prefix = 'wcmc_ajax_';

//xóa vĩnh viển đối tượng
var del_this = null;
var del_id	 = null;

//xóa group option
body.on('click', '.delete-option', function(event)  {
	del_this = $(this);
	del_id = del_this.attr('data-id');
    return false;
});

$('.delete-option').bootstrap_confirm_delete({
	heading:'Xác nhận xóa',
	message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
	callback:function ( event ) {
		if(del_id == null || del_id.length == 0) {
			show_message('Không có dữ liệu nào được xóa ?', 'error');
		}
		else {

			var data ={
				'action' : prefix+'options_del',
                'data'   : del_id,
                'table'  : del_this.attr('data-table'),
            };

            $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

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

//xóa item
body.on('click', '.delete-item', function(event)  {
	del_this = $(this);
	del_id = del_this.attr('data-id');
    return false;
});

$('.delete-item').bootstrap_confirm_delete({
	heading:'Xác nhận xóa',
	message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
	callback:function ( event ) {
		if(del_id == null || del_id.length == 0) {
			show_message('Không có dữ liệu nào được xóa ?', 'error');
		}
		else {

			var data ={
				'action' : prefix+'options_item_del',
                'data'   : del_id,
                'table'  : del_this.attr('data-table'),
            };

            $jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

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