<?php
/**
 * [store_register_nav_menus đăng ký các vị trí menu]
 * @return [type] [description]
 */
class store_bootstrap_nav_menu extends walker_nav_menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $ci =& get_instance();
        $class          = have_posts($item->child) ?'dropdown ':'';
        $class          .= isset($item->class) ?$item->class:'';
        $slug           = $ci->uri->segment('1');
        if( isset($ci->language['language_list']) && have_posts($ci->language['language_list']) && count($ci->language['language_list']) > 1 ) {
            $slug  = $ci->uri->segment('2');
        }
        $slug           = removeHtmlTags($slug);
        $slug           = (empty($slug)) ? 'trang-chu' : $slug;
        $check          = false;
        if( $slug == $item->slug ) {
            $class .= ' active';
            $check = true;
        }
        if( $check == false && is_page('products_index') && !empty($ci->data['category']) ) {
            $category = $ci->data['category'];
            while ( $category->parent_id != 0 ) {
                $category = wcmc_get_category( $category->parent_id );
            }
            if( $category->slug == $item->slug ) $class .= ' active';
        }
        $output         .= '<li class="'.$class.'">';
        $atts           = array();
        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? get_url($item->slug)       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<a '.$attributes.'>'.$item->name.'</a>' ;
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '</li>';
    }
}
class store_mobile_nav_menu extends walker_nav_menu {
    /**
    * Phương thức start_lvl()
    * Được sử dụng để hiển thị các thẻ bắt đầu cấu trúc của một cấp độ mới trong menu. (ví dụ: <ul class="sub-menu">)
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    **/
    function start_lvl(&$output, $depth = 0, $arg = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n";
    }
    /**
    * Phương thức end_lvl()
    * Được sử dụng để hiển thị đoạn kết thúc của một cấp độ mới trong menu. (ví dụ: </ul> )
    * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
    * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
    * @param array $args | Các tham số trong hàm wp_nav_menu()
    **/
    function end_lvl(&$output, $depth = 0, $arg = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent\n";
    }
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $class          = 'list-group-item-vmenu ';
        $class          .= isset($item->class) ?$item->class:'';
        $atts           = array();
        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? $item->slug       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        if(have_posts($item->child)) {
            $output .= '<a href="'.get_url($item->slug).'" class="list-group-item-vmenu">'.$item->name.'</a>';
            $output .= '<a href="#path_'.$item->id.'" data-toggle="collapse" data-parent="#MainMenu" class="icon arrow-sub-vmenu collapsed"><i class="icon-show fal fa-angle-right pull-right"></i></a>';   
            $this->start_el_sub($output, $item, $item->child);
            unset($item->child);
        }
        else $output .= '<a class="'.$class.'" '.$attributes.'>'.$item->name.'</a>' ;
    }
    function start_el_sub(&$output, $item, $subs) {
        $output .= '<div class="collapse" id="path_'.$item->id.'" style="height: 0px;">';
        foreach ($subs as $key => $sub) {
            $this->start_el($output, $sub);
            $this->end_el($output, $sub);
        }
        $output .= '</div>';
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // $output .= '</li>';
    }
}
class store_mega_nav_menu extends walker_nav_menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $class          = 'parent parent-megamenu  vertical-menu-category__nav';
        $class          .= isset($item->class) ?$item->class:'';
        $ci = &get_instance();
        $slug = $ci->uri->segment(1);
        if($slug == $item->slug ) $class .= ' active';
        if($slug == '' && $item->slug == '#') $class .= ' active';
        $atts           = array();
        $atts['title']  = isset( $item->attr )   ? $item->attr       : '';
        $atts['target'] = isset( $item->target ) ? $item->target     : '';
        $atts['rel']    = isset( $item->xfn )    ? $item->xfn        : '';
        $atts['href']   = isset( $item->slug )   ? $item->slug       : '';
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<li class="nav-item '.$class.'">';
        if(have_posts($item->child)) {
            $output .= '<a '.$attributes.' class="nav-link ">'.$item->name.'</a><i class="icon-show fal fa-angle-right pull-right"></i><span class="toggle-submenu"></span>' ;
            $output .= '<div class="dropdown megamenu drop-menu " style="right: auto; left: 0px;width:'. 230 * ($item->data['socot'] + 1).'px">';
            $this->start_el_sub($output, $item, $item->child);
            $output .= '</div>';
            unset($item->child);
        }
        else  {
            $output .= '<a '.$attributes.' class="nav-link ">'.$item->name.'</a>' ;
        }
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '</li>';
    }
    function start_el_sub(&$output, $item, $subs) {
        $a='col-md-12';
        if ($item->data['socot'] ==0) {$a='col-md-12'; }
        if ($item->data['socot'] ==1) {$a='col-md-6'; }
        if ($item->data['socot'] ==2) {$a='col-md-4'; }
        if ($item->data['socot'] ==3) {$a='col-md-3'; }
        for ($i=1; $i < ($item->data['socot'] + 2); $i++) { 
            $output .= '<ul class="'.$a.' group'.$i.'" style="padding:0 5px">';
            foreach ($subs as $key => $value) {
            // show_r($value->data['ngathang']);
                if ($value->data['vitri'] ==($i - 1) ) {
                    $image = '';
                    $output .= '<li class="">';
                    $output .= '<strong class="title"><a href="'.get_url($value->slug).'" title="">'.$image.'<span>'.$value->name.'</span></a></strong>';
                    
                    if(isset($value->child) && have_posts($value->child)) {
                       $b='col-md-12';
                       if ($value->data['socot'] ==0) {$b='col-md-12'; }
                       if ($value->data['socot'] ==1) {$b='col-md-6'; }
                       if ($value->data['socot'] ==2) {$b='col-md-4'; }
                       if ($value->data['socot'] ==3) {$b='col-md-3'; }
                        for ($j=1; $j < ($value->data['socot'] + 2); $j++) { 
                           $output .= '<ul class="'.$b.' child_group'.$j.'" style="padding:0 5px">';
                            foreach ($value->child as $key => $sub) {
                                if ($sub->data['vitri'] ==($j - 1) ) {
                                    $output .= '<a href="'.get_url($sub->slug).'" class="nav-link " style="padding:0 ">'.$sub->name.'</a>' ;
                                }
                            }
                           $output .= '</ul>';
                        }
                        
                            
                        
                    }
                    $output .= '</li>';
                }
            }
            $output .= '</ul>';
        }
    }
}
