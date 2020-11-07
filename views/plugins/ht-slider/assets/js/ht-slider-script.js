var slug = function(str) {
    var $slug = '';
    var trimmed = $.trim(str);
    $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
    replace(/-+/g, '-').
    replace(/^-|-$/g, '');
    return $slug.toLowerCase();
}

$(function() {

	var HtSliderHandler = function() {
		$( document )
			.on('blur',  '#form-sliders-group input[name="name"]', this.createdSlug )
			.on('submit','#form-sliders-group', this.onAdd )
			.on('click', '#add_slider_item', this.onAddItem )
			.on('click', '#ht_slider_btn_save', this.onSaveItem )
			.on('click', '#ht_slider_btn_del', this.onDelItem )
	};

	HtSliderHandler.prototype.createdSlug = function(e) {
		$('#form-sliders-group input[name="options"]').val(slug($(this).val()));
	}

	HtSliderHandler.prototype.onAdd = function(e) {

		var name 	= $(this).find('input[name="name"]').val();

		var option 	= $(this).find('input[name="options"]').val();

		if(name.length == 0) {
			show_message('Không được bỏ trống tên slider', 'error');
			return false;
		}

		if(option.length == 0) {
			show_message('Không được bỏ trống tên id slider', 'error');
			return false;
		}

		data = {
			'action' 		: 'ajax_group_add',
			'object_type' 	: 'ht-slider',
			'options' 		: option,
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
        	show_message(data.message, data.type);
        	if(data.type == 'success') window.location.reload();
    	});

		return false;
	}

	HtSliderHandler.prototype.onAddItem = function(e) {

		var slider_id = $(this).attr('data-id');

		data = {
			'action' 		: 'ajax_hts_add_item',
			'slider_id' 	: slider_id,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
	        show_message(data.message, data.type);
	        if(data.type == 'success') window.location = base+'/plugins?page=ht-slider&view=slider&slider='+slider_id+'&id='+data.id;
	    });

		return false;
	}

	HtSliderHandler.prototype.onSaveItem = function(e) {

		var id = $(this).attr('data-id');

		var value 	= $('input[name="value"]').val();

		var url = $('input[name="url"]').val();

		var name = $('input[name="name"]').val();

		var transition = 'data-transition="'+$('#resultanim').text()+'" data-slotamount="'+$('#resultslot').text()+'" data-masterspeed="'+$('#resultspeed').text()+'"';

		data = {
			'action' 	: 'ajax_hts_save_item',
			'id' 	 	: id,
			'value'  	: value,
			'url': url,
			'name': name,
			'data-transition' : $('#resultanim').text(),
			'data-slotamount' : $('#resultslot').text(),
			'data-masterspeed' : $('#resultspeed').text(),
			'transition': transition,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
	        show_message(data.message, data.type);
	    });
		return false;
	}

	HtSliderHandler.prototype.onDelItem = function(e) {

		var id = $(this).attr('data-id');

		data = {
			'action' : 'ajax_hts_del_item',
			'id'	 : id,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
	        show_message(data.message, data.type);
	        if( data.type == 'success') location.reload();
	    });

		return false;
	}

	/**
	 * Init AddToCartHandler.
	 */
	new HtSliderHandler();
});
