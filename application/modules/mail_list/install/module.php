<?php
$module['module'] = 'mail_list';
$module['install_name'] = 'Mailing lists management';
$module['install_descr'] = 'Manage mailing lists for users';
$module['version'] = '1.05';

$module['files'] = array(
	array('file', 'read', "application/modules/mail_list/js/admin-mail-list.js"),
	array('file', 'read', "application/modules/mail_list/controllers/admin_mail_list.php"),
	array('file', 'read', "application/modules/mail_list/install/module.php"),
	array('file', 'read', "application/modules/mail_list/install/permissions.php"),
	array('file', 'read', "application/modules/mail_list/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/mail_list/install/structure_install.sql"),
	array('file', 'read', "application/modules/mail_list/models/mail_list_install_model.php"),
	array('file', 'read', "application/modules/mail_list/models/mail_list_model.php"),
	array('file', 'read', "application/modules/mail_list/views/admin/filters.tpl"),
	array('file', 'read', "application/modules/mail_list/views/admin/users.tpl"),
	array('file', 'read', "application/modules/mail_list/views/admin/users_form.tpl"),
	array('file', 'read', "application/modules/mail_list/views/admin/css/style.css"),
	array('dir', 'read', 'application/modules/mail_list/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'users' => array('version'=>'3.01'),
	'subscriptions' => array('version'=>'1.03'),
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'ausers'	=> 'install_ausers'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu'
	)
);
