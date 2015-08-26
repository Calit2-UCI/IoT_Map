<?php

$module['module'] = 'file_uploads';
$module['install_name'] = 'File uploads management';
$module['install_descr'] = 'This module lets you manage the types and sizes of the files in promo content block and in mailbox';
$module['version'] = '1.05';
$module['files'] = array(
	array('file', 'read', "application/modules/file_uploads/controllers/admin_file_uploads.php"),
	array('file', 'read', "application/modules/file_uploads/install/module.php"),
	array('file', 'read', "application/modules/file_uploads/install/permissions.php"),
	array('file', 'read', "application/modules/file_uploads/install/settings.php"),
	array('file', 'read', "application/modules/file_uploads/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/file_uploads/install/structure_install.sql"),
	array('file', 'read', "application/modules/file_uploads/models/file_uploads_config_model.php"),
	array('file', 'read', "application/modules/file_uploads/models/file_uploads_install_model.php"),
	array('file', 'read', "application/modules/file_uploads/models/file_uploads_model.php"),
	array('file', 'read', "application/modules/file_uploads/views/admin/edit_settings.tpl"),
	array('file', 'read', "application/modules/file_uploads/views/admin/list_settings.tpl"),
	array('file', 'read', "application/modules/file_uploads/views/admin/link_index.tpl"),
	array('file', 'read', "application/modules/file_uploads/views/admin/test.tpl"),
	array('dir', 'read', 'application/modules/file_uploads/langs'),
	array('dir', 'write', "uploads/file-uploads/"),
);
$module['dependencies'] = array(
    'start' => array('version' => '1.03'),
    'menu' => array('version' => '2.03'),
);
$module['linked_modules'] = array(
	'install' => array(
		'menu' => 'install_menu'
	),
	'deinstall' => array(
		'menu' => 'deinstall_menu'
	)
);
