<?php
/**
NAVIGATION ADMIN
*/
if(!function_exists('register_admin_nav')) {
	function register_admin_nav( $title, $key, $slug, $position = null, $arg = array()) {
		$ci =& get_instance();
		$def = array( 'icon' => '', 'callback' => '', 'hidden' => false, 'position' => $position);
		$arg = array_merge($def, $arg);
		$ci->admin_nav[$key] = array(
			'key' 		=> $key,
			'name' 		=> $title,
			'icon'  	=> $arg['icon'],
			'url'   	=> $slug,
			'callback'  => $arg['callback'],
			'hidden'	=> $arg['hidden'],
			'position'	=> $arg['position'],
		);
	}
}
if(!function_exists('register_admin_subnav')) {
	function register_admin_subnav( $parent_key, $title, $key, $slug, $arg = '', $position = null ) {
		$ci =& get_instance();
		//$parent_key = slug( $parent_key );
		$ci->admin_nav_sub[$parent_key][$key] = array(
			'key' 		=> $key,
			'name' 		=> $title,
			'url'   	=> $slug,
			'position'  => $position,
			'callback'  => ( isset($arg['callback']) )?$arg['callback']:$arg,
		);
	}
}
if(!function_exists('remove_admin_nav')) {
	/**
	 * [remove_admin_nav description]
	 * singe 2.0.2
	 */
	function remove_admin_nav( $key ) {
		$ci =& get_instance();
		if( isset($ci->admin_nav[$key]) ) unset( $ci->admin_nav[$key] );
	}
}
if(!function_exists('remove_admin_subnav')) {
	/**
	 * [remove_admin_nav description]
	 * singe 2.0.2
	 */
	function remove_admin_subnav( $parent_key, $key ) {
		$ci =& get_instance();
		if( isset($ci->admin_nav[$parent_key][$key]) ) unset( $ci->admin_nav[$parent_key][$key] );
	}
}
//hiển thị menu
if(!function_exists('admin_nav')) {
	function admin_nav($group = '', $active = '') {
		$ci =& get_instance();
		$cate_type = $ci->taxonomy['list_cat_detail'];
		$cate_type_remove = array();
		$post_type = $ci->taxonomy['list_post_detail'];
		foreach ($cate_type as $cate_key => $cate ) {
			if( !empty($cate['capibilitie']['edit']) ) {
				if( !current_user_can( $cate['capibilitie']['edit'] ) ) {
					$cate_type_remove[] = $cate_key;
				}
			}
		}
		foreach ($post_type as $key => $post) {
			if( $post['show_in_nav_admin'] == true) {
				if($key != 'post') {
					if(current_user_can($post['capibilitie']['view'])) register_admin_nav($post['labels']['name'], $key, 'post?post_type='.$key, 'page', array('icon' => $post['menu_icon'] ));
					if(current_user_can($post['capibilitie']['view'])) register_admin_subnav($key, $post['labels']['singular_name'], $key, 'post?post_type='.$key);
					if(current_user_can($post['capibilitie']['add']))  register_admin_subnav($key, 'Viêt bài viết', $key.'-add',  'post/add?post_type='.$key);
				}
				foreach ($post['taxonomies'] as $key_taxonomy) {
					if( in_array( $key_taxonomy, $cate_type_remove) !== false ) continue;
					if( isset($ci->taxonomy['list_cat_detail'][$key_taxonomy]) ) {
						$taxonomy = $ci->taxonomy['list_cat_detail'][$key_taxonomy];
						if( $taxonomy['show_in_nav_admin'] == true ){
							register_admin_subnav( $key, $taxonomy['labels']['name'], $key_taxonomy, 'post/post_categories?cate_type='.$key_taxonomy.'&post_type='.$key);
						}
					}
				}
			}
		}
		if(have_posts($ci->admin_nav)) {
			/*****************************************************************
			* SORT NAV ADMIN
			* ***************************************************************/
			$temp_position 		= array();
			$temp_no_position 	= array();
			foreach ($ci->admin_nav as $key_admin_nav => $admin_nav_value) {
				if( !empty($admin_nav_value['position']) ) {
					$temp_position[$key_admin_nav] = $admin_nav_value;
				}
				else {
					$temp_no_position[$key_admin_nav] = $admin_nav_value;
				}
			}
			foreach ($temp_position as $key_position => $value_position) {
				if( isset($temp_no_position[$value_position['position']]) ) {
					$temp = array();
					foreach ($temp_no_position as $position => $temp_no_position_value) {
						$temp[$position] = $temp_no_position_value;
						if( $value_position['position'] == $position ) {
							$temp[$key_position] = $value_position;
							unset($temp_position[$key_position]);
						}
					}
					$temp_no_position = $temp;
				}
			}
			if( have_posts($temp_position) ) {
				foreach ($temp_position as $key_position => $value_position) {
					if( isset($temp_no_position[$value_position['position']]) ) {
						$temp = array();
						foreach ($temp_no_position as $position => $temp_no_position_value) {
							$temp[$position] = $temp_no_position_value;
							if( $value_position['position'] == $position ) {
								$temp[$key_position] = $value_position;
							}
						}
						$temp_no_position = $temp;
					}
				}
				$temp_no_position = array_merge($temp_no_position, $temp_position);
			}
			$ci->admin_nav = $temp_no_position;
			/*****************************************************************
			* SORT SUBNAV ADMIN
			* ***************************************************************/
			$temp_position 		= array();
			$temp_no_position 	= array();
			foreach ($ci->admin_nav_sub as $nav =>  $subnav) {
				foreach ($subnav as $key_subnav => $value_subnav ) {
					if( !empty($value_subnav['position']) ) {
						$temp_position[$nav][$key_subnav] = $value_subnav;
					}
					else {
						$temp_no_position[$nav][$key_subnav] = $value_subnav;
					}
				}
			}
			foreach ($temp_position as $key_nav => $subnav) {
				foreach ( $subnav as $key_position => $value_position ) {
					if( isset($temp_no_position[$key_nav][$value_position['position']]) ) {
						$temp = array();
						foreach ($temp_no_position[$key_nav] as $position => $temp_no_position_value) {
							$temp[$position] = $temp_no_position_value;
							if( $value_position['position'] == $position ) {
								$temp[$key_position] = $value_position;
								unset($temp_position[$key_nav][$key_position]);
							}
						}
						$temp_no_position[$key_nav] = $temp;
					}
				}
			}
			foreach ($temp_position as $key_nav => $subnav) {
				if( have_posts($subnav) ) {
					foreach ( $subnav as $key_position => $value_position ) {
						if( isset($temp_no_position[$key_nav][$value_position['position']]) ) {
							$temp = array();
							foreach ($temp_no_position[$key_nav] as $position => $temp_no_position_value) {
								$temp[$position] = $temp_no_position_value;
								if( $value_position['position'] == $position ) {
									$temp[$key_position] = $value_position;
									unset($temp_position[$key_nav][$key_position]);
								}
							}
							$temp_no_position[$key_nav] = $temp;
						}
					}
				}
			}
			$ci->admin_nav_sub = $temp_no_position;
			$output = '';
			$admin_nav 		= $ci->admin_nav;
			$admin_nav_sub 	= $ci->admin_nav_sub;
			$slug = str_replace( base_url().URL_ADMIN.'/', '', fullurl() );
			$group_active = '';
			$sub_active = '';
			foreach ($admin_nav as $key => $nav) {
				if( $nav['url'] == $slug ) {
					$group_active = $key;
					break;
				}
			}
			foreach ($admin_nav_sub as $key_nav => $nav_sub) {
				foreach ($nav_sub as $key_sub => $sub) {
					if( $sub['url'] == $slug ) {
							$sub_active = $key_sub;
							if( $group_active == '' ) $group_active = $key_nav;
							break;
					}
				}
			}
			if($group_active != '') $group = $group_active;
			if($sub_active != '' ) $active = $sub_active;
            foreach ($admin_nav as $key => $item) {
            	if( isset($item['hidden']) && $item['hidden'] == true ) continue;
                $item = (object)$item;
                $current = (isset($group) && ($key == $group))?'has-current-submenu':'not-current-submenu';
                $output .= '<li class="has-submenu menu-top '.$current.'">';
                $output .= '<a href="'.URL_ADMIN.'/'.$item->url.'" class="has-submenu '.$current.' menu-top">';
	            $output .= '<div class="menu-arrow"><div></div></div>';
	            $output .= '<div class="menu-image">'.$item->icon.'</div>';
	            $output .= '<div class="menu-name">'.$item->name.'</div>';
	            $output .= '</a>';
	            if( isset( $admin_nav_sub[$key] ) ) {
	            	$output .= '<ul class="submenu submenu-wrap">';
	            	foreach ($admin_nav_sub[$key] as $sub_key => $sub ): $sub = (object)$sub;
	            		$current_sub = (isset($active) && ($sub_key == $active))?'current':'';
	            		$output .= '<li class="'.$current_sub.' '.$sub_key.'---'.$active.'">';
                    	$output .= '<a class="'.$current_sub.'" href="'.URL_ADMIN.'/'.$sub->url.'">'.$sub->name.'</a>';
                    	$output .= '</li>';
					endforeach;
	            	$output .= '</ul>';
	            }
                $output .= '</li>';
            }
            echo $output;
		}
	}
}
/**
 * MENU ADMIN
 * */
