<?php

$module["module"] = "cookie_policy";
$module["install_name"] = "Cookie policy";
$module["install_descr"] = "Cookie policy";
$module["version"] = "1.01";
$module["files"] = array(
	array("file", "read", "application/modules/cookie_policy/helpers/cookie_policy_helper.php"),
	array("file", "read", "application/modules/cookie_policy/install/module.php"),
	array("file", "read", "application/modules/cookie_policy/install/settings.php"),
	array("file", "read", "application/modules/cookie_policy/js/cookie_policy.js"),
	array("file", "read", "application/modules/cookie_policy/models/cookie_policy_install_model.php"),
	array("file", "read", "application/modules/cookie_policy/views/default/helper_cookie_policy.tpl"),
	array("dir", "read", "application/modules/cookie_policy/langs"),
);

$module["dependencies"] = array(

);

$module["linked_modules"] = array(
	"install" => array(
		"content"		=> "install_content",
	),
	"deinstall" => array(
		"content"			=> "deinstall_content",
	),
);

