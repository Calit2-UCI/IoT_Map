<?php

$module['module'] = 'mobile';
$module['install_name'] = 'Mobile module';
$module['install_descr'] = 'Mobile version of the site';
$module['version'] = '1.04';
$module['files'] = array(
    array('file', 'read', "application/hooks/autoload/post_controller_constructor-mobile_detect.php"),
    array('file', 'read', "application/modules/mobile/controllers/admin_mobile.php"),
    array('file', 'read', "application/modules/mobile/controllers/api_mobile.php"),
    array('file', 'read', "application/modules/mobile/controllers/mobile.php"),
    array('file', 'read', "application/modules/mobile/install/module.php"),
    array('file', 'read', "application/modules/mobile/install/permissions.php"),
    array('file', 'read', "application/modules/mobile/install/settings.php"),
    array('file', 'read', "application/modules/mobile/install/structure_deinstall.sql"),
    array('file', 'read', "application/modules/mobile/install/structure_install.sql"),
    array('file', 'read', "application/modules/mobile/models/mobile_model.php"),
    array('file', 'read', "application/modules/mobile/models/mobile_install_model.php"),
    array('file', 'read', "application/modules/mobile/views/admin/index.tpl"),
    array('file', 'write', "m/index.html"),
    array('file', 'write', "m/scripts/app.js"),
    array('dir', 'read', "application/modules/mobile/langs"),
);
$module['libraries'] = array(
    'mobile_detect'
);
$module['dependencies'] = array(
    'get_token' => array('version' => '1.01'),
    'im' => array('version' => '1.01'),
    'properties' => array('version' => '1.03'),
    'start' => array('version' => '1.03'),
    'users' => array('version' => '3.01'),
    //'friendlist' => array('version' => '1.01'),
);

$module['linked_modules'] = array(
    'install' => array(
        'menu' => 'install_menu',
    ),
    'deinstall' => array(
        'menu' => 'deinstall_menu',
    )
);
