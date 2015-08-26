<?php
$module['module'] = 'users_connections';
$module['install_name'] = 'Users connections module';
$module['install_descr'] = 'The users connections module has to do with authorization methods including OAuth';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/users_connections/helpers/users_connections_helper.php"),
	array('file', 'read', "application/modules/users_connections/controllers/users_connections.php"),
	array('file', 'read', "application/modules/users_connections/install/module.php"),
	array('file', 'read', "application/modules/users_connections/install/permissions.php"),
	array('file', 'read', "application/modules/users_connections/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/users_connections/install/structure_install.sql"),
	array('file', 'read', "application/modules/users_connections/models/users_connections_model.php"),
	array('file', 'read', "application/modules/users_connections/models/users_connections_install_model.php"),
	array('file', 'read', "application/modules/users_connections/views/default/oauth_link.tpl"),
	array('file', 'read', "application/modules/users_connections/views/default/oauth_login.tpl"),
	array('file', 'read', "application/modules/users_connections/views/default/oauth_usertype.tpl"),
	array('dir', 'read', 'application/modules/users_connections/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'social_networking' => array('version'=>'1.03'),
	'users' => array('version'=>'3.01')
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'	=> 'install_menu'
	),
	'deinstall' => array(
		'menu'	=> 'deinstall_menu'
	)
);