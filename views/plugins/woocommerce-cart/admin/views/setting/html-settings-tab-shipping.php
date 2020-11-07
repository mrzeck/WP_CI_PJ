<?php

$tabs 		= woocommerce_cart_settings_tabs_shipping();

if(have_posts($tabs)) {

reset($tabs);

$section 		= removeHtmlTags( ($ci->input->get('section'))?$ci->input->get('section'):key($tabs) );

$wcmc_shipping 			= get_option('wcmc_shipping', []);

$wcmc_shipping_default 	= get_option('wcmc_shipping_default', $section);

if(!isset($wcmc_shipping[$section])) {

	$wcmc_shipping[$section]['enabled'] 		= true;

	$wcmc_shipping[$section]['label'] 			= $tabs[$section]['label'];

	$wcmc_shipping[$section]['price_default'] 	= 'Liên hệ';
}

$wcmc_shipping = $wcmc_shipping[$section];

?>

<div class="section-list">
	<ul>
		<?php foreach ($tabs as $key => $tab): ?>
		<li class="<?php echo ($section == $key )?'active':'';?>"><a href="admin/plugins?page=woocommerce_cart_settings&tab=shipping&section=<?= $key ?>"><?= $tab['label'];?></a></li>
		<?php endforeach ?>
	</ul>
</div>

<div class="clearfix"></div>

<style type="text/css">
	.section-list { overflow:hidden; margin-bottom: 10px;}
	.section-list ul li { float: left; }
	.section-list ul li a { display: block; margin-right: 10px; position: relative; }
	.section-list ul li a:after { content: ''; border-right: 1px solid #000; position: relative; right:-5px; }
	.section-list ul li.active a { color:#000; }
</style>


<div class="box">
    <div class="box-content">
	
	<input type="hidden" name="wcmc_shipping_key" class="form-control" value="<?php echo $section;?>">
	
    <?php
        echo '<div class="clearfix"></div><br/ >';
        $input = array(
            'field' => 'wcmc_shipping_enabled','label'	=> $wcmc_shipping['label'],	'type'  => 'checkbox',	'options' => 1,	'after' => '<div>',	'before' => '</div>',
        );
        echo '<div class="col-md-2"><label for="">Bật/Tắt</label></div>';
        echo '<div class="col-md-6">';
        echo _form($input, $wcmc_shipping['enabled']);
		echo '</div><div class="clearfix"></div>';
		
        $input = array(
            'field' => 'wcmc_shipping_label',	'label'	=> 'Tiêu đề',	'type'  => 'text',	'after' => '<div>',	'before' => '</div>',
        );
        echo '<div class="col-md-2"><label for="">Tiêu đề</label></div>';
        echo '<div class="col-md-6">';
        echo _form($input, $wcmc_shipping['label']);
		echo '</div><div class="clearfix"></div>';
		
		$input = array(
            'field' => 'wcmc_shipping_price_default',	'label'	=> 'Giá mặc định',	'type'  => 'text','note' => 'Hiển thị khi phí ship bằng 0',	'after' => '<div>',	'before' => '</div>',
        );
        echo '<div class="col-md-2"><label for="">Giá mặc định</label></div>';
        echo '<div class="col-md-6">';
        echo _form($input, $wcmc_shipping['price_default']);
		echo '</div><div class="clearfix"></div>';
		
		$input = array(
            'field' => 'wcmc_shipping_default','label'	=> 'Chọn làm phí vận chuyển mặc định', 'note' => 'Khi có nhiều hình thức vận chuyển', 'type'  => 'checkbox',	'options' => $section,	'after' => '<div>',	'before' => '</div>',
        );
        echo '<div class="col-md-2"><label for="">Mặc định</label></div>';
        echo '<div class="col-md-6">';
        echo _form($input, $wcmc_shipping_default);
		echo '</div><div class="clearfix"></div>';
    ?>
    </div>
</div>

<?php call_user_func( $tabs[$section]['callback'], $ci, $section ) ?>

<style>
    .radio label, .checkbox label {
        padding-left:0;
    }
</style>


<script type="text/javascript">
	$(document).ready(function() {

	   $('#mainform').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'wcmc_ajax_setting_shipping_save';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
	  			show_message(data.message, data.status);
			});

			return false;

		});

		$('.wcmc-shipping-zone__btn-delete').click(function() {

			tr = $(this).closest('.wcmc-shipping-zone__item');

			$(this).text('Đang xóa ...');

			var data 		= {
				zone_id 		: $(this).attr('data-id')
			}

			data.action     =  'wcmc_shipping_ajax_zone_delete';

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {

	  			show_message(data.message, data.type);

	  			if( data.type == 'success' ) tr.remove();
			});

			return false;

		});
	});
</script>
<?php } ?>


