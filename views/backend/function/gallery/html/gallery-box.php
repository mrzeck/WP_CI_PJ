<?php 
	$object_id = (isset($object))?$object->id:0;

	$items = array();

	$object_module 	= $module;

	if($module == 'post') $object_module .= '_'.$ci->post_type;
	
	if($module == 'post_categories') $object_module .= '_'.$ci->cate_type;

	$args = array(
		'where' => array('object_id' => $object_id, 'object_type' => $object_module )
	);
	
	if($object_id != 0 ) $items = gets_gallery( $args );
?>
<div class="box" style="overflow:hidden">
	<div class="header" style="padding-top:10px;">
		<h3 class="pull-left" style="padding-top:10px;">Thư Viện</h3>
		<?php
			$object_id 		= (isset($object))?$object->id:0;
			$object_module 	= $module;
			if($module == 'post') $object_module .= '_'.$ci->post_type;
			if($module == 'post_categories') $object_module .= '_'.$ci->cate_type;
		?>
		<div class="object-gallery-action" style="overflow: hidden;">
			<div class="pull-right">
				<button id="object-gallery-btn-del" data-object-id="<?= $object_id;?>" data-object-module="<?= $object_module;?>" data-edit="0" class="btn-icon btn-red del-img" type="button"><?php echo admin_button_icon('delete');?>Xóa</button>
			</div>
		</div>
	</div>
	<div class="box-content collapse in" id="object-gallery">
		<div class="tab-content object-gallery-tabs">
			<?php include 'gallery-tab.php';?>
		</div>
	</div>
</div>

<style type="text/css">
	.object-gallery-action .btn-icon { margin-right: 0; }
	#js_object_gallery_sort { overflow:hidden; display:block; }
	.add_gallery_item_box {
		position: relative;
		display: inline-block;
		margin-bottom: 10px;
		height: 88px;
		border: 4px solid #fff;
		background: transparent;
		box-sizing: border-box;
		overflow: hidden;
	}
	.add_gallery_item_box .add_gallery_item_icon {
		position: absolute;
		top: 0px; left:0;
		width: 100%;
		height: 100%;
		display: block;
		text-align: center;
		font-size: 35px;
		border: 1px dashed #ddd;
	}
	.add_gallery_item_box .add_gallery_item_icon i {
		display: block;
		position: absolute;
		left: 0px; top:20px;
		width: 100%;
		height: 100%;
		color:#000;
	}

	.modal-body {
		overflow:hidden;
	}
	.modal-content { border-radius:0; }
</style>

<script>
	$(function(){

		var box 	= $('#object-gallery');

		var form 	= box.find('.object-gallery-form');

		var GalleryObjectHandler = function() {

			$(document)
				.on('click', '.object-gallery-item', 		this.onEdit )
				.on('click', '#object-gallery-btn-save', 	this.onSave )
				.on('click', '#object-gallery-btn-del', 	this.onDel )
				.on('click', '#js_object_gallery_btn_add_item', 	this.onModal )
		};


		GalleryObjectHandler.prototype.onEdit 	= function(e) {

			if($(this).hasClass('active')) {

				$(this).removeClass('active');

				GalleryObjectHandler.prototype.onReset();
			}
			else {

				$(this).removeClass('active');

		      	id 	= $(this).attr('data-id');

		      	$('#object-gallery-btn-save').attr('data-edit', id);

		      	$(this).find('input, select, textarea').each(function(index, el) {
					if (/radio|checkbox/i.test($(this).attr('type')) == false) {
						name = $(this).attr('data-name');
						form.find('#'+name+'').val($(this).val());
					}
				});

				$('.object-gallery-item').removeClass('active');
				  
				$(this).addClass('active');

				$('#modal_add_object_gallery_item').modal('show');
			}

			return false;
		}

		GalleryObjectHandler.prototype.onSave 	= function(e) {

			var data 	= {};

			form.find('input, select, textarea').each(function(index, el) {
		    	if($(this).attr('type') == 'checkbox' && !isset(data[$(this).attr('id')])) {
		    		var data_checkbox = new Array();
		    		form.find("input[id='"+$(this).attr('id')+"']:checked").each(function(index, el) {
			    		data_checkbox.push($(this).val());
			    	});

			    	data[$(this).attr('id')] = data_checkbox;;
				}
				if (/radio|checkbox/i.test($(this).attr('type')) == false) {
					data[$(this).attr('id')] = $(this).val();
				}
			});

			data['action']        = 'ajax_object_gallery_save';

			data['object_id']     = $(this).attr('data-object-id');

			data['object_module'] = $(this).attr('data-object-module');

			data['id']   		  = $(this).attr('data-edit');

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {
			    
			    if(data.type == 'success') {

			    	if(data.id == 0) {

			    		box.find('#js_object_gallery_sort').prepend(data.data);

			    	} else {

			    		box.find('#js_object_gallery_sort .object-gallery-item.active').before(data.data);	

						box.find('#js_object_gallery_sort .object-gallery-item.active').hide().remove();
						
						$('#modal_add_object_gallery_item').modal('hide');
			    	}

					GalleryObjectHandler.prototype.onReset();

					checkbox_style();
			    }
			    else show_message(data.message, data.type);
			});

			return false;
		}

		GalleryObjectHandler.prototype.onDel 	= function(e) {

			var id = $(this).attr('data-object-id');

			var data = [], i = 0;

			if(id == 0) {
				box.find('input.gallery-item-checkbox:checked').each(function () {
		            $(this).closest('.object-gallery-item').remove();
		        });
			}
			else {
				box.find('input.gallery-item-checkbox:checked').each(function () {
		            data[i++] = $(this).val();
		        });

				post = {
					id : id,
					data : data,
					'action' : 'ajax_object_gallery_del',
				}

				$jqxhr   = $.post(base+'/ajax', post, function(data) {}, 'json');

				$jqxhr.done(function( data ) {

				    show_message(data.message, data.type);

				    if(data.type == 'success') {
				    	
				    	box.find('.object-gallery-items').html(data.data);

				    	checkbox_style();
				    }

				    $('#ajax_loader_del').hide();
				});
			}
			return false;
		}

		GalleryObjectHandler.prototype.onReset 	= function(e) {

			form.find('input, select, textarea').each(function(index, el) {
				if (/radio|checkbox/i.test($(this).attr('type')) == false) {
					$(this).val('');
				}
			});

			$('#object-gallery-btn-save').attr('data-edit',0);

			$('.object-gallery-item').removeClass('active');

			$('.result-img').parent().remove();

			$('.result-img-info').remove();
		}

		GalleryObjectHandler.prototype.onModal 	= function(e) {

			$('#modal_add_object_gallery_item').modal('show');

			$('#modal_add_object_gallery_item .iframe-btn').trigger('click');

			return false;
		}

		GalleryObject = new GalleryObjectHandler();

		$('#modal_add_object_gallery_item').on('hidden.bs.modal', function (e) {
			GalleryObject.onReset();
		})

		Sortable.create(js_object_gallery_sort, {
			animation: 200,

			// Element dragging ended
			onEnd: function (/**Event*/evt) {

				o = 0;

				$.each($(".js_object_gallery_sort_item"), function(e) {

					console.log(o);

					i = $(this).attr("data-id");

					$('#gallery_'+i+'_order').val(o);

					o++;
				});
			},
		});
	})
</script>