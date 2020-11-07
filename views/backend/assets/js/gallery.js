var body = $(document);

var id = null;

var gallery_check_all = false;

$(function() {
	var GalleryHandler = function() {
		$( document )
			.on('click', '.gallery-list .root-list li a', this.loadGallery)
			
			.on('click', 		'.gallery-item .gallery-check-all', this.checkAllItem)
			.on('ifChecked', 	'.gallery-item .gallery-box .gallery-item-checkbox', this.checkedItem)
			.on('ifUnchecked', 	'.gallery-item .gallery-box .gallery-item-checkbox', this.checkedItem)
			.on('click', 		'.gallery-item .gallery-box', this.loadItemEdit )
			.on('click', 		'.del-img', this.delItemGallery )

			.on('submit', 	'#form-gallery-group', 	this.addGallery)
			.on('submit', 	'#form-gallery', 		this.saveItemGallery)
			.on('reset', 	'#form-gallery', 		this.resetItemEdit)
			.on('click', '.camera-container-link i', this.inputLinkShow)
			.on('keyup', '.camera-container-link input', this.inputLinkReview)
			.on('click', '.camera-container-link .input-group-addon', this.inputLinkHide)
	};
	/**
	 * [loadGallery Load gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.inputLinkShow = function (e) {

		$(this).parent().toggleClass('active');

		return false;
	}
	/**
	 * [loadGallery Load gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.inputLinkHide = function (e) {

		btn = $(this).closest('.camera-container-link');

		url = btn.find('input').val();

		if(url.length != 0) $('input#value').val(url);

		btn.toggleClass('active');

		return false;
	}
	/**
	 * [loadGallery Load gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.inputLinkReview = function (e) {

		url = $(this).val();

		if (validateYouTubeUrl(url)) {

			url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';

		}
		else if (url.search('http') == -1 || url.search(domain) != -1) {

			url = str_replace(url, domain + 'uploads/source/', '');

			url = domain + 'uploads/source/' + url;
		}

		$('.js_gallery_review').css('background-image', 'url("' + url + '")');

		return false;
	}
	/**
	 * [loadGallery Load gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.loadGallery = function (e) {

		$this = $(this);

		$('.gallery-item .loading').show();

		$('.gallery-list .root-list li a').removeClass('active');

		$this.addClass('active');

		id = $this.attr('data-id');

		$jqxhr = $.post(base + '/ajax', { 'action': 'ajax_gallery_load', id: id }, function (data) { }, 'json');

		$jqxhr.done(function (data) {

			if (data.type == 'success') {

				$('#js_object_gallery_sort').html(data.data);

				$('.gallery-del-img').attr('data-id', id);

				$('#form-gallery').attr('data-add', id);

				$('#form-gallery').trigger('reset');

				checkbox_style();
			}

			$('.gallery-item .loading').hide();
		});
		return false;
	}
	/**
	 * [addGallery thêm gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.addGallery = function(e) {

		var name 	= $(this).find('input[name="name"]').val();

		if(name.length == 0) { show_message('Không được bỏ trống tên gallery', 'error'); return false; }

		data = {
			'action' 		: 'ajax_group_add',
			'object_type' 	: 'gallery',
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {
	        show_message(data.message, data.type);
	        if(data.type == 'success') window.location.reload();
	    });

	    return false;
	}
	/**
	 * [checkAllItem check all item]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.checkAllItem = function (e) {

		$(this).toggleClass('color-green');

		if (gallery_check_all == false) {

			$('.gallery-item-checkbox').iCheck('check');

			gallery_check_all =  true;

		}
		else {

			$('.gallery-item-checkbox').iCheck('uncheck');

			gallery_check_all = false;
		}

		return false;
	}
	/**
	 * [checkedItem chọn item]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.checkedItem = function (e) {

		var selected = [];

		$('.gallery-item .gallery-box .gallery-item-checkbox:checked').each(function () {
			selected.push($(this).val());
		});

		if (selected.length == 0) {

			$('.gallery-del-img').addClass('disabled-item');

			$('.gallery-del-img').removeClass('color-red');

			$('.gallery-del-img').removeClass('del-img');
		}
		else {

			$('.gallery-del-img').addClass('del-img');

			$('.gallery-del-img').addClass('color-red');

			$('.gallery-del-img').removeClass('disabled-item');
		}

		return false;
	}
	/**
	 * [loadItemEdit Load thông tin gallery item khi click edit]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.loadItemEdit = function(e) {

		$this = $(this);

		if($this.hasClass('active')) {

			$this.removeClass('active');

			$('#form-gallery').trigger('reset');
		}
		else {

			$('.gallery-item .gallery-box').removeClass('active');

			$('.gallery-form .loading').show();

			id = $this.attr('data-id');
			  
			$this.addClass('active');

	      	$jqxhr   = $.post(base+'/ajax', {'action':'ajax_gallery_get_item', id : id}, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				$('.gallery-form .loading').hide();

			    if(data.type == 'success') {

					$('#form-gallery').find('input[name="value"]').val(data.data.value);
					$('#form-gallery').find('.camera-container-link input').val(data.data.value);

					url = data.data.value;

					if (validateYouTubeUrl(url)) {

						url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';

					}
					else if (url.search('http') == -1 || url.search(domain) != -1) {

						url = str_replace(url, domain + 'uploads/source/', '');

						url = domain + 'uploads/source/' + url;
					}
					
					$('.js_gallery_review').css('background-image', 'url("' + url + '")');

				    var options = data.data.options;

				    for (var option in options) {
				        // skip loop if the property is from prototype
				        if(!options.hasOwnProperty(option)) continue;
				        $('#form-gallery').find('input[name="option['+option+']"]').val(options[option]);
				    }

					$('#form-gallery').attr('data-edit',data.data.id);

					image_review($('#value'));

					video_review();
			    }

			    $('#ajax_loader').hide();
			});

			
		}
		return false;
	}
	/**
	 * [addItemGallery thêm item vào gallery]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.saveItemGallery = function (e) {

		$('#ajax_loader').show();

		var $this = $(this);

		var data = $(':input', $(this)).serializeJSON();

		data.action = 'ajax_gallery_save';

		data.id = $(this).attr('data-edit');

		data.group_id = $(this).attr('data-add');

		$jqxhr = $.post(base + '/ajax', data, function (data) { }, 'json');

		$jqxhr.done(function (data) {

			show_message(data.message, data.type);

			if (data.type == 'success') {

				$('#js_object_gallery_sort').html(data.data);

				$this.trigger('reset');

				checkbox_style();
			}

			$('#ajax_loader').hide();
		});

		return false;
	}
	/**
	 * [resetItemEdit Load thông tin gallery item khi click edit]
	 * @param {[type]} e [description]
	 */
	GalleryHandler.prototype.resetItemEdit = function(e) {

		$this = $(this);

		$this.find('input, select, textarea').each(function(index, el) {
			if (/radio|checkbox/i.test($(this).attr('type')) == false) {
				$(this).val('');
			}
		});

		$this.attr('data-edit','0');

		$('.result-img').remove();

		$('.result-img-info').remove();

		$('.js_gallery_review').css('background-image', '');

		return false;
	}

	/**
	 * [delItemGallery xóa item khỏi gallery]
	 * @param  {[type]} e [description]
	 * @return {[type]}   [description]
	 */
	GalleryHandler.prototype.delItemGallery = function(e) {

		$('#ajax_loader_del').show();

		var id = $(this).attr('data-id');

		var data = [], i = 0;

	    $('input.gallery-item-checkbox:checked').each(function () {
	        data[i++] = $(this).val();
	    });

		$jqxhr   = $.post(base+'/ajax', {  'action':'ajax_gallery_del_item', id : id, data : data }, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

		    show_message(data.message, data.type);

		    if(data.type == 'success') {

		    	$('#js_object_gallery_sort').html(data.data);

				checkbox_style();

				$('#form-gallery').trigger('reset');

				$('.gallery-del-img').addClass('disabled-item');

				$('.gallery-del-img').removeClass('color-red');

				$('.gallery-del-img').removeClass('del-img');
		    }

		    $('#ajax_loader_del').hide();
		});

		return false;
	}

	/**
	 * Init AddToCartHandler.
	 */
	new GalleryHandler();

	if( typeof $('#js_object_gallery_sort').html() != 'undefined') {

		Sortable.create(js_object_gallery_sort, {
			animation: 200,

			// Element dragging ended
			onEnd: function (/**Event*/evt) {

				o = 0;

				var d = {};

				$.each($(".js_object_gallery_sort_item"), function(e) {
					i = $(this).attr("data-id");
					d[i] = o;
					o++;
				});

				$jqxhr   = $.post(base+'/ajax', { 'action':'ajax_gallery_sort_item', 'data' : d }, function(data) {}, 'json');

				$jqxhr.done(function( data ) {
				    show_message(data.message, data.type);
				});
			},
		});

	}
});



//xóa gallery
body.on('click', '.delete', function(event) {
	id = $(this).attr('data-id');
    return false;
});

$('.delete').bootstrap_confirm_delete({
	heading:'Xác nhận xóa',
	message:'Bạn muốn xóa trường dữ liệu này ?',
	callback:function ( event ) {
		$jqxhr   = $.post(base+'/ajax', { 'action' : 'ajax_gallery_del', id : id}, function(data) {}, 'json');
		$jqxhr.done(function( data ) {
		    show_message(data.message, data.type);
		    if(data.type == 'success') {
		    	window.location.reload();
		    }
		});
    },
});

function gallery_responsive_filemanager_callback( id ) {

	url = $('#' + id).val();

	if (validateYouTubeUrl(url)) {

		url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';

	}
	else if (url.search('http') == -1 || url.search(domain) != -1) {

		url = str_replace(url, domain + 'uploads/source/', '');

		url = domain + 'uploads/source/' + url;
	}

	$('.js_gallery_review').css('background-image', 'url("' + url + '")');
}

