<?php defined('BASEPATH') OR exit('No direct script access allowed');

class post extends MY_Controller {

    public $post = null;

	function __construct() {

		parent::__construct();

        if($this->cate_type != null && !isset_cate_type($this->cate_type)) {
            echo notice('error','category type don\'t exits!', true);
            die;
        }

        if($this->post_type == null || !isset_post_type($this->post_type)) {
            echo notice('error','post type don\'t exits!', true);
            die;
        }


        if($this->cate_type != null && isset_cate_type($this->cate_type)) {
            $this->data['group'] = $this->cate_type;
        }
        else if($this->post_type == null || isset_post_type($this->post_type)) {
             $this->data['group'] = $this->post_type;
        }

        $this->post = get_post_type($this->post_type);

        $this->load->model($this->data['module'].'_model');
	}

    public function index() {

        $model      = $this->data['module'].'_model';

        $keyword    = $this->input->get('keyword');

        $category_id= ($this->input->get('category')!=null)?(int)$this->input->get('category'):0;

        $trash      = ($this->input->get('status') == 'trash')?1:0;

        $where      = array('trash' => $trash, 'post_type' => $this->post_type);

        $category   = array();

        $total_rows = 0;

        if( $category_id != 0 ) $category   = get_post_category($category_id);

        if(have_posts($category)) {

            $listID         = $this->$model->gets_category_sub($category);

            $list_post      = $this->$model->gets_relationship_list($listID, 'object_id', 'post');
        }

        /*===================================================
        TÍNH TỔNG SỐ DỮ LIỆU SẼ CÓ
        ====================================================*/

        /* search keyword */
        if(!empty($keyword)) {

            $data['like'] = array( 'title' => array($keyword));

            if( have_posts($category) && isset($list_post) ) {
                $data['in']['field']    = 'id';
                $data['in']['data']     = $list_post;
            }

            $total_rows = $this->$model->count_where_more($data, $where);
        }
        /* search categories */
        else if( $category_id != 0 ) {

            if( have_posts($category) && isset($list_post) && count( $list_post ) ) {

                $data['field']    = 'id';

                $data['data']     = $list_post;

                $total_rows =  $this->$model->count_where_in( $data,  $where);
            }
        }
        /* get all products */
        else $total_rows = $this->$model->count_where($where);


        /*===================================================
        PHÂN TRANG
        ====================================================*/
        $url        = base_url().URL_ADMIN.'/'.$this->data['module'].$this->url_type.'&page={page}';

        if(!empty($keyword))    $url .= '&keyword='.$keyword;

        if($category_id != 0)   $url .= '&category='.$category_id;

        if($trash == 1)         $url .= '&status=trash';

        $this->data['pagination'] = pagination($total_rows, $url, get_option('admin_pg_page'));

        /*===================================================
        LẤY DỮ LIỆU
        ====================================================*/
        $params = array(
			'limit'  => get_option('admin_pg_page'),
			'start'  => $this->data['pagination']->getoffset(),
			'orderby'=> 'order, created desc',
		);

        $this->data['objects'] = array();

        if(!empty($keyword)) {
            $this->data['objects'] =  $this->$model->gets_where_more($data, $where, $params);
        }
        else if($category_id != 0) {
            if( have_posts($category) && isset($list_post) && count( $list_post ) ) {
                $this->data['objects'] =  $this->$model->gets_where_in($data, $where, $params);
            }
        }
        else $this->data['objects'] =  $this->$model->gets_where($where, $params);


        if(have_posts($this->data['objects'])) {
            foreach ($this->data['objects'] as $key => $val) {
                $this->data['objects'][$key]->categories = $this->$model->gets_relationship_join_categories($val->id, 'post');
            }
        }


        $this->data['trash']    =  $this->$model->count_where(array('trash' => 1, 'post_type' => $this->post_type));
        $this->data['public']   =  $this->$model->count_where(array('public' => 1, 'trash' => 0, 'post_type' => $this->post_type));
        
        if(empty($keyword) && $category_id == 0) $this->data['total'] = $total_rows;
        else $this->data['total']    =  $this->$model->count_where($where);

        /* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
            // '_column_headers' => $this->col['post'],
        );

        if(class_exists('skd_post_'.$this->post_type.'_list_table')) {
            $class_table = 'skd_post_'.$this->post_type.'_list_table';
        } else $class_table = 'skd_post_list_table';

        $this->data['table_list'] = new $class_table($args);
        /* hiển thị*/
        $this->template->render();
    }

    public function add() {

        $model = $this->{$this->data['module'].'_model'};

        foreach ($this->post['taxonomies'] as $key_taxonomy) {

            if( isset($this->taxonomy['list_cat_detail'][$key_taxonomy]) ) {

                $taxonomy = $this->taxonomy['list_cat_detail'][$key_taxonomy];

                $this->data['dropdown']['taxonomy['.$key_taxonomy.']'] = gets_post_category( array('mutilevel' => $key_taxonomy) );

                unset($this->data['dropdown']['taxonomy['.$key_taxonomy.']'][0]);

            }
        }

        $this->form_action();

        $this->template->render('post-save');
    }

    public function edit($slug = '') {

        $model = $this->{$this->data['module'].'_model'};

		$this->data['object'] = get_post_by('slug', $slug); // lấy dữ liệu page

        if(have_posts($this->data['object'])) {

            $model->settable('relationships');

            $categories = $model->gets_where(array('object_id' => $this->data['object']->id, 'object_type' => 'post'));

            foreach ($categories as $key => $val) {

                $this->data['object']->{'taxonomy['.$val->value.']'}[] = $val->category_id;
            }

            $model->settable('post');

            foreach ($this->post['taxonomies'] as $key_taxonomy) {

                if( isset($this->taxonomy['list_cat_detail'][$key_taxonomy]) ) {

                    $taxonomy = $this->taxonomy['list_cat_detail'][$key_taxonomy];

                    $this->data['dropdown']['taxonomy['.$key_taxonomy.']'] = gets_post_category( array('mutilevel' => $key_taxonomy) );

                    unset($this->data['dropdown']['taxonomy['.$key_taxonomy.']'][0]);

                }
            }

            $this->form_sets_field($this->data['object']);

            $this->form_action($this->data['object']);

            $this->template->render('post-save');
        }
        else {
            $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));

            $this->template->error('error-404');
        }
    }
}