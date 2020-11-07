<?php defined('BASEPATH') OR exit('No direct script access allowed');
class products extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->skd_security->auth_backend();
        $this->load->model($this->data['module'].'_model');
        $this->data['dropdown']['category_id'] = wcmc_gets_category( array('mutilevel' => 'option') );
        unset($this->data['dropdown']['category_id'][0]);
        foreach ($this->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
            if( $taxonomy_value['post_type'] == 'products' ) {
                $this->data['dropdown']['taxonomy['.$taxonomy_key.']'] = gets_post_category( array('mutilevel' => $taxonomy_key) );
                unset($this->data['dropdown']['taxonomy['.$taxonomy_key.']'][0]);
            }
        }
    }
    public function index() {
        $model      = $this->data['module'].'_model';
        $keyword    = $this->input->get('keyword');
        $category_id= ($this->input->get('category')!=null)?(int)$this->input->get('category'):0;
        $trash      = ($this->input->get('status') == 'trash')?1:0;
        $args       = [
            'where' => array('trash' => $trash)
        ];
        $category   = array();
        if( $category_id != 0 ) $category   = wcmc_get_category($category_id);
        if(have_posts($category)) {
            $args['where_category'] = $category;
        }
        /*===================================================
        TÍNH TỔNG SỐ DỮ LIỆU SẼ CÓ
        ====================================================*/
        $total_rows = 0;
        /* search keyword */
        if(!empty($keyword)) {
            $args['where_like'] = array( 'title' => array($keyword));
        }
        $total_rows = count_product($args);
        /*===================================================
        PHÂN TRANG
        ====================================================*/
        $url        = base_url().URL_ADMIN.'/'.$this->data['module'].'?page={page}';
        if(!empty($keyword))    $url .= '&keyword='.$keyword;
        if($trash == 1)         $url .= '&status=trash';
        if($category_id != 0)   $url .= '&category='.$category_id;
        $this->data['pagination'] = pagination($total_rows, $url, get_option('admin_pg_page',10));
        /*===================================================
        LẤY DỮ LIỆU
        ====================================================*/
        $params = array(
            'limit'  => get_option('admin_pg_page',10),
            'start'  => $this->data['pagination']->getoffset(),
            'orderby'=> 'order, created desc',
        );
        $args['params'] = $params;
        $this->data['objects'] = gets_product($args);
        if(have_posts($this->data['objects'])) {
            foreach ($this->data['objects'] as $key => $val) {
                $this->data['objects'][$key]->categories = $this->$model->gets_relationship_join_categories($val->id, 'products', 'products_categories');
            }
        }
        $this->data['trash']    =  count_product(array('where' => ['trash' => 1]));
        $this->data['public']   =  count_product(array('where' => ['public' => 1, 'trash' => 0]));
        $this->data['total']    =  $total_rows;
        /* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => $this->$model->gettable(),
            'model' => $this->$model,
            'module'=> $this->data['module'],
        );
        $class_table = 'skd_product_list_table';
        $this->data['table_list'] = new $class_table($args);
        /* hiển thị*/
        $this->template->render();
    }
    public function add() {
        $model     = $this->data['module'].'_model';
        if( current_user_can('wcmc_product_edit') ) {
            $this->form_action();
            $this->template->render('products-save');
        }
        else $this->template->error('404');
    }
    public function edit($slug = '') {
        $model = $this->data['module'].'_model';
        if( current_user_can('wcmc_product_edit') ) {
            $this->data['object'] = get_product( array( 'where' => array('slug' => $slug) ) ); // lấy dữ liệu page
            if( have_posts($this->data['object']) ) {
                $this->$model->settable('relationships');
                $categories = $this->$model->gets_where(array('object_id' => $this->data['object']->id, 'object_type' => 'products'));
                foreach ($categories as $key => $val) {
                    if( $val->value == null ||  $val->value == 'products_categories' )
                        $this->data['object']->category_id[] = $val->category_id;
                    else $this->data['object']->{'taxonomy['.$val->value.']'}[] = $val->category_id;
                }
                $this->$model->settable('products');
                $this->form_sets_field($this->data['object']);
                $this->form_action($this->data['object']);
                $this->template->render('products-save');
            }
            else {
                $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));
                $this->template->error('error-404');
            }
        }
        else $this->template->error('404');
    }
}