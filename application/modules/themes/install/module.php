<?php
$module['module'] = 'themes';
$module['install_name'] = 'Themes management';
$module['install_descr'] = 'The module allows you to install, activate, edit site themes';
$module['version'] = '3.01';
$module['files'] = array(
	array('file', 'read', "application/modules/themes/controllers/admin_themes.php"),
	array('file', 'read', "application/modules/themes/controllers/themes.php"),
	array('file', 'read', "application/modules/themes/install/module.php"),
	array('file', 'read', "application/modules/themes/install/permissions.php"),
	array('file', 'read', "application/modules/themes/install/structure_install.sql"),
	array('file', 'read', "application/modules/themes/models/themes_install_model.php"),
	array('file', 'read', "application/modules/themes/models/themes_model.php"),
	array('file', 'read', "application/modules/themes/views/admin/css/style.css"),
	array('file', 'read', "application/modules/themes/views/admin/edit_set.tpl"),
	array('file', 'read', "application/modules/themes/views/admin/form_rtl.tpl"),
	array('file', 'read', "application/modules/themes/views/admin/list_installed.tpl"),
	array('file', 'read', "application/modules/themes/views/admin/list_enable.tpl"),
	array('file', 'read', "application/modules/themes/views/admin/list_sets.tpl"),
	array('file', 'read', "application/modules/themes/views/admin/view.tpl"),
	array('file', 'read', "application/modules/themes/views/default/dynamic_block_logo.tpl"),
	array('dir', 'read', 'application/modules/themes/langs'),
	array('dir', 'write', 'application/views/default/logo'),
	array('dir', 'write', 'application/views/default/sets'),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'				=> 'install_menu',
		'moderators'			=> 'install_moderators',
		'dynamic_blocks'	=> 'install_dynamic_blocks',
	),
	'deinstall' => array(
		'menu'				=> 'deinstall_menu',
		'moderators'			=> 'deinstall_moderators',
		'dynamic_blocks'	=> 'deinstall_dynamic_blocks',
	)
);
