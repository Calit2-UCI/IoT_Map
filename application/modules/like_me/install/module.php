<?php

$module['module'] = 'like_me';
$module['install_name'] = 'LikeMe module';
$module['install_descr'] = 'The module lets site members rate people by liking/skipping them and be notified when there is a match';
$module['version'] = '1.01';
$module['files'] = array(
	array('file', 'read', "application/modules/like_me/controllers/admin_like_me.php"),
	array('file', 'read', "application/modules/like_me/controllers/api_like_me.php"),
	array('file', 'read', "application/modules/like_me/controllers/like_me.php"),
	
	array('file', 'read', "application/modules/like_me/helpers/like_me_helper.php"),
	
	array('file', 'read', "application/modules/like_me/install/module.php"),
	array('file', 'read', "application/modules/like_me/install/permissions.php"),
	array('file', 'read', "application/modules/like_me/install/settings.php"),
	array('file', 'read', "application/modules/like_me/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/like_me/install/structure_install.sql"),
	
	array('file', 'read', "application/modules/like_me/js/like_me.js"),
	array('file', 'read', "application/modules/like_me/js/match_me.js"),
	
	array('file', 'read', "application/modules/like_me/models/like_me_install_model.php"),
	array('file', 'read', "application/modules/like_me/models/like_me_model.php"),
	
	array('file', 'read', "application/modules/like_me/views/admin/settings.tpl"),

	array('file', 'read', "application/modules/like_me/views/default/ajax_like_me.tpl"),
	array('file', 'read', "application/modules/like_me/views/default/ajax_notify.tpl"),
	array('file', 'read', "application/modules/like_me/views/default/helper_play.tpl"),
	array('file', 'read', "application/modules/like_me/views/default/like_me.tpl"),
	array('file', 'read', "application/modules/like_me/views/default/like_me_matches.tpl"),
	
	array('dir', 'read', "application/modules/like_me/langs"),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.01'),
	'menu' => array('version'=>'1.01'),
	'users' => array('version'=>'1.01'),
	'notifications' => array('version'=>'1.01'),
);

$module['linked_modules'] = array(
	'install' => array(
		'ausers'			=> 'install_ausers',
		'banners'			=> 'install_banners',
		'bonuses'			=> 'install_bonuses',
		'menu'				=> 'install_menu',
		'notifications'		=> 'install_notifications',
		'start'				=> 'install_start',
		'users'				=> 'install_users',
	),
	'deinstall' => array(
		'ausers'			=> 'deinstall_ausers',
		'banners'			=> 'deinstall_banners',
		'bonuses'			=> 'deinstall_bonuses',
		'menu'				=> 'deinstall_menu',
		'notifications'		=> 'deinstall_notifications',
		'start'				=> 'deinstall_start',
		'users'				=> 'deinstall_users',

	)
);
