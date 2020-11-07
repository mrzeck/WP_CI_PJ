<?php
/**
Plugin name     : Tùy Chỉnh Seo
Plugin class    : skd_seo
Plugin uri      : https://vitechcenter.com
Description     : Ứng dụng Tùy chỉnh SEO sẽ giúp bạn tự SEO hiệu quả cho website của mình
Author          : SKDSoftware Dev Team
Version         : 1.3.2
*/
define( 'SKD_SEO_NAME', 'skd-seo' );

define( 'SKD_SEO_PATH', plugin_dir_path( SKD_SEO_NAME ) );

class skd_seo {

    private $name = 'skd_seo';

    public  $ci;

    function __construct() {

        $this->ci =&get_instance();
    }

    //active plugin
    public function active() {

    	$model = get_model('plugins', 'backend');

    	//add sitemap to router
		$model->settable('routes');

		$count = $model->count_where(array('slug' => 'sitemap.xml', 'plugin' => 'skd_seo'));

		if($count == 0) {

			$model->add(array(
				'slug'        => 'sitemap.xml',
				'controller'  => 'frontend_home/home/page/',
				'plugin'      => 'skd_seo',
				'object_type' => 'skd_seo',
				'directional' => 'skd_seo_sitemap',
				'callback' 	  => 'skd_seo_sitemap',
			));
		}

		//add robots to router
		$count = $model->count_where(array('slug' => 'robots.txt', 'plugin' => 'skd_seo'));

		if($count == 0) {
			$model->add(array(
				'slug'        => 'robots.txt',
				'controller'  => 'frontend_home/home/page/',
				'plugin'      => 'skd_seo',
				'object_type' => 'skd_seo',
				'directional' => 'skd_seo_robots',
				'callback' 	  => 'skd_seo_robots',
			));
		}

		//add setting
		add_option( 'skd_seo_robots', '' );
    }

    //Gở bỏ plugin
    public function uninstall() {

    	$model = get_model('plugins', 'backend');

    	//add sitemap to router
		$model->settable('routes');

		$model->delete_where(array('plugin' => 'skd_seo'));

		//add setting
		delete_option( 'skd_seo_robots' );
    }
}

require_once 'admin/index.php';

require_once 'skd-seo-schema.php';

require_once 'skd-seo-sitemap.php';

/**
 * [skd_seo add các hạng mục seo cho header]
 * @return [type] [description]
 */
function skd_seo () {

	$ci =&get_instance();

	$seo['title'] 		= get_option('general_title');

	$seo['description'] = get_option('general_description');

	$seo['keywords'] 	= get_option('general_keyword');

	$seo['image'] 		= get_img_link(get_option('logo_header'));

	if(isset($ci->data['category']) && have_posts($ci->data['category']) && $ci->template->method == 'index') {

		$seo['title'] 		= ( $ci->data['category']->seo_title 		!= '')?$ci->data['category']->seo_title:$ci->data['category']->name;

		$seo['description'] = ( $ci->data['category']->seo_description 	!= '')?$ci->data['category']->seo_description:removeHtmlTags($ci->data['category']->excerpt);

		$seo['keywords'] 	= ( $ci->data['category']->seo_keywords		!= '')?$ci->data['category']->seo_keywords:$seo['keywords'];

		$seo['image'] 		= ( $ci->data['category']->image 			!= '')?get_img_link($ci->data['category']->image):$seo['image'];
	}

	if(isset($ci->data['object']) && have_posts($ci->data['object']) && $ci->template->method == 'detail') {

		$seo['title'] 		= ( $ci->data['object']->seo_title 			!= '')?$ci->data['object']->seo_title:$ci->data['object']->title;

		$seo['description'] = ( $ci->data['object']->seo_description 	!= '')?$ci->data['object']->seo_description:removeHtmlTags($ci->data['object']->excerpt);

		$seo['keywords'] 	= ( $ci->data['object']->seo_keywords 		!= '')?$ci->data['object']->seo_keywords:$ci->data['object']->title;

		$seo['image'] 		= ( $ci->data['object']->image 				!= '')?get_img_link($ci->data['object']->image):$seo['image'];
	}
	?>
	<title><?= $seo['title'];?></title>

	<meta name="keywords" content="<?= $seo['keywords'];?>" />
    <meta name="description" content="<?= $seo['description'];?>" />
	<link rel="canonical" href="<?= current_url();?>" />
	<!-- ================= facebook - google - zalo ======================= -->

    <meta property="og:url" content="<?= fullurl();?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $seo['title'];?>" />
    <meta property="og:description" content="<?= $seo['description'];?>" />
    <meta property="og:image" content="<?= base_url().$seo['image'];?>" />

	<!-- ================= twitter ======================= -->
    <meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?= $seo['title'];?>" />
	<meta name="twitter:description" content="<?= $seo['description'];?>" />
	<meta name="twitter:image" content="<?= base_url().$seo['image'];?>" />

	<meta itemprop="name" content="<?= get_option('general_title');?>" />
	<meta itemprop="url" content="<?= base_url();?>" />
	<meta itemprop="creator accountablePerson" content="vitechcenter" />
	<meta itemprop="image" content="<?= base_url().$seo['image'];?>" />

	<meta http-equiv="content-language" content="vi" />
	<meta name="Area" content="Vietnam" />
	<meta name="geo.region" content="VN" />
	<meta name="language" content="vietnamese" />
	<meta name="author" content="<?= get_option('general_title');?>" />
	<meta itemprop="author" content="<?= base_url();?>" />
	<meta itemprop="dateModified" content="<?php echo date('Y-m-d');?>" />
    <meta itemprop="name" content="<?= $seo['title'];?>" />
	<meta itemprop="creator accountablePerson" content="<?php echo date('Y-m-d');?>" />
    <meta itemprop="datePublished" content="<?php echo date('Y-m-d');?>" >
    <meta itemprop="headline" content="<?= $seo['title'];?>" >
    <div itemprop="publisher" itemscope itemtype="<?= fullurl();?>">
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= base_url().$seo['image'];?>">
            <meta itemprop="width" content="400">
            <meta itemprop="height" content="60">
        </div>
        <meta itemprop="name" content="<?php echo date('Y-m-d');?>">
    </div>


	<?php if( get_option('seo_google_masterkey') != '') { ?>
	<meta name="google-site-verification" content="<?php echo get_option('seo_google_masterkey');?>" />
	<?php } ?>

	<?php $schema =  skd_seo_schema_generator();?>
	<?php if(!empty($schema)) { ?>
	<script type="application/ld+json">
	<?php echo $schema;?>
	</script>
	<?php } ?>
	<?php
}



function skd_seo_in_tag_html() {

	$output = 'itemscope ';

	$output .= 'itemtype="http://schema.org/Article" ';

	$output .= 'prefix="og: http://ogp.me/ns#"';



	echo $output;

}



add_action('cle_header', 'skd_seo', 1);



add_action('in_tag_html', 'skd_seo_in_tag_html', 1);



function skd_seo_robots($ci , $model) {

	header('Content-type: text/plain');

	$robots = get_option('skd_seo_robots');

	if( !empty($robots) ) {

		echo $robots;

	}

	else {

		echo 'User-agent: *'."\n";

		echo 'Disallow:'."\n"; 

		echo 'Disallow: /cgi-bin/'."\n";

		echo 'Sitemap: '.base_url('sitemap.xml')."\n";

	}

}