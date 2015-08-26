<?php

$module['module'] = 'friendlist';
$module['install_name'] = 'Friendlist module';
$module['install_descr'] = 'The module manages friends list';
$module['category'] = 'action';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/friendlist/controllers/api_friendlist.php"),
	array('file', 'read', "application/modules/friendlist/controllers/friendlist.php"),
	array('file', 'read', "application/modules/friendlist/helpers/friendlist_helper.php"),
	array('file', 'read', "application/modules/friendlist/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/friendlist/install/module.php"),
	array('file', 'read', "application/modules/friendlist/install/permissions.php"),
	array('file', 'read', "application/modules/friendlist/install/settings.php"),
	array('file', 'read', "application/modules/friendlist/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/friendlist/install/structure_install.sql"),
	array('file', 'read', "application/modules/friendlist/js/friends-input.js"),
	array('file', 'read', "application/modules/friendlist/js/friends-select.js"),
	array('file', 'read', "application/modules/friendlist/js/lists_links.js"),
	array('file', 'read', "application/modules/friendlist/js/friendlist_multi_request.js"),
	array('file', 'read', "application/modules/friendlist/models/friendlist_callbacks_model.php"),
	array('file', 'read', "application/modules/friendlist/models/friendlist_install_model.php"),
	array('file', 'read', "application/modules/friendlist/models/friendlist_model.php"),
	array('file', 'read', "application/modules/friendlist/views/default/ajax_friend_select_form.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/helper_friend_input.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/helper_friend_select.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/helper_lists_links.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/request_block.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/friendlist.tpl"),
	array('file', 'read', "application/modules/friendlist/views/default/wall_events_friendlist.tpl"),
	array('dir', 'read', 'application/modules/friendlist/langs'),
);

$module['dependencies'] = array(
	'start' => array('version' => '1.03'),
	'menu' => array('version' => '2.03'),
	'moderation' => array('version' => '1.01'),
	'users' => array('version' => '3.01'),
	'notifications' => array('version' => '1.04'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu' => 'install_menu',
		'moderation' => 'install_moderation',
		'wall_events' => 'install_wall_events',
		'site_map' => 'install_site_map',
		'notifications' => 'install_notifications',
		'banners' => 'install_banners',
		'network' => 'install_network',
	),
	'deinstall' => array(
		'menu' => 'deinstall_menu',
		'moderation' => 'deinstall_moderation',
		'wall_events' => 'deinstall_wall_events',
		'site_map' => 'deinstall_site_map',
		'notifications' => 'deinstall_notifications',
		'banners' => 'deinstall_banners',
		'network' => 'deinstall_network',
	)
);
