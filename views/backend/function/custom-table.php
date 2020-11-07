<?php
//dùng cho toàn bộ table
class skd_object_list_table extends SKD_list_table {

    function column_public($item, $column_name, $module) {
        $this->column_boole($item, $column_name, $module);
    }

    function column_boole($item, $column_name, $module) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$module.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_image($item) {
        echo get_img($item->image, $item->title, array('style' => 'width:50px;'), 'medium');
    }

    function column_created($item) {
        echo date("d-m-Y", strtotime($item->created));
    }

    function _column_action( $item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/'.$module.'/';
        $status = $this->ci->input->get('status');
        $class .= ' text-center';
        echo '<td class="'.$class.'">';
        if($status == 'trash') {
            echo '<a href="'.$url.'untrash?id='.$item->id.'" class="btn-blue btn">'.admin_button_icon('undo').'</a>';
            echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
        } else {
            echo '<a href="'.$url.'edit/'.$item->slug.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
            echo '<button class="btn-red btn trash" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
        }
        echo "</td>";
    }

    //search box
    function display_search() {
        ?>
        <div class="table-search row">
            <div class="col-md-12">
                <div class="pull-left" style="padding:10px 0 10px 10px;">
                    <?php $this->search_left();?>
                </div>
                <div class="pull-right" style="padding-right:10px;">
                    <?php $this->search_right();?>
                </div>
            </div>
        </div>
        <?php
    }

    function search_left() {
        $module = $this->ci->data['ajax'];
        $public = $this->ci->data['public'];
        $trash  = $this->ci->data['trash'];

        $status = $this->ci->input->get('status');

        $url        = URL_ADMIN.'/'.$module;
        $url_trash  = URL_ADMIN.'/'.$module.'?status=trash';
        $url_type   = $this->ci->url_type;
        if($url_type != null) $url .= $url_type;
        if($url_type != null) $url_trash = URL_ADMIN.'/'.$module.$url_type.'&status=trash';

        $text_status_normal = admin_button_icon('edit').' đã đăng <b style="color:red;">( '.$public.' )</b>';
        $text_status_trash  = admin_button_icon('delete').' thùng rác <b style="color:red;">( '.$trash.' )</b>';

        if($status == 'trash') $text_status_trash = '<strong>'.$text_status_trash.'</strong>';
        else $text_status_normal = '<strong>'.$text_status_normal.'</strong>';
        ?>
        <a href="<?= $url;?>" ><?= $text_status_normal; ?></a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?= $url_trash;?>" ><?= $text_status_trash; ?></a>
        <?php
    }

    function search_right() {
        $module     = $this->ci->data['ajax'];
        $url        = URL_ADMIN.'/'.$module;
        if($this->ci->input->get('cate_type') != null) $url .='?cate_type='.$this->ci->input->get('cate_type');
        ?>
        <form class="search-box" action="<?= $url ;?>">
            <?php echo _form(array( 'field' => 'keyword', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),'');?>      
            <label for="title" class="control-label"></label>
            <button type="submit" class="btn"><?php echo admin_button_icon('search');?></button>
        </form>
        <?php
    }
}

//Page
class skd_page_list_table extends skd_object_list_table {

    function get_columns() {
        $this->_column_headers = array(
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'created'   => 'Ngày Tạo',
            'public'    => 'Hiển Thị',
        );

        $this->_column_headers = apply_filters( "manage_pages_columns", $this->_column_headers );
        $this->_column_headers['action'] = 'Hành Động';
        return $this->_column_headers;
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_pages_custom_column', $column_name, $item );
    }

