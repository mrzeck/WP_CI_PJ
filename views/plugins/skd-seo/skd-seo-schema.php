<?php
if( !function_exists('skd_seo_schema_breadcrumb') ) {

	function skd_seo_schema_breadcrumb () {

		add_filter('breadcrumb_first', 		'skd_seo_schema_breadcrumb_first', 10 );

		add_filter('breadcrumb_item', 		'skd_seo_schema_breadcrumb_item', 10, 2 );

		add_filter('breadcrumb_item_last', 	'skd_seo_schema_breadcrumb_item', 10, 2 );

		add_filter('breadcrumb_icon', 		'skd_seo_schema_breadcrumb_icon', 10, 1 );

		add_action('theme_custom_css', 		'skd_seo_schema_breadcrumb_css', 10 );
	}

	add_action('init', 'skd_seo_schema_breadcrumb' );

}

if( !function_exists('skd_seo_schema_breadcrumb_first') ) {

	function skd_seo_schema_breadcrumb_first ( $bre ) {

		$bre = '<span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"> <a href="'.base_url().'" class="btn btn-default" itemprop="url"> <span itemprop="title">'.__('Trang Chủ','trang-chu').'</span> </a> </span>';
		return $bre;
	}
}

if( !function_exists('skd_seo_schema_breadcrumb_item') ) {

	function skd_seo_schema_breadcrumb_item ( $bre, $val = '' ) {

		$ci =& get_instance();

		if( have_posts($val) ) {

			$slug = '';

			if( !empty($val->slug) ) $slug = $val->slug;

			else if( !empty( $ci->data['object']->slug) ) $slug =  $ci->data['object']->slug;

			else if( !empty( $ci->data['category']->slug) ) $slug =  $ci->data['category']->slug;

			$bre = '<span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"> <a href="'.get_url($slug).'" class="btn btn-default" itemprop="url"> <span itemprop="title">'.$val->name.'</span> </a> </span>';

		}

		return $bre;
	}
}

if( !function_exists('skd_seo_schema_breadcrumb_icon') ) {

	function skd_seo_schema_breadcrumb_icon ( $bre ) {

		$bre = '<span><a class="btn btn-default btn-next"><i class="fal fa-angle-right"></i></a></span>';

		return $bre;
	}
}

if( !function_exists('skd_seo_schema_breadcrumb_css') ) {

	function skd_seo_schema_breadcrumb_css () {
		?> 
		.breadcrumb span a.btn.btn-default { color: #333; background-color: #fff; border-color: #ccc;height:37px; position: relative; float: left;border: 0; border-radius: 0; }
		.btn-breadcrumb a.btn.btn-default { color:#fff;background-color: transparent;border-color: transparent;height:37px; position: relative; float: left;border: 0; border-radius: 0; } <?php
	}
}

if( !function_exists('skd_seo_schema_generator') ) {

	function skd_seo_schema_generator () {

		$ci =& get_instance();

		$schema = '';

		if( is_home() ) $schema = skd_seo_schema_website();

		if( is_page('products_detail') ) $schema = skd_seo_schema_product( $ci->data['object']);

		if( is_page('post_detail') ) $schema = skd_seo_schema_post( $ci->data['object']);

		if( is_page('post_index') && have_posts($ci->data['category']) ) $schema = skd_seo_schema_post_category( $ci->data['category']);

		return $schema;
	}
}

if( !function_exists('skd_seo_schema_website') ) {

	function skd_seo_schema_website () {

		$schema = array(
			"@context"      => "http://schema.org/",
			"@type"         => "WebSite",
			"name"          => get_option('general_label'),
			"alternateName" => get_option('general_title'),
			"url"           => base_url(),
		);

		return json_encode($schema);
	}
}

if( !function_exists('skd_seo_schema_product') ) {

	function skd_seo_schema_product ( $item ) {

		$schema = array(
			"@context"      => "http://schema.org/",
			"@type"         => "Product",
			"name"          => get_option('general_label'),
			"image"         => base_url().get_img_link( $item->image ),
			"description" 	=> removeHtmlTags($item->excerpt),
			"offers" 		=> array(
				"@type"         => "AggregateOffer",
				"priceCurrency" => "VND",
				"lowPrice"      => $item->price,
				"offerCount"    => $item->price
    		)
		);

		return json_encode($schema);
	}
}


if( !function_exists('skd_seo_schema_post') ) {

	function skd_seo_schema_post ( $item ) {

		if(have_posts($item)) {

			$schema = array(
				"@context"         => "http://schema.org/",
				"@type"            => "NewsArticle",
				"mainEntityOfPage" => fullurl(),
				"headline"         => $item->title,
				"datePublished"    => date(DATE_ATOM, strtotime($item->created)),
				"dateModified"     => 	date(DATE_ATOM),
				"image"            => array(
					"@type"  => "ImageObject",
					"url"    => base_url().get_img_link( $item->image ),
					"height" => 400,
					"width"  => 700
				),
				"author"           => array(
					"@type"  => "Person",
					"name"   => 'Quản trị',
				),
				"publisher"            => array(
					"@type"  => "Organization",
					"name"    => get_option('general_label'),
					"logo"            => array(
						"@type"  => "ImageObject",
						"url"    => base_url().get_img_link( get_option('logo_header') ),
						"height" => 260,
						"width"  => 100
					),
				),
			);
			return json_encode($schema);
		}
		else return '';
	}
}



if( !function_exists('skd_seo_schema_post_category') ) {

	function skd_seo_schema_post_category ( $item ) {

		$schema = array(
			"@context"         => "http://schema.org/",
			"@type"            => "NewsArticle",
			"mainEntityOfPage" => fullurl(),
			"headline"         => $item->name,
			"datePublished"    => date(DATE_ATOM, strtotime($item->created)),
			"dateModified"     => 	date(DATE_ATOM),
			"image"            => array(
				"@type"  => "ImageObject",
				"url"    => base_url().get_img_link( $item->image ),
				"height" => 400,
				"width"  => 700
    		),
    		"author"           => array(
				"@type"  => "Person",
				"name"   => 'Quản trị',
    		),
    		"publisher"            => array(
				"@type"  => "Organization",
				"name"    => get_option('general_label'),
				"logo"            => array(
					"@type"  => "ImageObject",
					"url"    => base_url().get_img_link( get_option('logo_header') ),
					"height" => 260,
					"width"  => 100
	    		),
    		),
		);
		return json_encode($schema);
	}
}