/**
MENU : Tạo cây menu trong menu index
*/
if(!function_exists('created_admin_tree_menu')) {
	function created_admin_tree_menu($trees = array()) {
		$ci =& get_instance();
		if(count($trees)) {
			echo '<ol class="mjs-nestedSortable-branch mjs-nestedSortable-expanded">';
			foreach ($trees as $key => $item) {
				echo '<li id="menuItem_'.$item->id.'" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">';
				echo $ci->load->view($ci->template->name.'/include/loop/menu_item_content',array('val' =>$item), true);
				if(have_posts($item->child))
					created_admin_tree_menu($item->child);
				echo '</li>';
			}
			echo "</ol>";
		}
	}
}
/**
MENU : Thêm option vào menu
*/
if(!function_exists('add_menu_option')) {
	function add_menu_option($key, $option = array()) {
		if(have_posts($option)) {
			$ci =& get_instance();
			$ci->menu_option['all']['all'][$key] = $option;
		}
	}
}
if(!function_exists('add_menu_option_page')) {
	function add_menu_option_page($key, $option = array()) {
		if(have_posts($option)) {
			$ci =& get_instance();
			$ci->menu_option['page'][$object_type][$key] = $option;
		}
	}
}
if(!function_exists('add_menu_option_post')) {
	function add_menu_option_post($object_type = '', $key, $option = array()) {
		if(have_posts($option)) {
			$ci =& get_instance();
			if($object_type == '') {
				$ci->menu_option['post']['all'][$key] = $option;
			}
			else if(isset_post_type($object_type)) {
				$ci->menu_option['post'][$object_type][$key] = $option;
			}
		}
	}
}
if(!function_exists('add_menu_option_cate')) {
	function add_menu_option_cate($object_type = '', $key, $option = array()) {
		if(have_posts($option)) {
			$ci =& get_instance();
			if($object_type == '') {
				$ci->menu_option['post_categories']['all'][$key] = $option;
			}
			else if(isset_cate_type($object_type)) {
				$ci->menu_option['post_categories'][$object_type][$key] = $option;
			}
		}
	}
}
/**
MENU : Lấy option của menu
*/
if(!function_exists('get_menu_option')) {
	function get_menu_option($object = '', $object_type = '') {
		$ci =& get_instance();
		if($object_type == '') {
			if($object == '' && isset($ci->menu_option['all']['all'])) return $ci->menu_option['all']['all'];
			else if($object == 'post' && isset($ci->menu_option['post']['all'])) return $ci->menu_option['post']['all'];
			else if($object == 'categories' && isset($ci->menu_option['post_categories']['all'])) return $ci->menu_option['post_categories']['all'];
			else if(isset($ci->menu_option[$object]['all'])) return $ci->menu_option[$object]['all'];
		}
		else {
			if($object == 'post' && isset($ci->menu_option['post'][$object_type])) return $ci->menu_option['post'][$object_type];
			else if($object == 'categories' && isset($ci->menu_option['post_categories'][$object_type])) return $ci->menu_option['post_categories'][$object_type];
			else if(isset($ci->menu_option[$object][$object_type])) return $ci->menu_option[$object][$object_type];
		}
		return array();
	}
}
/**
FRONTEND NAVIGATION
*/
if(!function_exists('register_nav_menus')) {
	function register_nav_menus($menus = array()) {
		$ci =& get_instance();
		if(have_posts($menus)) {
			foreach ($menus as $key => $name) {
				$ci->navigation[$key] = $name;
			}
		}
	}
}
/**
MENU : Lấy dữ liệu menu frontend
*/
if(!function_exists('get_data_menu')) {
	function get_data_menu($id = 0) {
		$ci 	=& get_instance();
		$cacheID = 'menu-'.$id;
		$cacheID = apply_filters( 'get_data_menu_capcheID', $cacheID );
		$menu = array();
		if( !$menu = get_cache($cacheID) ) {
			$model 	= get_model('menu_model', 'backend_theme');
			$model->settable('group');
			$where = array('id' => $id);
			$group = $model->get_where($where);
			if(have_posts($group)) {
				$model->settable('menu');
				$menu = $model->gets_where(array('menu_id' => $group->id, 'parent_id' => 0));
				$menus = $ci->multilevel_categories($menu, array('menu_id' => $group->id), $model);
			}
			$options = get_menu_option();
			if(have_posts($options)) {
				$keys = array();
				foreach ($options as $opKey => $opValue) {
					$list = array_keys($opValue);
					$keys = array_merge($keys, $list);
				}
			}
			get_option_menu($menu);
			$menu = apply_filters( 'get_data_menu' , $menu );
			save_cache($cacheID, $menu); //lưu cache 1 ngày
		}
		return apply_filters( 'get_data_menu' , $menu );
	}
}
/**
MENU : Lấy dữ liệu option của menu frontend
*/
if(!function_exists('get_option_menu')) {
	function get_option_menu(&$menu = 0) {
		$ci =& get_instance();
		if(have_posts($menu)) {
			foreach ($menu as $key => &$item) {
				$options = array();
				foreach ($ci->menu_option as $value) {
					foreach ($value as $val) {
						foreach ($val as $key => $data) {
							$options[$key] = null;
						}
					}
				}
				if(isset($item->data)) {
					if($item->data != null) $item->data = unserialize($item->data);
					$item->data = array_merge($options, $item->data);
				}
				else $item->data = $options;
				if(isset($item->child) && have_posts($item->child)) get_option_menu($item->child);
			}
		}
	}
}
/**
MENU : hiển thị cây menu
*/
if(!function_exists('cle_nav_menu')) {
	function cle_nav_menu($args = array()) {
		$def = array('theme_location' => 0, 'walker' => 'walker_nav_menu');
		$args = array_merge($def, $args);
		$ci 	=& get_instance();
		$model 	= get_model('menu_model', 'backend_theme');
		$model->settable('relationships');
		$menu 	= array();
		if( $args['theme_location'] != '') {
			if( !get_cache('menu-group-'.$args['theme_location'])  ) {
				$menu 	= $model->get_where(array('category_id' => $args['theme_location'], 'object_type' => 'menu'));
				save_cache('menu-group-'.$args['theme_location'], $menu); //Lưu cache 1 ngày
			}
			else {
				$menu = get_cache('menu-group-'.$args['theme_location']);
				delete_cache('menu-group-'.$args['theme_location']);
			}
		}
		else if( isset( $args['theme_id'] ) && $args['theme_id'] != 0 ) {
			if( !get_cache('menu-'.$args['theme_id'])) {
				$model->settable('group');
				$menu 	= $model->get_where(array('id' => $args['theme_id'], 'object_type' => 'menu'));
				$menu->object_id = $menu->id;
			}
			else {
				$menu = (object)array( 'object_id' => $args['theme_id'] );
			}
		}
		if(have_posts($menu)){
			$items 	=  get_data_menu($menu->object_id);
			if(!class_exists($args['walker'])) $args['walker'] = 'walker_nav_menu';
			if(have_posts($items)) {
				$walker = new $args['walker'];
				$output = null;
				$depth  = 0;
				foreach($items as $item) {
					$walker->start_el($output, $item, $depth);
					if(isset($item->child) && have_posts($item->child)) cle_nav_menu_sub($output, $item->child,$walker, $depth+1);
					$walker->end_el($output, $item, $depth);
				}
				echo $output;
			}
		}
		else echo "no menu";
	}
}
/**
MENU : hiển thị sub menu
*/
if(!function_exists('cle_nav_menu_sub')) {
	function cle_nav_menu_sub(&$output, $items, $walker, $depth) {
		if(have_posts($items)) {
			$walker->start_lvl($output, $depth);
			foreach($items as $item) {
				$walker->start_el($output, $item, $depth);
				if(isset($item->child) && have_posts($item->child)) cle_nav_menu_sub($output, $item->child,$walker, $depth+1);
				$walker->end_el($output, $item, $depth);
			}
			$walker->end_lvl($output, $depth);
		}
	}
}
