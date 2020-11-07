<?php 

function add_metabox_thuonghieu()
{
	add_meta_box(
		'thuonghieu_metabox',
		'thuonghieu',
		'thuonghieu_metabox_callback',
		'post_thuonghieu'
	);
}
add_action( 'init', 'add_metabox_thuonghieu');
if( !function_exists('thuonghieu_metabox_callback') ) {
	function thuonghieu_metabox_callback( $object ) {
		$value = '';
		if( have_posts($object))
			$value = get_metadata( 'post_thuonghieu', $object->id, 'thuonghieu_url', true );
		$input = array( 'field' => 'thuonghieu_url', 'label'	=> 'Url Video', 'type'  => 'text', 'note'  => 'Liên kết thuonghieu <b>youtube</b>', );
		echo _form($input, $value);
	}
}
if( !function_exists('wcmc_metabox_thuonghieu_save') ) {
    function wcmc_metabox_thuonghieu_save($post_id, $model) {
        $ci =& get_instance();
        if($ci->data['module'] == 'post' && $ci->post_type == 'thuonghieu') {
            $data = $ci->input->post();
            $currenttable = $model->gettable();
            $url  = get_metadata('post_thuonghieu', $object->id, 'thuonghieu_url', true);
            if( $url != removeHtmlTags($data['thuonghieu_url']) )
                update_metadata( 'post_thuonghieu', $post_id, 'thuonghieu_url', removeHtmlTags($data['thuonghieu_url']) );
        }
    }
    add_action('save_object', 'wcmc_metabox_thuonghieu_save', '', 2);
}
class skd_post_thuonghieu_list_table extends skd_post_list_table {
	function column_image($item) {
		if( !empty($item->image) )
        	echo get_img($item->image, $item->title, array('style' => 'width:50px;'), 'medium');
        else {
        	$img = get_metadata( 'post_thuonghieu', $item->id, 'thuonghieu_url', true);
        	echo get_img('https://img.youtube.com/vi/'.getYoutubeID($img).'/0.jpg', '', array('style'=>'width:50px;'));
        }
    }
}
if( !function_exists('template_thuonghieu_save') ) {
    function template_thuonghieu_save($ins_data, $data_outside) {
        $ci =& get_instance();
        if($ci->data['module'] == 'post' && $ci->post_type == 'thuonghieu') {
        	$ins_data['theme_view'] = 'post-detail-thuonghieu';
        }
        return $ins_data;
    }
    add_filter('save_object_before', 'template_thuonghieu_save', '', 2);
}
?>