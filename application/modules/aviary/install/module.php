<?php

$module["module"] = "aviary";
$module["install_name"] = "Aviary";
$module["install_descr"] = "Aviary integration";
$module["version"] = "1.02";
$module["files"] = array(
	array('file', 'read', "application/modules/aviary/controllers/admin_aviary.php"),
	array('file', 'read', "application/modules/aviary/controllers/aviary.php"),
	array('file', 'read', "application/modules/aviary/helpers/aviary_helper.php"),
	array('file', 'read', "application/modules/aviary/install/module.php"),
	array('file', 'read', "application/modules/aviary/install/permissions.php"),
	array('file', 'read', "application/modules/aviary/install/settings.php"),
	array('file', 'read', "application/modules/aviary/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/aviary/install/structure_install.sql"),
	array('file', 'read', "application/modules/aviary/models/aviary_install_model.php"),
	array('file', 'read', "application/modules/aviary/models/aviary_model.php"),
	array('file', 'read', "application/modules/aviary/views/admin/helper_button.tpl"),
	array('file', 'read', "application/modules/aviary/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/aviary/views/default/helper_button.tpl"),
	array("dir", "read", "application/modules/aviary/langs"),	
);

$module["dependencies"] = array(
	"start" => array("version"=>"1.01"),
	"menu" => array("version"=>"1.01"),
);

$module["linked_modules"] = array(
	"install" => array(
		"menu"			=> "install_menu",
		"moderators"		=> "install_moderators",
	),
	"deinstall" => array(
		"menu"			=> "deinstall_menu",
		"moderators"		=> "deinstall_moderators",
	)
);

