<?php

$module['module'] = 'blacklist';
$module['install_name'] = 'Blacklist module';
$module['install_descr'] = 'The module manages blacklist';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/blacklist/controllers/api_blacklist.php"),
	array('file', 'read', "application/modules/blacklist/controllers/blacklist.php"),
	array('file', 'read', "application/modules/blacklist/helpers/blacklist_helper.php"),
	array('file', 'read', "application/modules/blacklist/install/module.php"),
	array('file', 'read', "application/modules/blacklist/install/permissions.php"),
	array('file', 'read', "application/modules/blacklist/install/settings.php"),
	array('file', 'read', "application/modules/blacklist/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/blacklist/install/structure_install.sql"),
	array('file', 'read', "application/modules/blacklist/js/blacklist.js"),
	array('file', 'read', "application/modules/blacklist/models/blacklist_callbacks_model.php"),
	array('file', 'read', "application/modules/blacklist/models/blacklist_install_model.php"),
	array('file', 'read', "application/modules/blacklist/models/blacklist_model.php"),
	array('file', 'read', "application/modules/blacklist/views/default/helper_blacklist.tpl"),
	array('file', 'read', "application/modules/blacklist/views/default/list.tpl"),
	array('dir', 'read', 'application/modules/blacklist/langs'),
);

$module['dependencies'] = array(
	'start'			=> array('version'=>'1.03'),
	'menu'			=> array('version'=>'2.03'),
	'users'			=> array('version'=>'3.01'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'site_map'		=> 'install_site_map',
		'banners'		=> 'install_banners',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'site_map'		=> 'deinstall_site_map',
		'banners'		=> 'deinstall_banners',
	)
);
