<?php
if( !function_exists('tags_metabox') ) {

	function tags_metabox() {

		add_meta_box( 'product_metabox_tag', 'Tags', 'tags_metabox_callback', 'products', 1, 'right', 'price' );

		add_meta_box( 'post_metabox_tag', 'Tags', 'tags_metabox_callback', 'post_post', 1, 'right', 'media' );
	}

	add_action( 'init', 'tags_metabox', 1);
}

if( !function_exists('tags_metabox_callback') ) {
	/**
	 * [hook_metabox_callback nội dung metabox]
	 */
	function tags_metabox_callback( $object ) {

		$tags = gets_tags([
			'params' => [
				'limit' => 10
			]
		]);

		$_metabox = [];

		if( have_posts($object)) {

			if(isset($object->post_type) && $object->post_type == 'post')
				$_metabox  = get_metadata( 'post', $object->id, 'tag', true);
			else
				$_metabox  = get_metadata( 'product', $object->id, 'tag', true);
		}

		?>
		<section class="ui-layout__section">
            <div class="col-md-12" id="box_tags_search">
                <label for="">Thêm tags</label>
                <div class="group input-popover-group input-popover-tags" data-name="tags" id="tags_search" data-module="tags_search" data-key-type="tags">
					<div class="collections">
						<ul class="collection-list">
							<?php if(have_posts($_metabox)) {
								foreach ($_metabox as $key => $text) { ?>
								<li class="collection-list__li">
									<input type="hidden" name="tags[]" value="<?php echo $text;?>" class="inp-tags">
									<div class="collection-list__grid">
										<div class="collection-list__cell"><a href=""><?php echo $text;?></a></div>
										<div class="collection-list__cell">
											<button class="ui-button collection-list-delete"> <i class="fal fa-times"></i> </button>
										</div>
									</div>
								</li>
								<?php }
							}?>
						</ul>
					</div>
					<div style="position:relative">
						<input type="text" class="form-control input-popover-tags-search" placeholder="Tìm kiếm tags">
						<div class="popover-content">
							<div class="popover__tooltip"></div>
							<div class="popover__scroll">
								<ul class="popover__ul" style="display: block;">
									<?php if(have_posts($tags)) {
										foreach ($tags as $key => $item) { echo tags_template_item($item); }
									} else { ?>
									<li class="">
										<div class="item-tag">
											<div class="item-tag__name">
												<span>Không có tag nào <br> Vui lòng nhập để thêm tag.</span>
											</div>
										</div>
									</li>
									<?php } ?>                   
								</ul>
								<div class="popover__loading text-center" style="display: none;">
									Đang tải…
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </section>
		<?php
	}
}

if( !function_exists('tags_template_item') ) {

	function tags_template_item($item, $active = '') {

		$str = '';

		if($item->id == 0) {

			$str .= '
			<li class="option-tag option-tag-add option-'.$active.'">
				<div class="item-tag">
					<div class="item-tag__name"> '.$item->name.'</div>
				</div>
			</li>';
		}
		else {

			$str .= '
			<li class="option-tag option-tag-data option-'.slug($item->name).' '.$active.'" data-key="'.$item->name.'">
				<div class="item-tag">
					<div class="item-tag__name">
						<span>'.$item->name.'</span>
					</div>
				</div>
			</li>';

		}

		return $str;
	}
}

if( !function_exists('tags_search') ) {

    function tags_search($object, $keyword) {

        $tags = gets_tags([
            'where' => "`name` LIKE CONCAT('%', CONVERT('".$keyword."', BINARY), '%')"
        ]);

        if(!have_posts($tags)) {

            $tags = [
                (object)['id' => 0, 'name' => '<div><i class="fas fa-plus-circle"></i> <strong>Thêm</strong> <span>'.$keyword.'</span></div>'],
            ];
        }

        return $tags;
    }

    add_filter('input_popover_tags_search_search', 'tags_search', 10, 2);
}

if( !function_exists('tags_search_template') ) {

    function tags_search_template($str, $item, $active) {

        return tags_template_item($item, $active);
    }

    add_filter('input_popover_tags_search_search_template', 'tags_search_template', 10, 3);
}

if( !function_exists('tag_metabox_save') ) {
	/**
	 * [real_metabox_save lưu thông tin metabox khi sau khi lưu dự án]
	 */
	function tag_metabox_save($object_id, $model) {

		$ci =& get_instance();

		$data = $ci->input->post();
		
		if(isset($data['tags']) && have_posts($data['tags'])) {

			if($ci->data['module'] == 'products') {
				$object_type = 'product';
			}
			else {
				$object_type = 'post';
			}

			foreach ($data['tags'] as $key => &$tag) {

				$tag = removeHtmlTags($tag);

				$tag_format = strtolower($tag);

				$count = count_tags([
					'where' => [
						'name_format' => $tag_format,
					]
				]);

				if($count == 0) insert_tags([ 'name' => $tag ]);
			}

			$tags  = $data['tags'];

			update_metadata( $object_type, $object_id, 'tag', $tags );

			$tags = implode(',',$tags);

            update_metadata( $object_type, $object_id, 'tag-slug', slug($tags) );
		}
	}

	add_action('save_object', 'tag_metabox_save', '', 2);
}