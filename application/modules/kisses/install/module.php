<?php
$module['module'] = 'kisses';
$module['install_name'] = 'Kisses module';
$module['install_descr'] = 'This module will let site members exchange kisses. Site admin can upload their own kisses images';
$module['category'] = 'action';
$module['version'] = '1.02';
$module['files'] = array(
	array('file', 'read', "application/modules/kisses/controllers/admin_kisses.php"),
	array('file', 'read', "application/modules/kisses/controllers/kisses.php"),
	
	array('file', 'read', "application/modules/kisses/helpers/kisses_helper.php"),
	
	array('file', 'read', "application/modules/kisses/install/module.php"),
	array('file', 'read', "application/modules/kisses/install/permissions.php"),
	array('file', 'read', "application/modules/kisses/install/settings.php"),
	array('file', 'read', "application/modules/kisses/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/kisses/install/structure_install.sql"),
	
	array('file', 'read', "application/modules/kisses/js/kisses.js"),
	array('file', 'read', "application/modules/kisses/js/kisses_multi_request.js"),
	
	array('file', 'read', "application/modules/kisses/langs/en/menu.php"),
	array('file', 'read', "application/modules/kisses/langs/en/pages.php"),
	
	array('file', 'read', "application/modules/kisses/langs/ru/menu.php"),
	array('file', 'read', "application/modules/kisses/langs/ru/pages.php"),
	
	array('file', 'read', "application/modules/kisses/models/kisses_install_model.php"),
	array('file', 'read', "application/modules/kisses/models/kisses_model.php"),
	
	array('file', 'read', "application/modules/kisses/views/admin/ajax_delete_select_block.tpl"),
	array('file', 'read', "application/modules/kisses/views/admin/form_add.tpl"),
	array('file', 'read', "application/modules/kisses/views/admin/form_edit.tpl"),
	array('file', 'read', "application/modules/kisses/views/admin/index.tpl"),
	array('file', 'read', "application/modules/kisses/views/admin/settings.tpl"),
	
	array('file', 'read', "application/modules/kisses/views/default/helper_kisses_btn.tpl"),
	array('file', 'read', "application/modules/kisses/views/default/list_kisses.tpl"),
	array('file', 'read', "application/modules/kisses/views/default/helper_new_kisses_header.tpl"),
	
	array('dir', 'write', "uploads/kisses-file"),
);

$module['demo_content'] = array(
	'reinstall' => false, // install demo content on module reinstall
);

/*$module['dependencies'] = array(
	'start' => array('version' => '1.04'),
	'menu' => array('version' => '2.03'),
	'users' => array('version' => '3.02'),
);*/

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'moderators'	=> 'install_moderators',
		'uploads'	=> 'install_uploads',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'moderators'	=> 'deinstall_moderators',
		'uploads'	=> 'deinstall_uploads',
	)
);
