<?php
/* стандартные ограничения нам не подходят. ставим свои */
set_time_limit(0);
ini_set('memory_limit', '256M');
ini_set('display_errors' , '0');
error_reporting(E_ERROR);

unset($argv[0]);
$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = implode('/', $argv);

include(dirname(__FILE__).'/index.php');
?>