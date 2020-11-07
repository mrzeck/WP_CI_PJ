<?php
function tddq_database_table_create() {

    $model = get_model('plugins', 'backend');

	$model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."tddq_history` (
        `id` int(11) NOT NULL,
        `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `bank` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `point` int(11) NOT NULL DEFAULT '0',
        `point_conver` int(11) NOT NULL DEFAULT '0',
        `content` text COLLATE utf8_unicode_ci,
        `created` datetime NOT NULL,
        `updated` datetime NOT NULL,
        `user_id` int(11) NOT NULL DEFAULT '0',
        `order` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

function tddq_database_table_drop() {

	$model = get_model('plugins', 'backend');

	$model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."tddq_history`");
}
function affiliate_database_table_create() {

    $model = get_model('plugins', 'backend');

    $model->query("CREATE TABLE IF NOT EXISTS `".CLE_PREFIX."affiliate_history` (
        `id` int(11) NOT NULL,
        `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
         `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `point` int(11) NOT NULL DEFAULT '0',

        `created` datetime NOT NULL,
        `updated` datetime NOT NULL,
        `user_id` int(11) NOT NULL DEFAULT '0',
        `order` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

function affiliate_database_table_drop() {

    $model = get_model('plugins', 'backend');

    $model->query("DROP TABLE IF EXISTS `".CLE_PREFIX."affiliate_history`");
}