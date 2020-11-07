<?php foreach ($header_data as $folder => $item): ?>
<div class="col-md-12 header_service" data-id="<?php echo $item['id'];?>" data-folder="<?php echo $folder;?>" data-type="<?php echo $type;?>">
	<div class="box">
		<div class="box-content">
			<div class="header" style="padding-top: 10px;"> <h3><?php echo $item['title'];?></h3> </div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-9 header_service__img"> <?php get_img($item['image']);?> </div>
					<div class="col-md-3 header_service__action">
						<?php if( !is_dir($path.'/'.$folder) ) { ?>
							<button type="button" class="header-install btn-green btn btn-block">DOWNLOAD</button>
						<?php } else { ?>
							<?php if( empty($header_style_active[$type][$folder]) ) { ?>
								<button type="button" class="header-active btn-green btn btn-block" >KÍCH HOẠT</button>
								<button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>
							<?php } else { ?>
								<button type="button" class="header-unactive btn-white btn btn-block">NGƯNG KÍCH HOẠT</button>
							<?php } ?>
						<?php } ?>
					</div>   
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach ?>

<script type="text/javascript">
	$(function(){

		var HeaderHandler = function() {

			$( document )
				//cài widget
				.on( 'click', '.header-install', this.download )
				.on( 'click', '.header-active', this.active )
				.on( 'click', '.header-unactive', this.unactive )
		};

		/**
		* [Download widget về website]
		*/
		HeaderHandler.prototype.download = function(e) {

			var button = $(this);

			var item   = $(this).closest('.header_service');

			var id 		= item.attr('data-id');

			var type 	= item.attr('data-type');

			button.text('Đang download');

			data = {
				'action' 		: 'ajax_service_header_download',
				'id' 			: id,
				'type' 			: type,
			}

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.type);

				if(data.type == 'success') {

					button.text('Đang cài đặt');

					setTimeout( function()  {
						HeaderHandler.prototype.install( item, button );
					}, 500);

					
				}
			});

			return false;
		}

		/**
		* [install Widget giải nén và cài đặt]
		*/
		HeaderHandler.prototype.install = function( item, button ) {

			var id 		= item.attr('data-id');

			var type 	= item.attr('data-type');

			header_action = button.closest('.header_service__action');

			data = {
				'action' 		: 'ajax_service_header_install',
				'id' 			: id,
				'type' 			: type,
			}

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.type);

				if( data.type == 'success' ) {

					button.text('Đã cài đặt');

					button.remove();

					header_action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button>\
						<button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
				}

			});

			return false;
		}

		HeaderHandler.prototype.active = function( e ) {

			var button = $(this);

			var item   = $(this).closest('.header_service');

			var type 	= item.attr('data-type');

			var folder 	= item.attr('data-folder');

			header_action = $(this).closest('.header_service__action');

			data = {
				'action' 		: 'ajax_admin_header_active',
				'folder' 		: folder,
				'type' 			: type,
			};

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.type);

				if( data.type == 'success' ) {

					$('.header-unactive').each(function(){
						header_service__action = $(this).closest('.header_service__action');
						header_service__action.find('.header-unactive').remove();
						header_service__action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button>\
						<button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
					});

					header_action.find('.header-active').remove();
					header_action.find('.header-remove').remove();
					header_action.append('<button type="button" class="header-unactive btn-white btn btn-block">NGƯNG KÍCH HOẠT</button>');
				}
			});

			return false;
		}

		HeaderHandler.prototype.unactive = function( e ) {

			var button = $(this);

			var item   = $(this).closest('.header_service');

			var type 	= item.attr('data-type');

			var folder 	= item.attr('data-folder');

			header_action = $(this).closest('.header_service__action');

			data = {
				'action' 		: 'ajax_admin_header_unactive',
				'folder' 		: folder,
				'type' 			: type,
			};

			$jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.type);

				if( data.type == 'success' ) {

					header_action.find('.header-unactive').remove();
					header_action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button>\
						<button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
				}
			});

			return false;
		}

		new HeaderHandler();
	});
</script>