    function column_title($item) {
        ?>
        <h3><?= $item->title;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= get_url($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function _column_action( $item, $column_name, $module, $table, $class) {
        
        $url = URL_ADMIN.'/'.$module.'/';

        $status = $this->ci->input->get('status');

        $class .= ' text-center';

        echo '<td class="'.$class.'">';

        if($status == 'trash') {

            echo '<a href="'.$url.'untrash?id='.$item->id.'" class="btn-blue btn">'.admin_button_icon('undo').'</a>';
            
            if(current_user_can('delete_pages')) {

                echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }

        } else {

            echo '<a href="'.$url.'edit/'.$item->slug.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
            
            if(current_user_can('delete_pages')) {

                echo '<button class="btn-red btn trash" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
        }
        echo "</td>";
    }
}

//category
class skd_category_list_table extends skd_object_list_table {

    function get_columns() {

        $cate_type = get_cate_type( $this->ci->cate_type );

        $this->_column_headers = array(
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'order'     => 'Thứ Tự',
            'public'    => 'Hiển Thị',
        );

        $this->_column_headers['cb'] = 'cb';

        if( in_array('image', $cate_type['supports'] ) !== false ) $this->_column_headers['image'] = 'Ảnh';

        $this->_column_headers['title'] = 'Tiêu đề';

        if( in_array('excerpt', $cate_type['supports'] ) !== false ) $this->_column_headers['excerpt'] = 'Mô tả';

        $this->_column_headers['public'] = 'Hiển Thị';

        $this->_column_headers['action'] = 'Hành Động';

        $this->_column_headers = apply_filters( 'manage_categories_'.$this->ci->cate_type.'_columns', $this->_column_headers );

        return $this->_column_headers;
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_categories_'.$this->ci->cate_type.'_custom_column', $column_name, $item );
    }

    function column_title($item) {
        ?>
        <h3><?= str_repeat('|-----', (($item->level > 0)?($item->level - 1):0)).$item->name;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= get_url($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_image($item) {
        echo get_img($item->image, $item->name, array('style' => 'width:50px;'), 'medium');
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url    = URL_ADMIN.'/'.$this->ci->data['ajax'].'/';
        $cate   = ($this->ci->cate_type != null)?'?cate_type='.$this->ci->cate_type:'';
        $class .= ' text-center';
        echo '<td class="'.$class.'">';
        if( $this->ci->cate_type != 'post_categories' || current_user_can('delete_categories'))
            echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$module.'"><i class="fa fa-trash"></i></button>';
        echo '<a href="'.$url.'edit/'.$item->slug.$cate.'" class="btn-blue btn"><i class="fas fa-pen-square"></i></a>';
        echo "</td>";
    }

    function search_left() {}

    function search_right() {
        $module     = $this->ci->data['ajax'];
        $url        = URL_ADMIN.'/'.$module;
        if( empty($this->ci->cate_type) ) $this->ci->cate_type = 'post_categories';
        ?>
        <form class="search-box" action="<?= $url.$this->ci->url_type ;?>">
            <?= _form(array( 'field' => 'keyword', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),'');?>
            <?php if( isset_cate_type($this->ci->cate_type) ) echo _form(array( 'field' => 'category', 'label' => '', 'type'  => 'cate_'.$this->ci->cate_type, 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),$this->ci->input->get('category'));?>      
            <?php if( isset_post_type($this->ci->post_type) ) echo _form(array( 'field' => 'post_type', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:none;">', 'before' => '</div>'), $this->ci->post_type);?>      
            <?php if( isset_cate_type($this->ci->cate_type) ) echo _form(array( 'field' => 'cate_type', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:none;">', 'before' => '</div>'), $this->ci->cate_type);?>      
            <label for="title" class="control-label"></label>
            <button type="submit" class="btn"><?php echo admin_button_icon('search');?></button>
        </form>
        <?php
    }
}

//post
class skd_post_list_table extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'        => 'cb',
            'image'     => 'Hình',
            'title'     => 'Tiêu Đề',
        );

        $post_type = $this->ci->post_type;

        $taxonomies = get_object_taxonomies( $post_type, 'objects' );

        $taxonomies = apply_filters( "manage_taxonomies_for_{$post_type}_columns", $taxonomies, $post_type );

        foreach ( $taxonomies as $taxonomy_key => $taxonomy_value ) {

            if( $taxonomy_value->show_admin_column == false ) continue;

            if ( 'post_categories' === $taxonomy_key ) {

                $column_key = 'categories';

            } else {

                $column_key = 'taxonomy-' . $taxonomy_key;

            }

            $this->_column_headers[ $column_key ] = $taxonomy_value->labels['name'];
        }

        $this->_column_headers['order']     = 'Thứ Tự';
        $this->_column_headers['status']    = 'Nổi bật';
        $this->_column_headers['public']    = 'Hiển Thị';
        $this->_column_headers['action']    = 'Hành Động';

        $this->_column_headers = apply_filters( "manage_post_".$post_type."_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_post_'.$this->ci->post_type.'_custom_column', $column_name, $item );

       if( strpos( $column_name, 'taxonomy-' ) !== false ) {

            $taxonomy = substr( $column_name, 9);

            if( get_cate_type( $taxonomy )['show_admin_column'] == true ) {

                $str = '';

                $categories = get_the_terms( $item->id, 'post', $taxonomy );

                foreach ($categories as $key => $value) {
                    $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/post/post_categories/edit/'.$value->slug.$this->ci->url_type, $value->name);
                }

                echo trim($str,', ');
            }
       }
    }

    function column_title($item) {
        ?>
        <h3><?= $item->title;?></h3>
        <div style="color:#ddd;padding:5px 0;"><?= str_word_cut(removeHtmlTags($item->excerpt),10);?></div>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= get_url($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_categories( $item ) {

        $str = '';

        $categories = get_the_terms( $item->id, 'post', 'post_categories' );

        foreach ($categories as $key => $value) {
            $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/post/post_categories/edit/'.$value->slug.'?cate_type='.$this->ci->cate_type, $value->name);
        }
        echo trim($str,', ');
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }

    function column_status($item, $column_name, $module, $table) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$table.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {

        $ci = $this->ci;

        $url = URL_ADMIN.'/'.$module.'/';

        $status = $ci->input->get('status');

        $url_type = $ci->url_type;

        $url_page = (int)$ci->input->get('page');

        $url_page = ($url_page != 0 && $url_page != 1)  ? '&page='.$url_page : '';

        $class .= ' text-center';

        echo '<td class="'.$class.'">';

        if($status == 'trash') {

            echo '<a href="'.$url.'untrash'.$url_type.'&id='.$item->id.'" class="btn-blue btn">'.admin_button_icon('undo').'</a>';
            
            /**
             * DELETE POST BUTTON
             */
            if(!empty($ci->post['capibilitie']['delete'])) {
                
                if(current_user_can($ci->post['capibilitie']['delete'])) echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
            else{
                echo '<button class="btn-red btn delete" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }

        } else {
            /**
             * EDIT POST BUTTON
             */
            if(!empty($ci->post['capibilitie']['edit'])) {
                
                if(current_user_can($ci->post['capibilitie']['edit'])) echo '<a href="'.$url.'edit/'.$item->slug.$url_type.$url_page.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
            }
            else{
                echo '<a href="'.$url.'edit/'.$item->slug.$url_type.$url_page.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
            }

            /**
             * DELETE POST BUTTON
             */
            if(!empty($ci->post['capibilitie']['delete'])) {
                
                if(current_user_can($ci->post['capibilitie']['delete'])) echo '<button class="btn-red btn trash" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
            else{
                echo '<button class="btn-red btn trash" data-id="'.$item->id.'" data-table="'.$table.'">'.admin_button_icon('delete').'</button>';
            }
                
        }
        echo "</td>";
    }

    function search_right() {
        
        $module     = $this->ci->data['ajax'];

        $url        = URL_ADMIN.'/'.$module;

        $cate_type = get_object_taxonomies($this->ci->post_type);

        if(empty($this->ci->cate_type)) {

            if($this->ci->post_type == 'post') {

                $this->ci->cate_type = 'post_categories';
            }
            else {

                $post_type = get_post_type($this->ci->post_type);

                if(isset($post_type['taxonomies'][0])) {
                    
                    $this->ci->cate_type = $post_type['taxonomies'][0];

                    $cate_type = get_object_taxonomies($this->ci->post_type);
                }
            }
        }

        ?>
        <form class="search-box" action="<?= $url.$this->ci->url_type ;?>">
            <?= _form(array( 'field' => 'keyword', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),'');?>
            <?php if( have_posts($cate_type) ) echo _form(array( 'field' => 'category', 'label' => '', 'type'  => 'cate_'.$this->ci->cate_type, 'after' => '<div style="display:inline-block;">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...'),),$this->ci->input->get('category'));?>      
            <?php if( isset_post_type($this->ci->post_type) ) echo _form(array( 'field' => 'post_type', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:none;">', 'before' => '</div>'), $this->ci->post_type);?>      
            <?php if( have_posts($cate_type) ) echo _form(array( 'field' => 'cate_type', 'label' => '', 'type'  => 'text', 'after' => '<div style="display:none;">', 'before' => '</div>'), $this->ci->cate_type);?>      
            <label for="title" class="control-label"></label>
            <button type="submit" class="btn"><?php echo admin_button_icon('search');?></button>
        </form>
        <?php
    }
}

//user
class skd_user_list_table extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'       => 'cb',
            'username' => 'Tên đăng nhập',
            'fullname' => 'Tên hiển thị',
            'email'    => 'Email',
            'phone'    => 'Số điện thoại',
            'role'     => 'Chức vụ',
            'action'   => 'Hành động',
        );

        $this->_column_headers = apply_filters( "manage_user_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function _column_username($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->username;
        echo "</td>";
    }

    function _column_fullname($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->firstname.' '.$item->lastname;
        echo "</td>";
    }

    function _column_email($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->email;
        echo "</td>";
    }

    function _column_phone($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->phone;
        echo "</td>";
    }

    function _column_role($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        $user_role = user_role( $item->id );
            if(have_posts($user_role)) {
                $user_role = get_role( array_pop( $user_role ) );
                echo __($user_role->name);
            }
        echo "</td>";
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/'.$module.'/';
        $class .= ' text-center';
        echo '<td class="'.$class.'">';
        echo '<a href="'.$url.'edit/?view=profile&id='.$item->id.'" class="btn-blue btn">'.admin_button_icon('edit').'</a>';
        if( current_user_can('remove_user') ) {
            echo '<button data-id="'.$item->id.'" class="btn-red btn user-trash">'.admin_button_icon('delete').'</button>';
        }
        if( is_super_admin()) {
            echo '<a href="'.$item->id.'" class="btn-green btn btn-reset-pass"><i class="fa fa-key" aria-hidden="true"></i></a>';
        }
        echo "</td>";
    }

    function column_default( $item, $column_name ) {
       do_action( 'manage_user_custom_column', $column_name, $item );
    }

    function search_left() {
    }
}

// add_filter( 'manage_product_columns', 'page_colum');
// add_action( 'manage_product_custom_column', 'custom_page_colum',10,2);

// function page_colum( $columns ) {
//     $columnsnew['cb']   = 'cb';    
//     $columnsnew['thum'] = 'Ảnh đại diện';
//     $columns            = array_merge($columnsnew, $columns);
//     return $columns;
// }

// function custom_page_colum( $column_name, $item ) {
//     switch ( $column_name ) {
//         case 'thum':
//             echo get_img($item->image, $item->title,array('style'=>'width:50px;'));
//             break;
//     }
// }