<?php
include_once 'tinymce-ajax.php';

function get_font_family() {

	$font_family = [
		[
			'key' 	=> 'arial,helvetica,sans-serif',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Arial',
		],
		[
			'key' 	=> 'arial,helvetica,sans-serif',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Arial Black',
		],
		[
			'key' 	=> 'courier new,courier',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Courier New',
		],
		[
			'key' 	=> 'helvetica,sans-serif',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Helvetica',
		],
		[
			'key' 	=> 'tahoma,arial,helvetica,sans-serif',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Tahoma',
		],
		[
			'key' 	=> 'times new roman,times, sans-serif',
			'type' 	=> 'default',
			'load' 	=> '',
			'label' => 'Times New Roman',
		],
		[
			'key' 	=> 'UTMAvo,sans-serif',
			'type' 	=> 'theme',
			'load' 	=> '',
			'label' => 'UTM Avo',
		],
		[
			'key' 	=> 'UTMAvoBold,sans-serif',
			'type' 	=> 'theme',
			'load' 	=> '',
			'label' => 'UTM Avo Bold',
		],
		[
			'key' 	=> 'UTMCafeta,sans-serif',
			'type' 	=> 'theme',
			'load' 	=> '',
			'label' => 'UTM Cafeta',
		],
		[
			'key' 	=> 'Roboto, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Roboto',
			'label' => 'Roboto',
		],
		[
			'key' 	=> 'Roboto Condensed, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Roboto+Condensed',
			'label' => 'Roboto Condensed',
		],
		[
			'key' 	=> 'Roboto Slab, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Roboto+Slab',
			'label' => 'Roboto Slab',
		],
		[
			'key' 	=> 'Lobster, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Lobster',
			'label' => 'Lobster',
		],
		[
			'key' 	=> 'Open Sans, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Open+Sans',
			'label' => 'Open Sans',
		],
		[
			'key' 	=> 'Open Sans Condensed, Geneva, sans-serif',
			'type' 	=> 'google',
			'load' 	=> 'Open+Sans+Condensed:700|Open+Sans+Condensed:300',
			'label' => 'Open Sans Condensed',
		],

		
	];

	return get_option('tinymce_config_font_family', $font_family);
}

