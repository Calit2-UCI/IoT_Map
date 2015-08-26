<?php

$module["module"] = "memberships";
$module["install_name"] = "Memberships";
$module["install_descr"] = "Memberships management";
$module["version"] = "1.01";
$module["files"] = array(
	array('file', 'read', "application/modules/memberships/controllers/admin_memberships.php"),
	array('file', 'read', "application/modules/memberships/controllers/api_memberships.php"),
	array('file', 'read', "application/modules/memberships/controllers/memberships.php"),
	array('file', 'read', "application/modules/memberships/helpers/memberships_helper.php"),
	array('file', 'read', "application/modules/memberships/install/module.php"),
	array('file', 'read', "application/modules/memberships/install/permissions.php"),
	array('file', 'read', "application/modules/memberships/install/settings.php"),
	array('file', 'read', "application/modules/memberships/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/memberships/install/structure_install.sql"),
	array('file', 'read', "application/modules/memberships/models/memberships_install_model.php"),
	array('file', 'read', "application/modules/memberships/models/memberships_model.php"),
	array("dir", "read", "application/modules/memberships/langs"),	
);

$module["dependencies"] = array(
	"start" => array("version"=>"1.06"),
	"menu" => array("version"=>"2.05"),
	"services" => array("version"=>"2.03"),
    "users_payments" => array("version" => "1.04"),
);

$module["linked_modules"] = array(
	"install" => array(
		"menu"			=> "install_menu",
		"ausers"		=> "install_ausers",
		"payments"		=> "install_payments",
		"cronjob"		=> "install_cronjob",
	),
	"deinstall" => array(
		"menu"			=> "deinstall_menu",
		"ausers"		=> "deinstall_ausers",
		"payments"		=> "deinstall_payments",
		"cronjob"		=> "deinstall_cronjob",
        "services"		=> "deinstall_services",
	),
);

