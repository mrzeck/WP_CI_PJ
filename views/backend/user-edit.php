<?php //$this->template->render_include('action_bar');?>

<?php
	$view 		= removeHtmlTags($this->input->get('view'));
	$view 		= ( empty($view) ) ? 'profile' : $view;
	$action_tab = admin_my_action_links();
?>
  
<?php if(isset($object) && have_posts($object)) { ?>
<div class="ui-layout">
	<div class="col-md-12">
		<div class="ui-title-bar__group">
			<h1 class="ui-title-bar__title"><?php echo $user->firstname.' '.$user->lastname;?></h1>
			<div class="ui-title-bar__action">
				<?php foreach ($action_tab as $key => $tab): ?>
				<a href="<?php echo URL_ADMIN;?>/user/edit?view=<?php echo $key;?>&id=<?php echo $object->id;?>" class="<?php echo ($view == $key)?'active':'';?> btn btn-default">
					<?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
					<?php echo $tab['label'];?>
				</a>
				<?php endforeach ?>
				
				<?php do_action('user_detail_header_action', $user);?>
			</div>
		</div>
	</div>
</div>

<?php call_user_func( $action_tab[$view]['callback'], $object, $this->user_model, $action_tab[$view] ) ?>

<style type="text/css">
	.form-group { overflow: hidden; }

	.ui-layout {
		box-sizing: border-box;
	    max-width: 1100px;
	    margin-right: auto;
	    margin-left: auto;
	    font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
	}

	.ui-layout .box {
		border-radius: 3px;
		-webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    	box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
	}

    section.ui-layout__section {
        padding:20px;
    }

    section.ui-layout__section+section.ui-layout__section {
	    border-top: 1px solid #dfe4e8;
    }
    section.ui-layout__section~section.ui-layout__section {
        border-top: 1px solid #ebeef0;
    }
    
    section.ui-layout__section header.ui-layout__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
        display:inline-block;
	}
</style>
<?php } else { echo notice('danger','Không có dữ liệu để hiển thị');}  ?>