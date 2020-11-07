var body = $(document);

var sidebarWidget;

var id = 0;

$(function(){

	var modal_action = '';

	var widget_new = false;

	var widget_new_key = [];

	var widget_old_key = [];

	var widget_height = 0;

	var WidgetHandler = function() {

		$( document )
			/************* Serivce ********************/
			//Load widget từ server về
			.on( 'click', '#service-widget', this.serviceLoad )
			//Submit license key
			.on( 'submit', '#service-widget-license', this.serviceLicense )
			//Load widget theo danh mục từ server về
			.on( 'click', '.widget-cat-item', this.serviceLoadWithCate )
			//cài widget
			.on( 'click', '.wg-install', this.download )

			/************* In Website ********************/
			.on( 'keyup', '#widget #widget_main_search', this.searchWidget )

			.on( 'click', '#widget .widget_add_sidebar', this.addWidget )

			.on( 'click', '.widget-sidebar-content .icon-edit', this.editWidget )

			.on( 'click', '.widget-sidebar-content .icon-copy', this.copyWidget )

			.on( 'click', '.widget-sidebar-content .icon-delete', this.deleteWidget )

	};

	/**
	 * show modal service widget
	 */
	WidgetHandler.prototype.serviceLoad = function( e ) {
		
		form_edit = $('#modal-service-widget');

		form_edit.modal('show');

		loading = form_edit.find('.ajax-load-qa');

		loading.show();

		$jqxhr   = $.post( base +'/ajax', { 'action' : 'ajax_widget_service' }, function(data) {}, 'json');


		$jqxhr.done(function( data ) {

			loading.hide();

			show_message(data.message, data.type);

			form_edit.find('#widget-view-content').html(data.data);

		});

	};

	WidgetHandler.prototype.serviceLicense = function( e ) {
		
		form_license = $(this);

		var data        = form_license.serializeJSON();

		data.action     =  'ajax_service_license_save';

		form_edit = $('#modal-service-widget');

		loading = form_edit.find('.loading-model');

		loading.show();

		$jqxhr   = $.post( base +'/ajax', data, function(data) {}, 'json');


		$jqxhr.done(function( data ) {

			loading.hide();

			show_message(data.message, data.status);

			WidgetHandler.prototype.serviceLoad();

		});

		return false;

	};

	WidgetHandler.prototype.serviceLoadWithCate = function( e ) {

		loading = $('#modal-service-widget').find('.ajax-load-qa');

		loading.show();

		$('.widget-cat-item').parent().removeClass('active');

		$(this).parent().addClass('active');
		
		$jqxhr   = $.post( base +'/ajax', { 'action' : 'ajax_widget_service_category', 'cate': $(this).attr('data-id') }, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

			loading.hide();

			show_message(data.message, data.type);

			$('#widget-service-kho-list__item').html(data.data);

		});

		return false;

	};

	/**
	 * [Download widget về website]
	 */
	WidgetHandler.prototype.download = function(e) {

		var name 	= $(this).attr('data-url');

		var button = $(this);

		if(name.length == 0) { show_message('Không được bỏ trống tên menu', 'error'); return false; }

		$(this).text('Đang download');

		data = {
			'action' 		: 'ajax_plugin_wg_download',
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

	        show_message(data.message, data.type);

	        if(data.type == 'success') {

	        	button.text('Đang cài đặt');

	        	widget_new = true;

	        	setTimeout( function()  {
	        		WidgetHandler.prototype.install( button );
	        	}, 500);

	        	
	        }
	    });

	    return false;
	}

	/**
	 * [install Widget giải nén và cài đặt]
	 */
	WidgetHandler.prototype.install = function( button ) {

		var name 	= button.attr('data-url');

		data = {
			'action' 		: 'ajax_plugin_wg_install',
			'name' 			: name,
		}

		$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

		$jqxhr.done(function( data ) {

	        show_message(data.message, data.type);

	        button.text('Đã cài đặt');

	        widget_new = true;
	    });

	    return false;
	}

	//Load widget
	WidgetHandler.prototype.loadWidget = function( e ) {


		$('#js_widget_main_list .js_widget_item').each( function(index) {
			widget_old_key.push( $(this).attr('data-key') );
		});

		

		var data = {
			'action' :'ajax_widget_load',
			'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
		}

		$jqxhr = $.post( base + '/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data) {

			if(data.type == 'success') {

				$('#js_widget_main_list').html( data.data );

				$('#js_widget_main_list .js_widget_item .box').each(function( index ) {
					if( $( this ).height() > widget_height ) widget_height = $( this ).height();
				});

				$('#js_widget_main_list .js_widget_item .box').height( widget_height );

				checkbox_style();

				$('#js_widget_main_list .js_widget_item').each( function(index) {
					if( widget_old_key.indexOf( $(this).attr('data-key') ) == -1 ) {
						$(this).addClass('widget-just-added');
					}
				});
			}
		});
	}

	//Search widget
	WidgetHandler.prototype.searchWidget = function( e ) {

		var value = this.value;

	    $('.widget_sidebar').parent().hide().each(function () {
	        if ($(this).find('.widget-name').text().toUpperCase().search(value.toUpperCase()) > -1) {
	            $(this).parent().add(this).show();
	        }
	        if ($(this).find('.widget-key').text().toUpperCase().search(value.toUpperCase()) > -1) {
	            $(this).parent().add(this).show();
	        }
	    });
	}

	//Add widget to sidebar
	WidgetHandler.prototype.addWidget = function( t, s ) {

		var widget_id  = t.attr('data-key');

		var data = {
			'action' :'ajax_widget_add',
			widget_id: widget_id,
			sidebar_id: s,
		}

		$jqxhr = $.post( base + '/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data){

			show_message(data.message, data.type);

			if(data.type == 'success') {
				t.attr('data-id', data.id);
				t.find('.action .icon-edit').attr('href', data.id);
				t.find('.action .icon-copy').attr('href', data.id);
				t.find('.action .icon-delete').attr('href', data.id);
				t.find('.action .icon-edit[href='+data.id+']').trigger('click');

				WidgetHandler.prototype.sortWidget(s);
			}
		});
	}

	//show edit widget
	WidgetHandler.prototype.editWidget = function( e ) {

		$this 	= $(this);

		id = $this.attr('href');

		var data = {
			'action': 'ajax_widget_edit',
			'id'	: id,
		};

		$('#modal-edit-widget').addClass('open');

		$('#ajax_edit_widget_loader').show();

		$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data){

			$('#modal-edit-widget .box-edit-widget').html(data.data).promise().done(function(){

				$('#ajax_edit_widget_loader').fadeOut();

				tinymce.remove();

		        tinymce_load();

		        load_image_review();

		        rangeSlider();

				tinyMCE.execCommand('mceAddControl', false, "content");

				$(".select2-multiple").select2();
		    });
		});

		return false;
	}

	WidgetHandler.prototype.moveWidget = function( t, s ) {

		var datas = {

			action : 'ajax_widget_move',

			widget_id : t.attr('data-id'),

			sidebar_id: s,
		}

		$jqxhr = $.post(base+'/ajax', datas, function(){}, 'json');

		$jqxhr.done( function(data) {

			show_message(data.message, data.type);

			if( data.type == 'success' ) {

				WidgetHandler.prototype.sortWidget( s );

			}

		});

		return false;
	}

	WidgetHandler.prototype.sortWidget = function( s ) {

		o = [];

		$('#'+s).find('.js_widget_item').each(function(index) {
			o.push($(this).attr('data-id'));
		});

		$('#box_' + s ).find('.loading').show();

		var data = {
			'action' : 'ajax_widget_sort',
			data: o
		};

		$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data){

			$('#box_'+ s ).find('.loading').hide();

			show_message(data.message, data.type);
		});

		return false;
	}

	WidgetHandler.prototype.copyWidget = function( s ) {

		$this 	= $(this);

		id = $this.attr('href');

		var data = {
			'action': 'ajax_widget_copy',
			'id'	: id,
		};

		$this.html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Nhân bản</a>');

		$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data){

			if(data.type == 'success') {

				$('#'+data.sidebar_id).append(data.data);

				$this.html('<i class="fal fa-clone"></i>');
			}
		});


		return false;
	}

	WidgetHandler.prototype.deleteWidget = function( s ) {

		$this 	= $(this);

		$id = $this.attr('href');

		var data = {
			'action' : 'ajax_widget_del',
			'id'	: $id,
		};

		$jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

		$jqxhr.done(function(data){
			if(data.type == 'success') {
				$this.closest('.js_widget_item').remove();
			}
			show_message(data.message, data.type);
		});

		return false;
	}

	new WidgetHandler();


	Sortable.create( js_widget_main_list, {
		sort: false,
		group: {
			name: 'advanced',
			pull: 'clone',
			put: false
		},
		animation: 150,
		onEnd: function (/**Event*/evt) {
			if( evt.to.id != evt.from.id ) {
				WidgetHandler.prototype.addWidget( $(evt.item) , evt.to.id )
			}
		},
	});

	$('.js_sidebar').each(function(e) {

		id = $(this).attr('id');

		Sortable.create( document.getElementById(id), {
			sort: true,
			group: {
				name: 'advanced',
				pull: true,
				put: true
			},
			animation: 150,
			onEnd: function (/**Event*/evt) {

				if( evt.to.id != evt.from.id ) {
					WidgetHandler.prototype.moveWidget( $(evt.item), evt.to.id );
				}
				else if( evt.oldIndex != evt.newIndex )	{
					WidgetHandler.prototype.sortWidget( evt.to.id );
				}	
			},
		});
	});


	$('#widget_list .box').each(function( index ) {
		if( $( this ).height() > widget_height ) widget_height = $( this ).height();
	});

	$('#widget_list .box').height( widget_height );

	$('#modal-service-widget').on('hidden.bs.modal', function (e) {

		if( widget_new == true ) WidgetHandler.prototype.loadWidget();
	})
});	

