<?php
$module['module'] = 'get_token';
$module['install_name'] = 'Product Api';
$module['install_descr'] = 'This module lets you access tokens and use API methods';
$module['version'] = '1.03';

$module['files'] = array(
	array('file', 'read', "application/helpers/api_helper.php"),
	array('file', 'read', "application/modules/get_token/controllers/api_get_token.php"),
	array('file', 'read', "application/modules/get_token/install/module.php"),
	array('file', 'read', "application/modules/get_token/install/permissions.php"),
	array('file', 'read', "application/modules/get_token/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/get_token/install/structure_install.sql"),
	array('file', 'read', "application/modules/get_token/models/get_token_install_model.php"),
);

$module['dependencies'] = array(
	'users' => array('version'=>'3.01')
);

$module['libraries'] = array(
	'Array2XML'
);

$module['linked_modules'] = array(
	
);
