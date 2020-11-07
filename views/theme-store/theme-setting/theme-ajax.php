<?php
function theme_ajax_product_search( $ci, $model ) {

	$result['message'] 	= 'Không tìm thấy.';

	$result['status'] 	= 'error';

	if( $ci->input->post() ) {

		$keyword = removeHtmlTags( $ci->input->post('keyword') );

		$objects =  gets_product( array(
			'where' 		=> array('public' => 1, 'trash' => 0 ),
			'params'		=> array('limit' => 5),
			'where_like' 	=> array( 'title' => array($keyword) ),
		) );

		$result['data'] = '';

		if( have_posts($objects) ) {
			$result['data'] = '<div class="product-slider-vertical">';
			foreach ($objects as $object) {
				$result['data'] .= wcmc_get_template( 'loop/item_product_vertical', array('val' => $object), true );
			}
			$result['data'] .= '</div>';
		}
		else {
			$result['data'] = '<div class="result-msg no-result">Không tìm thấy</div>';
		}

	}

	echo json_encode($result);
}

register_ajax('theme_ajax_product_search');
