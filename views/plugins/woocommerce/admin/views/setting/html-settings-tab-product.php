<?php

$section 	= removeHtmlTags( ($ci->input->get('section'))?$ci->input->get('section'):'object' );

$tabs 		= woocommerce_settings_tabs_sub_product();

?>

<div class="section-list">
	<ul>
		<?php foreach ($tabs as $key => $tab): ?>
		<li class="<?php echo ($section == $key )?'active':'';?>"><a href="admin/plugins?page=woocommerce_settings&view=settings&tab=product&section=<?= $key ?>"><?= $tab['label'];?></a></li>
		<?php endforeach ?>
	</ul>
</div>

<style type="text/css">
	.section-list ul { overflow:hidden; }
	.section-list ul li { float: left; }
	.section-list ul li a { display: block; margin-right: 10px; position: relative; }
	.section-list ul li a:after { content: ''; position: relative; right:-5px; }
	.section-list ul li.active a { color:#000; }
</style>
<div class="clearfix"></div>
<div>
	<?php admin_loading_icon();?>
	<?php call_user_func( $tabs[$section]['callback'], $ci, $section ) ?>
</div>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {

			$('.loading').show();

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_ajax_setting_product_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {

				$('.loading').hide();

	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>