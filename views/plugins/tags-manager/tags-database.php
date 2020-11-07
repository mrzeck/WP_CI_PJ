<?php
function tags_database_table_create() {

	$model = get_model('plugins', 'backend');

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."tags` (
		`id` int(11) NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `name_format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `created` datetime NOT NULL,
        `updated` datetime NOT NULL,
        `order` int(11) NOT NULL DEFAULT '0'
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

function tags_database_table_drop() {

	$model = get_model('plugins', 'backend');

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."tags`");
}