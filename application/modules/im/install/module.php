<?php
$module['module'] = 'im';
$module['install_name'] = 'Instant messenger module';
$module['install_descr'] = 'The module installs the one-on-one communication tool on your site';
$module['category'] = 'communication';
$module['version'] = '2.01';
$module['files'] = array(
	array('file', 'read', "application/modules/im/controllers/admin_im.php"),
	array('file', 'read', "application/modules/im/controllers/api_im.php"),
	array('file', 'read', "application/modules/im/controllers/class_im.php"),
	array('file', 'read', "application/modules/im/controllers/im.php"),
	array('file', 'read', "application/modules/im/helpers/im_helper.php"),
	array('file', 'read', "application/modules/im/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/im/install/module.php"),
	array('file', 'read', "application/modules/im/install/permissions.php"),
	array('file', 'read', "application/modules/im/install/settings.php"),
	array('file', 'read', "application/modules/im/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/im/install/structure_install.sql"),
	array('file', 'read', "application/modules/im/js/im.js"),
	array('file', 'read', "application/modules/im/models/im_contact_list_model.php"),
	array('file', 'read', "application/modules/im/models/im_install_model.php"),
	array('file', 'read', "application/modules/im/models/im_messages_model.php"),
	array('file', 'read', "application/modules/im/models/im_model.php"),
	array('file', 'read', "application/modules/im/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/im/views/default/helper_im.tpl"),
	array('file', 'read', "application/modules/im/views/default/helper_im_add.tpl"),
	array('dir', 'read', 'application/modules/im/langs'),
);

$module['dependencies'] = array(
	'moderation'	=> array('version'=>'1.01'),
	'start'			=> array('version'=>'1.03'),
	'users'			=> array('version'=>'3.01'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'moderation'	=> 'install_moderation',
		'users'			=> 'install_users',
		//'users_lists'	=> 'install_users_lists',
		'friendlist'	=> 'install_friendlist',
		'services'		=> 'install_services',
        'network'       => 'install_network',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'moderation'	=> 'deinstall_moderation',
		'users'			=> 'deinstall_users',
		//'users_lists'	=> 'deinstall_users_lists',
		'friendlist'	=> 'deinstall_friendlist',
		'services'		=> 'deinstall_services',
        'network'       => 'deinstall_network',
	)
);
