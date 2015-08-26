<?php

$module['module'] = 'favourites';
$module['install_name'] = 'Favourites module';
$module['install_descr'] = 'The module manages favourites';
$module['category'] = 'action';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/favourites/controllers/api_favourites.php"),
	array('file', 'read', "application/modules/favourites/controllers/favourites.php"),
	array('file', 'read', "application/modules/favourites/helpers/favourites_helper.php"),
	array('file', 'read', "application/modules/favourites/install/module.php"),
	array('file', 'read', "application/modules/favourites/install/permissions.php"),
	array('file', 'read', "application/modules/favourites/install/settings.php"),
	array('file', 'read', "application/modules/favourites/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/favourites/install/structure_install.sql"),
	array('file', 'read', "application/modules/favourites/js/favourites.js"),
	array('file', 'read', "application/modules/favourites/models/favourites_callbacks_model.php"),
	array('file', 'read', "application/modules/favourites/models/favourites_install_model.php"),
	array('file', 'read', "application/modules/favourites/models/favourites_model.php"),
	array('file', 'read', "application/modules/favourites/views/default/helper_favourites.tpl"),
	array('file', 'read', "application/modules/favourites/views/default/list.tpl"),
	array('dir', 'read', 'application/modules/favourites/langs'),
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
