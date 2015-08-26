<?php
$module['module'] = 'banners';
$module['install_name'] = 'Banners module';
$module['install_descr'] = 'Banners management, including prices, banner positions on pages';
$module['version'] = '1.08';
$module['files'] = array(
	array('file', 'read', "application/modules/banners/controllers/api_banners.php"),
	array('file', 'read', "application/modules/banners/controllers/admin_banners.php"),
	array('file', 'read', "application/modules/banners/controllers/api_banners.php"),
	array('file', 'read', "application/modules/banners/controllers/banners.php"),
	array('file', 'read', "application/modules/banners/helpers/banners_helper.php"),
	array('file', 'read', "application/modules/banners/install/module.php"),
	array('file', 'read', "application/modules/banners/install/permissions.php"),
	array('file', 'read', "application/modules/banners/install/settings.php"),
	array('file', 'read', "application/modules/banners/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/banners/install/structure_install.sql"),
	array('file', 'read', "application/modules/banners/js/admin_banner.js"),
	array('file', 'read', "application/modules/banners/js/banner-activate.js"),
	array('file', 'read', "application/modules/banners/js/banners.js"),
	array('file', 'read', "application/modules/banners/models/banner_group_model.php"),
	array('file', 'read', "application/modules/banners/models/banner_place_model.php"),
	array('file', 'read', "application/modules/banners/models/banners_install_model.php"),
	array('file', 'read', "application/modules/banners/models/banners_model.php"),
	array('file', 'read', "application/modules/banners/models/banners_stat_model.php"),
	array('file', 'read', "application/modules/banners/views/admin/css/style.css"),
	array('file', 'read', "application/modules/banners/views/admin/ajax_banner_groups.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/ajax_banner_html_form.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/ajax_banner_image_form.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/ajax_module_pages.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/form_banner.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/form_group.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/form_place.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/helper_admin_home_block.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/list_banners.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/list_group_pages.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/list_groups.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/list_places.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/preview.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/settings.tpl"),
	array('file', 'read', "application/modules/banners/views/admin/statistic.tpl"),
	array('file', 'read', "application/modules/banners/views/default/my_activate.tpl"),
	array('file', 'read', "application/modules/banners/views/default/my_form.tpl"),
	array('file', 'read', "application/modules/banners/views/default/my_list.tpl"),
	array('file', 'read', "application/modules/banners/views/default/my_list_block.tpl"),
	array('file', 'read', "application/modules/banners/views/default/my_statistic.tpl"),
	array('file', 'read', "application/modules/banners/views/default/show_banner_place.tpl"),
	array('file', 'read', "application/modules/banners/views/default/show_banner_setup.tpl"),
	array('dir', 'read', "application/modules/banners/langs"),
	array('dir', 'write', "uploads/banner")
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'moderation'	=> array('version'=>'1.01'),
	'uploads' => array('version'=>'1.03'),
	'notifications' => array('version'=>'1.03'),
	'cronjob' => array('version'=>'1.04'),
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'				=> 'install_menu',
		'moderation'		=> 'install_moderation',
		'uploads'			=> 'install_uploads',
		'services'			=> 'install_services',
		'cronjob'			=> 'install_cronjob',
		'moderators'			=> 'install_moderators',
		'notifications'		=> 'install_notifications',
	),
	'deinstall' => array(
		'menu'				=> 'deinstall_menu',
		'moderation'		=> 'deinstall_moderation',
		'uploads'			=> 'deinstall_uploads',
		'services'			=> 'deinstall_services',
		'cronjob'			=> 'deinstall_cronjob',
		'moderators'			=> 'deinstall_moderators',
		'notifications'		=> 'deinstall_notifications',
	)
);
