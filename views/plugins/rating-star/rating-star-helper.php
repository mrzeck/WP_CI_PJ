<?php
if(! function_exists('get_rating_star_setting')) {

	function get_rating_star_setting() {
		$setting = [
			'has_approving' => 0,
			'form' => [
				'email' => [
					'label' 	=> 'nguyenvan@gmail.com',
					'required' 	=> 'required',
				],
				'name' => [
					'label' 	=> 'Nhập tên của bạn',
					'required' 	=> 'required',
				],
				'title' => [
					'label' 	=> 'Tiêu đề',
					'required' 	=> 'required',
				]
			],
			'color' => [
				'star' => [
					'form' 		=> '#ffbe00',
					'detail' 	=> '#ffbe00',
					'object' 	=> '#ffbe00',
				]
			]
		];

		$option = get_option('rating_star_setting', $setting);

		$setting['form'] = array_merge($setting['form'], $option['form']);

		$setting['color'] = array_merge($setting['color'], $option['color']);

		return $setting;
	}
}

if(! function_exists('get_rating_star')) {

	function get_rating_star($args = '') {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('rating_star');

		if( is_numeric($args) ) {

			$args = array( 'where' => array('id' => (int)$args ) );
		}

		if( !have_posts($args) ) return array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where 	= $args['where'];

        $params = $args['params'];

        $rating_star =  $model->get_where($where, $params );

        return $rating_star;
	}
}

if(! function_exists('gets_rating_star')) {

	function gets_rating_star($args = '') {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('rating_star');

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where 	= $args['where'];

        $params = $args['params'];

        $rating_star = array();

        $rating_star =  $model->gets_where($where, $params );

        return $rating_star;
	}
}

if(! function_exists('count_rating_star')) {

	function count_rating_star($args = '') {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('rating_star');

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where 	= $args['where'];

        $params = $args['params'];

        $rating_star = array();

        $rating_star =  $model->count_where($where, $params );

        return $rating_star;
	}
}

if(!function_exists('insert_rating_star')) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function insert_rating_star( $rating_star = '' ) {

		$ci =& get_instance();

        $model = get_model('products');

        $model->settable('rating_star');

        $user = get_user_current();

        if ( ! empty( $rating_star['id'] ) ) {

			$id 		   = (int) $rating_star['id'];

			$update 	   = true;

			$old_rating_star = get_rating_star($id);

			if ( ! $old_rating_star ) return new SKD_Error( 'invalid_rating_star_id', __( 'ID đanh giá sao không chính xác.' ) );
		}
		else {

			$update = false;
        }
        
        if(!empty($rating_star['name'])) $name = removeHtmlTags($rating_star['name']);

        if(!empty($rating_star['email'])) $email = removeHtmlTags($rating_star['email']);

        if(!empty($rating_star['title'])) $title = removeHtmlTags($rating_star['title']);

        if(!empty($rating_star['message'])) $message = removeHtmlTags($rating_star['message']);

        if(!empty($rating_star['star'])) $star = (int)removeHtmlTags($rating_star['star']);

        if(!empty($rating_star['object_type'])) $object_type = removeHtmlTags($rating_star['object_type']);

        if(!empty($rating_star['object_id'])) $object_id = (int)removeHtmlTags($rating_star['object_id']);

		$status  = empty( $rating_star['status'] ) ? 'public' : removeHtmlTags($rating_star['status']);

		$data = compact('name', 'email', 'title', 'message', 'star', 'object_type', 'object_id', 'status');

	    if( $update ) {

	    	$model->settable('rating_star');

	    	$model->update_where( $data, compact( 'id' ) );

	    	$rating_star_id = $id;
	    }
	    else{

	    	$model->settable('rating_star');

	    	$rating_star_id = $model->add( $data );
	    }

		return $rating_star_id;
	}
}

if(! function_exists('delete_rating_star')) {

	function delete_rating_star($id = '') {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('rating_star');

		$rating_star = get_rating_star($id);

		if(have_posts($rating_star)) {

			$rating_star_product = get_metadata('product', $rating_star->object_id, 'rating_star', true );

			if(!have_posts($rating_star_product)) {
				$rating_star_product = array(
						'count' => 0,
						'star'  => 0
				);
			}
			else {

				$rating_star_product['count'] -= 1;

				$rating_star_product['star']  -= $rating_star->star;
			}

			update_metadata('product', $rating_star->object_id, 'rating_star', $rating_star_product);

			$model->settable('rating_star');

			return $model->delete_where(['id' => $id]);
		}

		return 0;
	}
}

if(!function_exists('get_rating_star_html')) {

	function get_rating_star_html($args = '') {

		$ci =& get_instance();

		$model = get_model('products');

		$model->settable('rating_star');

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        $where 	= $args['where'];

        $params = $args['params'];

        $rating_star = array();

        $rating_star =  $model->gets_where($where, $params );

        return $rating_star;
	}
}