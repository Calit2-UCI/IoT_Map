<?php
$module['module'] = 'seo';
$module['install_name'] = 'SEO settings';
$module['install_descr'] = 'Basic SEO tools including title, keywords, description meta tags and Open Graph tags';
$module['version'] = '2.01';
$module['files'] = array(
	array('file', 'read', "application/modules/seo/controllers/admin_seo.php"),
	array('file', 'read', "application/modules/seo/install/module.php"),
	array('file', 'read', "application/modules/seo/install/permissions.php"),
	array('file', 'read', "application/modules/seo/install/settings.php"),
	array('file', 'read', "application/modules/seo/models/seo_install_model.php"),
	array('file', 'read', "application/modules/seo/models/seo_model.php"),
	array('file', 'read', "application/modules/seo/views/admin/default_edit_form.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/default_list.tpl"),
	array('dir', 'read', 'application/modules/seo/langs'),
	array('file', 'write', "application/config/seo_module_routes.php"),
	array('file', 'write', "application/config/seo_module_routes.xml"),
	array('file', 'write', "application/config/langs_route.php"),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.01'),
	'menu' => array('version'=>'1.01')
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'moderators'	=> 'install_moderators',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'moderators'	=> 'deinstall_moderators',
	)
);
