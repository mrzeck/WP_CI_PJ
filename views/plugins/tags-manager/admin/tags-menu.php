<?php
if( !function_exists('tags_admin_menu_list') ) {

	function tags_admin_menu_list($list_object) {

        $tags = gets_tags(['params' => ['limit' => 30]]);

        foreach ($tags as $key => &$tag) {

            $tag->title = $tag->name;
        }

        $list_object['tags'] = [
            'label' => 'Tags',
            'type'  => 'tags',
            'data'  => $tags
        ];

		return $list_object;
	}

	add_filter( 'admin_menu_list_object', 'tags_admin_menu_list', 1);
}