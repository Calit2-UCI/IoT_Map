<?php
$module['module'] = 'shoutbox';
$module['install_name'] = 'Shoutbox module';
$module['install_descr'] = 'The module installs ShoutBox on the site ';
$module['version'] = '1.04';
$module['files'] = array(
	array('file', 'read', "application/modules/shoutbox/controllers/admin_shoutbox.php"),
	array('file', 'read', "application/modules/shoutbox/controllers/api_shoutbox.php"),
	array('file', 'read', "application/modules/shoutbox/controllers/class_shoutbox.php"),
	array('file', 'read', "application/modules/shoutbox/controllers/shoutbox.php"),
	array('file', 'read', "application/modules/shoutbox/helpers/shoutbox_helper.php"),
	array('file', 'read', "application/modules/shoutbox/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/shoutbox/install/module.php"),
	array('file', 'read', "application/modules/shoutbox/install/permissions.php"),
	array('file', 'read', "application/modules/shoutbox/install/settings.php"),
	array('file', 'read', "application/modules/shoutbox/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/shoutbox/install/structure_install.sql"),
	array('file', 'read', "application/modules/shoutbox/js/shoutbox.js"),
	array('file', 'read', "application/modules/shoutbox/models/shoutbox_install_model.php"),
	array('file', 'read', "application/modules/shoutbox/models/shoutbox_model.php"),
	array('file', 'read', "application/modules/shoutbox/views/admin/index.tpl"),
	array('file', 'read', "application/modules/shoutbox/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/shoutbox/views/default/helper_shoutbox.tpl"),
	array('dir', 'read', 'application/modules/shoutbox/langs'),
);

$module['demo_content'] = array(
	'reinstall' => false, // install demo content on module reinstall
);

$module['dependencies'] = array(
	'start'			=> array('version'=>'1.03'),
	'menu'			=> array('version'=>'1.01'),
	'moderation'	=> array('version'=>'1.01'),
	'users'	=> array('version'=>'1.01')
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'moderation'	=> 'install_moderation',
		'cronjob'		=> 'install_cronjob',
		"moderators"		=> "install_moderators",
		'users'			=> 'install_users',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'moderation'	=> 'deinstall_moderation',
		'cronjob'		=> 'deinstall_cronjob',
		"moderators"		=> "deinstall_moderators",
		'users'			=> 'deinstall_users',
	)
);