//close edit widget
body.on('click','#modal-edit-widget button.btn-close', function() {
	$('#modal-edit-widget').removeClass();
	$('#modal-edit-widget .box-edit-widget').html('');
	return false;
});

//save widget
body.on('submit','#form-edit-widget', function() {
	$('#ajax_edit_widget_loader').show();

	var $this = $(this);

	var datas = {};

	//input
    $this.find('input, textarea, select').each(function(index, el) {
		if($(this).attr('type') == 'checkbox' && !isset(datas[$(this).attr('name')])) {
    		var data_checkbox = new Array();
    		$this.find("input[name='"+$(this).attr('name')+"']:checked").each(function(index, el) {
	    		data_checkbox.push($(this).val());
	    	});

	    	datas[$(this).attr('name')] = data_checkbox;;
		}
		if (/radio|checkbox/i.test($(this).attr('type')) == false)  datas[$(this).attr('name')] = $(this).val();
	});

	$this.find('textarea').each(function(index, el) {
		var textareaid 	= $(this).attr('id');
		var value 		= $(this).val();
		if($(this).hasClass('tinymce') == true || $(this).hasClass('tinymce-shortcut') == true){
			value 	= document.getElementById(textareaid+'_ifr').contentWindow.document.body.innerHTML;
		}
		console.log($(this).attr('name'));
		datas[$(this).attr('name')] = value;
	});

	datas['id'] = id;
	datas['action'] = 'ajax_widget_save';

    $jqxhr   = $.post(base+'/ajax', datas, function(data) {}, 'json');

	$jqxhr.done(function( data ) {
		show_message(data.message, data.type);
		$('#ajax_edit_widget_loader').hide();
	    if(data.type == 'error'){
	    	if(isset($('input#'+data.field).val())) { $('#'+data.field).focus();}
	    } else {
	    	$('#modal-edit-widget').removeClass();
	    	$('#modal-edit-widget .box-edit-widget').html('');
	    	// $.fancybox.close();
	    }
	});
	return false;
});