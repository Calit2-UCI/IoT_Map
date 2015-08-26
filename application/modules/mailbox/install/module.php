<?php
$module['module'] = 'mailbox';
$module['install_name'] = 'Mailbox';
$module['install_descr'] = 'The module installs mailbox on your site';
$module['category'] = 'communication';
$module['version'] = '2.02';
$module['files'] = array(
	array('file', 'read', "application/modules/mailbox/helpers/mailbox_helper.php"),
	array('file', 'read', "application/modules/mailbox/controllers/admin_mailbox.php"),
	array('file', 'read', "application/modules/mailbox/controllers/api_mailbox.php"),
	array('file', 'read', "application/modules/mailbox/controllers/mailbox.php"),
	array('file', 'read', "application/modules/mailbox/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/mailbox/install/module.php"),
	array('file', 'read', "application/modules/mailbox/install/permissions.php"),
	array('file', 'read', "application/modules/mailbox/install/settings.php"),
	array('file', 'read', "application/modules/mailbox/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/mailbox/install/structure_install.sql"),
	array('file', 'read', "application/modules/mailbox/js/mailbox.js"),
	array('file', 'read', "application/modules/mailbox/js/mailbox_multi_request.js"),
	array('file', 'read', "application/modules/mailbox/models/mailbox_model.php"),
	array('file', 'read', "application/modules/mailbox/models/mailbox_install_model.php"),
	array('file', 'read', "application/modules/mailbox/models/mailbox_service_model.php"),
	array('file', 'read', "application/modules/mailbox/views/admin/list.tpl"),
	array('file', 'read', "application/modules/mailbox/views/admin/view.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/ajax_thread.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/edit.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/helper_message_button.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/helper_new_messages_header.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/helper_new_messages_homepage.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/index.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/mailbox_content.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/mailbox_menu.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/mailbox_top_panel.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/send_message.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/view.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/write.tpl"),
	array('file', 'read', "application/modules/mailbox/views/default/write_form.tpl"),

	array('dir', 'write', "uploads/file-uploads/mailbox-attach"),
	array('dir', 'read', 'application/modules/mailbox/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'moderation' => array('version'=>'1.03'),
	'users' => array('version'=>'3.01'),
	'notifications' => array('version'=>'1.04'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'moderation'	=> 'install_moderation',
		'notifications'	=> 'install_notifications',
		'services'		=> 'install_services',
		'site_map'		=> 'install_site_map',
		'file_uploads'	=> 'install_file_uploads',
		'banners'		=> 'install_banners',
		'cronjob'		=> 'install_cronjob',
		'network'		=> 'install_network',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'moderation'	=> 'deinstall_moderation',
		'notifications'	=> 'deinstall_notifications',
		'services'		=> 'deinstall_services',
		'site_map'		=> 'deinstall_site_map',
		'file_uploads'	=> 'deinstall_file_uploads',
		'banners'		=> 'deinstall_banners',
		'cronjob'		=> 'deinstall_cronjob',
		'network'		=> 'deinstall_network',
	)
);
