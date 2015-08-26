<?php
$module['module'] = 'linker';
$module['install_name'] = 'Linker module';
$module['install_descr'] = 'This module stores information about links between different objects ';
$module['version'] = '1.02';
$module['files'] = array(
	array('file', 'read', "application/modules/linker/controllers/admin_linker.php"),
	array('file', 'read', "application/modules/linker/install/module.php"),
	array('file', 'read', "application/modules/linker/install/permissions.php"),
	array('file', 'read', "application/modules/linker/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/linker/install/structure_install.sql"),
	array('file', 'read', "application/modules/linker/models/linker_install_model.php"),
	array('file', 'read', "application/modules/linker/models/linker_model.php"),
	array('file', 'read', "application/modules/linker/models/linker_type_model.php"),
);
$module['dependencies'] = array(
);
