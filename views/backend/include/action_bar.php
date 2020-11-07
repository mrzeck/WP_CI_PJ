<div class="action-bar">
    <div class="ui-layout">
    <?php
        /**
         * Hook hiển thị khi bắt đầu action bar
         */
        do_action( 'action_bar_before', $module );


        /**
         * Hook hiển thị khi kết thúc action bar
         */
        do_action( 'action_bar_after', $module );
    ?>
    </div>
</div>

<div class="col-md-12"><?php admin_notices();?></div>

<div class="clearfix"></div>

<?php
    //POST
    if($this->template->class == 'post') {

        if($this->template->method == 'index' && !empty($this->post['capibilitie']['view']) ) {
            if(!current_user_can($this->post['capibilitie']['view'])) {
                echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
                exit();
            }
        }

        if($this->template->method == 'add' && !empty($this->post['capibilitie']['add']) ) {
            if(!current_user_can($this->post['capibilitie']['add'])) {
                echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
                exit();
            }
        }

        if($this->template->method == 'edit' &&  !empty($this->post['capibilitie']['edit']) ) {
            if(!current_user_can($this->post['capibilitie']['edit'])) {
                echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
                exit();
            }

        }
    }

    //PAGE
    if($this->template->class == 'page') {

        if($this->template->method == 'index' && !current_user_can('view_pages') ) {
            echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
            exit();
        }

        if($this->template->method == 'add' && !current_user_can('add_pages') ) {
            echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
            exit();
        }

        if($this->template->method == 'edit' && !current_user_can('edit_pages') ) {
            echo notice('error', 'Bạn không có quyền sử dụng chức năng này.');
            exit();
        }
    }
?>

