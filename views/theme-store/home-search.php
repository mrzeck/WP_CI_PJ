<?php
	$type 		= removeHtmlTags( $this->input->get( 'type' ) );
	$keyword 	= removeHtmlTags( $this->input->get( 'keyword' ) );
?>
<?php if ( $type == 'post' || $type == null ): ?>

	<?php if(have_posts($objects)):?>
		<div class="search-page">
			<div class="post">
			<?php foreach ($objects as $key => $val): ?>
				<?php $this->template->render_include('loop/item_post',array('val' => $val));?>
			<?php endforeach ?>
			</div>
		</divs>

	<?php else: ?>
		<?php echo notice('error', 'Không có kết quả tim kiếm cho từ khóa');?>
	<?php endif ?>
	
<?php endif ?>

<?php do_action('get_search_view', $objects, $type, $keyword ) ;?>

