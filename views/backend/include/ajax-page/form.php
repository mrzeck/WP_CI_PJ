<?php echo form_open();?>


<?php if($this->template->method == 'index') {?>
<?php $this->template->render_include('ajax-page/form_short');?>
<?php } else if(isset($form) && have_posts($form)) {?>
    <?php if(have_posts($form['right']) && ( have_posts($form['lang']) || have_posts($form['left']) || have_posts($form['lefb']))) {?>
    <div class="col-sm-8 col-md-8">
        <?= $this->template->render_include('ajax-page/form_left');?>
        <?php do_action( 'before_admin_form_left' );?>
    </div>
    <div class="col-sm-4 col-md-4 col-form-right">
        <?= $this->template->render_include('ajax-page/form_right');?>
        <?php do_action( 'before_admin_form_right' );?>
    </div>
    <?php }  else {?>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <?= $this->template->render_include('ajax-page/form_left');?>
            <?= $this->template->render_include('ajax-page/form_right');?>
        </div>
    </div>
    <?php } ?>
<?php } ?>

<script>
$(document).ready(function(){
    $(".box-content").on("hide.bs.collapse", function(){
  	    $id = $(this).attr('id');
        $("#btn-"+$id).html('<i class="fal fa-plus-square"></i>');
    });
    $(".box-content").on("show.bs.collapse", function(){
        $id = $(this).attr('id');
        console.log('aa');
        $("#btn-"+$id).html('<i class="fal fa-minus-square"></i>');
    });
});
</script>