<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $table 			= null; //table đang xử lý

	public $table_metabox 	= null; //table đang xử lý

	public $table_category 	= null; //table category

	public $select 			= '*';

	public $language_select = '*';

	public $language 		= '';

	public $language_default= '';

	public $mutilang 		= false;

	public $current_page 	= 'backend';

	function __construct($table = null, $category = null) {

		$this->table = $table;

		$this->language_select = 'language.name, language.title, language.content, language.excerpt, language.language';

		if($category != null) $this->table_category = $category;

		$ci =& get_instance();

		$this->language 		= (isset($ci->language['current']))?$ci->language['current']:'vi';

		$this->language_default = (isset($ci->language['default']))?$ci->language['default']:'vi';

		if( isset($ci->language['language_list']) && have_posts($ci->language['language_list']) && count($ci->language['language_list']) > 1 ) $this->mutilang = true;

		if($ci->uri->segment(1) != URL_ADMIN) {
			$this->current_page = 'frondend';
		}

		parent::__construct();
	}

	public function settable($table = NULL)
	{
		$this->table = $table;
	}

	public function settable_category($table_category = NULL)
	{
		$this->table_category = $table_category;
	}

	public function settable_metabox($table_metabox = NULL)
	{
		$this->table_metabox = $table_metabox;
	}

	public function gettable()
	{
		return $this->table;
	}

	public function gettable_category()
	{
		return $this->table_category;
	}

	public function query($sql = '')
	{

		if(empty($sql)) return false;

		return $this->db->query($sql);
	}

	//Xử lý lấy dữ liệu
	public function _general($param = '')
	{
		//Select
		if(isset($param['select']) && !empty($param['select'])) {
			$this->db->select($param['select']);
		}

		//Table
		if(isset($param['table']) && !empty($param['table'])){
			$this->db->from($param['table']);
		}

		//Join table with table
		if(isset($param['join']) && !empty($param['join'])){
			$this->db->join($param['join']['table'], $param['join']['where']);
		}

		//Join table with more table
		if(isset($param['joins']) && !empty($param['joins'])){
			foreach ($param['joins'] as $key => $table) {
				$this->db->join($table['table'], $table['where']);
			}
		}

		//Where String
		if(isset($param['param']) && !empty($param['param'])){
			$this->db->where($param['param']);
		}

		//Where Array
		if(isset($param['param_where']) && is_array($param['param_where'])){
			$this->db->where($param['param_where']);
		}

		//Or Where Array
		if(isset($param['param_where_or']) && is_array($param['param_where_or'])){
			$this->db->or_where($param['param_where_or']);
		}

		//Where In
		if(isset($param['field_where_in']) && !empty($param['field_where_in']) && isset($param['param_where_in']) && is_array($param['param_where_in'])){
			$this->db->where_in($param['field_where_in'], $param['param_where_in']);
		}
		//Where Not In
		if(isset($param['field_where_not_in']) && !empty($param['field_where_not_in']) && isset($param['param_where_not_in']) && is_array($param['param_where_not_in'])){
			$this->db->where_not_in($param['field_where_not_in'], $param['param_where_not_in']);
		}

		//Like
		if(isset($param['like']) && is_array($param['like'])){
			foreach($param['like'] as $key => $val){
				//$val[0] : row
				//$val[1] : keyword
				//$val[2] : before, after, both, none
				$this->db->like($key, $val[0], isset($val[1])?$val[1]:'');
			}
		}

		//Or_like
		if(isset($param['or_like']) && is_array($param['or_like'])){
			foreach($param['or_like'] as $key => $val){
				//$val[0] : row
				//$val[1] : keyword
				//$val[2] : before, after, both, none
				$this->db->or_like($key, $val[0], isset($val[1])?$val[1]:'');
			}
		}

		//Order by
		if(isset($param['orderby']) && !empty($param['orderby'])){
			$this->db->order_by($param['orderby']);
		}

		//Limit
		if(isset($param['limit']) && (int)$param['limit'] > 0){
			$this->db->limit((int)$param['limit'], (int)$param['start']);
		}
		//Group by
		if(isset($param['groupby']) && !empty($param['groupby'])) {

			$this->db->group_by($param['groupby']);
		}

		//Count
		if(isset($param['sql']) && $param['sql'] == TRUE) {

			$data = $this->db->get_compiled_select();

			$this->db->flush_cache();

			return $data;
		}
		else if(isset($param['count']) && $param['count'] == TRUE) {

			$data = $this->db->count_all_results();

			$this->db->flush_cache();

			return $data;
		}
		//Return data
		else{
			//List
			if(isset($param['list']) && $param['list'] == TRUE){
				if(isset($param['type']) && $param['type'] == 'object'){
					$data = $this->db->get()->result_object();
					$this->db->flush_cache();
					return $data;
				}
				$data = $this->db->get()->result_array();
				$this->db->flush_cache();
				return $data;
			}
			//Row
			else{
				if(isset($param['type']) && $param['type'] == 'object'){
					$data = $this->db->get()->row_object();
					$this->db->flush_cache();
					return $data;
				}
				$data = $this->db->get()->row_array();
				$this->db->flush_cache();
				return $data;
			}
		}
	}

	//Kết xuất dữ liệu theo sum, avg..v...v..
	public function _operator($param = NULL, $operator = NULL) {
		if(isset($param['table']) && !empty($param['table'])){
			$this->db->from($param['table']);
		}

		if(isset($param['param']) && !empty($param['param'])){
			$this->db->where($param['param']);
		}

		if(isset($param['param_where']) && is_array($param['param_where'])){
			$this->db->where($param['param_where']);
		}

		if(isset($operator) && !empty($operator)){
			if($operator == 'max')
				$this->db->select_max($param['col']);

			if($operator == 'min')
				$this->db->select_min($param['col']);

			if($operator == 'avg')
				$this->db->select_avg($param['col']);

			if($operator == 'sum')
				$this->db->select_sum($param['col']);
		}

		$data = $this->db->get()->row_object();

		$this->db->flush_cache();

		return $data;
	}

	//Cập nhật dữ liệu
	public function _save($param = NULL){

		$flag = 0;

		$time = gmdate('Y-m-d H:i:s', time() + 7*3600);

		$data = $param['data'];

		if(isset($param['param']) && !empty($param['param'])){
			$this->db->where($param['param']);
			$flag = $flag = + 1;
		}

		if(isset($param['param_where']) && is_array($param['param_where'])){
			$this->db->where($param['param_where']);
			$flag = $flag = + 1;
		}

		if(isset($param['field_where_in']) && !empty($param['field_where_in']) && isset($param['param_where_in']) && is_array($param['param_where_in'])){
			$this->db->where_in($param['field_where_in'], $param['param_where_in']);
			$flag = $flag = + 1;
		}
		//Insert dữ liệu mới
		if($flag == 0){

			$data['created'] = $time;

			$this->db->insert($param['table'], $data);

			$insert_id = $this->db->insert_id();

			$this->db->flush_cache();

			return $insert_id;
		}
		//Update dữ liệu mới
		else{

			$data['updated'] = $time;

			$this->db->set($data);

			$this->db->update($param['table']);

			$affected_rows = $this->db->affected_rows();

			$this->db->flush_cache();

			return $affected_rows;
		}
	}

	//Cập nhật nhiều dữ liệu
	public function _saveBatch($param = NULL){

		$data = $param['data'];

		if(!isset($param['field']))
			$this->db->insert_batch($param['table'], $data);
		else
			$this->db->update_batch($param['table'], $data, $param['field']);

		$affected_rows = $this->db->affected_rows();

		$this->db->flush_cache();

		return $affected_rows;
	}

	//Xóa Dữ Liệu
	public function _Del($param = NULL){
		$flag = 0;
		if(isset($param['param']) && !empty($param['param'])){
			$this->db->where($param['param']);
			$flag = $flag = + 1;
		}
		if(isset($param['param_where']) && is_array($param['param_where'])){
			$this->db->where($param['param_where']);
			$flag = $flag = + 1;
		}
		if(isset($param['field_where_in']) && !empty($param['field_where_in']) && isset($param['param_where_in']) && is_array($param['param_where_in'])){
			$this->db->where_in($param['field_where_in'], $param['param_where_in']);
			$flag = $flag = + 1;
		}
		if(isset($param['field_where_not_in']) && !empty($param['field_where_not_in']) && isset($param['param_where_not_in']) && is_array($param['param_where_not_in'])){
			$this->db->where_not_in($param['field_where_not_in'], $param['param_where_not_in']);
		}
		//Like
		if(isset($param['like']) && is_array($param['like'])){
			foreach($param['like'] as $key => $val){
				//$val[0] : row
				//$val[1] : keyword
				//$val[2] : before, after, both, none
				$this->db->like($key, $val[0], isset($val[1])?$val[1]:'');
			}
		}
		if($flag > 0){
			$this->db->delete($param['table']);
			$affected_rows = $this->db->affected_rows();
			$this->db->flush_cache();
			return $affected_rows;
		}
		return 0;
	}

	//Get 1 trường Dữ Liệu dùng chung
	public function get($type = 'object')
	{
		return $this->_general(array(
			'select'	  => $this->select,
			'table'       => $this->table,
			'type'        => $type,
		));
	}

	public function get_where($where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
		);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		return $this->_general($params);
	}

	public function gets($params = array())
	{

		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		$params = array_merge($param, $params);

		return $this->_general($params);
	}

	public function gets_where($where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'order, created desc',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		if(isset($params['groupby'])) unset($param['orderby']);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		return $this->_general($params);
	}

	public function gets_where_in($data = array(), $where= array(), $params = array())
	{
		if( !have_posts($data['data']) ) return array();
		
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'limit' 	=> 0,
			'start' 	=> 0
		);

		if(isset($params['groupby'])) unset($param['orderby']);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		$params['field_where_in'] = $data['field'];
		$params['param_where_in'] = $data['data'];

		return $this->_general($params);
	}

	public function gets_where_notin($data = array(), $where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		if(isset($params['groupby'])) unset($param['orderby']);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		$params['field_where_not_in'] = $data['field'];
		$params['param_where_not_in'] = $data['data'];

		return $this->_general($params);
	}

	public function gets_where_like($data = array(), $where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		//Like
		$params['like']		= $data['like'];

		if(isset($data['or_like']))
			$params['or_like']	= $data['or_like'];

		return $this->_general($params);
	}

	public function gets_where_more($data = array(), $where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		//Like
		if(isset($data['like']))
			$params['like']			  = $data['like'];

		//or_like
		if(isset($data['or_like']))
			$params['or_like']	= $data['or_like'];

		//in
		if(isset($data['in'])) {
			$params['field_where_in'] = $data['in']['field'];
			$params['param_where_in'] = $data['in']['data'];
		}

		//not in
		if(isset($data['not_in'])) {
			$params['field_where_not_in'] = $data['not_in']['field'];
			$params['param_where_not_in'] = $data['not_in']['data'];
		}

		return $this->_general($params);
	}

	public function gets_join($where = array(), $params= array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> $this->table.'.order,'.$this->table.'.created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		$params = array_merge($param, $params);

		foreach ($params['table_join'] as $key => $value) {
			$params['joins'][$key]['table'] = $value['table'];
			$params['joins'][$key]['where'] = $value['where'];
		}

		unset($params['table_join']);

		foreach ($where as $key => $val) {
			if($key == 'where_table_join')
			{
				foreach ($val as $k => $wh) {
					$params['param_where'][$k] = $wh;
				}
			}
			else
				$params['param_where'][$params['table'].'.'.$key] = $val;
		}
		return $this->_general($params);
	}

	public function operatorby($where = array(), $col = NULL, $operator = 'sum')
	{
		return $this->_operator(array(
			'table'       => $this->table,
			'param_where' => $where,
			'col'		  => $col), $operator);
	}

	//Count dữ liệu
	public function count( $params = array() )
	{

		$param = array(
			'table'       => $this->table,
			'count'       => TRUE
		);

		$params = array_merge($param, $params);

		return $this->_general($params);
	}

	public function count_where($where = array())
	{
		$param = array(
			'table'   => $this->table,
			'count'       => TRUE
		);

		if(is_array($where)) $param['param_where'] = $where;
		else $param['param'] = $where;

		return $this->_general($param);
	}

	public function count_where_in( $data, $where = array())
	{

		if(!have_posts($data['data'])) return 0;

		$param = array(
			'table'   => $this->table,
			'count'       => TRUE
		);

		if(is_array($where)) $param['param_where'] = $where;
		else $param['param'] = $where;

		$param['field_where_in'] = $data['field'];
		$param['param_where_in'] = $data['data'];

		return $this->_general($param);
	}

	public function count_where_notin($data, $where)
	{
		$param = array(
			'table'   => $this->table,
			'count'       => TRUE
		);

		if(is_array($where)) $param['param_where'] = $where;
		else $param['param'] = $where;

		$param['field_where_not_in'] = $data['field'];
		$param['param_where_not_in'] = $data['data'];

		return $this->_general($param);
	}

	public function count_where_like($data = array(), $where= array(), $params = array())
	{
		$param = array(
			'table'   	=> $this->table,
			'count'    	=> true,
		);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		//Like
		$params['like']		= $data['like'];

		if(isset($data['or_like']))
			$params['or_like']	= $data['or_like'];

		return $this->_general($params);
	}

	public function count_where_more($data = array(), $where= array())
	{
		$params = array(
			'table'   	=> $this->table,
			'count'       => TRUE
		);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		//Like
		if(isset($data['like']))
			$params['like']			  = $data['like'];

		//or_like
		if(isset($data['or_like']))
			$params['or_like']	= $data['or_like'];

		//in
		if(isset($data['in'])) {
			$params['field_where_in'] = $data['in']['field'];
			$params['param_where_in'] = $data['in']['data'];
		}

		//not in
		if(isset($data['not_in'])) {
			$params['field_where_not_in'] = $data['not_in']['field'];
			$params['param_where_not_in'] = $data['not_in']['data'];
		}

		return $this->_general($params);
	}

	//Cập nhật dữ liệu
	public function add($data)
	{
		return $this->_save(array(
			'table' => $this->table,
			'data'  =>  $data));
	}

	public function adds($data)
	{
		return $this->_saveBatch(array(
			'table' => $this->table,
			'data'  =>  $data));
	}

	public function update_where($data = array(), $where = array())
	{
		return $this->_save(array(
			'table'       => $this->table,
			'param_where' => $where,
			'data'        => $data));
	}

	public function update_where_in($in = array(), $data = array() , $where = array())
	{
		return $this->_save(array(
			'table'       => $this->table,
			'param_where' => $where,
			'data'        => $data,
			'field_where_in' => $in['field'],
			'param_where_in' => $in['data'],
		));
	}

	public function updates_where($data = array())
	{
		$param = array(
				'table' => $this->table,
				'data'  => $data['value'],
				'field' => $data['field'],
		);
		return $this->_saveBatch($param);
	}

	//Xóa dữ liệu
	public function delete_where($where)
	{
		return $this->_Del(array(
			'table'       => $this->table,
			'param_where' => $where,
			));
	}

	public function delete_where_in($data, $where = array())
	{
		return $this->_Del(array(
			'table'       => $this->table,
			'param_where' => $where,
			'field_where_in' => $data['field'],
			'param_where_in' => $data['data'],
		));
	}

	public function delete_where_notin($data, $where = array())
	{
		return $this->_Del(array(
			'table'       => $this->table,
			'param_where' => $where,
			'field_where_not_in' => $data['field'],
			'param_where_not_in' => $data['data'],
		));
	}

	public function delete_where_like($data, $where = array())
	{
		return $this->_Del(array(
			'table'       => $this->table,
			'param_where' => $where,
			'like' => $data,
		));
	}

	public function empty_table($tables = '')
	{
		if(is_array($tables) && count($tables))
		{
			foreach ($tables as $key => $table) {
				$this->db->empty_table($table);
			}
		}
		else if(is_string($tables) && !empty($tables))
		{
			$this->db->empty_table($tables);
		}
		else
		{
			$this->db->empty_table($this->table);
		}
	}

	/**
	 * [get_categories_where Lấy category]
	 * @param  object  $category  [(object) category {name, left, rigt, levl...}]
	 * @param  boolean $andparent [true: lấy luôn id danh mục cha, false: không lấy danh mục cha]
	 * @param  string  $row       [trường dữ liệu cần lấy]
	 * @return array              [list id]
	 */
	public function get_categories_where($where= array(), $params = array())
	{
		$param = array(
			'select'  	=> '*',
			'table'   	=> $this->table_category,
			'type'    	=> 'object',
		);

		$params = array_merge($param, $params);

		if(is_array($where)) $params['param_where'] = $where;
		else $params['param'] = $where;

		return $this->_general($params);
	}

	/**
	 * [gets_category_sub Lấy danh sách $row là con của danh mục $category]
	 * @param  object  $category  [(object) category {name, left, rigt, levl...}]
	 * @param  boolean $andparent [true: lấy luôn id danh mục cha, false: không lấy danh mục cha]
	 * @param  string  $row       [trường dữ liệu cần lấy]
	 * @return array              [list id]
	 */
	public function gets_category_sub($category, $andparent = TRUE, $row = 'id')
	{
		$andparent = (isset($andparent) && ($andparent == TRUE))?'=':'';

		$param = array(
			'select'      => $row,
			'table'       => $this->table_category,
			'type'        => 'array',
			'orderby'     => 'order',
			'list'        => true,
			'param_where' => array(
				'lft >'.$andparent.'' => $category->lft,
				'rgt <'.$andparent.'' => $category->rgt,
			)
		);

		$children =  $this->_general($param);
		$temp = array();
		if(have_posts($children)) foreach($children as $key => $val) $temp[] = $val[$row];
		return $temp;
	}

	/*=============================== RELATIONSHIP ========================================*/
	/**
	 * lấy danh sách id sản phẩm thuộc danh sách id danh mục object ( ngược lại nếu select == 'category_id')
	 * @param array $list_id : danh sách id danh mục sản phẩm ( ngược lại nếu select == 'category_id')
	 * @return array : danh sách id sản phẩm thuộc các danh mục trên ( ngược lại nếu select == 'category_id')
	 */
	public function gets_relationship_list($list_id ='', $select = 'object_id', $type = 'post', $taxonomy = 'products_categories')
	{
		$params['table'] 	= 'relationships';

		$params['select'] 	= $select;

		$params['groupby'] 	= array($select);

		$data['field'] 	= ($select == 'object_id')?'category_id':'object_id';

		$data['data'] 	= $list_id;

		$where =  array('object_type' => $type );

		if( $type == 'products' ) $where['value'] = $taxonomy;

		if(isset($params['orerby'])) unset($params['orerby']);

		$object = $this->gets_where_in($data, $where, $params);

		$result = array();

		if(have_posts($object)) {

			foreach ($object as $key => $value) {
				$result[] = $value->{$select};
			}

		}
		
		return $result;
	}

	/**
	 * lấy danh sách id sản phẩm thuộc id danh mục sản phẩm ( ngược lại nếu select == 'category_id')
	 * @param array $id : id danh mục sản phẩm ( ngược lại nếu select == 'category_id')
	 * @return array : danh sách id sản phẩm thuộc các danh mục( ngược lại nếu select == 'category_id')
	 */
	public function gets_relationship_id($id ='', $select = 'object_id', $type = 'post')
	{
		$params['table'] 	= 'relationships';
		$params['select'] 	= $select;
		$params['groupby'] 	= array($select);

		$field 	= ($select == 'object_id')?'category_id':'object_id';

		$data['data'] 	= $id;

		$object = $this->gets_where(array($field => $id, 'object_type' => $type), $params);

		$result = array();
		if(have_posts($object)) {
			foreach ($object as $key => $value) {
				$result[] = $value->{$select};
			}
		}
		return $result;
	}

	/**
	 * lấy danh sách thong tin danh mục thuộc id sản phẩm
	 * @param array $id : id sản phẩm
	 * @return array : danh sách thông tin danh mục sản phẩm thuộc các id sản phẩm
	 */
	public function gets_relationship_join_categories($id, $object_type = null, $taxonomy = null)
	{
		$where  = array('object_id' => $id);

		if( $object_type != null ) $where['object_type'] = $object_type;

		if( $taxonomy != null ) $where['value'] = $taxonomy;

		$current_table = $this->gettable();

		$this->settable('relationships');

		$params['select'] = $this->table_category.'.*';

		$params['table_join'][] = array(
			'table' => $this->table_category,
			'where' => "{$this->table}.category_id = {$this->table_category}.id");

		$result = $this->gets_join($where, $params);

		$this->settable($current_table);

		return $result;
	}


	/*======================================================
	FRONT END
	========================================================*/
	public function fget_where($module = '', $where = array(), $param = array())
	{
		$params = array(
			'table'       => $this->table,
			'type'        => 'object',
		);

		$params = array_merge($param, $params);

		if(is_array($where)) {

			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table.'.id = language'.'.object_id ';

			foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language'] 		= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	public function fgets_where($module = '', $where = array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'order, created desc',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		if(isset($params['groupby'])) unset($param['orderby']);

		$params = array_merge($param, $params);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table.'.id = language'.'.object_id ';

			if(isset($params['param_where'])) {

				$param_where = [];

				foreach ($params['param_where'] as $key => $val) { 
					$param_where[$this->table.'.'.$key] = $val; 
				}

				$params['param_where'] = $param_where;
			}

			$params['param_where']['language.language'] 		= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	public function fgets_where_in($module = '', $data = array(), $where = array(), $params = array())
	{

		if($data['data'] == null || count($data['data']) == 0) return array();

		$param = array(
			'table' => $this->table,
			'list'	=> true,
			'type'  => 'object',
			'limit' => 0,
			'start' => 0,
		);

		if(isset($params['groupby'])) unset($param['orderby']);

		$params = array_merge($param, $params);

		$params['field_where_in'] = $this->table.'.'.$data['field'];

		$params['param_where_in'] = $data['data'];

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table.'.id = language'.'.object_id ';

			if(isset($params['param_where']))
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	public function fget_where_in($module = '', $data = array(), $where = array(), $params = array())
	{

		if($data['data'] == null || count($data['data']) == 0) return null;

		$param = array(
			'table' => $this->table,
			'type'  => 'object',
			'order' => 'order, created desc',
		);

		$params = array_merge($param, $params);

		$params['field_where_in'] = $this->table.'.'.$data['field'];

		$params['param_where_in'] = $data['data'];

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table.'.id = language'.'.object_id ';

			if(isset($params['param_where']))
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	public function fgets_where_like($module = '', $data = array(), $where= array(), $params = array())
	{
		$param = array(
			'select'  	=> $this->select,
			'table'   	=> $this->table,
			'type'    	=> 'object',
			'list'    	=> true,
			'orderby' 	=> 'created',
			'limit' 	=> 0,
			'start' 	=> 0
		);

		$params = array_merge($param, $params);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		//Like
		$params['like'] = $data['like'];

		if(isset($data['or_like'])) $params['or_like']	= $data['or_like'];

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table.'.id = language'.'.object_id ';

			if(isset($params['param_where'])) {
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table.'.'.$key] = $val; unset($params['param_where'][$key]); }
			}

			if(have_posts($params['like'])) {
				foreach ($params['like'] as $key => $val) { $params['like'][$this->table.'.'.$key] = $val; unset($params['like'][$key]); }
			}

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		return $this->_general($params);
	}
	//categories
	/**
	 * [fget_categories_where lấy category theo điều kiện]
	 */
	public function fget_categories_where($module = '', $where = array())
	{
		$params = array(
			'table'       => $this->table_category,
			'type'        => 'object',
		);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table_category.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table_category.'.id = language'.'.object_id ';

			if(isset($params['param_where']))
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table_category.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	/**
	 * [fgets_categories_where lấy danh sách category theo điều kiện]
	 */
	public function fgets_categories_where($module = '', $where = array(), $params = array())
	{
		$param = array(
			'table' => $this->table_category,
			'list'	=> true,
			'type'  => 'object',
			'limit' => 0,
			'start' => 0,
			'order' => 'order, created desc',
		);

		$params = array_merge($param, $params);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table_category.'.*,'.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table_category.'.id = language'.'.object_id ';

			if(isset($params['param_where']))
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table_category.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}

	//ket hop categories và object
	/**
	 * [lấy danh sách bài viết thuộc danh sách categoryes]
	 */
	public function fgets_object_category($module = '', $category, $where = array(), $params = array()) {

		$param = array(
			'table' => $this->table,
			'list'	=> true,
			'type'  => 'object',
			'limit' => 0,
			'start' => 0,
		);

		$params = array_merge($param, $params);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if(is_array($category)) {

			$data = $category;

		} else {
			
			$data = $this->gets_category_sub($category);
		}

		$params['select'] 			= $this->table.'.*,';

		$params['joins'][0]['table'] 	= 'relationships';

		$params['joins'][0]['where']  	= 'relationships.object_id = '.$this->table.'.id';

		$params['field_where_in'] = 'relationships.category_id';

		$params['param_where_in'] = $data;

		$params['param_where']['relationships.object_type'] = $module;

		$params['groupby'] = $this->table.'.id';

		if( $module == 'products' ) $params['param_where']['relationships.value'] = 'products_categories';

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $this->table.'.*,'.$this->language_select;

			$params['joins'][1]['table'] = 'language';

			$params['joins'][1]['where']  = $this->table.'.id = language'.'.object_id ';

			$params['param_where']['language.language'] 	= $this->language;

			$params['param_where']['language.object_type'] 	= $module;

			$params['groupby'] = $this->table.'.id, '.$this->language_select;
		}

		return $this->_general($params);
	}

	public function count_object_category($module = '', $category, $where = array(), $params = array())
	{
		$param = array(
			'table' => 'relationships',
			'list'	=> true, 
			'type' => 'object'
		);

		$params = array_merge($param, $params);

		if(is_array($where)) {
			$params['param_where'] = $where;
		}
		else {
			$params['param'] = $where;
		}

		if(is_array($category)) {

			$data = $category;

		} else {
			
			$data = $this->gets_category_sub($category);
		}


		$params['select'] 				= 'relationships.category_id, count( DISTINCT object_id ) as count';

		$params['joins'][0]['table'] 	= $this->table;

		$params['joins'][0]['where']  	= 'relationships.object_id = '.$this->table.'.id';

		$params['field_where_in'] 		= 'relationships.category_id';

		$params['param_where_in'] 		= $data;

		$params['param_where']['relationships.object_type'] = $module;

		if( $module == 'products' ) $params['param_where']['relationships.value'] = 'products_categories';

		$params['groupby'] = 'relationships.category_id';

		$result = $this->_general($params);

		if(have_posts($result)) {

			$total = 0;

			foreach ($result as $key => $val) {

				$total += $val->count;
			}

			return $total;
		}

		return 0;
	}

	//ket hop categories và object
	/**
	 * [lấy danh sách bài viết thuộc danh sách categoryes]
	 */
	public function fget_object_category($module = '', $category, $where = array(), $params = array())
	{
		$data['field'] 	= 'id';

		$data['data'] 	= $this->gets_relationship_list($this->gets_category_sub($category), 'object_id', $module);

		if(!have_posts($data['data'])) return array();

		return $this->fget_where_in($module, $data, $where, $params);
	}


	//lấy bài viết cùng danh mục
	public function fgets_related($module = '', $id = 0, $where = array('public'=>1), $params = array())
	{
		$sql = "SELECT `object`.* FROM `".CLE_PREFIX.$this->table."` as object ";

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$sql = "SELECT SQL_CALC_FOUND_ROWS `object`.*, lg.title, lg.content, lg.excerpt, lg.language FROM `".CLE_PREFIX.$this->table."` as object ";
			
			$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
		}

		$sql .= "INNER JOIN `".CLE_PREFIX."relationships` as relationship INNER JOIN `".CLE_PREFIX."relationships` as relationship2 WHERE object.id <> ".$id." AND public = 1 AND trash = 0";

		if( $this->mutilang == true && $this->language != $this->language_default) {
			$sql .= ' AND `lg`.`object_id` = object.`id` AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' ';
		}

		$sql .= " AND object.`id` = relationship.`object_id` AND relationship2.`category_id` = relationship.`category_id` AND relationship2.`object_id` = ".$id." AND relationship2.`object_type` = '".$module."' GROUP BY relationship2.`category_id`, relationship.object_id";

		if(have_posts($params)) {

			if(isset($params['orderby'])) {

				$orderby = explode(',',$params['orderby']);

				if(have_posts($orderby)) {

					foreach ($orderby as &$oby) {

						if( strpos($oby, 'desc') !== false ) {

							$oby = str_replace('desc', '', $oby);

							$oby = trim($oby);

							$oby = '`object`.`'.removeHtmlTags($oby).'` desc';
						}
						else if( strpos($oby, 'asc') !== false ) {

							$oby = str_replace('asc', '', $oby);

							$oby = trim($oby);

							$oby = '`object`.`'.removeHtmlTags($oby).'` desc';
						}
						else $oby = '`object`.`'.removeHtmlTags($oby).'`';
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
		// debug($sql);
		$query = $this->query($sql);

		$objects = [];

		foreach ($query->result() as $row ) {

			$objects[] = $row;
		}

		return $objects;
	}

	public function breadcrumb($category, $where = array(), $module = '') {

		$ci =& get_instance();

		if( $module == '') $module = get_instance()->data['module'];

		if( !have_posts($category) ) return array();
		
		$params = array(
			'select'	  => $this->table_category.'.id, '.$this->table_category.'.name, '.$this->table_category.'.slug',
			'table'       => $this->table_category,
			'list'		  => true,
			'type'        => 'object',
			'orderby'	  => 'level',
		);

		if(is_array($where)) {
			
			$params['param_where'] = $where;

			$params['param_where']['public'] = 1;
		}
		else {

			$params['param'] 	= $where;
		}

		$params['param_where']['lft <='] 				= $category->lft;

		$params['param_where']['rgt >='] 				= $category->rgt;

		if( $this->mutilang == true && $this->language != $this->language_default) {

			$params['select'] 		= $params['select'].','.$this->language_select;

			$params['join']['table'] = 'language';

			$params['join']['where']  = $this->table_category.'.id = language'.'.object_id ';

			if(isset($params['param_where']))
				foreach ($params['param_where'] as $key => $val) { $params['param_where'][$this->table_category.'.'.$key] = $val; unset($params['param_where'][$key]); }

			$params['param_where']['language.language']    	= $this->language;
			
			if(is_page('post_index')) 		$module = 'post_categories';

			if(is_page('products_index')) 	$module = 'products_categories';

			$module = apply_filters('my_model_breadcrumb', $module, $category, $where );

			$params['param_where']['language.object_type'] 	= $module;
		}

		$result = $this->_general($params);

		return $result;
	}
	/**
	 * Lấy danh sách tablse database
	 *
	 * @since 2.1.4
	 * @return array
	 */
	public function db_list_table() {
		
		return $this->db->list_tables();
	}

	/**
	 * Kiểm tra table có tồn tại không
	 *
	 * @since 2.1.4
	 * @param string
	 * @return true|false
	 */
	public function db_table_exists( $table_name = '' ) {

		$table_name = (!empty($table_name)) ? $table_name : $this->table;
		
		return $this->db->table_exists( $table_name );
	}

	/**
	 * Lấy danh sách field của table
	 *
	 * @since 2.1.4
	 * @param string
	 * @return array
	 */
	public function db_list_fields( $table_name = '' ) {

		$table_name = (!empty($table_name)) ? $table_name : $this->table;
		
		return $this->db->list_fields( $table_name );
	}

	/**
	 * Kiểm tra field trong table có tồn tại không
	 *
	 * @since 2.1.4
	 * @param string
	 * @return true|false
	 */
	public function db_field_exists( $field_name = '', $table_name = '' ) {

		$table_name = (!empty($table_name)) ? $table_name : $this->table;
		
		return $this->db->field_exists( $field_name, $table_name );
	}

	public function get_data( $args =  array(), $module = '' ) {

		$where  = $args['where'];

		$params = $args['params'];

		if(isset($args['sql'])) $params['sql'] = $args['sql'];

		$table 	 = $this->table;

		$metabox = CLE_PREFIX.$this->table_metabox;

		$objects = [];

		/**
		 * Gets where in
		 */
		if(isset($args['where_in'])) {

        	$data = $args['where_in'];

        	$objects = $this->fget_where_in( $module, $data, $where, $params );
		}
		/**
		 * Gets theo điều kiện like
		 */
		else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $objects = $this->fget_where_like($module, $data, $where, $params);
		}
		/**
		 * Gets theo danh mục
		 */
		else if( isset($args['where_category']) && have_posts($args['where_category']) ) {

        	$data = $args['where_category'];

        	$objects = $this->fget_object_category($module, $data, $where, $params);
		}
		else $objects = $this->fget_where($module, $where, $params );

		return apply_filters( 'get_data_'.$module, $objects, $args );
	}

	public function gets_data( $args =  array(), $module = '' ) {

		$where  = $args['where'];

		$params = $args['params'];

		if(isset($args['sql'])) $params['sql'] = $args['sql'];

		$table 	 = $this->table;

		$metabox = CLE_PREFIX.$this->table_metabox;

		$objects = [];
		/**
		 * Gets where in
		 */
		if(isset($args['where_in'])) {

        	$data = $args['where_in'];

        	$objects = $this->fgets_where_in( $module, $data, $where, $params );
		}
		/**
		 * Gets theo điều kiện like
		 */
		else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $objects = $this->fgets_where_like($module, $data, $where, $params);
		}
		/**
		 * Gets theo danh mục
		 */
		else if( isset($args['where_category']) && have_posts($args['where_category']) ) {

        	$data = $args['where_category'];

        	$objects = $this->fgets_object_category($module, $data, $where, $params);
		}
		/**
		 * Gets theo điều kiện đối tượng liên quan
		 */
		else if( isset($args['related']) ) {

            $data = $args['related'];

            $objects = $this->fgets_related($module, $data, $where, $params );
		}
		/**
		 * Gets dữ liệu theo metadata
		 */
		else if( !empty($this->table_metabox) && (!empty($args['meta_key']) || !empty($args['meta_value']) || !empty($args['meta_query']) )) {

			$sql_table = CLE_PREFIX.$table;

			$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.* FROM `'.$sql_table.'` ';

			if( $this->mutilang == true && $this->language != $this->language_default) {

				$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$sql_table.'`.*, lg.name, lg.title, lg.content, lg.excerpt, lg.language FROM `'.$sql_table.'` ';
				
				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}

			if( !empty($args['meta_key']) || !empty($args['meta_value']) ) {

				$sql .= 'INNER JOIN `'.$metabox.'` WHERE 1=1 AND ( `'.$sql_table.'`.id = `'.$metabox.'`.`object_id` ) AND ';

				if( $this->mutilang == true && $this->language != $this->language_default) {

					$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
				}

				$compare 	= (!empty($args['meta_compare'])) ? $args['meta_compare'] : '=';

				$meta_key 	= (!empty($args['meta_key'])) ? $args['meta_key'] : '';

				$meta_value = (!empty($args['meta_value'])) ? $args['meta_value'] : '';

				if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

					$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

					if(!empty($meta_value)) $sql .= '`meta_value`'.$compare.' \''.$meta_value.'\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_key))  $sql .= '`meta_key`'.$compare.' \''.$meta_key.'\'';
				}
				else if( $compare == 'LIKE' ) {

					if(!empty($meta_key))$sql .= '`meta_key` LIKE \'%'.$meta_key.'%\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_value))$sql .= '`meta_value` LIKE \'%'.$meta_value.'%\'';
				}
				else if( $compare == 'NOT LIKE' ) {

					if(!empty($meta_key)) $sql .= '`meta_key` NOT LIKE \'%'.$meta_key.'%\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_value)) $sql .= '`meta_value` NOT LIKE \'%'.$meta_value.'%\'';
				}
			}
			else if( !empty($args['meta_query']) ) {

				$meta_query = $args['meta_query'];

				$relation 	= 'AND';

				$meta 		= array();

				if( !empty($meta_query['relation']) ) {
					$relation = $meta_query['relation']; unset($meta_query['relation']);
				}

				if($relation != 'AND' || $relation != 'OR') $relation = 'AND';

				if( count($meta_query) == 1 ) {

					$sql .= 'INNER JOIN `'.$metabox.'` WHERE 1=1 AND ( `'.$sql_table.'`.id = `'.$metabox.'`.`object_id` ) AND ';

					if( $this->mutilang == true && $this->language != $this->language_default) {

						$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
					}

					foreach ($meta_query as $data) {

						$sql .= ( !empty($data['key']) ) ? '`meta_key` = \''.$data['key'].'\'' : '';

						$compare = (!empty($data['compare'])) ? $data['compare'] : '=';

						if( !empty($data['value']) ) {

							if( !empty($data['key']) ) $sql .= ' AND ';

							if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

								$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

								$sql .= '`meta_value`'.$compare.' \''.$data['value'].'\'';
							}
							else if( $compare == 'LIKE' ) {

								$sql .= '`meta_value` LIKE \'%'.$data['value'].'%\'';
							}
							else if( $compare == 'NOT LIKE' ) {

								$sql .= '`meta_value` NOT LIKE \'%'.$data['value'].'%\'';
							}
						}
					}

				}
				else {

					foreach ($meta_query as $key => $data) {
						$sql .= 'INNER JOIN `'.$metabox.'` AS mt'.$key.' ON ( `'.$sql_table.'`.id = mt'.$key.'.object_id ) ';
					}

					$sql .= 'WHERE 1=1 AND';

					if( $this->mutilang == true && $this->language != $this->language_default) {

						$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
					}

					$sql .= ' (( ';

					foreach ($meta_query as $key => $data) {

						$mt = 'mt'.$key.'.';

						$sql .= '(';

						$sql .= ( !empty($data['key']) ) ? $mt.'`meta_key` = \''.$data['key'].'\'' : '';

						$compare = (!empty($data['compare'])) ? $data['compare'] : '=';

						if( !empty($data['value']) ) {

							if( !empty($data['key']) ) $sql .= ' AND ';

							if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

								$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

								$sql .= $mt.'`meta_value`'.$compare.' \''.$data['value'].'\'';
							}
							else if( $compare == 'LIKE' ) {

								$sql .= $mt.'`meta_value` LIKE \'%'.$data['value'].'%\'';
							}
							else if( $compare == 'NOT LIKE' ) {

								$sql .= $mt.'`meta_value` NOT LIKE \'%'.$data['value'].'%\'';
							}
						}

						$sql .= ') '.$relation.' ';
					}

					$sql = trim( $sql, ' '.$relation.' ' );

					$sql .= '))';
				}
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

			$sql .= ' GROUP BY `'.$sql_table.'`.`id`';

			if(have_posts($params)) {

				if(isset($params['orderby'])) {

					$orderby = explode(',',$params['orderby']);

					if(have_posts($orderby)) {

						foreach ($orderby as &$oby) {
							$oby = '`'.removeHtmlTags($oby).'`';
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

			$query = $this->query($sql);

			foreach ($query->result() as $row ) {

				$objects[] = $row;
			}
		}
		else {
			$objects = $this->fgets_where($module, $where, $params );
		}

		return apply_filters( 'gets_data_'.$module, $objects, $args );
	}

	public function count_data( $args =  array(), $module = '' ) {

		$where  = $args['where'];

		$params = $args['params'];

		if(isset($args['sql'])) $params['sql'] = $args['sql'];

		$table 	 = $this->table;

		$metabox = CLE_PREFIX.$this->table_metabox;

		$objects = 0;
		/**
		 * Gets where in
		 */
		if(isset($args['where_in'])) {

        	$data = $args['where_in'];

        	$objects = $this->count_where_in( $data, $where );
		}
		/**
		 * Gets theo điều kiện like
		 */
		else if( isset($args['where_like'])) {

            $data['like'] = $args['where_like'];

            if( isset($args['where_or_like']) ) $data['or_like'] = $args['where_or_like'];

            $objects = $this->count_where_like($data, $where);
		}
		/**
		 * Gets theo danh mục
		 */
		else if( isset($args['where_category']) && have_posts($args['where_category']) ) {

        	$data = $args['where_category'];

			$objects = $this->count_object_category($module, $data, $where, $params );
		}
		/**
		 * Gets dữ liệu theo metadata
		 */
		else if( !empty($this->table_metabox) && (!empty($args['meta_key']) || !empty($args['meta_value']) || !empty($args['meta_query']) )) {

			$sql_table = CLE_PREFIX.$table;

			$sql = 'SELECT COUNT(`'.$sql_table.'`.`id`) FROM `'.$sql_table.'` ';

			if( $this->mutilang == true && $this->language != $this->language_default) {

				$sql .= 'INNER JOIN `'.CLE_PREFIX.'language` AS lg ';
			}

			if( !empty($args['meta_key']) || !empty($args['meta_value']) ) {

				$sql .= 'INNER JOIN `'.$metabox.'` WHERE 1=1 AND ( `'.$sql_table.'`.id = `object_id` ) AND ';

				if( $this->mutilang == true && $this->language != $this->language_default) {

					$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
				}

				$compare 	= (!empty($args['meta_compare'])) ? $args['meta_compare'] : '=';

				$meta_key 	= (!empty($args['meta_key'])) ? $args['meta_key'] : '';

				$meta_value = (!empty($args['meta_value'])) ? $args['meta_value'] : '';

				if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

					$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

					if(!empty($meta_value)) $sql .= '`meta_value`'.$compare.' \''.$meta_value.'\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_key))  $sql .= '`meta_key`'.$compare.' \''.$meta_key.'\'';
				}
				else if( $compare == 'LIKE' ) {

					if(!empty($meta_key))$sql .= '`meta_key` LIKE \'%'.$meta_key.'%\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_value))$sql .= '`meta_value` LIKE \'%'.$meta_value.'%\'';
				}
				else if( $compare == 'NOT LIKE' ) {

					if(!empty($meta_key)) $sql .= '`meta_key` NOT LIKE \'%'.$meta_key.'%\'';

					if(!empty($meta_key) && !empty($meta_value)) $sql .= ' AND ';

					if(!empty($meta_value)) $sql .= '`meta_value` NOT LIKE \'%'.$meta_value.'%\'';
				}
			}
			else if( !empty($args['meta_query']) ) {

				$meta_query = $args['meta_query'];

				$relation 	= 'AND';

				$meta 		= array();

				if( !empty($meta_query['relation']) ) {
					$relation = $meta_query['relation']; unset($meta_query['relation']);
				}

				if($relation != 'AND' || $relation != 'OR') $relation = 'AND';

				if( count($meta_query) == 1 ) {

					$sql .= 'INNER JOIN `'.$metabox.'` WHERE 1=1 AND ( `'.$sql_table.'`.id = `'.$metabox.'`.`object_id` ) AND ';

					if( $this->mutilang == true && $this->language != $this->language_default) {

						$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
					}

					foreach ($meta_query as $data) {

						$sql .= ( !empty($data['key']) ) ? '`meta_key` = \''.$data['key'].'\'' : '';

						$compare = (!empty($data['compare'])) ? $data['compare'] : '=';

						if( !empty($data['value']) ) {

							if( !empty($data['key']) ) $sql .= ' AND ';

							if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

								$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

								$sql .= '`meta_value`'.$compare.' \''.$data['value'].'\'';
							}
							else if( $compare == 'LIKE' ) {

								$sql .= '`meta_value` LIKE \'%'.$data['value'].'%\'';
							}
							else if( $compare == 'NOT LIKE' ) {

								$sql .= '`meta_value` NOT LIKE \'%'.$data['value'].'%\'';
							}
						}
					}

				}
				else {

					foreach ($meta_query as $key => $data) {
						$sql .= 'INNER JOIN `'.$metabox.'` AS mt'.$key.' ON ( `'.$sql_table.'`.id = mt'.$key.'.object_id ) ';
					}

					$sql .= 'WHERE 1=1 AND';

					if( $this->mutilang == true && $this->language != $this->language_default) {

						$sql .= ' `lg`.`object_id` = '.$sql_table.'.id AND `lg`.`language` = \''.$this->language.'\' AND `lg`.`object_type` = \''.$module.'\' AND ';
					}

					$sql .= ' (( ';

					foreach ($meta_query as $key => $data) {

						$mt = 'mt'.$key.'.';

						$sql .= '(';

						$sql .= ( !empty($data['key']) ) ? $mt.'`meta_key` = \''.$data['key'].'\'' : '';

						$compare = (!empty($data['compare'])) ? $data['compare'] : '=';

						if( !empty($data['value']) ) {

							if( !empty($data['key']) ) $sql .= ' AND ';

							if( $compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=' ) {

								$compare = ( $compare == '==' ) ? '' : ' '.$compare ;

								$sql .= $mt.'`meta_value`'.$compare.' \''.$data['value'].'\'';
							}
							else if( $compare == 'LIKE' ) {

								$sql .= $mt.'`meta_value` LIKE \'%'.$data['value'].'%\'';
							}
							else if( $compare == 'NOT LIKE' ) {

								$sql .= $mt.'`meta_value` NOT LIKE \'%'.$data['value'].'%\'';
							}
						}

						$sql .= ') '.$relation.' ';
					}

					$sql = trim( $sql, ' '.$relation.' ' );

					$sql .= '))';
				}
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

			$sql .= ' GROUP BY `'.$sql_table.'`.`id`';

			if(have_posts($params)) {

				if(isset($params['limit'])) {

					$sql .= ' LIMIT ';

					if(isset($params['start'])) {

						$sql .= (int)$params['start'].',';
					}

					$sql .= (int)$params['limit'];
				}
			}

			if(isset($args['sql']) && $args['sql'] == true) return $sql;

			$query = $this->query($sql);

			$objects = $query->num_rows();
		}
		else {

			$objects = $this->count_where($where);
		}

		return apply_filters( 'count_data_'.$module, $objects, $args, $module );
		
	}
}