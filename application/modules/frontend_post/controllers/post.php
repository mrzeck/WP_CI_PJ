<?php defined('BASEPATH') OR exit('No direct script access allowed');

class post extends MY_Controller {

	function __construct() {

		parent::__construct('frontend');

		$this->load->model($this->data['module'].'_model');
	}


	/*==================== DISPLAY ================*/
	public function index($slug = '') {

		$this->data['objects'] 	= [];

		$this->data['category'] = [];

		$category = get_post_category( array('where' => array('slug' => $slug) ) );

		if(have_posts($category)) {

			$check_post_type = false;

			foreach ($this->taxonomy['list_post_detail'] as $post_type ) {

				if( in_array( $category->cate_type, $post_type['taxonomies'] ) !== false ) { $check_post_type = true; break; }
			}

			if( $check_post_type == false ) {

				$this->template->error('404');

				return false;
			}
			
			$page                     = (int)$this->input->get('page');
			
			$url                      = base_url().get_url($category->slug).'?page={page}';
			
			$args                     = array(
				'where_category'          => $category,
				'where'                   => array('public' => 1),
			);

			$args                     = apply_filters( 'post_controllers_index_args', $args );
			
			$total_rows               = apply_filters( 'post_controllers_index_count', count_post( $args ) );
			
			$this->data['pagination'] = apply_filters( 'post_controllers_index_paging', pagination( $total_rows, $url, get_option('num_post') ) );
			
			$args['params']           = apply_filters( 'post_controllers_index_params', array('limit' => get_option('num_post'),'start' => $this->data['pagination']->getoffset(),'orderby' => 'post.order, post.created desc') );
			
			$this->data['objects']    = apply_filters( 'post_controllers_index_objects', gets_post($args),  $args ); 
			
			$this->data['category']   = $category;
		}

		$this->template->render();
	}

	public function detail($slug = '')
	{
		$this->data['object']     = get_post( array( 'where' => array( 'slug' => $slug ) ) );

		$this->data['category'] = [];

		if(have_posts($this->data['object'])) {

			$this->data['categories'] = get_the_terms($this->data['object']->id, 'post');

			if( have_posts($this->data['categories']) ) $this->data['category'] = $this->data['categories'][0];

			$this->template->render();
		}
		else {
			$this->template->error('404');
		}
	}
}