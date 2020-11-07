<input type="hidden" name="value" value="" id="value" class="form-control">
<div class="clearfix"></div>
<hr />
<?php 
if( isset($this->gallery_options['image']['gallery']) && have_posts($this->gallery_options['image']['gallery']) ) {
	
	$option = $this->gallery_options['image']['gallery'];

	foreach ($option as $key => $input) {

		$input['field'] = 'option['.$input['field'].']';

		echo _form($input);
	}
}