// view setting tinymce
function tinymce_editor() {

	$ci =& get_instance();

	$get_tiny = get_option('tinymce_config_general', array(
		'group1' 				=>array('undo','redo','pastetext'),
		'group2' 				=>array('styleselect'),
		'group3' 				=>array('fontselect'),
		'group4' 				=>array('fontsizeselect'),
		'group5' 				=>array('bold','italic','underline'),
		'group6' 				=>array('alignleft','aligncenter','alignright','alignjustify'),
		'group7' 				=>array('bullist','numlist','outdent','indent'),
		'group8' 				=>array('link','unlink','anchor'),
		'group9' 				=>array('table','responsivefilemanager','image','media'),
		'group10' 				=>array('forecolor','backcolor'),
		'group11' 				=>array('print','preview','code'),
	));

	$get_tiny_shortcut = get_option('tinymce_config_general_shortcut', array(
		'group1' 				=>array('undo','redo','pastetext'),
		'group2' 				=>array('styleselect'),
		'group3' 				=>array('fontselect'),
		'group4' 				=>array('fontsizeselect'),
		'group5' 				=>array('bold','italic','underline'),
		'group8' 				=>array('link','unlink','anchor'),
		'group10' 				=>array('forecolor','backcolor'),
		'group11' 				=>array('code'),
	));
	
	$tiny_setting = array(
		'group1' 				=>array('undo','redo','pastetext'),
		'group2' 				=>array('styleselect'),
		'group3' 				=>array('fontselect'),
		'group4' 				=>array('fontsizeselect'),
		'group5' 				=>array('bold','italic','underline'),
		'group6' 				=>array('alignleft','aligncenter','alignright','alignjustify'),
		'group7' 				=>array('bullist','numlist','outdent','indent'),
		'group8' 				=>array('link','unlink','anchor'),
		'group9' 				=>array('table','responsivefilemanager','image','media'),
		'group10' 				=>array('forecolor','backcolor'),
		'group11' 				=>array('print','preview','code'),
	);

	$get_color 	= get_option('tinymce_config_general', array('color'=>''));

	$font_size 	= get_option('tinymce_config_font_size', ['8px','9px','10px','11px','12px', '13px', '14px', '15px', '16px', '17px','18px', '20px', '26px', '36px', '40px', '46px', '62px', '72px']);

	$font_size  = implode(',', $font_size);
	?>

	<div class="col-md-8">
	    <div class="box">
	        <div  class="box-nav-tab">
	            <ul class="nav nav-tabs" role="tablist">
	                <li class="active" role="presentation"><a href="#general" role="tab" data-toggle="tab" >Cấu hình chung</a></li>
	                <li role="presentation"><a href="#addfont" role="tab" data-toggle="tab" >Font chữ</a></li>
	            </ul>
	            <div class="tab-content">

	            	<!-- tab cấu hình chung -->
	            	<div id="general" class="tab-pane active">
						<?php include 'tinymce-tab-general.php';?>
		            </div>
		            <!-- Tab cấu hình Font -->
	            	<div id="addfont" class="tab-pane" style="padding: 10px">
	            		<?php include 'tinymce-tab-font.php';?>
	            	</div>

	            </div>
	        </div>
	      
	        <style>
	        	#general .bg-img {
	        		width: 100%; background-repeat: no-repeat; background-position: center center; margin-bottom: 10px;
	        	}
	        	.list-item {
	        		padding: 20px 10px 10px 10px; background-color: #f0f0f0; overflow: hidden;
	        	}
	        	.list-item.item2 { padding: 10px; }
	        	.list-item ul li {
	        		float: left;
	        		height: 35px; width: 80px;
	        		margin: 0px;
	        		position: relative;
	        		opacity: 0.5;
	        	}
	        	.list-item ul li.i-checked {
	        		opacity: 1;
	        	}
	        	.list-item ul li.i-color {
	        		opacity: 1;
	        	}

	        	.list-item ul li img { height: 30px;}

	        	.list-item ul li input{ position: absolute; top: -5px;left: -2px; z-index: 95; width: 15px; height: 15px; }

	        	.tab-content .form-group { margin-bottom: 10px; overflow: hidden; }

	        	.bootstrap-tagsinput .badge [data-role="remove"]:after { content: "×" !important; }

				#adminmenumain, #adminmenuwrap{
					z-index: 9999!important;
				}
				.tab-content{
					position: relative;
					min-height: 44em;
				}
				.tab-content.abc:after{
					content: '';
					position: absolute;
					display: block;
					top: 0;
					left: 0;
					z-index: 999;
					width: 100%;
					height: 100%;
					background: rgba(0,0,0,0.3);
				}
	        </style>
	    </div>
	</div>
	
	
	<?php
}


