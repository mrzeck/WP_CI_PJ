<!-- các field ở vị trí left - top -->
<?php if (isset($form['leftt']) && have_posts($form['leftt'])): ?>
  	<?php foreach ($form['leftt'] as $id => $name): ?>
  	<div class="box">
	  	<div class="header">
	  		<h3 class="pull-left"><?= $name;?></h3>
	  		<a class="pull-right btn-collapse" id="btn-<?=$id;?>" data-toggle="collapse" data-target="#<?=$id;?>"><?php echo (get_cookie("btn-".$id)== null)?'<i class="fal fa-minus-square"></i>':'<i class="fal fa-plus-square"></i>';?></a>
	  	</div>
		<div class="box-content collapse <?= (get_cookie("btn-".$id)== null)?'in':'';?>" id="<?= $id;?>">
	    	<?php if(isset($this->metaboxs[$id])) call_user_func( $this->metaboxs[$id]['callback'], $id, $this->metaboxs[$id]['callback'] ); ?>

	    	<?php foreach ($form['field'] as $key => $val):
			if($id == $val['group']) echo show_form($val, isset($dropdown)?$dropdown:null); 
			endforeach ?>
			<div class="clearfix"></div>
	    </div>
  	</div>
  	<?php endforeach ?>
<?php endif ?>

<!-- các field ở vị trí language -->
<?php if(isset($form['lang']) && have_posts($form['lang'])) { ?>
		<?php foreach ($form['lang'] as $id => $name): ?>
		<div class="box">
			<div class="header">
				<h3 class="pull-left"><?= $name;?></h3>
				<a class="pull-right btn-collapse" id="btn-<?=$id;?>" data-toggle="collapse" data-target="#<?=$id;?>"><?php echo (get_cookie("btn-".$id)== null)?'<i class="fal fa-minus-square"></i>':'<i class="fal fa-plus-square"></i>';?></a>
			</div>
			<div class="box-content collapse <?= (get_cookie("btn-".$id)== null)?'in':'';?>" id="<?= $id;?>">
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
				<div class="clearfix"></div>
			</div>
		</div>
		<?php endforeach ?>
<?php } ?>

<!-- các field ở vị trí left - bottom -->

<?php if (isset($form['leftb']) && have_posts($form['leftb'])): ?>
  	<?php foreach ($form['leftb'] as $id => $name): ?>
  	<div class="box">
	  	<div class="header">
	  		<h3 class="pull-left"><?= $name;?></h3>
	  		<a class="pull-right btn-collapse" id="btn-<?=$id;?>" data-toggle="collapse" data-target="#<?=$id;?>"><?php echo (get_cookie("btn-".$id)== null)?'<i class="fal fa-minus-square"></i>':'<i class="fal fa-plus-square"></i>';?></a>
	  	</div>
		<div class="box-content collapse <?= (get_cookie("btn-".$id)== null)?'in':'';?>" id="<?= $id;?>">
	    	<?php if(isset($this->metaboxs[$id])) call_user_func( $this->metaboxs[$id]['callback'], (isset($object))?$object:array(), $id, $this->metaboxs[$id]['callback'] ); ?>
	    	<?php foreach ($form['field'] as $key => $val):
			if($id == $val['group']) echo show_form($val, isset($dropdown)?$dropdown:null); 
			endforeach ?>
			<div class="clearfix"></div>
	    </div>
  	</div>
  	<?php endforeach ?>
<?php endif ?>