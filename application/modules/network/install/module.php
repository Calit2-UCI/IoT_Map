<?php
$module['module'] = 'network';
$module['install_name'] = 'Network module';
$module['install_descr'] = '';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/network/client/actions/abstract_action.php"),
	array('file', 'read', "application/modules/network/client/actions/get_profiles_action.php"),
	array('file', 'read', "application/modules/network/client/actions/get_profiles_status_action.php"),
	array('file', 'read', "application/modules/network/client/actions/get_removed_action.php"),
	array('file', 'read', "application/modules/network/client/actions/get_updated_action.php"),
	array('file', 'read', "application/modules/network/client/actions/put_profiles_action.php"),
	array('file', 'read', "application/modules/network/client/actions/put_profiles_status_action.php"),
	array('file', 'read', "application/modules/network/client/actions/put_removed_action.php"),
	array('file', 'read', "application/modules/network/client/actions/put_removed_status_action.php"),
	array('file', 'read', "application/modules/network/client/actions/put_updated_action.php"),
	array('file', 'read', "application/modules/network/client/actions/settings_action.php"),
	array('file', 'read', "application/modules/network/client/configs/settings.php"),
	array('file', 'read', "application/modules/network/client/libs/ElephantIO/Client.php"),
	array('file', 'read', "application/modules/network/client/libs/ElephantIO/Payload.php"),
	array('file', 'read', "application/modules/network/client/libs/api.php"),
	array('file', 'read', "application/modules/network/client/libs/daemon.php"),
	array('file', 'read', "application/modules/network/client/libs/loader.php"),
	array('file', 'read', "application/modules/network/client/libs/local.php"),
	array('dir', 'write', "application/modules/network/client/logs"),
	array('file', 'read', "application/modules/network/client/fast-client-service.php"),
	array('file', 'read', "application/modules/network/client/slow-client-service.sh"),
	array('file', 'read', "application/modules/network/client/slow-client.php"),
	array('file', 'read', "application/modules/network/client/slow-client.start"),
	array('file', 'read', "application/modules/network/client/slow-client.stop"),
	array('file', 'read', "application/modules/network/client/slow-client.test"),
	array('file', 'read', "application/modules/network/controllers/admin_network.php"),
	array('file', 'read', "application/modules/network/install/module.php"),
	array('file', 'read', "application/modules/network/install/permissions.php"),
	array('file', 'read', "application/modules/network/install/settings.php"),
	array('file', 'read', "application/modules/network/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/network/install/structure_install.sql"),
	array('file', 'read', "application/modules/network/install/user_fields_data.php"),
	array('file', 'read', "application/modules/network/js/admin-network.js"),
	array('dir', 'read', "application/modules/network/langs"),
	array('file', 'read', "application/modules/network/models/network_actions_model.php"),
	array('file', 'read', "application/modules/network/models/network_events_model.php"),
	array('file', 'read', "application/modules/network/models/network_install_model.php"),
	array('file', 'read', "application/modules/network/models/network_model.php"),
	array('file', 'read', "application/modules/network/models/network_users_model.php"),
	array('file', 'read', "application/modules/network/views/admin/index.tpl"),
	array('file', 'read', "application/modules/network/views/admin/settings-form.tpl"),
    array('file', 'read', "application/modules/network/views/default/register.tpl"),
	array('dir', 'write', "temp/network/events"),
);

$module['dependencies'] = array(
	'start' => array('version' => '1.05'),
	'menu' => array('version' => '2.04'),
	'field_editor' => array('version' => '2.02'),
	'users' => array('version' => '3.03'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'field_editor'	=> 'install_field_editor',
		'cronjob'		=> 'install_cronjob',
		//'users'			=> 'install_users',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'field_editor'	=> 'deinstall_field_editor',
		'cronjob'		=> 'deinstall_cronjob',
		//'users'			=> 'deinstall_users',
	),
);
