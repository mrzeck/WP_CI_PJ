<div class="col-md-8">
    <div class="box">
        <div  class="box-content">
        	<div class="col-md-12">
        		<?php echo notice('warning', 'Việc xóa cache ở đây chỉ liên quan đến các việc chỉnh sửa dữ liệu không liên quan đến cache giao diện được lưu ở trình duyệt.');?>
	            <table class="table table-condensed table-hover">
	            	<tbody>
	            		<?php foreach ($cache as $key => $item): ?>
	            		<tr>
	            			<td><?php echo $item['label'];?></td>
	            			<td><button class="cache-clear btn btn-<?php echo $item['color'];?> btn-block" data-clear="<?php echo $key;?>"><?php echo $item['btnlabel'];?></button></td>
	            		</tr>
	            		<?php endforeach ?>
	            	</tbody>
	            </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {

		$('.cache-clear').click(function(event){
			
				var data = {
					action : 'ajax_admin_cache_clear',
					data : $(this).attr('data-clear'),
				};

				$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

  				$jqxhr.done(function( data ) {

					if(data.status == 'success') {
						show_message(data.message, data.status);
					}	
				});

				return false;
			});
	});
</script>