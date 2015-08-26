<?php
$module['module'] = 'moderators';
$module['install_name'] = 'Moderators management';
$module['install_descr'] = 'This module lets you create, edit and delete moderators accounts';
$module['version'] = '1.01';
$module['files'] = array(
	array('file', 'read', "application/hooks/autoload/post_controller_constructor-check_moderator_access.php"),
	array('file', 'read', "application/modules/moderators/controllers/admin_moderators.php"),
	array('file', 'read', "application/modules/moderators/helpers/moderators_helper.php"),
	array('file', 'read', "application/modules/moderators/install/module.php"),
	array('file', 'read', "application/modules/moderators/install/permissions.php"),
	array('file', 'read', "application/modules/moderators/install/settings.php"),
	array('file', 'read', "application/modules/moderators/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/moderators/install/structure_install.sql"),
	array('file', 'read', "application/modules/moderators/models/moderators_install_model.php"),
	array('file', 'read', "application/modules/moderators/models/moderators_model.php"),
	array('file', 'read', "application/modules/moderators/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/moderators/views/admin/helper_add_button.tpl"),
	array('file', 'read', "application/modules/moderators/views/admin/helper_count.tpl"),
	array('file', 'read', "application/modules/moderators/views/admin/link_edit.tpl"),
	array('file', 'read', "application/modules/moderators/views/admin/list.tpl"),
	array('dir', 'read', "application/modules/moderators/langs")
);
$module['dependencies'] = array(
	'ausers' => array('version'=>'2.03'),
);
