<?php
$module['module'] = 'languages';
$module['install_name'] = 'Languages';
$module['install_descr'] = 'Editing language files (words and phrases that are used on the site), adding new language versions, doing translations';
$module['version'] = '2.05';
$module['files'] = array(
	array('file', 'read', "application/modules/languages/controllers/api_languages.php"),
	array('file', 'read', "application/modules/languages/controllers/admin_languages.php"),
	array('file', 'read', "application/modules/languages/controllers/languages.php"),
	array('file', 'read', "application/modules/languages/js/lang-edit.js"),
	array('file', 'read', "application/modules/languages/install/module.php"),
	array('file', 'read', "application/modules/languages/install/permissions.php"),
	array('file', 'read', "application/modules/languages/models/languages_install_model.php"),
	array('file', 'read', "application/modules/languages/models/languages_model.php"),
	array('file', 'read', "application/modules/languages/views/admin/css/style.css"),
	array('file', 'read', "application/modules/languages/views/admin/edit_ds.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/edit_ds_item.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/edit_lang.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/edit_page.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/items_ds.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/link_pages.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/list_ds.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/list_langs.tpl"),
	array('file', 'read', "application/modules/languages/views/admin/list_pages.tpl"),
	array('file', 'read', "application/modules/languages/views/default/helper_lang_select.tpl"),
	array('file', 'read', "application/modules/languages/views/default/lang_editor.tpl"),
	array('file', 'read', "application/modules/languages/helpers/languages_helper.php"),
	array('dir', 'read', 'application/modules/languages/langs'),
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
