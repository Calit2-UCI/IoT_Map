<?php
$module['module'] = 'start';
$module['install_name'] = 'User and Admin index pages';
$module['install_descr'] = 'Index pages for administrator and user area';
$module['version'] = '1.07';
$module['files'] = array(
	array('file', 'read', "application/modules/start/helpers/start_helper.php"),
	array('file', 'read', "application/modules/start/controllers/start.php"),
	array('file', 'read', "application/modules/start/controllers/admin_start.php"),
	array('file', 'read', "application/modules/start/install/module.php"),
	array('file', 'read', "application/modules/start/install/permissions.php"),
	array('file', 'read', "application/modules/start/install/settings.php"),
	array('file', 'read', "application/modules/start/js/admin-banners.js"),
	array('file', 'read', "application/modules/start/js/admin_lang_inline_editor.js"),
	array('file', 'read', "application/modules/start/js/checkbox.js"),
	array('file', 'read', "application/modules/start/js/date_formats.js"),
	array('file', 'read', "application/modules/start/js/hlbox.js"),
	array('file', 'read', "application/modules/start/js/lang_inline_editor.js"),
	array('file', 'read', "application/modules/start/js/search.js"),
	array('file', 'read', "application/modules/start/js/selectbox.js"),
	array('file', 'read', "application/modules/start/js/start_multi_request.js"),
	array('file', 'read', "application/modules/start/models/start_install_model.php"),
	array('file', 'read', "application/modules/start/models/start_model.php"),
	array('file', 'read', "application/modules/start/views/admin/css/style.css"),
	array('file', 'read', "application/modules/start/views/admin/date_formats.tpl"),
	array('file', 'read', "application/modules/start/views/admin/error.tpl"),
	array('file', 'read', "application/modules/start/views/admin/helper_lang_inline_editor.tpl"),
	array('file', 'read', "application/modules/start/views/admin/helper_lang_inline_editor_js.tpl"),
	array('file', 'read', "application/modules/start/views/admin/index.tpl"),
	array('file', 'read', "application/modules/start/views/admin/index_moderator.tpl"),
	array('file', 'read', "application/modules/start/views/admin/install_settings_form.tpl"),
	array('file', 'read', "application/modules/start/views/admin/menu_list.tpl"),
	array('file', 'read', "application/modules/start/views/admin/modules_login.tpl"),
	array('file', 'read', "application/modules/start/views/admin/numerics_list.tpl"),
	array('file', 'read', "application/modules/start/views/admin/numerics_menu.tpl"),
	array('file', 'read', "application/modules/start/views/default/available_browsers.tpl"),
	array('file', 'read', "application/modules/start/views/default/error.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_checkbox.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_hlbox.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_lang_inline_editor.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_lang_inline_editor_js.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_pagination.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_search_form.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_selectbox.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_slider.tpl"),
	array('file', 'read', "application/modules/start/views/default/helper_sorter.tpl"),
	array('file', 'read', "application/modules/start/views/default/homepage.tpl"),
	array('file', 'read', "application/modules/start/views/default/index.tpl"),
	array('file', 'read', "application/modules/start/views/default/left_panel.tpl"),
	array('file', 'read', "application/modules/start/views/default/stat_block.tpl"),
	array('file', 'read', "application/modules/start/views/default/test_file_upload.tpl"),

	array('dir', 'read', 'application/modules/start/langs'),

	array('dir', 'write', "temp/"),
	array('dir', 'write', "temp/cache/"),
	array('dir', 'write', "temp/captcha/"),
	array('dir', 'write', "temp/logs/"),
	array('dir', 'write', "temp/rss/"),
	array('dir', 'write', "temp/templates_c/"),
	array('dir', 'write', "temp/trash/"),

	array('dir', 'write', "application/libraries/dompdf/lib/fonts/"),

	array('dir', 'write', "application/views/default/logo"),
	array('dir', 'write', "application/views/default/sets"),
	array('dir', 'write', "application/views/default/sets/lavender"),
	array('dir', 'write', "application/views/default/sets/lavender/css"),
	array('dir', 'write', "application/views/default/sets/lavender/img"),
	array('dir', 'write', "application/views/default/sets/mediumturquoise"),
	array('dir', 'write', "application/views/default/sets/mediumturquoise/css"),
	array('dir', 'write', "application/views/default/sets/mediumturquoise/img"),

	array('dir', 'write', "application/views/admin/logo"),
	array('dir', 'write', "application/views/admin/sets"),
	array('dir', 'write', "application/views/admin/sets/default"),
	array('dir', 'write', "application/views/admin/sets/default/css"),
	array('dir', 'write', "application/views/admin/sets/default/img"),
	
	array('dir', 'write', "uploads/wysiwyg"),
);

$module['dependencies'] = array(
	'menu' => array('version'=>'2.03'),
);

$module['libraries'] = array(
	'dompdf'
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'				=> 'install_menu',
		'banners'			=> 'install_banners',
		'dynamic_blocks'	=> 'install_dynamic_blocks'
	),
	'deinstall' => array(
		'menu'				=> 'deinstall_menu',
		'banners'			=> 'deinstall_banners',
		'dynamic_blocks'	=> 'deinstall_dynamic_blocks'
	)
);
