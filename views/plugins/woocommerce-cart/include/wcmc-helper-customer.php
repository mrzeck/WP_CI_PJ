<?php

if( ! function_exists('insert_user_data_order_colum') ) {
    /**
     * Add colum order_total to function insert_user
     *  */
	function insert_user_data_order_colum( $data, $userdata, $id ) {

        $ci =& get_instance();
        
        if ( !$id ) {

            $order_total 	= empty( $userdata['order_total'] ) ? 0 : $userdata['order_total'];

            $order_count 	= empty( $userdata['order_count'] ) ? 0 : $userdata['order_count'];

            $customer 	    = empty( $userdata['customer'] ) ? 0 : $userdata['customer'];
	    }
	    else {

            $old_user_data = get_user_by( 'id', $id );

            if(!isset($userdata['order_total'])) {

                $order_total 	= $old_user_data->order_total;
            }
            else {

                $order_total 	= $userdata['order_total'];
            }

            if(!isset($userdata['order_count'])) {

                $order_count 	= $old_user_data->order_count;
            }
            else {

                $order_count 	= $userdata['order_count'];
            }

            if(!isset($userdata['customer'])) {

                $customer 	= $old_user_data->customer;
            }
            else {

                $customer 	= $userdata['customer'];
            }

        }
        
        $data['order_total'] = $order_total;

        $data['order_count'] = $order_count;

        $data['customer']    = $customer;

        return $data;
    }
    
    add_filter('pre_insert_user_data','insert_user_data_order_colum', 10,3);
}

if( ! function_exists('set_customer_order_total') ) {

	function set_customer_order_total() {

		$ci =& get_instance();

        return $order;
	}
}

if( ! function_exists('gets_customer') ) {

	function gets_customer($args = array()) {

        $ci =& get_instance();

        if(!is_array($args)) $args = array();

        if(isset($args['where'])) {

            $args['where'] = array_merge($args['where'], ['customer <>' => 0]);

        }
        else {

            $args = array_merge($args, ['where' => ['customer <>' => 0]]);

        }
        
        return gets_user($args);
	}
}

if( ! function_exists('count_customer') ) {
	/**
	 * [count_customer điếm sớ lượng khách hàng]
	 * singe 2.3.3
	 */
	function count_customer($args = '') {

		$ci =& get_instance();

		$model = get_model('products');

        $model->settable('users');

		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );

		if( !have_posts($args) ) $args = array();

        $args = array_merge( array('where' => array(), 'params' => array() ), $args );

        if(isset($args['where'])) {
            $args['where'] = array_merge($args['where'], ['customer <>' => 0]);
        }
        else {
            $args = array_merge($args, ['where' => ['customer <>' => 0]]);
        }

        $where 	= $args['where'];

        $params = $args['params'];

        $customer = array();

        $customer =  $model->count_where($where, $params );

        return $customer;
	}
}