<?php
$module['module'] = 'wall_events';
$module['install_name'] = 'Wall events module';
$module['install_descr'] = 'The module displays different events on the user walls';
$module['version'] = '1.04';
$module['files'] = array(
	array('file', 'read', "application/modules/wall_events/controllers/admin_wall_events.php"),
	array('file', 'read', "application/modules/wall_events/controllers/api_wall_events.php"),
	array('file', 'read', "application/modules/wall_events/controllers/wall_events.php"),
	array('file', 'read', "application/modules/wall_events/helpers/wall_events_helper.php"),
	array('file', 'read', "application/modules/wall_events/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/wall_events/install/module.php"),
	array('file', 'read', "application/modules/wall_events/install/permissions.php"),
	array('file', 'read', "application/modules/wall_events/install/settings.php"),
	array('file', 'read', "application/modules/wall_events/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/wall_events/install/structure_install.sql"),
	array('file', 'read', "application/modules/wall_events/js/wall.js"),
	array('file', 'read', "application/modules/wall_events/models/wall_events_install_model.php"),
	array('file', 'read', "application/modules/wall_events/models/wall_events_model.php"),
	array('file', 'read', "application/modules/wall_events/models/wall_events_permissions_model.php"),
	array('file', 'read', "application/modules/wall_events/models/wall_events_types_model.php"),
	array('file', 'read', "application/modules/wall_events/views/admin/form_wall_events_type.tpl"),
	array('file', 'read', "application/modules/wall_events/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/wall_events/views/admin/wall_events_types.tpl"),
	array('file', 'read', "application/modules/wall_events/views/default/wall_block.tpl"),
	array('file', 'read', "application/modules/wall_events/views/default/wall_events.tpl"),
	array('file', 'read', "application/modules/wall_events/views/default/wall_events_wall_post.tpl"),
	array('file', 'read', "application/modules/wall_events/views/default/wall_permissions.tpl"),
	array('dir', 'read', 'application/modules/wall_events/langs'),
	array('dir', 'write', "uploads/wall-image"),
	array('dir', 'write', "uploads/video/wall-video"),
);

$module['dependencies'] = array(
	'start'			=> array('version'=>'1.03'),
	'menu'			=> array('version'=>'2.03'),
	'moderation'	=> array('version'=>'1.01'),
	'uploads'		=> array('version'=>'1.03'),
	'video_uploads' => array('version'=>'1.03'),
	'users'			=> array('version'=>'3.01'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'moderation'	=> 'install_moderation',
		'comments'		=> 'install_comments',
		'uploads'		=> 'install_uploads',
		'video_uploads'	=> 'install_video_uploads',
		'spam'			=> 'install_spam',
		'users'			=> 'install_users',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'moderation'	=> 'deinstall_moderation',
		'comments'		=> 'deinstall_comments',
		'uploads'		=> 'deinstall_uploads',
		'video_uploads'	=> 'deinstall_video_uploads',
		'spam'			=> 'deinstall_spam',
		'users'			=> 'deinstall_users',
	)
);
