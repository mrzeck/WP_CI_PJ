<!DOCTYPE html>
<html lang="<?= $this->language['current'];?>" <?php do_action('in_tag_html');?>>
	<?php $this->template->render_include('head'); ?>
	<body <?php do_action('in_tag_body');?> style="height: auto">
		<?php $this->template->render_include('mobile-search'); ?>
		<div id="td-outer-wrap">
			<?php $this->template->render_include('top'); ?>
			<div class="warper">
				<div class="container">
					<?php if( !is_user_logged_in() ) {?>
						<div class="user-signin">
							<?php $this->template->render_view(); ?>
						</div>
					<?php } else { ?>
						<div class="user-profile">
							<div class="user-header">
								<div class="col-sm-4 col-md-3"></div>
								<div class="col-sm-8 col-md-9" style="padding-left: 0;">
								</div>
							</div>
							<div class="user-action col-sm-4 col-md-3">
								<?php $this->template->render_include('user-action'); ?>
							</div>
							<div class="user-content col-sm-8 col-md-9">
								<?php $this->template->render_view(); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php $this->template->render_include('footer'); ?>
		</div>
	</body>
</html>