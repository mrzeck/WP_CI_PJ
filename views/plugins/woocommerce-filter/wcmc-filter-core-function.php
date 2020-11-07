<?php
if ( ! function_exists( 'wcmc_filter_get_template' ) ) {

	function wcmc_filter_get_template( $template_path = '' , $args = '', $return = false) {

		$ci =& get_instance();

		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path 	= $ci->plugin->dir.'/'.WCMCF_FOLDER.'/'.$template_path.'.php';

		if( !file_exists($path) ) $path = $ci->plugin->dir.'/'.WCMCF_FOLDER.'/template/'.$template_path.'.php';

		ob_start();

		include $path;

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

if ( ! function_exists( 'wcmc_filter_get_include' ) ) {

	function wcmc_filter_get_include( $template_path = '' , $args = '', $return = false) {

		$ci =& get_instance();

		extract($ci->data);

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$path 	= $ci->plugin->dir.'/'.WCMCF_FOLDER.'/'.$template_path.'.php';

		if( !file_exists($path) ) $path = $ci->plugin->dir.'/'.WCMCF_FOLDER.'/'.$template_path.'.php';

		ob_start();

		include $path;

		if ($return === true)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		ob_end_flush();
	}
}

if ( ! function_exists( 'wcmc_filter_get_url' ) ) {

	function wcmc_filter_get_url( $input, $option_value ) {

		$ci =& get_instance();

		$input = removeHtmlTags( $input );

		$option_value = removeHtmlTags( $option_value );

		$get = apply_filters( 'wcmc_filter_get_url', $ci->input->get(), $input, $option_value  );
		// echo $get;
		return wcmc_filter_creat_url( $get );
	}
}


if ( ! function_exists( 'wcmc_filter_creat_url' ) ) {

	function wcmc_filter_creat_url( $get ) {

		$ci =& get_instance();

		$filter = '';
		if( have_posts( $get ) ) {

			// $filter = '?';

			foreach ($get as $filter_key => $filter_value ) {

				if( $filter != '?' ) $filter .= '&';

				$filter .= $filter_key.'='.$filter_value;
			}
		}

		return $filter;
	}
}


if ( ! function_exists( 'wcmc_filter_product' ) ) {

	function wcmc_filter_product_where( $where ) {

		return apply_filters( 'wcmc_filter_product_where', $where );

	}

	add_filter( 'woocommerce_controllers_index_where', 'wcmc_filter_product_where', 10, 2 );
}