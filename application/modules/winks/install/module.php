<?php
$module['module'] = 'winks';
$module['install_name'] = 'Winks module';
$module['install_descr'] = 'This module will let site members exchange winks as a means of attracting attention or establishing first contact.';
$module['category'] = 'action';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/winks/controllers/admin_winks.php"),
	array('file', 'read', "application/modules/winks/controllers/winks.php"),
	array('file', 'read', "application/modules/winks/helpers/winks_helper.php"),
	array('file', 'read', "application/modules/winks/install/module.php"),
	array('file', 'read', "application/modules/winks/install/permissions.php"),
	array('file', 'read', "application/modules/winks/install/settings.php"),
	array('file', 'read', "application/modules/winks/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/winks/install/structure_install.sql"),
	array('file', 'read', "application/modules/winks/js/winks.js"),
	array('file', 'read', "application/modules/winks/js/winks_multi_request.js"),
	array('file', 'read', "application/modules/winks/langs/en/arbitrary.php"),
	array('file', 'read', "application/modules/winks/langs/en/menu.php"),
	array('file', 'read', "application/modules/winks/langs/en/pages.php"),
	array('file', 'read', "application/modules/winks/langs/ru/arbitrary.php"),
	array('file', 'read', "application/modules/winks/langs/ru/menu.php"),
	array('file', 'read', "application/modules/winks/langs/ru/pages.php"),
	array('file', 'read', "application/modules/winks/models/winks_install_model.php"),
	array('file', 'read', "application/modules/winks/models/winks_model.php"),
	array('file', 'read', "application/modules/winks/views/default/helper_wink.tpl"),
	array('file', 'read', "application/modules/winks/views/default/helper_winks_header.tpl"),
	array('file', 'read', "application/modules/winks/views/default/list.tpl"),
	array('file', 'read', "application/modules/winks/views/default/list_top_panel.tpl"),
);

$module['dependencies'] = array(
	'start' => array('version' => '1.04'),
	'menu' => array('version' => '2.03'),
	'users' => array('version' => '3.02'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'moderators'	=> 'install_moderators',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'moderators'	=> 'deinstall_moderators',
	)
);
