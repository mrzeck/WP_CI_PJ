<!-- các field ở vị trí left - top -->
<?php if (isset($form['leftt']) && have_posts($form['leftt'])): ?>
  	<?php foreach ($form['leftt'] as $id => $name): ?>

		<div class="box-content collapse in" id="<?= $id;?>">
	    	<?php foreach ($form['field'] as $key => $val):
			if($id == $val['group']) echo show_form($val, isset($dropdown)?$dropdown:null); 
			endforeach ?>
	    </div>

  	<?php endforeach ?>
<?php endif ?>

<!-- các field ở vị trí language -->
<?php if(isset($form['lang']) && have_posts($form['lang'])) { ?>
		<?php foreach ($form['lang'] as $id => $name): ?>

			<div class="box-content collapse in" id="<?= $id;?>">
				<!-- tab language -->
				<?php if(count($this->language['language_list']) > 1) { ?>
					<ul class="nav nav-tabs" role="tablist">
						<?php foreach ($this->language['language_list'] as $key => $val) { ?>
						<li role="presentation" class="<?=($key == $this->language['default'])?'active':'';?>"><a href="#lang_<?=$key;?>" aria-controls="<?=$key;?>" role="tab" data-toggle="tab"><?=$val['label'];?></a></li>
						<?php } ?>
					</ul>
				<?php } ?>

				<!-- hiển thị các field -->
				<?php if(count($this->language['language_list']) > 1) { ?>
				<div class="tab-content">
				<?php } else { ?>
				<div class="box-content" style="margin:0;padding:0px;"> 
				<?php } ?>

				<?php foreach ($this->language['language_list'] as $key_lang => $lang) { ?>
					<div role="tabpanel" class="tab-pane <?=($key_lang == $this->language['default'])?'active':'';?>" id="lang_<?=$key_lang;?>">
					<?php foreach ($form['field'] as $val):
					if($id == $val['group'] && isset($val['lang']) && $val['lang'] == $key_lang) 
						echo show_form($val, isset($dropdown)?$dropdown:null); 
					endforeach ?>
					</div>
				<?php } ?>

				</div>
			</div>

		<?php endforeach ?>
<?php } ?>

<!-- các field ở vị trí left - bottom -->

<?php if (isset($form['leftb']) && have_posts($form['leftb'])): ?>

  	<?php foreach ($form['leftb'] as $id => $name): ?>

		<div class="box-content collapse in" id="<?= $id;?>">
	    	<?php foreach ($form['field'] as $key => $val):
			if($id == $val['group']) {
				echo show_form($val, isset($dropdown)?$dropdown:null);
			}
			endforeach ?>
	    </div>

  	<?php endforeach ?>
<?php endif ?>

<?php if (isset($form['right']) && have_posts($form['right'])): ?>
  	<?php foreach ($form['right'] as $id => $name): ?>
		<div class="box-content collapse in" id="<?= $id;?>">
	    	<?php foreach ($form['field'] as $key => $val):
			if($id == $val['group']) echo show_form($val, isset($dropdown)?$dropdown:null); 
			endforeach ?>
	    </div>

  	<?php endforeach ?>
<?php endif ?>