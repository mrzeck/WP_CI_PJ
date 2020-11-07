<?php
function tags_box_product( $object ) {

	$tags  = get_metadata( 'product', $object->id, 'tag', true);

	$str = '';

	if( have_posts($tags) ) {

		?>
		<div class="tags">
			<p>
				Tags:
				<?php
					foreach ($tags as $tag):
						$str .= '<a href="'.get_url('tag/'.slug($tag)).'">'.$tag.'</a>';
					endforeach;
					echo $str;
				?>
			</p>
		</div>
		<style type="text/css">
			.tags { margin: 20px 0 0 0; }
			.tags a { color:#000; border:1px dashed #ccc; border-radius:4px; padding:3px 10px; font-size:13px; margin-right:5px; }
			.tags a:hover { color:#f15928; }
		</style>
		<?php
	}
}

add_action('woocommerce_products_detail_info', 'tags_box_product', 50 );

function tags_box_post($content) {

	$ci =& get_instance();

	if(is_page('post_detail')) {

		$object = $ci->data['object'];

		if($object->post_type == 'post') {

			ob_start();

			$tags  = get_metadata( 'post', $object->id, 'tag', true);

			$str = '';

			if( have_posts($tags) ) {

				?>
				<div class="tags">
					<p>
						Tags:
						<?php
							foreach ($tags as $tag):
								$str .= '<a href="'.get_url('tag/'.slug($tag)).'?type=post">'.$tag.'</a>';
							endforeach;

							echo $str;
						?>
					</p>
				</div>
				<style type="text/css">
					.object-detail-content { overflow:hidden; }
					.tags { margin: 20px 0 0 0; }
					.tags a { color:#000; border:1px dashed #ccc; border-radius:4px; padding:3px 10px; font-size:13px; margin-right:5px; }
					.tags a:hover { color:#f15928; }
				</style>
				<?php
			}
			
			$content .= ob_get_contents();

			ob_clean();

			ob_end_flush();
		}
	}

	return $content;
}

add_filter('the_content', 'tags_box_post', 50 );


function template_page_tags(){

    $ci =& get_instance();

    if( !is_admin() ) {

		if( is_page('page_detail') ) {

			if($ci->data['object']->slug == 'tag') {

				$layout_products_category   = theme_layout_list(get_option('layout_products_category', 'layout-sidebar-right-banner-2'));

				$ci->template->set_view(TAG_PATH.'/template/page-tag');

				echo $ci->load->view($ci->template->name.'/'.$layout_products_category['template'], $ci->data, TRUE);

				die;
			}
		}

	}
}

add_action('template_redirect', 'template_page_tags');