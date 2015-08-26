<?php
$module['module'] = 'cronjob';
$module['install_name'] = 'Cronjob module';
$module['install_descr'] = 'This module lets you set up and manage cron jobs';
$module['version'] = '1.06';
$module['files'] = array(
	array('file', 'read', "application/modules/cronjob/controllers/admin_cronjob.php"),
	array('file', 'read', "application/modules/cronjob/controllers/cronjob.php"),
	array('file', 'read', "application/modules/cronjob/install/messages.php"),
	array('file', 'read', "application/modules/cronjob/install/module.php"),
	array('file', 'read', "application/modules/cronjob/install/permissions.php"),
	array('file', 'read', "application/modules/cronjob/install/settings.php"),
	array('file', 'read', "application/modules/cronjob/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/cronjob/install/structure_install.sql"),
	array('file', 'read', "application/modules/cronjob/models/cronjob_model.php"),
	array('file', 'read', "application/modules/cronjob/models/cronjob_install_model.php"),
	array('file', 'read', "application/modules/cronjob/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/cronjob/views/admin/link_index.tpl"),
	array('file', 'read', "application/modules/cronjob/views/admin/list.tpl"),
	array('file', 'read', "application/modules/cronjob/views/admin/list_log.tpl"),
	array('dir', 'read', 'application/modules/cronjob/langs'),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
);
$module['libraries'] = array(
	'Cronparser'
);
$module['linked_modules'] = array(
	'install' => array(
		'menu' => 'install_menu'
	),
	'deinstall' => array(
		'menu' => 'deinstall_menu'
	)
);
