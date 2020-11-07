<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <base href="<?=base_url()?>">
    <title>Đăng nhập</title>
    <?php do_action('admin_header');?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script>
      $( document ).ready(function() {
          domain    = '<?= base_url();?>';
          base      = '<?= base_url().URL_ADMIN;?>';
          path      = '<?= base_url().URL_ADMIN.'/'.$ajax;?>';
          $.ajaxSetup({
              beforeSend: function(xhr, settings) {
                  if (settings.data.indexOf('csrf_test_name') === -1) {
                      settings.data += '&csrf_test_name=' + encodeURIComponent('<?= $_COOKIE['csrf_cookie_name'];?>');
                  }
              }
          });
      });
    </script>

</head>

    <body class="login-page">
        <div class="login-widget">
            <?php $this->template->render_view(); ?>
        </div><!-- /login-widget -->
        <?php do_action('admin_footer');?>
    </body>
</html>
