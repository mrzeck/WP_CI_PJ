<?php 
function slider_animation( $key = '') {

	$list_anim['Fade'] = array(
        'fade'                    => 'Fade',
        'boxfade'                 => 'Fade Boxes',
        'slotfade-horizontal'     => 'Fade Slots Horizontal',
        'slotfade-vertical'       => 'Fade Slots Vertical',
        'fadefromright'           => 'Fade and Slide from Right',
        'fadefromleft'            => 'Fade and Slide from Left',
        'fadefromtop'             => 'Fade and Slide from Top',
        'fadefrombottom'          => 'Fade and Slide from Bottom',
        'fadetoleftfadefromright' => 'Fade To Left and Fade From Right',
        'fadetorightfadetoleft'   => 'Fade To Right and Fade From Left',
    );

    $list_anim['Zoom'] = array(
        'scaledownfromright'  => 'Zoom Out and Fade From Right',
        'scaledownfromleft'   => 'Zoom Out and Fade From Left',
        'scaledownfromtop'    => 'Zoom Out and Fade From Top',
        'scaledownfrombottom' => 'Zoom Out and Fade From Bottom',
        'zoomout'             => 'ZoomOut',
        'zoomin'              => 'ZoomIn',
        'slotzoom-horizontal' => 'Zoom Slots Horizontal',
        'slotzoom-vertical'   => 'Zoom Slots Vertical',
    );

    $list_anim['Parallax'] = array(
        'parallaxtoright'     => 'Parallax to Right',
        'parallaxtoleft'     => 'Parallax to Left',
        'parallaxtotop'     => 'Parallax to Top',
        'parallaxtobottom'    => 'Parallax to Bottom',
    );

    $list_anim['Slide'] = array(
        'slideup'              => 'Slide To Top',
        'slidedown'            =>'Slide To Bottom',
        'slideright'           =>'Slide To Right',
        'slideleft'            =>'Slide To Left',
        'slidehorizontal'      =>'Slide Horizontal (depending on Next/Previous)',
        'slidevertical'        =>'Slide Vertical (depending on Next/Previous)',
        'boxslide'             =>'Slide Boxes',
        'slotslide-horizontal' =>'Slide Slots Horizontal',
        'slotslide-vertical'   =>'Slide Slots Vertical',
        'curtain-1'            =>'Curtain from Left',
        'curtain-2'            =>'Curtain from Right',
        'curtain-3'            =>'Curtain from Middle',
    );

    $list_anim['Premium'] = array(
        '3dcurtain-horizontal' => '3D Curtain Horizontal',
        '3dcurtain-vertical'   => '3D Curtain Vertical',
        'cubic'                => 'Cube Vertical',
        'cubic'                => 'Cube Horizontal',
        'incube'               => 'In Cube Vertical',
        'incube-horizontal'    => 'In Cube Horizontal',
        'turnoff'              => 'TurnOff Horizontal',
        'turnoff-vertical'     => 'TurnOff Vertical',
        'papercut'             => 'Paper Cut',
        'flyin'                => 'Fly In',
        'random-static'        => 'Random Premium',
        'random'               => 'Random Flat and Premium',
    );

    return (isset($list_anim[$key]))?$list_anim[$key]:($key == '')?$list_anim:array();
}

function ht_slider_item_options( $options ) {

	$option_default = array(
	    'url'              => '',
	    'name'              => '',
	    'data_transition'  =>'fade',
	    'data_slotamount'  =>5,
	    'data_masterspeed' =>700,
	    'transition' => '',
	);

	if( !have_posts($options) ) $options = @unserialize($options);

	if( !have_posts($options) ) $options = array();

	return (object)array_merge($option_default, $options);
}

function ht_slider_libary() {
	$ci =& get_instance();
	?>
	<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
	<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/css/navstylechange.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/editor/type/fontello.css">
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/editor/editor.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/css/settings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/css/style.css" media="screen" />

	<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
	<script type="text/javascript" src="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/editor/editor.js"></script>
	<script type="text/javascript" src="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/js/jquery.themepunch.plugins.min.js"></script>
	<script type="text/javascript" src="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/js/jquery.themepunch.revolution.min.js"></script>
	<?php
}

