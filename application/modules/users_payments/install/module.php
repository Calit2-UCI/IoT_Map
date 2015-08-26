<?php
$module['module'] = 'users_payments';
$module['install_name'] = 'Users payments module';
$module['install_descr'] = 'This module applies the payments module options to users, for example lets site users have money added to their internal accounts';
$module['version'] = '1.05';
$module['files'] = array(
	array('file', 'read', "application/modules/users_payments/helpers/users_payments_helper.php"),
	array('file', 'read', "application/modules/users_payments/controllers/admin_users_payments.php"),
	array('file', 'read', "application/modules/users_payments/controllers/api_users_payments.php"),
	array('file', 'read', "application/modules/users_payments/controllers/users_payments.php"),
	array('file', 'read', "application/modules/users_payments/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/users_payments/install/module.php"),
	array('file', 'read', "application/modules/users_payments/install/permissions.php"),
	array('file', 'read', "application/modules/users_payments/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/users_payments/install/structure_install.sql"),
	array('file', 'read', "application/modules/users_payments/models/users_payments_install_model.php"),
	array('file', 'read', "application/modules/users_payments/models/users_payments_model.php"),
	array('file', 'read', "application/modules/users_payments/views/admin/funds_form.tpl"),
	array('file', 'read', "application/modules/users_payments/views/admin/helper_add_funds.tpl"),
	array('file', 'read', "application/modules/users_payments/views/default/helper_account.tpl"),
	array('file', 'read', "application/modules/users_payments/views/default/helper_update_account.tpl"),
	array('dir', 'read', 'application/modules/users_payments/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'users' => array('version'=>'3.01'),
	'payments' => array('version'=>'2.01'),
	'notifications' => array('version'=>'1.04'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'payments'	=> 'install_payments',
		'notifications'		=> 'install_notifications',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'payments'	=> 'deinstall_payments',
		'notifications'		=> 'deinstall_notifications',
	)
);
