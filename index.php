<?php
ini_set('default_charset', 'utf8');
session_start();

require_once "config.php";

/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
*/
if (DISPLAY_ERRORS){
	ini_set("display_errors", '1');
	error_reporting(E_ALL);
} else {
	ini_set("display_errors", '0');
	error_reporting(~E_ALL);
}

/*
|---------------------------------------------------------------
| APPLICATION FOLDERS NAME
|---------------------------------------------------------------
|
| USE CLOSED TRAILING SLASH IN THE FOLLOWING VARIABLES!
*/

$system_folder = "system/";

$application_folder = "application/";

$updates_folder = "updates/";

$modules_folder = $application_folder."modules/";

$libraries_folder = $application_folder."libraries/";

$temp_folder = "temp/";

/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF		- The name of THIS file (typically "index.php")
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
| APDPATH	- The full server path to the "updates" folder
|
*/
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

define('TEMP_FOLDER', $temp_folder);
define('TRASH_FOLDER', $temp_folder . 'trash/');
define('APPLICATION_FOLDER', $application_folder);
define('UPDATES_FOLDER', $updates_folder);

define('BASEPATH', SITE_PHYSICAL_PATH.$system_folder);
define('APPPATH', SITE_PHYSICAL_PATH.$application_folder);
define('UPDPATH', SITE_PHYSICAL_PATH.$updates_folder);
define('MODULEPATH', SITE_PHYSICAL_PATH.$modules_folder);
define('LIBPATH', SITE_PHYSICAL_PATH.$libraries_folder);
define('TEMPPATH', SITE_PHYSICAL_PATH.$temp_folder);
define('TEMPPATH_VIRTUAL', SITE_VIRTUAL_PATH.$temp_folder);

define('APPPATH_VIRTUAL', SITE_VIRTUAL_PATH.$application_folder);
define('MODULEPATH_VIRTUAL', SITE_VIRTUAL_PATH.$modules_folder);
define('MODULEPATH_RELATIVE', $modules_folder);

define('NS_PREFIX', 'Pg\\');
define('NS_MODULES', NS_PREFIX . 'Modules\\');
define('NS_LIB', NS_PREFIX . 'System\\Libraries\\');

$sapi_type = php_sapi_name();
if ($sapi_type == 'cli') {
	define('CI_SERVER_API', 'cli');
}elseif (substr($sapi_type, 0, 3) == 'cgi') {
	define('CI_SERVER_API', 'cgi');
} else {
	define('CI_SERVER_API', 'module');
}

if(!defined('DEMO_MODE')){
	define('DEMO_MODE', false);
}

if(!defined('TRIAL_MODE')){
	define('TRIAL_MODE', false);
}

if(!defined('FREE_VERSION')){
	define('FREE_VERSION', false);
}

/*
|---------------------------------------------------------------
| GET INSTALL FLAG
|---------------------------------------------------------------
|
*/
if(file_exists(APPPATH.'config/install'.EXT )){
	require_once APPPATH.'config/install'.EXT;
	define("INSTALL_MODULE_DONE", $config["install_module_done"]);
}else{
	define("INSTALL_MODULE_DONE", false);
}

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */
