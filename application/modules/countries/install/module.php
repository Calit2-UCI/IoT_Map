<?php

$module['module'] = 'countries';
$module['install_name'] = 'Countries module';
$module['install_descr'] = 'Locations management (editing countries, regions, cities, geo-coordinates); installation from a ready database';
$module['version'] = '4.01';
$module['files'] = array(
    array('file', 'read', "application/modules/countries/helpers/countries_helper.php"),
    array('file', 'read', "application/modules/countries/js/admin-countries.js"),
    array('file', 'read', "application/modules/countries/js/location-autocomplete.js"),
    array('file', 'read', "application/modules/countries/js/location-popup.js"),
    array('file', 'read', "application/modules/countries/controllers/admin_countries.php"),
    array('file', 'read', "application/modules/countries/controllers/api_countries.php"),
    array('file', 'read', "application/modules/countries/controllers/countries.php"),
    array('file', 'read', "application/modules/countries/install/module.php"),
    array('file', 'read', "application/modules/countries/install/settings.php"),
    array('file', 'read', "application/modules/countries/install/permissions.php"),
    array('file', 'read', "application/modules/countries/install/structure_deinstall.sql"),
    array('file', 'read', "application/modules/countries/install/structure_install.sql"),
    array('file', 'read', "application/modules/countries/models/countries_install_model.php"),
    array('file', 'read', "application/modules/countries/models/countries_location_select_model.php"),
    array('file', 'read', "application/modules/countries/models/countries_model.php"),
    array('file', 'read', "application/modules/countries/views/admin/css/style.css"),
    array('file', 'read', "application/modules/countries/views/admin/edit_city_form.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/edit_country_form.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/edit_region_form.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/install_city_list.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/install_country_list.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/install_region_list.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/list_cities.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/list_regions.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/list.tpl"),
    array('file', 'read', "application/modules/countries/views/admin/link_index.tpl"),
    array('file', 'read', "application/modules/countries/views/default/ajax_country_form.tpl"),
    array('file', 'read', "application/modules/countries/views/default/helper_location_select_autocomplete.tpl"),
    array('file', 'read', "application/modules/countries/views/default/helper_location_select_popup.tpl"),
    array('dir', 'read', "application/modules/countries/langs"),
);
$module['dependencies'] = array(
    'start' => array('version' => '1.03'),
    'menu' => array('version' => '2.03')
);
$module['libraries'] = array(
);
$module['linked_modules'] = array(
    'install' => array(
        'menu' => 'install_menu'
    ),
    'deinstall' => array(
        'menu' => 'deinstall_menu'
    )
);
