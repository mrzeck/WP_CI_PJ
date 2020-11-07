<?php defined('BASEPATH') OR exit('No direct script access allowed');

class post_categories extends MY_Controller {

	public $category 		= null;

	public $url_type 	= null;

	function __construct() {

        parent::__construct();

		if(!isset_cate_type($this->cate_type)) {
            echo notice('error','category type don\'t exits!', true);
            die;
        }

        $this->data['group']    = $this->cate_type;

        $this->data['ajax']   	= 'post/post_categories';

		$this->load->model($this->data['module'].'_model');

		$this->category = get_cate_type($this->cate_type);

		$this->data['dropdown']['parent_id'] = gets_post_category( array('mutilevel' => $this->cate_type ) );

		$this->data['dropdown']['parent_id'][0] = 'Chọn danh mục cha';

		// ksort($this->data['dropdown']['parent_id']);
	}

	public function index() {

		$model = $this->data['module'].'_model';

		$this->form_action();

		$keyword    = $this->input->get('keyword');

        $category_id = ($this->input->get('category')!=null)?(int)$this->input->get('category'):0;

        $args['where'] = array( 'cate_type' => $this->cate_type );

        $args['params'] = array( 'orderby' => 'order, created desc');

        if(!empty($keyword)) {
            $args['where_like'] = array( 'name' => array($keyword));
        }
        else {
            $args['tree'] = array( 'parent_id' => $category_id );
        }

        $this->data['objects'] =  gets_post_category( $args );

        $this->data['total']   =  count_post_category( $args );

		/* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
        );

        if(class_exists('skd_cate_'.$this->cate_type.'_list_table')) {
        	$class_table = 'skd_cate_'.$this->cate_type.'_list_table';
        } else $class_table = 'skd_category_list_table';

        $this->data['table_list'] = new $class_table($args);

		$this->template->render();
	}

    public function add($slug = '') {

        $model = $this->data['module'].'_model';
        $this->form_action();
        $this->template->render('post_categories-save');
    }

	public function edit($slug = '') {

        $model = $this->data['module'].'_model';

		$this->data['object'] = get_post_category(array('where' => array('slug' => $slug))); // lấy dữ liệu page

        if(have_posts($this->data['object'])) {

            $this->form_sets_field($this->data['object']);

            $this->form_action($this->data['object']);

            $this->template->render('post_categories-save');

        }
        else {

            $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));
            
            $this->template->render('error-404');
        }
    }
}