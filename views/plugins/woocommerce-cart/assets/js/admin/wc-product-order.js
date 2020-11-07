$(document).on('DOMNodeInserted', function(e) {
	//xóa đơn hàng
    $('.woocommerce_cart_delete_order').bootstrap_confirm_delete({
		heading:'Xác nhận xóa',
		message:'Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>',
		callback:function ( event ) {

			var button 	= event.data.originalObject;

			var id 		= button.attr('data-id');

	    	if(id == null || id.length == 0) {
	      		show_message('Không có dữ liệu nào được xóa ?', 'error');
	    	}
	    	else {

	      		var data ={
	          		'action' 		: 'wcmc_ajax_order_del',
	          		'data'   		: id,
	      		};

	      		$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

		      	$jqxhr.done(function( data ) {
		          	show_message(data.message, data.type);
		          	if(data.type == 'success')
		          	{
		          		var button = event.data.originalObject;
		                  	button.closest( '.tr_'+id ).remove();
		          	}

		        	if(data.type == 'reload') { location.reload(); }
		      	});
	    	}
	  	},
	});
});