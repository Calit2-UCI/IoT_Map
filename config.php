<?php

date_default_timezone_set('Europe/Berlin');

define("INSTALL_DONE", true);
define("SHOW_CONFIG_READ_ERROR", false);

define("SITE_SUBFOLDER", '');
define("SITE_PATH", 'C:/xampp/htdocs/iot.calit2.uci.edu/');
define("SITE_PHYSICAL_PATH", SITE_PATH . SITE_SUBFOLDER );
define("SITE_SERVER", 'http://128.195.185.103/');
define("MOBILE_SERVER", '');
define("COOKIE_SITE_SERVER", '');

define("SITE_VIRTUAL_PATH", SITE_SERVER . SITE_SUBFOLDER );

define("DB_HOSTNAME", 'localhost');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", 'CalPlugSite2015SQL');
define("DB_DATABASE", 'iot.calit2');
define("DB_PREFIX", '');
define("DB_DRIVER", "pdo");

define("UPLOAD_DIR", "uploads/");
define("DEFAULT_DIR", "default/");
define("DATASOURCE_ICONS_DIR", "datasource_icons/");

define("FRONTEND_PATH", SITE_PHYSICAL_PATH . UPLOAD_DIR);
define("FRONTEND_URL", SITE_VIRTUAL_PATH . UPLOAD_DIR);

define("GENERATE_BACKTRACE", false);
define("USE_PROFILING", false);
define("DISPLAY_ERRORS", false);
define("ADD_LANG_MODE", false);
define("DEMO_MODE", false);
define("CUSTOM_MODE", '');
define("USE_MEMCACHE", false);
define("TPL_FORCE_COMPILE", false);
define("TPL_PRINT_NAMES", false);

define("PATH_TO_IMAGE_MAGIC", "/usr/bin/convert");
define("USE_IMAGE_MAGIC", false);

/**
 * Set to true, if you use .htaccess rule to remove $config['index_page'] file 
 * from the site URLs
 */
define("HIDE_INDEX_PAGE", true);
