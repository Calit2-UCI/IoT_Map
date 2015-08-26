<?php
$module['module'] = 'subscriptions';
$module['install_name'] = 'Users subscriptions management';
$module['install_descr'] = 'Managing user subscriptions';
$module['version'] = '1.06';

$module['files'] = array(
	array('file', 'read', "application/modules/subscriptions/controllers/admin_subscriptions.php"),
	array('file', 'read', "application/modules/subscriptions/helpers/subscriptions_helper.php"),
	array('file', 'read', "application/modules/subscriptions/install/module.php"),
	array('file', 'read', "application/modules/subscriptions/install/permissions.php"),
	array('file', 'read', "application/modules/subscriptions/install/settings.php"),
	array('file', 'read', "application/modules/subscriptions/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/subscriptions/install/structure_install.sql"),
	array('file', 'read', "application/modules/subscriptions/models/subscriptions_install_model.php"),
	array('file', 'read', "application/modules/subscriptions/models/subscriptions_model.php"),
	array('file', 'read', "application/modules/subscriptions/models/subscriptions_types_model.php"),
	array('file', 'read', "application/modules/subscriptions/models/subscriptions_users_model.php"),
	array('file', 'read', "application/modules/subscriptions/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/admin/list.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/admin/start_subscribe.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/default/helper_form_account.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/default/helper_form_profile.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/default/helper_form_register.tpl"),
	array('file', 'read', "application/modules/subscriptions/views/default/user_subscription_list.tpl"),
	array('dir', 'read', 'application/modules/subscriptions/langs'),
);

$module['dependencies'] = array(
	'start'			=> array('version'=>'1.03'),
	'menu'			=> array('version'=>'2.03'),
	'notifications' => array('version'=>'1.04'),
	'users'			=> array('version'=>'3.01'),
	'cronjob'		=> array('version'=>'1.04')
);

$module['linked_modules'] = array(
	'install'	=> array(
		'menu'		=> 'install_menu',
		'cronjob'	=> 'install_cronjob'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'cronjob'	=> 'deinstall_cronjob'
	)
);
