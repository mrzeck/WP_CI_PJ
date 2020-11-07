<?php 
	$style = $contact_mobile_icon->style4;

	if( isset($option['style-4']) ) $style = $option['style-4'];
?>
<div class="col-md-8">
	<table class="table table-bordered" cellspacing="0" id="cmi_style_4_icon">
		<thead>
			<tr>
				<th>Icon</th>
				<th>Url</th>
				<th>Alt</th>
				<th>Class</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody class="accounts ui-sortable">
			<?php if( have_posts($style['icon']) ) {?>
				<?php foreach ($style['icon'] as $key => $icon): ?>
				<tr class="account">
					<td><div class="group"><div class="input-group image-group"><input type="images" name="icon[<?php echo $key;?>][image]" value="<?php echo $icon['image'];?>" id="icon_<?php echo $key;?>_image" class="form-control "><span class="input-group-addon iframe-btn" data-fancybox="iframe" data-id="<?php echo $key;?>" href="<?php echo base_url();?>/scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=icon_<?php echo $key;?>_image&amp;callback=responsive_filemanager_callback"><i class="fas fa-upload"></i></span></div><p style="color:#999;margin:5px 0 5px 0;"></p></div></td>
					<td><input type="text" class="form-control" name="icon[<?php echo $key;?>][url]" value="<?php echo $icon['url'];?>"></td>
					<td><input type="text" class="form-control" name="icon[<?php echo $key;?>][alt]" value="<?php echo $icon['alt'];?>"></td>
					<td><input type="text" class="form-control" name="icon[<?php echo $key;?>][class]" value="<?php echo $icon['class'];?>"></td>
					<td class="sort">
						<button class="btn-delete btn-icon btn-red">Xóa</button>
					</td>
				</tr>
				<?php endforeach ?>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="7">
					<a href="#" class="add btn-white btn">+ Thêm icon</a>
				</th>
			</tr>
		</tfoot>
	</table>
</div>
<div class="col-md-4">
	<?php  $input = array(
		'field' => 'cmi_position', 'type'	=> 'select',
		'label' => 'Vị trí', 'options' => array( 'left' => 'Trái', 'right' => 'Phải' )
	); ?>
	<?php echo _form($input, $style['cmi_position']);?>

	<?php  $input = array(
		'field' => 'cmi_bottom', 'type'	=> 'number',
		'label' => 'Cách bên dưới',
	); ?>
	<?php echo _form($input, $style['cmi_bottom']);?>

	<?php  $input = array(
		'field' => 'cmi_margin', 'type'	=> 'number',
		'label' => 'Khoảng cách giữa các icon',
	); ?>
	<?php echo _form($input, $style['cmi_margin']);?>

	<?php  $input = array(
		'field' => 'cmi_width_image', 'type'	=> 'number',
		'label' => 'Kích thước icon',
	); ?>
	<?php echo _form($input, $style['cmi_width_image']);?>

	<?php  $input = array(
		'field' => 'cmi_animate', 'type'	=> 'select',
		'label' => 'Hiệu ứng',
		'options' => animate_css_option()
	); ?>
	<?php echo _form($input, $style['cmi_animate']);?>
</div>

<script type="text/javascript">
	$(function() {
		$('#cmi_style_4_icon').on( 'click', 'a.add', function(){

			var size = $('#cmi_style_4_icon').find('tbody .account').length;

			$('<tr class="account">\
					<td><div class="group"><div class="input-group image-group"><input type="images" name="icon[' + size + '][image]" value="" id="icon_' + size + '_image" class="form-control "><span class="input-group-addon iframe-btn" data-fancybox="iframe" data-id="id" href="<?php echo base_url();?>/scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=icon_' + size + '_image&amp;callback=responsive_filemanager_callback"><i class="fas fa-upload"></i></span></div><p style="color:#999;margin:5px 0 5px 0;"></p></div></td>\
					<td><input type="text" class="form-control" name="icon[' + size + '][url]"></td>\
					<td><input type="text" class="form-control" name="icon[' + size + '][alt]"></td>\
					<td><input type="text" class="form-control" name="icon[' + size + '][class]"></td>\
					<td class="sort">\
						<button class="btn-delete btn-icon btn-red">Xóa</button>\
					</td>\
				</tr>').appendTo('#cmi_style_4_icon tbody');

			return false;
		});

		$('#cmi_style_4_icon').on( 'click', 'button.btn-delete', function(){
			$(this).closest('tr.account').remove();
			return false;
		});
	});
</script>