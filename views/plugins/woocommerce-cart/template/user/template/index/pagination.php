<div class="clearfix"></div>
<?php if(isset($pagination) && is_object($pagination) && method_exists( $pagination, 'html' ) ) echo $pagination->html();?>