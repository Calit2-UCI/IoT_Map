<?php

$module['module'] = 'social_networking';
$module['install_name'] = 'Social networking';
$module['install_descr'] = 'Authorization with social media accounts, social media widgets';
$module['version'] = '1.06';
$module['files'] = array(
	array('file', 'read', "application/modules/social_networking/controllers/admin_social_networking.php"),
	array('file', 'read', "application/modules/social_networking/helpers/social_networking_helper.php"),
	array('file', 'read', "application/modules/social_networking/install/module.php"),
	array('file', 'read', "application/modules/social_networking/install/permissions.php"),
	array('file', 'read', "application/modules/social_networking/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/social_networking/install/structure_install.sql"),
	array('file', 'read', "application/modules/social_networking/models/social_networking_install_model.php"),
	array('file', 'read', "application/modules/social_networking/models/social_networking_services_model.php"),
	array('file', 'read', "application/modules/social_networking/models/social_networking_connections_model.php"),
	array('file', 'read', "application/modules/social_networking/models/social_networking_pages_model.php"),
	array('file', 'read', "application/modules/social_networking/models/social_networking_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/models/services/facebook_service_model.php"),
	array('file', 'read', "application/modules/social_networking/models/services/google_service_model.php"),
	array('file', 'read', "application/modules/social_networking/models/services/twitter_service_model.php"),
	array('file', 'read', "application/modules/social_networking/models/services/vkontakte_service_model.php"),
	array('file', 'read', "application/modules/social_networking/models/widgets/facebook_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/models/widgets/google_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/models/widgets/linkedin_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/models/widgets/twitter_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/models/widgets/vkontakte_widgets_model.php"),
	array('file', 'read', "application/modules/social_networking/views/admin/edit_application.tpl"),
	array('file', 'read', "application/modules/social_networking/views/admin/edit_service.tpl"),
	array('file', 'read', "application/modules/social_networking/views/admin/edit_widgets.tpl"),
	array('file', 'read', "application/modules/social_networking/views/admin/list_pages.tpl"),
	array('file', 'read', "application/modules/social_networking/views/admin/list_services.tpl"),
	array('file', 'read', "application/modules/social_networking/views/default/comments.tpl"),
	array('file', 'read', "application/modules/social_networking/views/default/like.tpl"),
	array('file', 'read', "application/modules/social_networking/views/default/share.tpl"),
	array('dir', 'read', 'application/modules/social_networking/langs'),
);
$module['dependencies'] = array(
	'start' => array('version' => '1.03'),
	'menu' => array('version' => '2.03'),
	'cronjob' => array('version' => '1.04')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu'
	)
);
