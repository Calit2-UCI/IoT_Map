<?php
$module['module'] = 'video_uploads';
$module['install_name'] = 'Video settings management';
$module['install_descr'] = 'Managing parameters of video uploads';
$module['version'] = '1.06';
$module['files'] = array(
	array('file', 'read', "application/modules/video_uploads/controllers/admin_video_uploads.php"),
	array('file', 'read', "application/modules/video_uploads/controllers/video_uploads.php"),
	array('file', 'read', "application/modules/video_uploads/install/module.php"),
	array('file', 'read', "application/modules/video_uploads/install/permissions.php"),
	array('file', 'read', "application/modules/video_uploads/install/settings.php"),
	array('file', 'read', "application/modules/video_uploads/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/video_uploads/install/structure_install.sql"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_config_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_install_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_local_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_process_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_settings_model.php"),
	array('file', 'read', "application/modules/video_uploads/models/video_uploads_youtube_model.php"),
	array('file', 'read', "application/modules/video_uploads/views/admin/edit_settings.tpl"),
	array('file', 'read', "application/modules/video_uploads/views/admin/link_system_settings.tpl"),
	array('file', 'read', "application/modules/video_uploads/views/admin/list_settings.tpl"),
	array('file', 'read', "application/modules/video_uploads/views/admin/system_settings.tpl"),
	array('file', 'read', "application/modules/video_uploads/views/admin/youtube_settings.tpl"),
	array('file', 'read', "application/modules/video_uploads/views/default/embed_test.tpl"),
	array('dir', 'read', 'application/modules/video_uploads/langs'),
	array('dir', 'write', "uploads"),
	array('dir', 'write', "uploads/video-default"),
	array('dir', 'write', "uploads/video"),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'cronjob' => array('version' => '1.04')
);

$module['libraries'] = array(
	'Zend', 'VideoEmbed'
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'video_uploads'	=> 'install_video_uploads',
		'cronjob'		=> 'install_cronjob',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'cronjob'		=> 'deinstall_cronjob',
	)
);