// setting js tinymce
function tiny() {
	
	$tinymce_setting = get_option('tinymce_config_general',  array(
		'group1' 				=>array('undo','redo','pastetext'),
		'group2' 				=>array('styleselect'),
		'group3' 				=>array('fontselect'),
		'group4' 				=>array('fontsizeselect'),
		'group5' 				=>array('bold','italic','underline'),
		'group6' 				=>array('alignleft','aligncenter','alignright','alignjustify'),
		'group7' 				=>array('bullist','numlist','outdent','indent'),
		'group8' 				=>array('link','unlink','anchor'),
		'group9' 				=>array('table','responsivefilemanager','image','media'),
		'group10' 				=>array('forecolor','backcolor'),
		'group11' 				=>array('print','preview','code'),)
	);

	$list = '';

	foreach ($tinymce_setting as $group_name => $value) {

		$arr = implode(' ', $tinymce_setting[$group_name]);

		$list = $list .' | '.$arr;
	}

	$list_shortcut = '';

	$tinymce_setting_shortcut = get_option('tinymce_config_general_shortcut',  array(
		'group1' 				=>array('undo','redo','pastetext'),
		'group2' 				=>array('styleselect'),
		'group3' 				=>array('fontselect'),
		'group4' 				=>array('fontsizeselect'),
		'group5' 				=>array('bold','italic','underline'),
		'group6' 				=>array('alignleft','aligncenter','alignright','alignjustify'),
		'group7' 				=>array('bullist','numlist','outdent','indent'),
		'group8' 				=>array('link','unlink','anchor'),
		'group9' 				=>array('table','responsivefilemanager','image','media'),
		'group10' 				=>array('forecolor','backcolor'),
		'group11' 				=>array('print','preview','code'))
	);


	foreach ($tinymce_setting_shortcut as $group_name => $value) {

		$arr = implode(' ', $tinymce_setting_shortcut[$group_name]);

		$list_shortcut = $list_shortcut .' | '.$arr;
	}


	$font_size 	= get_option('tinymce_config_font_size', ['8px','9px','10px','11px','12px', '13px', '14px', '15px', '16px', '17px','18px', '20px', '26px', '36px', '40px', '46px', '62px', '72px']);

	$font_family =  get_font_family();

	$font_formats = '';

	$font_googles = '';

	foreach ($font_family as $key => $font) {

		$font_formats .= $font['label'].'='.$font['key'].';';

		if($font['type'] == 'google') {

			$font_googles .= $font['load'].'|';
		}
	}

	if(!empty($font_googles)) {

		$font_googles = trim($font_googles, '|');
		$font_googles = 'https://fonts.googleapis.com/css?family='.$font_googles;
	}


	// config fontsize_formats
	$font_size  	= implode(' ', $font_size);
	?>
	<script type="text/javascript">
		function tinymce_load() {
		    tinymce.init({
		        fontsize_formats: "<?php echo $font_size;?>",
		        selector: "textarea.tinymce",
		        height: 400,
		        plugins: [
		            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
		            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking fullscreen",
		            "table directionality emoticons paste code responsivefilemanager"
		        ],
		        menubar: 'file edit insert view format table tools help',
		        toolbar1:"<?php echo $list;?>",
		        content_css: [domain+'scripts/tinymce/skins/custom.css?v=1.0.0','<?php echo $font_googles;?>'],
		        font_formats: '<?php echo $font_formats?>',
		        image_advtab: true ,
		        image_caption: true,
		        language: "vi",

		        relative_urls : true,
		        remove_script_host : true,
		        convert_urls : true,
		        document_base_url: domain,

		        external_filemanager_path: domain+"scripts/rpsfmng/filemanager/",
		        filemanager_title:"Responsive Filemanager" ,
		        external_plugins: { "filemanager" : domain+"scripts/rpsfmng/filemanager/plugin.min.js"},
		        file_browser_callback: function(field_name, url, type, win) {
		          	func_value = win.document.getElementById(field_name).value;
		         	filemanager(field_name, func_value, type, win); 
				}
		    });

		    tinymce.init({
		        selector: "textarea.tinymce-shortcut",
		        fontsize_formats: "<?php echo $font_size;?>",
		        height: 200,
		        plugins: [
		            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
		            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking fullscreen",
		            "table directionality emoticons paste responsivefilemanager code"
		       	],
				menubar:false,
		        toolbar1: "<?=$list_shortcut?>",
		        content_css: [domain+'scripts/tinymce/skins/custom.css','<?php echo $font_googles;?>'],
	  			font_formats: '<?php echo $font_formats?>',
		        image_advtab: true ,
		        language: "vi",
		        relative_urls : true,
		        remove_script_host : true,
		        convert_urls : true,
		        document_base_url: domain,
				
		        external_filemanager_path: domain+"scripts/rpsfmng/filemanager/",
		        filemanager_title:"Responsive Filemanager" ,
		        external_plugins: { "filemanager" : domain+"scripts/rpsfmng/filemanager/plugin.min.js"},
		        file_browser_callback: function(field_name, url, type, win) {
		          	func_value = win.document.getElementById(field_name).value;
		          	filemanager(field_name, func_value, type, win); 
		        }
		    });
		}
	</script>
	<?php
}

add_action('admin_footer', 'tiny' );