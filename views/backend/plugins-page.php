<?php echo $this->template->render_include('action_bar');?>
<?php
echo call_user_func(
	$callback_page['callback'],
	$this,
	$this->plugins_model
);?>