function ht_slider_demo($slotamount = 5, $masterspeed = 700) {
	$ci =& get_instance();
	?>
	<article class="spectaculus">
		<!-- START REVOLUTION SLIDER 3.1 rev5 fullwidth mode -->
		<div class="fullwidthbanner-container roundedcorners">
			<div class="fullwidthbanner" >
				<ul>
					<!-- SLIDE  -->
					<li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
						<!-- MAIN IMAGE -->
						<img src="<?= $ci->plugin->get_path('ht-slider');?>images-demo/bg1.jpg"   alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
					</li>

					<!-- SLIDE  -->
					<li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
						<!-- MAIN IMAGE -->
						<img src="<?= $ci->plugin->get_path('ht-slider');?>images-demo/bg2.jpg"   alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
						<!-- LAYERS -->
					</li>

					<!-- SLIDE  -->
					<li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
						<!-- MAIN IMAGE -->
						<img src="<?= $ci->plugin->get_path('ht-slider');?>images-demo/bg3.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
					</li>

					<!-- SLIDE  -->
					<li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
						<!-- MAIN IMAGE -->
						<img src="<?= $ci->plugin->get_path('ht-slider');?>images-demo/bg4.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
					</li>


				</ul>
				<div class="tp-bannertimer"></div>
			</div>
		</div>
		<script type="text/javascript">
			var revapi;
			jQuery(document).ready(function() {

				   revapi = jQuery('.fullwidthbanner').revolution(
					{
						delay:15000,
						startwidth:1170,
						startheight:500,
						height:500,
						hideThumbs:10,

						thumbWidth:100,
						thumbHeight:50,
						thumbAmount:5,

						navigationType:"both",
						navigationArrows:"solo",
						navigationStyle:"round",

						touchenabled:"on",
						onHoverStop:"on",

						navigationHAlign:"center",
						navigationVAlign:"bottom",
						navigationHOffset:0,
						navigationVOffset:0,

						soloArrowLeftHalign:"left",
						soloArrowLeftValign:"center",
						soloArrowLeftHOffset:20,
						soloArrowLeftVOffset:0,

						soloArrowRightHalign:"right",
						soloArrowRightValign:"center",
						soloArrowRightHOffset:20,
						soloArrowRightVOffset:0,

						shadow:0,
						fullWidth:"on",
						fullScreen:"off",

						stopLoop:"on",
						stopAfterLoops:0,
						stopAtSlide:1,


						shuffle:"off",

						autoHeight:"off",
						forceFullWidth:"off",

						hideThumbsOnMobile:"off",
						hideBulletsOnMobile:"on",
						hideArrowsOnMobile:"on",
						hideThumbsUnderResolution:0,

						hideSliderAtLimit:0,
						hideCaptionAtLimit:768,
						hideAllCaptionAtLilmit:0,
						startWithSlide:0,
						videoJsPath:"plugins/revslider/rs-plugin/videojs/",
						fullScreenOffsetContainer: ""
					});
			});	//ready
		</script>
		<!-- END REVOLUTION SLIDER -->
		<!-- Content End -->
	</article> <!-- END OF SPECTACULUS -->
	<article class="toolpad">
		<section class="tool">
				<div data-val="<?= $masterspeed;?>" id="mrtime" class="tooltext">Time: <?= $masterspeed/1000;?>s</div>
				<div class="toolcontrols">
					<div id="dectime" class="toolcontroll withspace"><i class="icon-minus"></i></div>
					<div id="inctime" class="toolcontroll withspace2"><i class="icon-plus"></i></div>
				</div>
				<div class="clear"></div>
		</section>

		<section class="tool last">
				<div data-val="<?= $slotamount;?>" class="tooltext" id="mrslot">Slots: <?= $slotamount;?></div>
				<div class="toolcontrols">
					<div id="decslot" class="toolcontroll withspace"><i class="icon-minus"></i></div>
					<div id="incslot" class="toolcontroll withspace2"><i class="icon-plus"></i></div>
				</div>
				<div class="clear"></div>
		</section>
		<div class="clear"></div>
	</article>
	<?php
}

function ht_slider_load_script() {
	$ci =& get_instance();
	?>
	<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
	<link rel="stylesheet" type="text/css" href="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/css/settings.css" media="screen" />
	<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
	<script type="text/javascript" src="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/js/jquery.themepunch.plugins.min.js"></script>
	<script type="text/javascript" src="<?= $ci->plugin->get_path('ht-slider');?>/assets/src/js/jquery.themepunch.revolution.min.js"></script>
	<?php
}

function ht_slider_creat_item($item) {

	// $ci =& get_instance();

	$op = ht_slider_item_options($item->options);

	$output = '';

	if(isset($item->value) && $item->value != '') {

		$output = '<li '.$op->transition.' data-link="'.$op->url.'">';

		$output .= '<img src="'.get_img_link($item->value).'"  alt=""  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">';

		$output .= '</li >';
	}

	echo $output;

}