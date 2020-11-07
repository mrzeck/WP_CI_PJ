<?php $this->template->render_include('action_bar');?>
<?php
foreach ($system_tabs as $key => $value) {

	if(isset($value['root']) && $value['root'] == true && !is_super_admin()) unset($system_tabs[$key]);
}

reset($system_tabs);

$current_tab = ($ci->input->get('tab') != '')?$ci->input->get('tab'):key($system_tabs); ?>

<form id="system_form" method="post">

	
	<input type="hidden" name="system_tab_key" value="<?php echo $current_tab;?>">
	

    <?php admin_loading_icon();?>

    <div class="col-md-12">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h1 class="ui-title-bar__title">Cấu Hình Hệ Thống - <?php echo $system_tabs[$current_tab]['label'];?></h1>
            <div class="ui-title-bar__action">
                <?php foreach ($system_tabs as $key => $tab): ?>
                <a href="<?php echo URL_ADMIN;?>/system?tab=<?php echo $key;?>" class="<?php echo ($key == $current_tab)?'active':'';?> btn btn-default">
                    <?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
                    <span><?php echo $tab['label'];?></span>
                </a>
                <?php endforeach ?>
            </div>
        </div>
	</div>
	
	
	<div class="clearfix"></div>

	<div role="tabpanel">
		<!-- Tab panes -->
		<div class="tab-content" style="padding-top: 10px;">
			<?php call_user_func( $system_tabs[$current_tab]['callback'], $ci, $current_tab ) ?>
		</div>
	</div>
</form>


<script type="text/javascript">
	$(function() {

		$('#system_form').submit(function() {

			var data 		= $(this).serializeJSON();

			$('textarea[type="code"]').each(function(index, el) {
				data[$(this).attr('name')] = editor[$(this).attr('name')].getValue();
			});

			data.action     =  'ajax_system_save';

			load = $(this).find('.loading'); load.show();

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
				load.hide();
	  			show_message(data.message, data.status);
			});

			return false;

		});

		$('#smtp_btn_test').click(function() {

			var data 		= $( ':input', $('#smtp_form_test') ).serializeJSON();

            data.action     =  'ajax_email_smtp_test';

			load = $(this).find('.ajax-load-qa'); load.show();

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
				load.hide();
				
	  			show_message(data.message, data.status);

	  			$('#smtp_form_test_result').html(data.data);
			});

			return false;

		});
	});
</script>