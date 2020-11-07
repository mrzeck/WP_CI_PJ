<?php
if (!function_exists('wcmc_get_category')) {
	/**
	 * [wcmc_get_category lấy danh mục sản phẩm]
	 */
	function wcmc_get_category( $args = array() ) {
		$ci = &get_instance();
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
        if( !have_posts($args) ) $args = array('where' => array(), 'params' => array() );
        $args = array_merge( array('where' => array(), 'params' => array() ), $args );
		$cache_id = 'products_category_'.md5( serialize($args) );
		if( $ci->language['default'] != $ci->language['current'] ) {
			$cache_id = 'products_category_'.$ci->language['current'].md5( serialize($args) );
		}
		$category = get_cache($cache_id);
		if( $ci->cache->get($cache_id) === false ) {
			$model = get_model('products');
			$model->settable('products_categories');
			$model->settable_category('products_categories');
			$model->settable_metabox('metadata');
			$where  	= $args['where'];
        	$params 	= $args['params'];
        	$category 	= $model->get_data($args, 'products_categories');
			$model->settable('products');
			save_cache( $cache_id, $category );
		}
		return $category;
	}
}
if (!function_exists('wcmc_gets_category')) {
	/**
	 * [wcmc_gets_category lấy danh sách danh mục sản phẩm]
	 */
	function wcmc_gets_category( $args = array() ) {
		$ci = &get_instance();
		if( !have_posts($args) ) $args = array('where' => array(), 'params' => array() );
		$args = array_merge( array('where' => array(), 'params' => array() ), $args );
		$cache_id = 'products_categories_'.md5( serialize($args) );
		if( $ci->language['default'] != $ci->language['current'] ) {
			$cache_id = 'products_categories_'.$ci->language['current'].md5( serialize($args) );
		}
		$categories = get_cache($cache_id);
		if( $categories === false ) {
			$model = get_model('products_categories', 'backend_products');
			$model->settable('products_categories');
			$model->settable_category('products_categories');
	        $where  = $args['where'];
	        $params = $args['params'];
	        if( isset($args['where_in'])) {
	            $data = $args['where_in'];
	            $categories = $model->fgets_where_in( 'product_categories', $data, $where, $params );
	        }
	        elseif( isset($args['where_like'])) {
	            $data['like'] = $args['where_like'];
	            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];
	            $categories = $model->fgets_where_like('product', $data, $where, $params);
	        }
	        else if( isset($args['tree']) ) {
	        	if( !isset($args['tree']['data']) || !have_posts($args['tree']['data']) ) $args['tree']['data'] = array();
		        $args['tree'] = array_merge( array( 'parent_id' => 0 ), $args['tree'] );
		        $where['parent_id'] = $args['tree']['parent_id'];
				$categories =  wcmc_gets_category_recurs( $args['tree']['data'], $where );
	        }
	        else if( isset($args['mutilevel']) ){
                if( is_numeric($args['mutilevel']) ) {
					$model->settable('products_categories');
                    if( $args['mutilevel'] != 0)
                        $where_level = array_merge(array('id' => $args['mutilevel']), $where);
                    else
                        $where_level = array_merge(array('parent_id' => $args['mutilevel']), $where);
					$category = $model->fgets_categories_where('products_categories', $where_level, $params);
                    $model->settable('products_categories');
                    $categories = $ci->multilevel_categories($category, $where, $model, $params);
                }
                else {
                	if( $args['mutilevel'] == 'data') {
	                    if( !class_exists('nestedset')) $ci->load->library('nestedset');
	                    $nestedset = new nestedset(array('model' => 'products_categories_model', 'table' => 'products_categories', 'where' => $where));
	                    $categories    = $nestedset->get_data_backend();
                	}
                	if( $args['mutilevel'] == 'option') {
	                    if( !class_exists('nestedset')) $ci->load->library('nestedset');
	                    $nestedset = new nestedset(array('model' => 'products_categories_model', 'table' => 'products_categories', 'where' => $where));
	                    $categories    = $nestedset->get_dropdown_backend();
                	}
                }
            }
			else $categories = $model->fgets_categories_where('products_categories', $where, $params);
			$model->settable('products_categories');
			$model->settable_category('products_categories');
			save_cache( $cache_id, $categories );
		}
		return $categories;
	}
}
function wcmc_gets_category_recurs( $trees = NULL, $where = array()) {
	$model = get_model('products');
	$model->settable('products_categories');
	$root = $model->gets_where( $where , array( 'type' => 'object', 'orderby' =>'order' ) );
	if( isset( $root ) &&  have_posts($root) ) {
		foreach ($root as $val) {
			$trees[] = $val;
			$where['parent_id'] = $val->id;
			$trees   = wcmc_gets_category_recurs($trees, $where);
		}
	}
	return $trees;
}
if(!function_exists('wcmc_gets_category_mutilevel')) {
	/**
	 * [wcmc_gets_category description]
	 * @param  string $product_id   [description]
	 * @param  string $attribute_op [description]
	 * @param  string $model        [description]
	 * @return [type]               [description]
	 */
	function wcmc_gets_category_mutilevel( $id = 0, $where = array(), $param = array(), $model = '') {
		if($model == '') $model = get_model('products');
		$where_level = array_merge(array('parent_id' => $id), $where);
		$category = $model->fgets_categories_where('products_categories', $where_level, $param);
		$ci =& get_instance();
		$model->settable('products_categories');
		$categories = $ci->multilevel_categories($category, $where, $model, $param);
		$model->settable('products');
		return $categories;
	}
}
if(!function_exists('wcmc_gets_category_mutilevel_data')) {
	/**
	 * [wcmc_gets_category description]
	 * @param  string $product_id   [description]
	 * @param  string $attribute_op [description]
	 * @param  string $model        [description]
	 * @return [type]               [description]
	 */
	function wcmc_gets_category_mutilevel_data( $where = array( 'public' => 1 ), $params = array()) {
        $ci       = &get_instance();
        $args = array(
    		'mutilevel' => 'data',
    		'where' => $where,
    		'parms' => $params
    	);
	    return wcmc_gets_category( $args );
	}
}
if(!function_exists('wcmc_gets_category_mutilevel_option')) {
	/**
	 * [wcmc_gets_category description]
	 * @param  string $product_id   [description]
	 * @param  string $attribute_op [description]
	 * @param  string $model        [description]
	 * @return [type]               [description]
	 */
	function wcmc_gets_category_mutilevel_option( $where = array( 'public' => 1 ), $params = array()) {
        $ci       = &get_instance();
        $args = array(
    		'mutilevel' => 'option',
    		'where' => $where,
    		'parms' => $params
    	);
	    return wcmc_gets_category( $args );
	}
}
if(!function_exists('wcmc_gets_brand_mutilevel_option')) {
	/**
	 * [wcmc_gets_category description]
	 * @param  string $product_id   [description]
	 * @param  string $attribute_op [description]
	 * @param  string $model        [description]
	 * @return [type]               [description]
	 */
	function wcmc_gets_brand_mutilevel_option( $where = array( 'public' => 1 ), $params = array()) {
        $ci       = &get_instance();
        $args = array(
    		'mutilevel' => 'brand_categories',
    		'where' => $where,
    		'parms' => $params
    	);
	    return gets_post_category( $args );
	}
}
if(!function_exists('wcmc_delete_category')) {
    /**
     * @since  1.9.1
     */
    function wcmc_delete_category( $cate_ID = 0 ) {
        $ci =& get_instance();
        $cate_ID = (int)removeHtmlTags($cate_ID);
        if( $cate_ID == 0 ) return false;
        $model      = get_model('products_categories', 'backend_post');
        $model->settable('products_categories');
        $model->settable_category('products_categories');
        $category  = wcmc_get_category( $cate_ID );
        if( have_posts($category) ) {
            $ci->data['module']   = 'products_categories';
            $listID = $model->gets_category_sub($category);
            if(!have_posts($listID)) $listID = [$cate_ID];
            $model->settable('relationships');
            $model->delete_where( array( 'category_id' => (string)$cate_ID, 'value' => 'products_categories' ) );
            if(have_posts($listID)) {
                $where['object_type'] = 'products_categories';
                $data['field'] = 'object_id';
                $data['data']  = $listID;
                //xóa router
                $model->settable('routes');
                $model->delete_where_in( $data, $where );
                //xóa ngôn ngữ
                $model->settable('language');
                $model->delete_where_in( $data, $where );
                //xóa gallery
                foreach ($listID as $key => $id) {
                    delete_gallery_by_object($id, 'products_categories');
                    delete_metadata_by_mid('products_categories', $id);
                }
                $model->settable('products_categories');
                $data['field'] = 'id';
                $data['data']  = $listID;
                if($model->delete_where_in($data)) return $listID;
            }
        }
        return false;
    }
}
if(!function_exists('wcmc_delete_list_category')) {
    /**
     * @since  1.9.1
     */
    function wcmc_delete_list_category( $cate_ID = array() ) {
        $ci =& get_instance();
        $result = array();
        if(!have_posts($cate_ID)) return false;
        foreach ($cate_ID as $key => $id) {
            if( wcmc_delete_category($id) != false ) $result[] = $id;
        }
        if(have_posts($result)) return $result;
        return false;
    }
}
/** PRODUCTS */
if ( !function_exists( 'get_product' ) ) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function get_product( $args = '') {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('products');
		$model->settable_metabox('product_metadata');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) return array();
		$args = array_merge( array('where' => array(), 'params' => array() ), $args );
		if(is_array($args) && (!isset($args['where']['type']) && !isset($args['where']['type <>']))) {
			$args['where']['type'] = 'product';
		}
        if( !is_admin() && isset( $args['where'] ) ) {
            $args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
		}
        $where 	= $args['where'];
		$params = $args['params'];
        $product = $model->get_data($args, 'products');
        return apply_filters('get_product', $product, $args );
	}
}
if ( !function_exists( 'gets_product' ) ) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function gets_product( $args = '') {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('products');
		$model->settable_metabox('product_metadata');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) $args = array();
		$args = array_merge( array('where' => array(), 'params' => array(), 'join' => false ), $args );
		if(is_array($args['where'])) {
			if( !is_admin() && isset( $args['where'] ) ) {
				$args['where'] = array_merge( array('public' => 1, 'trash' => 0 ) , $args['where'] );
			}
			if(is_array($args) && (!isset($args['where']['type']) && !isset($args['where']['type <>']))) {
				$args['where']['type'] = 'product';
			}
		}
        $where 	= $args['where'];
        $params = $args['params'];
        $products = array();
        /**
		 * @since 1.7.2
		 * Thêm $args['join'] : kết hợp nhiều điều kiện lại với nhau
		 */
        if( $args['join'] == true ) {
        	$data = array();
        	$params_where  = $params;
        	if( isset($args['where_category']) ) $params_where['select'] = 'id';
			if(isset($args['where_in'])) 		$data['in']      = $args['where_in'];
			if(isset($args['where_not_in'])) 	$data['not_in']  = $args['where_not_in'];
			if(isset($args['where_like'])) 		$data['like']    = $args['where_like'];
			if(isset($args['where_or_like'])) 	$data['or_like'] = $args['where_or_like'];
			$products = $model->gets_where_more( $data, $where, $params_where  );
			if( isset($args['where_category']) && have_posts($products) ) {
				$data_where =  array();
				foreach ($products as $value) $data_where[] = $value->id;
				$products = array();
				$data_category = array();
				if( is_numeric($args['where_category']) ) {
	        		$args['where_category'] = wcmc_get_category( $args['where_category'] );
	        	}
				if( have_posts($args['where_category']) ) $data_category	= $model->gets_relationship_list($model->gets_category_sub($args['where_category']), 'object_id', 'products');
				if( have_posts($data_category) ) {
					$data['field'] = 'id';
					$data['data'] = array_intersect( $data_category, $data_where );
					$products =  $model->fgets_where_in( 'products' , $data, $where, $params);
				}
			}
        }
        else {
			/**
			 * Gets where taxonomy
			 * @since  3.0.5
			 */
			if(isset($args['tax_query'])) {
				$products = [];
				$where  = $args['where'];
				$params = $args['params'];
				$sql_table  = CLE_PREFIX.'products';
				if(isset($params['select'])) {
					$select = removeHtmlTags($params['select']);
				}
				else {
					$select = '`'.$sql_table.'`.*';
				}
				$sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.' FROM `'.$sql_table.'` ';
				if( $model->mutilang == true && $model->language != $model->language_default) {
					$sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.', lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
					$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
				}
				$tax_query  = $args['tax_query'];
				$taxonomyID = [];
				$relation 	= 'AND';
				if( !empty($tax_query['relation']) ) {
					$relation = $tax_query['relation']; 
					unset($tax_query['relation']);
				}
				if($relation != 'AND' || $relation != 'OR') $relation = 'AND';
				foreach ($tax_query as $key => $tax) {
					$dataID = [];
					if(isset($tax['field']) && isset($tax['terms'])) {
						$field = $tax['field'];
						if(is_array($tax['terms'])) {
							$dataID = $tax['terms'];
						}
						else {
							if($tax['taxonomy'] == 'products_categories') {
								$taxonomy = wcmc_get_category(['where' => array($field => removeHtmlTags($tax['terms']))]);
								$model->settable_category('products_categories');
							}
							else {
								$taxonomy = get_post_category(['where' => array(
									$field => removeHtmlTags($tax['terms']),
									'cate_type'   => removeHtmlTags($tax['taxonomy']),
								)]);
								$model->settable_category('categories');
							}
							if(have_posts($taxonomy)) {
								$taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
							}
						}
					}
					$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS txnm'.$key.' ON ( `'.$sql_table.'`.id = txnm'.$key.'.object_id ) ';
					if(isset($dataID) && have_posts($dataID)) {
						$taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
					}
				}
				if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
					foreach ($args['attr_query'] as $key => $attr) {
						$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS attr'.$key.' ON ( `'.$sql_table.'`.id = attr'.$key.'.object_id ) ';
					}
				}
				$sql .= 'WHERE 1=1 ';
				if( $model->mutilang == true && $model->language != $model->language_default) {
					$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'products\' AND ';
				}
				if(have_posts($taxonomyID)) {
					$sql .= ' AND (';
					foreach ($taxonomyID as $txnmkey => $taxonomy) {
						$sql    .= '(';
						$sql .= $txnmkey.'.`category_id` IN ('.implode(',',$taxonomy['data']).')';
						$sql .= ' AND '.$txnmkey.'.`value` = \''.$taxonomy['taxonomy'].'\'';
						$sql .= ') '.$relation.' ';
					}
					$sql = trim( $sql, ' '.$relation.' ' );
					$sql .= ')';
				}
				if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
					$sql .= ' AND (';
					foreach ($args['attr_query'] as $key => $attr) {
						$attrkey = 'attr'.$key;
						$sql .= '(';
						$sql .= $attrkey.'.`object_type` = \'attributes\' AND '.$attrkey.'.`category_id` = \'attribute_op_'.$attr['group'].'\' AND '.$attrkey.'.`value` IN ('.implode(',',$attr['attribute']).')';
						$sql .= ') AND ';
					}
					$sql = trim( $sql, ' '.$relation.' ' );
					$sql .= ')';
				}
				if(have_posts($where)) {
					$sql .= ' AND (';
					foreach ($where as $key => $value) {
						$key = trim($key);
						$compare = '=';
						if(substr($key,-1) == '>') $compare = '>';
						if(substr($key,-1) == '<') $compare = '<';
						if(substr($key,-2) == '>=') $compare = '>=';
						if(substr($key,-2) == '<=') $compare = '<=';
						if(substr($key,-2) == '<>') $compare = '<>';
						if(substr($key,-2) == '!=') $compare = '<>';
						$key = trim(removeHtmlTags($key));
						$sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
					}
					$sql = trim( $sql, 'AND ' );
					$sql .= ')';
				}
				else {
					$sql .= ' AND ('.$where.')';
				}
				if(have_posts($params)) {
					if(isset($params['groupby'])) {
						if(is_array($params['groupby'])) {
							$groupby = explode(',',$params['groupby']);
							if(have_posts($groupby)) {
								foreach ($groupby as &$oby) {
									$oby_arr = explode('.',$oby);
									if(count($oby_arr) == 2) {
										$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
									}
									else {
										$oby = '`'.removeHtmlTags($oby).'`';
									}
								}
								$params['groupby'] = implode(',',$groupby);
							}
							else {
								$params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
							}
						}
						$sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
					}
					else {
						$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
					}
					if(isset($params['orderby'])) {
						$orderby = explode(',',$params['orderby']);
						if(have_posts($orderby)) {
							foreach ($orderby as &$oby) {
								$oby_arr = explode('.',$oby);
								if(count($oby_arr) == 2) {
									$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
								}
								else {
									$oby = '`'.removeHtmlTags($oby).'`';
								}
							}
							$params['orderby'] = implode(',',$orderby);
						}
						else {
							$params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
						}
						$sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
					}
					if(isset($params['limit'])) {
						$sql .= ' LIMIT ';
						if(isset($params['start'])) {
							$sql .= (int)$params['start'].',';
						}
						$sql .= (int)$params['limit'];
					}
				}
				else {
					$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
				}
				if(isset($args['sql']) && $args['sql'] == true) return $sql;
				$query = $model->query($sql);
				foreach ($query->result() as $row ) {
					$products[] = $row;
				}
			}
			else if(isset($args['attr_query'])) {
				$products = [];
				$where  = $args['where'];
				$params = $args['params'];
				$sql_table  = CLE_PREFIX.'products';
				$sql        = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.* FROM `'.$sql_table.'` ';
				$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.* FROM `'.$sql_table.'` ';
				if( $model->mutilang == true && $model->language != $model->language_default) {
					$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.*, lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
					$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
				}
				if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
					foreach ($args['attr_query'] as $key => $attr) {
						$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS attr'.$key.' ON ( `'.$sql_table.'`.id = attr'.$key.'.object_id ) ';
					}
				}
				$sql .= 'WHERE 1=1 AND';
				if( $model->mutilang == true && $model->language != $model->language_default) {
					$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'products\' AND ';
				}
				if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
					$sql .= '(';
					foreach ($args['attr_query'] as $key => $attr) {
						$attrkey = 'attr'.$key;
						$sql .= '(';
						$sql .= $attrkey.'.`object_type` = \'attributes\' AND '.$attrkey.'.`category_id` = \'attribute_op_'.$attr['group'].'\' AND '.$attrkey.'.`value` IN ('.implode(',',$attr['attribute']).')';
						$sql .= ') AND ';
					}
					$sql = trim( $sql, ' AND ' );
					$sql .= ')';
				}
				if(have_posts($where)) {
					$sql .= ' AND (';
					foreach ($where as $key => $value) {
						$key = trim($key);
						$compare = '=';
						if(substr($key,-1) == '>') $compare = '>';
						if(substr($key,-1) == '<') $compare = '<';
						if(substr($key,-2) == '>=') $compare = '>=';
						if(substr($key,-2) == '<=') $compare = '<=';
						if(substr($key,-2) == '<>') $compare = '<>';
						if(substr($key,-2) == '!=') $compare = '<>';
						$key = trim(removeHtmlTags($key));
						$sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
					}
					$sql = trim( $sql, 'AND ' );
					$sql .= ')';
				}
				else {
					$sql .= ' AND ('.$where.')';
				}
				if(have_posts($params)) {
					if(isset($params['groupby'])) {
						if(is_array($params['groupby'])) {
							$groupby = explode(',',$params['groupby']);
							if(have_posts($groupby)) {
								foreach ($groupby as &$oby) {
									$oby_arr = explode('.',$oby);
									if(count($oby_arr) == 2) {
										$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
									}
									else {
										$oby = '`'.removeHtmlTags($oby).'`';
									}
								}
								$params['groupby'] = implode(',',$groupby);
							}
							else {
								$params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
							}
						}
						$sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
					}
					else {
						$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
					}
					if(isset($params['orderby'])) {
						$orderby = explode(',',$params['orderby']);
						if(have_posts($orderby)) {
							foreach ($orderby as &$oby) {
								$oby_arr = explode('.',$oby);
								if(count($oby_arr) == 2) {
									$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
								}
								else {
									$oby = '`'.removeHtmlTags($oby).'`';
								}
							}
							$params['orderby'] = implode(',',$orderby);
						}
						else {
							$params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
						}
						$sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
					}
					if(isset($params['limit'])) {
						$sql .= ' LIMIT ';
						if(isset($params['start'])) {
							$sql .= (int)$params['start'].',';
						}
						$sql .= (int)$params['limit'];
					}
				}
				else {
					$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
				}
				if(isset($args['sql']) && $args['sql'] == true) return $sql;
				$query = $model->query($sql);
				foreach ($query->result() as $row ) {
					$products[] = $row;
				}
			}
	        //Lấy sản phẩm theo danh mục taxonomy
	        else if( isset($args['taxonomy']) ) {
	        	if( is_string($args['taxonomy']) ) {
	        		$args['taxonomy'] = get_product_category( array('where' => array('slug' => removeHtmlTags($args['taxonomy'])) ) );
	        	}
	        	if( have_posts($args['taxonomy']) ) {
	        		$taxonomy = $args['taxonomy'];
	        		$model->settable_category('categories');
	        		//lấy danh sach ID danh mục cần lấy
	        		$listID = $model->gets_category_sub($taxonomy);
	        		$data['data'] 	=  $model->gets_relationship_list( $listID, 'object_id', 'products', $taxonomy->cate_type );
	        		$data['field'] 	=  'id';
	        		if( isset($args['where_category']) && have_posts($data['data']) ) {
	        			$model->settable_category('products_categories');
	        			if( is_numeric($args['where_category']) ) {
			        		$args['where_category'] = wcmc_get_category( $args['where_category'] );
			        	}
						if( have_posts($args['where_category']) ) $data_category	= $model->gets_relationship_list($model->gets_category_sub($args['where_category']), 'object_id', 'products');
						if( have_posts($data_category) ) {
							$data['data'] = array_intersect( $data_category, $data['data'] );
						}
						else {
							$data['data'] = array();
						}
	        		}
	        		$model->settable_category('products_categories');
	        		$products = $model->fgets_where_in( 'products' , $data, $where, $params);
	        	}
	        }
	        //Lấy sản phẩm theo danh mục
	        else if( isset($args['where_category']) ) {
				$model->settable_category('products_categories');
	        	/**
				 * @since 1.7.2
				 * Kiêm tra $args['where_category'] nếu là ID thì get category
				 */
	        	if( is_numeric($args['where_category']) ) {
	        		$args['where_category'] = wcmc_get_category( $args['where_category'] );
	        	}
	        	if( have_posts($args['where_category']) ) {
	        		$products = $model->gets_data( $args, 'products' );
	        	}
	        }
	        else $products = $model->gets_data( $args, 'products' );
        }
        return apply_filters('gets_product', $products, $args );
	}
}
if ( !function_exists( 'count_product' ) ) {
	/**
	 * [get_product lấy sản phẩm]
	 */
	function count_product( $args = '') {
		$ci =& get_instance();
		$model = get_model('products');
		$model->settable('products');
		if( is_numeric($args) ) $args = array( 'where' => array('id' => (int)$args ) );
		if( !have_posts($args) ) $args = array();
		$args = array_merge( array('where' => array(), 'params' => array() ), $args );
		if(is_array($args['where']) && (!isset($args['where']['type']) && !isset($args['where']['type <>']))) {
			$args['where']['type'] = 'product';
		}
        $where 	= $args['where'];
        $params = $args['params'];
		if(isset($args['tax_query'])) {
			$products = 0;
			$where  = $args['where'];
			$params = $args['params'];
			$sql_table  = CLE_PREFIX.'products';
			if(isset($params['select'])) {
				$select = removeHtmlTags($params['select']);
			}
			else {
				$select = '`'.$sql_table.'`.*';
			}
			$sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.' FROM `'.$sql_table.'` ';
			if( $model->mutilang == true && $model->language != $model->language_default) {
				$sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.', lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}
			$tax_query  = $args['tax_query'];
			$taxonomyID = [];
			$relation 	= 'AND';
			if( !empty($tax_query['relation']) ) {
				$relation = $tax_query['relation']; 
				unset($tax_query['relation']);
			}
			if($relation != 'AND' || $relation != 'OR') $relation = 'AND';
			foreach ($tax_query as $key => $tax) {
				$dataID = [];
				if(isset($tax['field']) && isset($tax['terms'])) {
					$field = $tax['field'];
					if(is_array($tax['terms'])) {
						$dataID = $tax['terms'];
					}
					else {
						if($tax['taxonomy'] == 'products_categories') {
							$taxonomy = wcmc_get_category(['where' => array($field => removeHtmlTags($tax['terms']))]);
							$model->settable_category('products_categories');
						}
						else {
							$taxonomy = get_post_category(['where' => array(
								$field => removeHtmlTags($tax['terms']),
								'cate_type'   => removeHtmlTags($tax['taxonomy']),
							)]);
							$model->settable_category('categories');
						}
						if(have_posts($taxonomy)) {
							$dataID  = $model->gets_category_sub($taxonomy);
						}
					}
				}
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS txnm'.$key.' ON ( `'.$sql_table.'`.id = txnm'.$key.'.object_id ) ';
				if(isset($dataID) && have_posts($dataID)) {
					$taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
				}
			}
			if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
				foreach ($args['attr_query'] as $key => $attr) {
					$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS attr'.$key.' ON ( `'.$sql_table.'`.id = attr'.$key.'.object_id ) ';
				}
			}
			$sql .= 'WHERE 1=1 ';
			if( $model->mutilang == true && $model->language != $model->language_default) {
				$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'products\' AND ';
			}
			if(have_posts($taxonomyID)) {
				$sql .= ' AND (';
				foreach ($taxonomyID as $txnmkey => $taxonomy) {
					$sql    .= '(';
					$sql .= $txnmkey.'.`category_id` IN ('.implode(',',$taxonomy['data']).')';
					$sql .= ' AND '.$txnmkey.'.`value` = \''.$taxonomy['taxonomy'].'\'';
					$sql .= ') '.$relation.' ';
				}
				$sql = trim( $sql, ' '.$relation.' ' );
				$sql .= ')';
			}
			if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
				$sql .= ' AND (';
				foreach ($args['attr_query'] as $key => $attr) {
					$attrkey = 'attr'.$key;
					$sql .= '(';
					$sql .= $attrkey.'.`object_type` = \'attributes\' AND '.$attrkey.'.`category_id` = \'attribute_op_'.$attr['group'].'\' AND '.$attrkey.'.`value` IN ('.implode(',',$attr['attribute']).')';
					$sql .= ') AND ';
				}
				$sql = trim( $sql, ' '.$relation.' ' );
				$sql .= ')';
			}
			if(have_posts($where)) {
				$sql .= ' AND (';
				foreach ($where as $key => $value) {
					$key = trim($key);
					$compare = '=';
					if(substr($key,-1) == '>') $compare = '>';
					if(substr($key,-1) == '<') $compare = '<';
					if(substr($key,-2) == '>=') $compare = '>=';
					if(substr($key,-2) == '<=') $compare = '<=';
					if(substr($key,-2) == '<>') $compare = '<>';
					if(substr($key,-2) == '!=') $compare = '<>';
					$key = trim(removeHtmlTags($key));
					$sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
				}
				$sql = trim( $sql, 'AND ' );
				$sql .= ')';
			}
			else {
				$sql .= ' AND ('.$where.')';
			}
			if(have_posts($params)) {
				if(isset($params['groupby'])) {
					if(is_array($params['groupby'])) {
						$groupby = explode(',',$params['groupby']);
						if(have_posts($groupby)) {
							foreach ($groupby as &$oby) {
								$oby_arr = explode('.',$oby);
								if(count($oby_arr) == 2) {
									$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
								}
								else {
									$oby = '`'.removeHtmlTags($oby).'`';
								}
							}
							$params['groupby'] = implode(',',$groupby);
						}
						else {
							$params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
						}
					}
					$sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
				}
				else {
					$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
				}
				if(isset($params['orderby'])) {
					$orderby = explode(',',$params['orderby']);
					if(have_posts($orderby)) {
						foreach ($orderby as &$oby) {
							$oby_arr = explode('.',$oby);
							if(count($oby_arr) == 2) {
								$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
							}
							else {
								$oby = '`'.removeHtmlTags($oby).'`';
							}
						}
						$params['orderby'] = implode(',',$orderby);
					}
					else {
						$params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
					}
					$sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
				}
				if(isset($params['limit'])) {
					$sql .= ' LIMIT ';
					if(isset($params['start'])) {
						$sql .= (int)$params['start'].',';
					}
					$sql .= (int)$params['limit'];
				}
			}
			else {
				$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
			}
			if(isset($args['sql']) && $args['sql'] == true) return $sql;
			$query = $model->query($sql);
			$query = $model->query('SELECT FOUND_ROWS() as count');
            foreach ($query->result() as $row ) {
                $products = $row;
            }
            $products = $products->count;
		}
		else if(isset($args['attr_query'])) {
			$products = [];
			$where  = $args['where'];
			$params = $args['params'];
			$sql_table  = CLE_PREFIX.'products';
			$sql        = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.* FROM `'.$sql_table.'` ';
			$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.* FROM `'.$sql_table.'` ';
			if( $model->mutilang == true && $model->language != $model->language_default) {
				$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.*, lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}
			if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
				foreach ($args['attr_query'] as $key => $attr) {
					$sql .= 'INNER JOIN `'.CLE_PREFIX.'relationships` AS attr'.$key.' ON ( `'.$sql_table.'`.id = attr'.$key.'.object_id ) ';
				}
			}
			$sql .= 'WHERE 1=1 AND';
			if( $model->mutilang == true && $model->language != $model->language_default) {
				$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$model->language.'\' AND `lg`.`object_type` = \'products\' AND ';
			}
			if(isset($args['attr_query']) && have_posts($args['attr_query'])) {
				$sql .= '(';
				foreach ($args['attr_query'] as $key => $attr) {
					$attrkey = 'attr'.$key;
					$sql .= '(';
					$sql .= $attrkey.'.`object_type` = \'attributes\' AND '.$attrkey.'.`category_id` = \'attribute_op_'.$attr['group'].'\' AND '.$attrkey.'.`value` IN ('.implode(',',$attr['attribute']).')';
					$sql .= ') AND ';
				}
				$sql = trim( $sql, ' AND ' );
				$sql .= ')';
			}
			if(have_posts($where)) {
				$sql .= ' AND (';
				foreach ($where as $key => $value) {
					$key = trim($key);
					$compare = '=';
					if(substr($key,-1) == '>') $compare = '>';
					if(substr($key,-1) == '<') $compare = '<';
					if(substr($key,-2) == '>=') $compare = '>=';
					if(substr($key,-2) == '<=') $compare = '<=';
					if(substr($key,-2) == '<>') $compare = '<>';
					if(substr($key,-2) == '!=') $compare = '<>';
					$key = trim(removeHtmlTags($key));
					$sql .= '`'.$sql_table.'`.`'.removeHtmlTags($key).'` '.$compare.' \''.removeHtmlTags($value).'\' AND ';
				}
				$sql = trim( $sql, 'AND ' );
				$sql .= ')';
			}
			else {
				$sql .= ' AND ('.$where.')';
			}
			if(have_posts($params)) {
				if(isset($params['groupby'])) {
					if(is_array($params['groupby'])) {
						$groupby = explode(',',$params['groupby']);
						if(have_posts($groupby)) {
							foreach ($groupby as &$oby) {
								$oby_arr = explode('.',$oby);
								if(count($oby_arr) == 2) {
									$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
								}
								else {
									$oby = '`'.removeHtmlTags($oby).'`';
								}
							}
							$params['groupby'] = implode(',',$groupby);
						}
						else {
							$params['groupby'] = '`'.removeHtmlTags($params['groupby']).'`';
						}
					}
					$sql .= ' GROUP BY '.removeHtmlTags($params['groupby']);
				}
				else {
					$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
				}
				if(isset($params['orderby'])) {
					$orderby = explode(',',$params['orderby']);
					if(have_posts($orderby)) {
						foreach ($orderby as &$oby) {
							$oby_arr = explode('.',$oby);
							if(count($oby_arr) == 2) {
								$oby = '`'.CLE_PREFIX.trim(removeHtmlTags($oby_arr[0])).'`.'.trim(removeHtmlTags($oby_arr[1]));
							}
							else {
								$oby = '`'.removeHtmlTags($oby).'`';
							}
						}
						$params['orderby'] = implode(',',$orderby);
					}
					else {
						$params['orderby'] = '`'.removeHtmlTags($params['orderby']).'`';
					}
					$sql .= ' ORDER BY '.removeHtmlTags($params['orderby']);
				}
				if(isset($params['limit'])) {
					$sql .= ' LIMIT ';
					if(isset($params['start'])) {
						$sql .= (int)$params['start'].',';
					}
					$sql .= (int)$params['limit'];
				}
			}
			else {
				$sql .= ' GROUP BY `'.$sql_table.'`.`id`';
			}
			if(isset($args['sql']) && $args['sql'] == true) return $sql;
			$query = $model->query($sql);
			$query = $model->query('SELECT FOUND_ROWS() as count');
            foreach ($query->result() as $row ) {
                $products = $row;
            }
            $products = $products->count;
		}
        else if( isset($args['where_category']) ) {
			$model->settable_category('products_categories');
			if( is_numeric($args['where_category']) ) {
				$args['where_category'] = wcmc_get_category( $args['where_category'] );
			}
        	if( have_posts($args['where_category']) ) {
            	return $model->count_data($args, 'products' );
        	}
        	return array();
		}
		else $products = $model->count_data($args, 'products' );
        return apply_filters('count_product', $products, $args );
	}
}
if ( !function_exists('insert_product') ) {
    function insert_product($product = array(), $outsite = array()) {
    	debug($product);
        $ci =& get_instance();
        $model = get_model('products');
        $model->settable('products');
		$user = get_user_current();
        if ( ! empty( $product['id'] ) ) {
			$id 			= (int) $product['id'];
			$update 	   = true;
			$old_product = get_product(['where' => array('id' => $id, 'type <>' => 'null')]);
			if ( ! $old_product ) return new SKD_Error( 'invalid_product_id', __( 'ID sản phẩm không chính xác.' ) );
			$user_updated = ( have_posts($user) ) ? $user->id : 0;
			$user_created = $old_product->user_created;
		}
		else {
			$update = false;
			$user_updated = 0;
			$user_created = ( have_posts($user) ) ? $user->id : 0;
		}
		if( ! $update ) {
			if(empty($product['type'])) return new SKD_Error('empty_product_type', __('Loại sản phẩm khống được để trống.') );
			$type    =  removeHtmlTags($product['type']);
			if($type == 'prdouct') {
				if(empty($product['title'])) return new SKD_Error('empty_product_title', __('Không thể cập nhật sản phẩm khi tên tên sản phẩm trống.') );
				$slug = $ci->create_slug( removeHtmlTags( $product['title'] ), $model);
			}
			else {
				if(empty($product['title'])) $product['title'] = '';
				$slug = '';
			}
			$supplier_id = (isset($product['supplier_id'])) ? (int)$product['supplier_id'] : 0;
			$code = (isset($product['code'])) ? removeHtmlTags($product['code']) : 0;
			$slug = empty( $product['slug'] ) ? $old_product->slug : slug($product['slug']);
		}
		else {
			$slug = empty( $product['slug'] ) ? $old_product->slug : slug($product['slug']);
			if( $slug != $old_product->slug ) $slug = $ci->edit_slug( $slug , $id, $model );
			if ( empty( $product['title'] ) ) $product['title'] = $old_product->title;
			$supplier_id 	= (isset($product['supplier_id'])) ? (int)$product['supplier_id'] : $old_product->supplier_id;
			$code 			= (isset($product['code'])) ? removeHtmlTags($product['code']) : $old_product->code;
			$type 			= empty($product['type']) ? $old_product->type : removeHtmlTags($product['type']);
		}
		$title 		= removeHtmlTags( $product['title'] );
		$pre_title 	= apply_filters( 'pre_title', $title );
		$title 		= trim( $pre_title );
		if( !empty( $product['content'] ) ) 	$content    	=  $product['content'];
		if( !empty( $product['excerpt'] ) ) 	$excerpt    	=  $product['excerpt'];
		if( !empty( $product['status'] ) ) 		$status    		=  removeHtmlTags($product['status']);
		if( !empty( $product['parent_id'] ) ) 	$parent_id    	=  (int)$product['parent_id'];
		if( isset( $product['price'] ) ) {
			$price    =  removeHtmlTags($product['price']);
			$price      = str_replace(',', '', trim($price));
			$price      = (int)str_replace('.', '', trim($price));
		}
		if( isset( $product['price_sale'] ) ) {
			$price_sale    =  removeHtmlTags($product['price_sale']);
			$price_sale      = str_replace(',', '', trim($price_sale));
			$price_sale      = (int)str_replace('.', '', trim($price_sale));
		}
		if( !empty( $product['image'] ) ) {
			$image    = removeHtmlTags($product['image']);
			$image    = process_file($image);
		}
		$data = compact('code', 'title', 'slug', 'content', 'excerpt', 'price', 'price_sale', 'status', 'image', 'user_created', 'user_updated', 'supplier_id', 'type', 'parent_id');
	    $data = apply_filters( 'pre_insert_product_data', $data, $update, $update ? (int) $id : null );
	    $relationships 	= !empty( $product['categories'] ) ? $product['categories'] : array();
	    $taxonomies 	= !empty( $product['taxonomies'] ) ? $product['taxonomies'] : array();
		$language 		= !empty( $product['language'] ) ? $product['language'] : array();
	    if ( $update ) {
	    	$model->update_where( $data, compact( 'id' ) );
	    	$product_id = (int) $id;
	    	/*=============================================================
	    	ROUTER
			=============================================================*/
			if(!empty($slug)) {
				$model->settable('routes');
				$router['object_type']	= 'products';
				$router['object_id']	= $product_id;
				if($model->update_where( array('slug' => $slug ), $router ) == 0) {
					$router['slug'] 		= $slug;
					$router['directional']	= 'products';
					$router['controller']	= 'frontend_products/products/detail/';
					$model->add($router);
				}
			}
			/*=============================================================
	    	CATEGORIES
	    	=============================================================*/
			if( have_posts($relationships) ) {
				$model->settable('relationships');
				$category['object_id']   = $product_id;
				$category['object_type'] = 'products';
				$category['value']       = 'products_categories';
				$temp = $model->gets_where_in( array('field' => 'category_id', 'data' => $relationships), $category );
				if( have_posts($temp) ) {
					$relationships_old       = array();
					foreach ($temp as $value) {
						$relationships_old[$value->category_id] = $value->category_id;
					}
					//Xóa relationships bị xóa
					$model->delete_where_notin( array('field' => 'category_id', 'data' => $relationships_old), $category );
					//Thêm relationships mới
					foreach ($relationships as $id_category) {
						if( in_array($id_category, $relationships_old ) === false ) {
							$category['category_id'] = $id_category;
							$model->add($category);
						}
					}
				}
				else {
					$model->delete_where( $category );
					foreach ($relationships as $key => $id_category) {
						$category['category_id'] = $id_category;
						$model->add($category);
					}
				}
			}
			/*=============================================================
	    	TAXONOMY
	    	=============================================================*/
	    	if( have_posts($taxonomies) ) {
				$model->settable('relationships');
				$taxonomy['object_id'] 		= $id;
				$taxonomy['object_type'] 	= 'products';
				foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
					$taxonomy['value'] 		= $taxonomy_key;
					foreach ($taxonomy_value as $taxonomy_id) {
						$taxonomy['category_id'] = $taxonomy_id;
						$model->add($taxonomy);
					}
				}
			}
	    }
	    else {
	    	$product_id = $model->add( $data );
	    	/*=============================================================
	    	ROUTER
			=============================================================*/
			if(!empty($slug)) {
				$model->settable('routes');
				$router['slug'] 		= $slug;
				$router['object_type']	= 'products';
				$router['directional']	= 'products';
				$router['controller']	= 'frontend_products/products/detail/';
				$router['object_id']	= $product_id;
				$model->add($router);
			}
			/*=============================================================
	    	CATEGORIES
	    	=============================================================*/
			if( have_posts($relationships) ) {
				$model->settable('relationships');
				$category['object_id']   = $product_id;
				$category['object_type'] = 'products';
				$category['value']       = 'products_categories';
				foreach ($relationships as $key => $value) {
					$category['category_id'] = $value;
					$model->add($category);
				}
			}
			/*=============================================================
	    	TAXONOMY
	    	=============================================================*/
	    	if( have_posts($taxonomies) ) {
				$model->settable('relationships');
				$taxonomy['object_id'] 		= $product_id;
				$taxonomy['object_type'] 	= 'products';
				foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
					$taxonomy['value'] 		= $taxonomy_key;
					foreach ($taxonomy_value as $taxonomy_id) {
						$taxonomy['category_id'] = $taxonomy_id;
						$model->add($taxonomy);
					}
				}
			}
			/*=============================================================
	    	LANGUAGE
	    	=============================================================*/
			if( have_posts($language) ) {
				$model->settable('language');
				foreach ($language as $key => $val) {
					$lang['title'] 			= removeHtmlTags($val['title']);
					$lang['excerpt'] 		= $val['excerpt'];
					$lang['content'] 		= $val['content'];
					$lang['language'] 		= $key;
					$lang['object_id']  	= $product_id;
					$lang['object_type']  	= 'products';
					$model->add($lang);
				}
			}
			$model->settable('products');
	    }
	    $model->settable('products');
	    // die;
	    return $product_id;	
    }
}
if ( !function_exists('insert_product2') ) {
    function insert_product2($product = array(), $outsite = array()) {
        $ci =& get_instance();
        $model = get_model('products');
        $model->settable('products');
		$user = get_user_current();
        if ( ! empty( $product['id'] ) ) {
			$id 			= (int) $product['id'];
			$update = false;
			$user_updated = 0;
		}
		if( ! $update ) {
			if(empty($product['type'])) return new SKD_Error('empty_product_type', __('Loại sản phẩm khống được để trống.') );
			$type    =  removeHtmlTags($product['type']);
			if($type == 'prdouct') {
				if(empty($product['title'])) return new SKD_Error('empty_product_title', __('Không thể cập nhật sản phẩm khi tên tên sản phẩm trống.') );
			}
			else {
				if(empty($product['title'])) $product['title'] = '';
			}
			$supplier_id = (isset($product['supplier_id'])) ? (int)$product['supplier_id'] : 0;
			$code = (isset($product['code'])) ? removeHtmlTags($product['code']) : 0;
		}
		else {
			if ( empty( $product['title'] ) ) $product['title'] = $old_product->title;
			$supplier_id 	= (isset($product['supplier_id'])) ? (int)$product['supplier_id'] : $old_product->supplier_id;
			$code 			= (isset($product['code'])) ? removeHtmlTags($product['code']) : $old_product->code;
			$type 			= empty($product['type']) ? $old_product->type : removeHtmlTags($product['type']);
		}
		$title 		= removeHtmlTags( $product['title'] );
		$pre_title 	= apply_filters( 'pre_title', $title );
		$slug = $product['slug'];
		$title 		= trim( $pre_title );
		if( !empty( $product['content'] ) ) 	$content    	=  $product['content'];
		if( !empty( $product['excerpt'] ) ) 	$excerpt    	=  $product['excerpt'];
		if( !empty( $product['status'] ) ) 		$status    		=  removeHtmlTags($product['status']);
		if( !empty( $product['parent_id'] ) ) 	$parent_id    	=  (int)$product['parent_id'];
		if( isset( $product['price'] ) ) {
			$price    =  removeHtmlTags($product['price']);
			$price      = str_replace(',', '', trim($price));
			$price      = (int)str_replace('.', '', trim($price));
		}
		if( isset( $product['price_sale'] ) ) {
			$price_sale    =  removeHtmlTags($product['price_sale']);
			$price_sale      = str_replace(',', '', trim($price_sale));
			$price_sale      = (int)str_replace('.', '', trim($price_sale));
		}
		if( !empty( $product['image'] ) ) {
			$image    = removeHtmlTags($product['image']);
			$image    = process_file($image);
		}
		$data = compact('code', 'title', 'slug', 'content', 'excerpt', 'price', 'price_sale', 'status', 'image', 'user_created', 'user_updated', 'supplier_id', 'type', 'parent_id');
	    $data = apply_filters( 'pre_insert_product_data', $data, $update, $update ? (int) $id : null );
	    $relationships 	= !empty( $product['categories'] ) ? $product['categories'] : array();
	    $taxonomies 	= !empty( $product['taxonomies'] ) ? $product['taxonomies'] : array();
		$language 		= !empty( $product['language'] ) ? $product['language'] : array();
	    if ( $update ) {
	    	$model->update_where( $data, compact( 'id' ) );
	    	$product_id = (int) $id;
	    	/*=============================================================
	    	ROUTER
			=============================================================*/
			if(!empty($slug)) {
				$model->settable('routes');
				$router['object_type']	= 'products';
				$router['object_id']	= $product_id;
				if($model->update_where( array('slug' => $slug ), $router ) == 0) {
					$router['slug'] 		= $slug;
					$router['directional']	= 'products';
					$router['controller']	= 'frontend_products/products/detail/';
					$model->add($router);
				}
			}
			/*=============================================================
	    	CATEGORIES
	    	=============================================================*/
			if( have_posts($relationships) ) {
				$model->settable('relationships');
				$category['object_id']   = $product_id;
				$category['object_type'] = 'products';
				$category['value']       = 'products_categories';
				$temp = $model->gets_where_in( array('field' => 'category_id', 'data' => $relationships), $category );
				if( have_posts($temp) ) {
					$relationships_old       = array();
					foreach ($temp as $value) {
						$relationships_old[$value->category_id] = $value->category_id;
					}
					//Xóa relationships bị xóa
					$model->delete_where_notin( array('field' => 'category_id', 'data' => $relationships_old), $category );
					//Thêm relationships mới
					foreach ($relationships as $id_category) {
						if( in_array($id_category, $relationships_old ) === false ) {
							$category['category_id'] = $id_category;
							$model->add($category);
						}
					}
				}
				else {
					$model->delete_where( $category );
					foreach ($relationships as $key => $id_category) {
						$category['category_id'] = $id_category;
						$model->add($category);
					}
				}
			}
			/*=============================================================
	    	TAXONOMY
	    	=============================================================*/
	    	if( have_posts($taxonomies) ) {
				$model->settable('relationships');
				$taxonomy['object_id'] 		= $id;
				$taxonomy['object_type'] 	= 'products';
				foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
					$taxonomy['value'] 		= $taxonomy_key;
					foreach ($taxonomy_value as $taxonomy_id) {
						$taxonomy['category_id'] = $taxonomy_id;
						$model->add($taxonomy);
					}
				}
			}
	    }
	    else {
	    	$product_id = $model->add( $data );
	    	/*=============================================================
	    	ROUTER
			=============================================================*/
			if(!empty($slug)) {
				$model->settable('routes');
				$router['slug'] 		= $slug;
				$router['object_type']	= 'products';
				$router['directional']	= 'products';
				$router['controller']	= 'frontend_products/products/detail/';
				$router['object_id']	= $product_id;
				$model->add($router);
			}
			/*=============================================================
	    	CATEGORIES
	    	=============================================================*/
			if( have_posts($relationships) ) {
				$model->settable('relationships');
				$category['object_id']   = $product_id;
				$category['object_type'] = 'products';
				$category['value']       = 'products_categories';
				foreach ($relationships as $key => $value) {
					$category['category_id'] = $value;
					$model->add($category);
				}
			}
			/*=============================================================
	    	TAXONOMY
	    	=============================================================*/
	    	if( have_posts($taxonomies) ) {
				$model->settable('relationships');
				$taxonomy['object_id'] 		= $product_id;
				$taxonomy['object_type'] 	= 'products';
				foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
					$taxonomy['value'] 		= $taxonomy_key;
					foreach ($taxonomy_value as $taxonomy_id) {
						$taxonomy['category_id'] = $taxonomy_id;
						$model->add($taxonomy);
					}
				}
			}
			/*=============================================================
	    	LANGUAGE
	    	=============================================================*/
			if( have_posts($language) ) {
				$model->settable('language');
				foreach ($language as $key => $val) {
					$lang['title'] 			= removeHtmlTags($val['title']);
					$lang['excerpt'] 		= $val['excerpt'];
					$lang['content'] 		= $val['content'];
					$lang['language'] 		= $key;
					$lang['object_id']  	= $product_id;
					$lang['object_type']  	= 'products';
					$model->add($lang);
				}
			}
			$model->settable('products');
	    }
	    $model->settable('products');
	    // die;
	    return $product_id;	
    }
}
if( !function_exists('delete_product') ) {
    /**
     * @since  1.9.1
     */
    function delete_product( $productID = 0, $trash = false ) {
        $ci =& get_instance();
        $productID = (int)removeHtmlTags($productID);
        if( $productID == 0 ) return false;
        $model      = get_model('products');
        $model->settable('products');
		$product  = get_product(['where' => array('id' => $productID, 'type <>' => 'null')]);
        if( have_posts($product) ) {
            $ci->data['module']   = 'products';
            //nếu bỏ vào thùng rác
            if( $trash == true ) {
                /**
                 * @since 2.5.0 add action delete_product_trash
                 */
                do_action('delete_product_trash', $productID );
                if($model->update_where(array('trash' => 1), array('id' => $productID))) {
                    return [$productID];
                }
                return false;
            }
            /**
             * @since 2.5.0 add action delete_product
             */
            do_action('delete_product', $productID );
            if($model->delete_where(['id'=> $productID])) {
                do_action('delete_product_success', $productID );
                //delete language
                $model->settable('language');
                $model->delete_where(['id'=> $productID, 'object_type' => 'products']);
                //delete router
                $model->settable('routes');
                $model->delete_where(['id'=> $productID, 'object_type' => 'products']);
                //delete gallerys
                delete_gallery_by_object($productID, 'products');
                delete_metadata_by_mid('product', $productID);
                //delete menu
                $model->settable('menu');
                $model->delete_where(['id'=> $productID, 'object_type' => 'product']);
                //xóa liên kết
                $model->settable('relationships');
				$model->delete_where(['object_id'=> $productID, 'object_type' => 'products']);
				if($product->type != 'variations') {
					//Xóa sản phẩm biến thể
					$variations = gets_product(['where' => ['parent_id' => $productID, 'type' => 'variations']]);
					foreach ($variations as $variation) {
						delete_metadata('wcmc_variations', $variation->id);
						delete_product($variation->id);
					}
				}
                return [$productID];
            }
        }
        return false;
    }
}
if( !function_exists('delete_list_product') ) {
    /**
     * @since  1.9.1
     */
    function delete_list_product( $productID = array(), $trash = false ) {
        $ci =& get_instance();
        if(have_posts($productID)) {
            $model      = get_model('products');
            $model->settable('products');
            if( $trash == true ) {
                do_action('delete_product_list_trash', $productID );
                if($model->update_where_in(['field' => 'id', 'data' => $productID], ['trash' => 1])) {
                    return $productID;
                }
                return false;
            }
            $products = gets_product(['where_in' => ['field' => 'id', 'data' => $productID]]);
            if($model->delete_where_in(['field' => 'id', 'data' => $productID])) {
                $where_in = ['field' => 'object_id', 'data' => $productID];
                do_action('delete_product_list_trash_success', $productID );
                //delete language
                $model->settable('language');
                $model->delete_where_in($where_in, ['object_type' => 'products']);
                //delete router
                $model->settable('routes');
                $model->delete_where_in($where_in, ['object_type' => 'products']);
                //delete router
                foreach ($products as $key => $product) {
                    delete_gallery_by_object($product->id, 'products');
                    delete_metadata_by_mid('product', $product->id);
                }
                //delete menu
                $model->settable('menu');
                $model->delete_where_in($where_in, ['object_type' => 'product']);
                //xóa liên kết
                $model->settable('relationships');
				$model->delete_where_in($where_in, ['object_type' => 'products']);
				//Xóa sản phẩm biến thể
				foreach ($products as $key => $product) {
					if($product->type == 'variations') continue;
					$variations = gets_product(['where' => ['parent_id' => $product->id, 'type' => 'variations']]);
					foreach ($variations as $variation) {
						delete_metadata('wcmc_variations', $variation->id);
						delete_product($variation->id);
					}
				}
                return $productID;
            }
        }
        return false;
    }
}
