<?php defined('BASEPATH') OR exit('No direct script access allowed');

class products_categories extends MY_Controller {

	function __construct() {

		parent::__construct();

		$this->skd_security->auth_backend();

        $this->data['group']    = 'products';

		$this->data['ajax']   	= 'products/products_categories';

		$this->load->model($this->data['module'].'_model');

        $this->data['dropdown']['parent_id'] = wcmc_gets_category( array('mutilevel' => 'option') );

        $this->data['dropdown']['parent_id'][0] = 'Chọn danh mục cha';

	}

	public function index()
	{
		$model = $this->data['module'].'_model';

		$this->form_action();

		$keyword    = $this->input->get('keyword');

        $category_id = ($this->input->get('category')!=null)?(int)$this->input->get('category'):0;

        $args['where'] = array();

        $args['params'] = array( 'orderby' => 'order, created desc');

        if(!empty($keyword)) {
            $args['where_like'] = array( 'name' => array($keyword));
        }
        else {
            $args['tree'] = array( 'parent_id' => $category_id );
        }

        $this->data['objects'] =  wcmc_gets_category( $args );

        $this->data['total']   =  $this->$model->count_where( $args['where'] );

		/* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
            '_column_headers' => $this->col['product_category'],
        );

        $class_table = 'skd_product_category_list_table';

        $this->data['table_list'] = new $class_table($args);

		$this->template->render();
	}

    public function add() {

        $model = $this->data['module'].'_model';

        if( current_user_can('wcmc_product_cate_edit') ) {

            $this->form_action();

            $this->template->render('products_categories-save');
        }
        else $this->template->error('404');
    }

	public function edit($slug = '') {

        $model = $this->data['module'].'_model';

        if( current_user_can('wcmc_product_cate_edit') ) {

    		$this->data['object'] = $this->$model->get_where(array('slug' => $slug)); // lấy dữ liệu page

            if(have_posts($this->data['object'])) {
                $this->form_sets_field($this->data['object']);
                $this->form_action($this->data['object']);
                $this->template->render('products_categories-save');
            }
            else {
                $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));
                $this->template->render('error-404');
            }
        }
        else $this->template->error('404');
    }
}