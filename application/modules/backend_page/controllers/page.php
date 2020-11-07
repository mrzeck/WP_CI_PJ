<?php defined('BASEPATH') OR exit('No direct script access allowed');

class page extends MY_Controller {

	function __construct() {
        
		parent::__construct();

        $this->load->model($this->data['module'].'_model');
	}

    public function index() {

        $model      = $this->data['module'].'_model';

        $keyword    = $this->input->get('keyword');

        $trash      = ($this->input->get('status') == 'trash')?1:0;

        $where      = array('trash' => $trash);

        /* phân trang */
        if(!empty($keyword)) {
            
            $data['like'] = array( 'title' => array($keyword));

            $total_rows = $this->$model->count_where_more($data, $where);

        } else $total_rows = $this->$model->count_where($where);

        $url        = base_url().URL_ADMIN.'/'.$this->data['module'].'?page={page}';

        if(!empty($keyword)) $url .= '&keyword='.$keyword;

        if($trash == 1)      $url .= '&status=trash';

        $this->data['pagination'] = pagination($total_rows, $url, get_option('admin_pg_page'));

        /* lấy dữ liệu */
        $params = array(
			'limit'  => get_option('admin_pg_page'),
			'start'  => $this->data['pagination']->getoffset(),
			'orderby'=> 'order, created desc',
		);

        if(!empty($keyword)) {
            $this->data['objects'] =  $this->$model->gets_where_more($data, $where, $params);
        }
        else {
            $this->data['objects'] =  $this->$model->gets_where($where, $params);
        }

        $this->data['trash']    =  $this->$model->count_where(array('trash' => 1));
        $this->data['public']   =  $this->$model->count_where(array('public' => 1, 'trash' => 0));
        $this->data['total']    =  $this->$model->count_where($where);

        /* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
        );

        $this->data['table_list'] = new skd_page_list_table($args);
        /* hiển thị*/
        $this->template->render();
    }

    public function add() {
        $model     = $this->data['module'].'_model';
        $this->form_action();
        $this->template->render('page-save');
    }

    public function edit($slug = '') {

        $model = $this->data['module'].'_model';

		$this->data['object'] = get_page(['where' => ['slug' => $slug]]); // lấy dữ liệu page

        if(have_posts($this->data['object'])) {

            $this->form_sets_field($this->data['object']);

            $this->data['form']['param']['redirect'] = '';

            $this->form_action($this->data['object']);

            $this->template->render('page-save');
        }
        else {
            $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));

            $this->template->render('error-404');
        }
    }
}