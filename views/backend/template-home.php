<!DOCTYPE html>
<html lang="vi">
    <?php $this->template->render_include('head'); ?>
    <body>
        <div class="wrapper">
            <?php $this->template->render_include('navigation'); ?>
            <div class="page-content">
                <div class="mobile-nav">
                    <div class="pull-left"><a href="" class="menu-i"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
                </div>
                <div class="page-body">
                    <div class="ui-layout">
                    <?php $this->template->render_view(); ?>
                    </div>
                </div>
            </div>
        </div><!-- container -->
        <!-- footer -->
        <?php $this->template->render_include('footer'); ?>
        <!--/footer -->
    </body>
</html>
