<li class="col-xs-6 col-sm-3 col-md-3 object-gallery-item js_object_gallery_sort_item" data-id="<?= $val->id;?>">
  	<div class="radio"><input type="checkbox" name="select[]" value="<?= $val->id;?>" class="icheck gallery-item-checkbox"></div>
  	<div class="img">
  		<?php if($val->type == 'file') {
        	echo get_img('https://lh3.googleusercontent.com/zqfUbCXdb1oGmsNEzNxTjQU5ZlS3x46nQoB83sFbRSlMnpDTZgdVCe_LvCx-rl7sOA%3Dw300');
  		} else if($val->type == 'youtube') {
  			echo get_img('https://img.youtube.com/vi/'.getYoutubeID($val->value).'/0.jpg');
  		} else { echo get_img( $val->value ); } ?>
 	</div>
 	<div class="hidden">
	<?php
		$input = array('field' => 'gallery['.$val->id.'][value]', 'type' => 'hidden', 'args' => array('data-name'=>"value"));
    	echo _form($input, $val->value);
    	$input = array('field' => 'gallery['.$val->id.'][order]', 'type' => 'hidden', 'args' => array('class'=>"gallery-item-order"));
    	echo _form($input, $val->order);

		$option = array();

		if( !isset($class)) {

			$class = $ci->template->class;
			$post_type = $ci->input->get('post_type');
			$cate_type = $ci->input->get('cate_type');
		}

		if(  $class == 'post') {
			
			if( isset($ci->gallery_options['object'][$class][$post_type]) && have_posts($ci->gallery_options['object'][$class][$post_type]) ) {

				$option = $ci->gallery_options['object'][$class][$post_type];
			}
		}
		else if(  $class == 'post_categories') {

			if( isset($ci->gallery_options['object'][$class][$cate_type]) && have_posts($ci->gallery_options['object'][$class][$cate_type]) ) {

				$option = $ci->gallery_options['object'][$class][$cate_type];
			}
		}
		else if( isset($ci->gallery_options['object'][$class]) && have_posts($ci->gallery_options['object'][$class]) ) {

			$option = $ci->gallery_options['object'][$class];
		}

		if( have_posts($option)) {
			
			foreach ($option as $key => $input) {

				$iput = array('field' => 'gallery['.$val->id.'][option_'.$input['field'].']', 'type' => 'hidden', 'args' => array('data-name'=>'option_'.$input['field'].''));
				
				$meta = get_metadata('gallerys', $val->id, $input['field'], true);

				if(empty($meta) && isset($val->options[$input['field']])) {

					$meta = $val->options[$input['field']];
				}

				echo _form($iput, $meta);
			}
		}
	?>
	</div>
</li>