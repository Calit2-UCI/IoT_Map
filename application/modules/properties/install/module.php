<?php
$module['module'] = 'properties';
$module['install_name'] = 'Properties management';
$module['install_descr'] = 'User types management, creating new user types';
$module['version'] = '1.05';

$module['files'] = array(
	array('file', 'read', "application/modules/properties/helpers/properties_helper.php"),
	array('file', 'read', "application/modules/properties/controllers/admin_properties.php"),
	array('file', 'read', "application/modules/properties/controllers/api_properties.php"),
	array('file', 'read', "application/modules/properties/controllers/properties.php"),
	array('file', 'read', "application/modules/properties/install/module.php"),
	array('file', 'read', "application/modules/properties/install/permissions.php"),
	array('file', 'read', "application/modules/properties/models/properties_install_model.php"),
	array('file', 'read', "application/modules/properties/models/properties_model.php"),
	array('file', 'read', "application/modules/properties/views/admin/css/style.css"),
	array('file', 'read', "application/modules/properties/views/admin/edit_ds_item.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/helper_properties_select.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/list.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/menu.tpl"),
	array('dir', 'read', 'application/modules/properties/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'moderators'	=> 'install_moderators'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'moderators'	=> 'deinstall_moderators'
	)
);
