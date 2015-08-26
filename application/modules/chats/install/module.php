<?php
$module['module'] = 'chats';
$module['install_name'] = 'Chats module';
$module['install_descr'] = 'This module is a hub that lets you install and activate (video) chat solutions from third parties';
$module['category'] = 'communication';
$module['version'] = '1.04';
$module['files'] = array(
	array('file', 'read', "application/modules/chats/controllers/admin_chats.php"),
	array('file', 'read', "application/modules/chats/controllers/chats.php"),
	array('file', 'read', "application/modules/chats/helpers/chats_helper.php"),
	array('file', 'read', "application/modules/chats/install/module.php"),
	array('file', 'read', "application/modules/chats/install/permissions.php"),
	array('file', 'read', "application/modules/chats/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/chats/install/structure_install.sql"),
);

$module['dependencies'] = array(
	'start'	=> array('version'=>'1.03'),
	'menu'	=> array('version'=>'2.03'),
	'users'	=> array('version'=>'3.01'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'	=> 'install_menu',
	),
	'deinstall' => array(
		'menu'	=> 'deinstall_menu',
	),
);
