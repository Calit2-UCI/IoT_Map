<?php
$module['module'] = 'uploads';
$module['install_name'] = 'Uploads settings management';
$module['install_descr'] = 'This module lets you manage the types and sizes of uploaded files';
$module['version'] = '1.08';
$module['files'] = array(
	array('file', 'read', "application/modules/uploads/controllers/admin_uploads.php"),
	array('file', 'read', "application/modules/uploads/install/module.php"),
	array('file', 'read', "application/modules/uploads/install/permissions.php"),
	array('file', 'read', "application/modules/uploads/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/uploads/install/structure_install.sql"),
	array('file', 'read', "application/modules/uploads/js/ajaxfileupload.min.js"),
	array('file', 'read', "application/modules/uploads/js/colorpicker.min.js"),
	array('file', 'read', "application/modules/uploads/models/uploads_config_model.php"),
	array('file', 'read', "application/modules/uploads/models/uploads_install_model.php"),
	array('file', 'read', "application/modules/uploads/models/uploads_model.php"),
	array('file', 'read', "application/modules/uploads/views/admin/edit_settings.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/edit_thumb.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/edit_watermark.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/link_watermarks.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/list_settings.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/list_thumbs.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/list_watermarks.tpl"),
	array('file', 'read', "application/modules/uploads/views/admin/css/style.css"),
	array('dir', 'read', 'application/modules/uploads/langs'),
	array('dir', 'write', "uploads"),
	array('dir', 'write', "uploads/watermark"),
	array('file', 'write', "uploads/watermark/wm_image-wm.png"),
	array('dir', 'write', "uploads/default"),
	array('file', 'write', "uploads/default/watermark_test.jpg"),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03')
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu'
	)
);
