<?php
$module['module'] = 'dynamic_blocks';
$module['install_name'] = 'Dynamic blocks';
$module['install_descr'] = 'Dynamic blocks editor for various content types for the site index page';
$module['version'] = '3.01';
$module['files'] = array(
	array('file', 'read', "application/modules/dynamic_blocks/controllers/admin_dynamic_blocks.php"),
	array('file', 'read', "application/modules/dynamic_blocks/helpers/dynamic_blocks_helper.php"),
	array('file', 'read', "application/modules/dynamic_blocks/install/module.php"),
	array('file', 'read', "application/modules/dynamic_blocks/install/permissions.php"),
	array('file', 'read', "application/modules/dynamic_blocks/install/settings.php"),
	array('file', 'read', "application/modules/dynamic_blocks/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/dynamic_blocks/install/structure_install.sql"),
	array('file', 'read', "application/modules/dynamic_blocks/js/dynamic_blocks_layout.js"),
	array('file', 'read', "application/modules/dynamic_blocks/models/dynamic_blocks_install_model.php"),
	array('file', 'read', "application/modules/dynamic_blocks/models/dynamic_blocks_model.php"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/edit_block_form.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/edit_block_list.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/layout.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/link_index.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/list_blocks.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/admin/list.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/default/dynamic_block_rich_text.tpl"),
	array('file', 'read', "application/modules/dynamic_blocks/views/default/helper_html_block.tpl"),
	array('dir', 'read', 'application/modules/dynamic_blocks/langs'),
	array('dir', 'write', "temp/dynamic_blocks/"),
	array('dir', 'write', "uploads/wysiwyg/dynamic_blocks"),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'moderators'	=> 'install_moderators'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'moderators'	=> 'deinstall_moderators'
	)
);
