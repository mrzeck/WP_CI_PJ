<?php if (isset($form['right']) && have_posts($form['right'])): ?>
  	<?php foreach ($form['right'] as $id => $name): ?>
  	<div class="box col-xs-12 col-sm-12 col-md-12">
	  	<div class="header">
	  		<h3 class="pull-left"><?= $name;?></h3>
	  		<a class="pull-right btn-collapse" id="btn-<?=$id;?>" data-toggle="collapse" data-target="#<?=$id;?>"><?php echo (get_cookie("btn-".$id)== null)?'<i class="fal fa-minus-square"></i>':'<i class="fal fa-plus-square"></i>';?></a>
	  	</div>
		<div class="box-content collapse <?= (get_cookie("btn-".$id)== null)?'in':'';?>" id="<?= $id;?>">
			<?php if(isset($this->metaboxs[$id])) call_user_func( $this->metaboxs[$id]['callback'], (isset($object))?$object:array(), $id, $this->metaboxs[$id]['callback'] ); ?>
	    	<?php foreach ($form['field'] as $key => $val):
	    	
			if($id == $val['group'])  {
				echo show_form($val, isset($dropdown)?$dropdown:null);
			}
			endforeach ?>
			<div class="clearfix"></div>
	    </div>
  	</div>
  	<?php endforeach ?>
<?php endif ?>