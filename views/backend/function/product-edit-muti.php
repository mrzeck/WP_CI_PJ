<?php
//add action bar
function action_bar_product() {
    ?>
    <a href="" class="btn-icon btn-green hvr-sweep-to-right btn-pr-edit-muti"><i class="fa fa-plus-circle"></i>Edit Nhiều dữ liệu</a>
    <?php
}


add_action('action_bar_product_left', 'action_bar_product');

function pr_edit_mti_add_script() {
	$ci = &get_instance();
	if($ci->template->is_page('products_index')) {
		?>
		<div class="modal fade" tabindex="-1" role="dialog" id="pr_edit_mti_modal">
		  	<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title">Chỉnh sửa sản phẩm</h4>
		      		</div>
		      		<form id="pr_edit_mti_form">
			      		<div class="modal-body" style="overflow: hidden;">
			      			<?php $options = wcmc_gets_category_mutilevel_option();?>
			      			<?php $input = array(
			      				'field' => 'pr_muti_input',
			      				'label' => 'Danh mục sản phẩm',
			      				'type' => 'checkbox',
			      				'options' => $options,
			      				'class' => 'pr_muti_input',
			      			);?>
			      			<?php echo _form($input);?>
			      		</div>
			      		<div class="modal-footer">
			        		<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
			        		<button type="submit" class="btn btn-blue">Lưu</button>
			      		</div>
		      		</form>
		    	</div><!-- /.modal-content -->
		  	</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<script type="text/javascript">
			$(function(){
				$('.btn-pr-edit-muti').click(function() {
					pr_id = []; var i = 0;
			        $('.select:checked').each(function () {
			            pr_id[i++] = $(this).val();
			        });

			        if(pr_id.length == 0 ) {
			        	show_message('Không có sản phẩm nào được chọn!', 'error');
			        	return false;
			        }

			        $('#pr_edit_mti_modal').modal('show');

					return false;
				});

				$('#pr_edit_mti_form').submit(function() {
					pr_id 	= []; var i = 0;
					cate_id = []; var j = 0;

			        $('.select:checked').each(function () {
			            pr_id[i++] = $(this).val();
			        });

			        $('.pr_muti_input:checked').each(function () {
			            cate_id[j++] = $(this).val();
			        });

			        if(pr_id.length == 0 ) {
			        	show_message('Không có sản phẩm nào được chọn!', 'error');
			        	return false;
			        }

			        if(cate_id.length == 0 ) {
			        	show_message('Không có danh mục sản phẩm nào được chọn!', 'error');
			        	return false;
			        }

			        var data = {
			        	'action' 	: 'ajax_product_edit_muti_save',
			        	pr_id 		: pr_id,
			        	cate_id		: cate_id,
			        };

			        $jqxhr   = $.post(base+'/ajax', data, function(data) {}, 'json');

			        $jqxhr.done(function( data ) {
					    if(data.type == 'error'){
					    	show_message(data.message, data.type);
					    }
					    else window.location = '<?= fullurl();?>';
					});

			        return false;
				})
			});
		</script>
		<?php
	}
}

add_action('admin_footer', 'pr_edit_mti_add_script');

function ajax_product_edit_muti_save($ci, $model)
{
	$result['type'] 	= 'error';
	$result['message'] 	= 'Cập nhật không thành công!';

	$products 	=  $ci->input->post('pr_id');
	$caterogies =  $ci->input->post('cate_id');

	if(have_posts($products) && have_posts($caterogies)) {

		$model->settable('relationships');

		$data['object_type'] 	= 'products';

		foreach ($products as $key => $id) {

			$model->delete_where(array('object_id' => $id, 'object_type' => 'products'));

			$id = (int) removeHtmlTags($id);

			if($id ==  0) continue;

			$data['object_id'] 		= $id;
			
			foreach ($caterogies as $category_id) {

				$category_id = (int) removeHtmlTags($category_id);

				if($category_id ==  0) continue;

				$data['category_id'] = $category_id;

				$model->add($data);
			}
		}

		$result['type'] 	= 'success';
		
		$result['message'] 	= 'cập nhật thành công!';
	}

	echo json_encode($result);
}

register_ajax_admin('ajax_product_edit_muti_save');