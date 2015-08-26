<?php
$module['module'] = 'geomap';
$module['install_name'] = 'Geo maps';
$module['install_descr'] = 'This module manages the use of maps on the site';
$module['version'] = '2.01';
$module['files'] = array(
	array('file', 'read', "application/modules/geomap/controllers/admin_geomap.php"),
	array('file', 'read', "application/modules/geomap/controllers/api_geomap.php"),
	array('file', 'read', "application/modules/geomap/controllers/geomap.php"),
	array('file', 'read', "application/modules/geomap/helpers/geomap_helper.php"),
	array('file', 'read', "application/modules/geomap/install/module.php"),
	array('file', 'read', "application/modules/geomap/install/permissions.php"),
	array('file', 'read', "application/modules/geomap/install/settings.php"),
	array('file', 'read', "application/modules/geomap/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/geomap/install/structure_install.sql"),
	array('file', 'read', "application/modules/geomap/js/bingmapsv7.js"),
	array('file', 'read', "application/modules/geomap/js/geomap-amenity-select.js"),
	array('file', 'read', "application/modules/geomap/js/googlemapsv3.js"),
	array('file', 'read', "application/modules/geomap/js/markerclusterer.js"),
	array('file', 'read', "application/modules/geomap/js/yandexmapsv2.js"),
	array('file', 'read', "application/modules/geomap/models/bingmapsv7_model.php"),
	array('file', 'read', "application/modules/geomap/models/geomap_install_model.php"),
	array('file', 'read', "application/modules/geomap/models/geomap_model.php"),
	array('file', 'read', "application/modules/geomap/models/geomap_settings_model.php"),
	array('file', 'read', "application/modules/geomap/models/googlemapsv3_model.php"),
	array('file', 'read', "application/modules/geomap/models/yandexmapsv2_model.php"),	
	array('file', 'read', "application/modules/geomap/views/admin/ajax_amenity_form.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/edit_settings_bingmapsv7.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/edit_settings_googlemapsv3.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/edit_settings.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/edit_settings_yandexmapsv2.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/helper_amenity_select.tpl"),
	array('file', 'read', "application/modules/geomap/views/admin/list.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/css/bingmapsv7.css"),
	array('file', 'read', "application/modules/geomap/views/default/css/googlev3.css"),
	array('file', 'read', "application/modules/geomap/views/default/css/yandexv2.css"),
	array('file', 'read', "application/modules/geomap/views/default/bingmapsv7_geocoder.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/bingmapsv7_html.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/bingmapsv7_update.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/example.tpl"),	
	array('file', 'read', "application/modules/geomap/views/default/googlemapsv3_geocoder.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/googlemapsv3_html.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/googlemapsv3_update.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/helper_show_map.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/yandexmapsv2_geocoder.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/yandexmapsv2_html.tpl"),
	array('file', 'read', "application/modules/geomap/views/default/yandexmapsv2_update.tpl"),	
	array('dir', 'read', 'application/modules/geomap/langs'),
);
$module['dependencies'] = array(
	'start' 	=> array('version'=>'1.03'),
	'menu'		=> array('version'=>'2.03'),
	'uploads'	=> array('version'=>'1.03'),
);
$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		"uploads"	=> "install_uploads",
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		"uploads"	=> "deinstall_uploads",
	)
);
