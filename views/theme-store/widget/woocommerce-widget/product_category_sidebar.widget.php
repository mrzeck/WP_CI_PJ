<?php 
class widget_product_category_sidebar extends widget {
	function __construct() {
		parent::__construct('widget_product_category_sidebar', 'DM Sản Phẩm Sidebar');
		$this->tags = array('sidebar');
		add_action('theme_custom_css', array( $this, 'css'), 10);
	}
	function form( $left = array(), $right = array()) {
		$left[] = array('field' => 'post_cate_id',      'label' =>'Nguồn bài viết ', 'type' => 'cate_post_categories');
		$right[] = array('field' => 'limit', 'label' =>'Số sản phẩm lấy ra', 'type' => 'number', 'value' => 10, 'note'=>'Để 0 để lấy tất cả (không khuyên dùng)');
		$right[] = array("field"=>"col_md", "label"=>"row desktop", "type"=>"col", "value"=>12, 'args' => array('max' => 13));
        $right[] = array("field"=>"col_sm", "label"=>"row table", "type"=>"col",   "value"=>12, 'args' => array('max' => 13));
        $right[] = array("field"=>"col_xs", "label"=>"row mobie", "type"=>"col",   "value"=>12, 'args' => array('max' => 13));
		parent::form($left, $right);
	}
	function widget($option) {
		//xử lý option
		$ci=&get_instance();
		// $categories = gets_post_category(['mutilevel' => 0, 'params' => array('select' => 'id, name, level')]);
		$model = get_model('post');
        $category=array();
        if($option->post_cate_id != 0) {
            $category = get_post_category( $option->post_cate_id );
        }

        if (count((array)$category)==0) {
            $category_id=0;
        }else{
            $category_id=$category->id;
        }
        $args = array(
            'where' => array(),
            'mutilevel' => $category_id,
            'params' => array( 'select' => 'id, name, slug,image' )
        );

        $categories = gets_post_category($args);
        $categories=$categories[0]->child;
        // /show_r($categories);

		$box = $this->container_box('widget_product_category_sidebar', $option);
        echo $box['before']; ?>
		<aside class="product-catgory-sidebar">
			<div class="header-title"> <h2 class="header"><span><?= $this->name;?></span></h2> </div>
			<div class="aside-content" id="mobile-aside-content">
				<nav class="nav-category navbar-toggleable-md">
					<?php if(isset($categories) && have_posts($categories)){ ?>
					<ul class="nav navbar-pills"> 
						<?php foreach ($categories as $key => $val): ?>
							<li class="nav-item ">
							<?php if( have_posts($val->child)){ ?>
							<div><a href="<?= get_url($val->slug);?>" class="nav-link"><?= $val->name;?></a><!-- <a class="click" data-id="<?=$val->slug?>"><i class="fa fa-plus"></i></a><div class="clr"></div> --></div>
							<ul style="list-style: none;" class="hide-<?=$val->slug?>">
								<?php foreach ($val->child as $k => $child): ?>
									<li class="nav-item">
										<a class="nav-link" href="<?= get_url($child->slug);?>"><?= $child->name;?><!--  <i class="fa fa-plus pull-right"></i> --></a>
										<?php if(have_posts($child->child)){ ?>
										<ul style="list-style: none;">
										<?php foreach ($child->child as $sub): ?>
											<li class="nav-item">
												<a class="nav-link" href="<?= get_url($sub->slug);?>"><?= $sub->name;?> <i class="fa fa-plus pull-right"></i></a>
											</li>
										<?php endforeach ?>
										</ul>
										<?php } ?>
									</li>
								<?php endforeach ?>
							</ul>
							<?php } else {?>
							<a class="nav-link" href="<?= get_url($val->slug);?>"><?= $val->name;?></a>
							<?php } ?>
							</li>
						<?php endforeach ?>
					</ul>
					<?php } ?>
				</nav>
			</div>
		</aside>
		<?php echo $box['after'];
	}
	function css() { include_once('assets/product-category-sidebar.css'); }
}
register_widget('widget_product_category_sidebar');