<?php

$tabs 		= woocommerce_cart_settings_tabs_checkout();

reset($tabs);

$section 	= removeHtmlTags( ($ci->input->get('section'))?$ci->input->get('section'):key($tabs) );

?>

<div class="section-list">
	<ul>
		<?php foreach ($tabs as $key => $tab): ?>
		<li class="<?php echo ($section == $key )?'active':'';?>"><a href="admin/plugins?page=woocommerce_cart_settings&tab=checkout&section=<?= $key ?>"><?= $tab['label'];?></a></li>
		<?php endforeach ?>
	</ul>
</div>

<style type="text/css">
	.section-list ul li { float: left; }
	.section-list ul li a { display: block; margin-right: 10px; position: relative; }
	.section-list ul li a:after { content: ''; border-right: 1px solid #000; position: relative; right:-5px; }
	.section-list ul li.active a { color:#000; }
</style>

<?php call_user_func( $tabs[$section]['callback'], $ci, $section ) ?>


