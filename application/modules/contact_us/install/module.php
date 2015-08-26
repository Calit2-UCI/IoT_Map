<?php
$module['module'] = 'contact_us';
$module['install_name'] = 'Contact module';
$module['install_descr'] = 'Contact us form for users, and special settings in administration panel (editing reasons for contact, email addresses that messages will be forwarded to)';
$module['version'] = '1.05';
$module['files'] = array(
	array('file', 'read', "application/modules/contact_us/controllers/api_contact_us.php"),
	array('file', 'read', "application/modules/contact_us/controllers/admin_contact_us.php"),
	array('file', 'read', "application/modules/contact_us/controllers/contact_us.php"),
	array('file', 'read', "application/modules/contact_us/install/module.php"),
	array('file', 'read', "application/modules/contact_us/install/permissions.php"),
	array('file', 'read', "application/modules/contact_us/install/settings.php"),
	array('file', 'read', "application/modules/contact_us/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/contact_us/install/structure_install.sql"),
	array('file', 'read', "application/modules/contact_us/models/contact_us_install_model.php"),
	array('file', 'read', "application/modules/contact_us/models/contact_us_model.php"),
	array('file', 'read', "application/modules/contact_us/views/admin/edit.tpl"),
	array('file', 'read', "application/modules/contact_us/views/admin/list.tpl"),
	array('file', 'read', "application/modules/contact_us/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/contact_us/views/default/form.tpl"),
	array('file', 'read', "application/modules/contact_us/views/default/link_contact_admin.tpl"),
	array('dir', 'read', "application/modules/contact_us/langs"),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'notifications' => array('version'=>'1.04')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'				=> 'install_menu',
		'moderation'		=> 'install_moderation',
		'banners'			=> 'install_banners',
		'notifications'		=> 'install_notifications',
		'site_map'			=> 'install_site_map',
		'social_networking' => 'install_social_networking'
	),

	'deinstall' => array(
		'menu'				=> 'deinstall_menu',
		'moderation'		=> 'deinstall_moderation',
		'banners'			=> 'deinstall_banners',
		'notifications'		=> 'deinstall_notifications',
		'site_map'			=> 'deinstall_site_map',
		'social_networking' => 'deinstall_social_networking'
	)
);
