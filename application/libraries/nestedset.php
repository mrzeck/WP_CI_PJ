<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class nestedset{

	private $CI;

	public $checked 	= NULL;

	public $params 		= NULL;

	public $where 		= NULL;

	public $data 		= NULL;

	public $count 		= 0;

	public $count_level = 0;

	public $lft 		= NULL;

	public $rgt 		= NULL;
	
	public $level 		= NULL;

	function __construct($params = NULL){

		$this->where       = (isset($params['where']))?$params['where']:array();

		$this->params      = $params;

		$this->checked     = NULL;

		$this->count       = 0;

		$this->count_level = 0;

		$this->lft         = NULL;

		$this->rgt         = NULL;

		$this->level       = NULL;

		$this->CI          =& get_instance();

		if( $this->params['model'] == 'post_categories_model' ) $this->CI->load->model(array( 'backend_post/post_categories_model' ));

		else $this->CI->load->model(array($this->params['model']));
	}

	//get dữ liệu
	public function get($param = NULL){

		$model = $this->params['model'];

		$this->data = $this->GetsCategory();

		return $this->data;
	}

	public function GetsCategory($parent_id = 0, $trees = NULL) {

		$model = $this->params['model'];

		if(!$trees) $trees = array();

		$this->where['parent_id'] = $parent_id;

		$root = $this->CI->$model->gets_where($this->where, array('type' => 'array', 'orderby' =>'order'));

		foreach ($root as $key => $val) {
			$trees[] = $val;
			$trees = $this->GetsCategory($val['id'] , $trees); 
		}

		return $trees;
	}

	//set dữ liệu
	public function set()
	{
		$arr = NULL;

		if(isset($this->data) && is_array($this->data)) {
			foreach ($this->data as $key => $val) {
				$arr[$val['id']][$val['parent_id']] = 1;
				$arr[$val['parent_id']][$val['id']] = 1;
			}
		}

		return $arr;
	}

	//hàm đệ quy
	public function recursive($start = 0, $arr = NULL){

		$this->lft[$start] = ++$this->count;

		$this->level[$start] = $this->count_level;

		if(isset($arr) && is_array($arr)){

			foreach($arr as $key => $val){

				if((isset($arr[$start][$key]) || isset($arr[$key][$start])) &&(!isset($this->checked[$key][$start]) && !isset($this->checked[$start][$key]))){
					
					$this->count_level++;

					$this->checked[$start][$key] = 1;

					$this->checked[$key][$start] = 1;

					$this->recursive($key, $arr);

					$this->count_level--;
				}
			}
		}

		$this->rgt[$start] = ++$this->count;

	}

	//hàm update level, lft, rgt dữ liệu
	public function action(){

		if(isset($this->level) && is_array($this->level) && isset($this->lft) && is_array($this->lft) && isset($this->rgt) && is_array($this->rgt)){

			$data = NULL;

			foreach($this->level as $key => $val){
				$data[] = array(
					'id'    => $key,
					'level' => $val,
					'lft'   => $this->lft[$key],
					'rgt'   => $this->rgt[$key],
				);
			}

			$model = $this->params['model'];

			$this->CI->$model->_savebatch(array(
				'table' => $this->params['table'],
				'data'  => $data,
				'field' => 'id'
			));
		}
    }

    //hàm lấy con
    public function children($param = NULL){

		$model = $this->params['model'];

		$catalogues = NULL;

		$param['andparent'] = (isset($param['andparent']) && ($param['andparent'] == TRUE))?'=':'';
		
		if(isset($param['lft']) && isset($param['rgt'])){
			$catalogues['lft'] = $param['lft'];
			$catalogues['rgt'] = $param['rgt'];
		}
		else if(isset($param['id'])){
			$catalogues = $this->CI->$model->_general(array(
				'select' => 'id, lft, rgt',
				'table' => $this->params['table'],
				'param_where' => array(
					'id' => $param['id'],
				)
			));
		}

		if($catalogues == NULL) return NULL;
		
		if(isset($param['count']) && $param['count'] == TRUE){
			$children = $this->CI->$model->_general(array(
				'select' => 'id',
				'table' => $this->params['table'],
				'count' => TRUE,
				'param_where' => array(
					'lft >'.$param['andparent'].'' => $catalogues['lft'],
					'rgt <'.$param['andparent'].'' => $catalogues['rgt'],
				)
			));
			return $children;
		}
		else{
			$temp = NULL;
			// show_r($catalogues);
			// die;
			$children = $this->CI->$model->_general(array(
				'select' => 'id',
				'table' => $this->params['table'],
				'list' => TRUE,
				'param_where' => array(
					'lft >'.$param['andparent'].'' => $catalogues['lft'],
					'rgt <'.$param['andparent'].'' => $catalogues['rgt'],
				)
			));
			if(isset($children) && is_array($children) && count($children)){
				foreach($children as $key => $val){
					$temp[] = $val['id'];
				}
				return $temp;
			}
			return NULL;
		}		
	}

    public function get_dropdown_backend($param = NULL, $col = 'id'){

		$this->get($param);

		if(isset($this->data) && is_array($this->data)){

			$temp = NULL;

			$temp[0] = 'Chọn danh mục';

			foreach($this->data as $key => $val){
				$temp[$val[$col]] = str_repeat('|-----', (($val['level'] > 0)?($val['level'] - 1):0)).$val['name'];
			}

			return $temp;
		}
	}

	public function get_data_backend($type = 'object'){

		$this->get();

		if(isset($this->data) && is_array($this->data)){

			$temp = array();

			$temp[0] = 'Chọn danh mục';

			foreach($this->data as $key => $val){
				$temp[$key+1]['id'] 	= $val['id'];
				$temp[$key+1]['name'] = str_repeat('|-----', (($val['level'] > 0)?($val['level'] - 1):0)).$val['name'];
				if($type == 'object')
					$temp[$key+1] = (object)$temp[$key+1];
			}
			return $temp;
		}
	}

}