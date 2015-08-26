<?php
$module['module'] = 'site_map';
$module['install_name'] = 'Sitemap generation';
$module['install_descr'] = 'The module gets modules links and generates the sitemap page ';
$module['version'] = '1.05';
$module['files'] = array(
	array('file', 'read', "application/modules/site_map/controllers/site_map.php"),
	array('file', 'read', "application/modules/site_map/install/module.php"),
	array('file', 'read', "application/modules/site_map/install/permissions.php"),
	array('file', 'read', "application/modules/site_map/install/settings.php"),
	array('file', 'read', "application/modules/site_map/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/site_map/install/structure_install.sql"),
	array('file', 'read', "application/modules/site_map/models/site_map_install_model.php"),
	array('file', 'read', "application/modules/site_map/models/site_map_model.php"),
	array('file', 'read', "application/modules/site_map/views/default/sitemap.tpl"),
	array('file', 'read', "application/modules/site_map/views/default/sitemap_level.tpl"),
	array('dir', 'read', 'application/modules/site_map/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'				=> 'install_menu'
	),
	'deinstall' => array(
		'menu'				=> 'deinstall_menu'
	)
);