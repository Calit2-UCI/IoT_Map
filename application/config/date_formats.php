<?php
/**
 * days :
 * d - 1,2,3..
 * dd 01,02,03...
 * months:
 * mm -01,02,03..
 * MM - July, August...
 */
$config['date_formats'][1] = array(
	'id' => 1, 
	'format' => 'dd/mm/yy'
);
$config['date_formats'][2] = array(
	'id' => 2, 
	'format' => 'mm/dd/yy'
);
$config['date_formats'][3] = array(
	'id' => 3, 
	'format' => 'dd/MM/yy'
);
$config['date_formats'][4] = array(
	'id' => 4, 
	'format' => 'MM/dd/yy'
);
$config['date_formats'][5] = array(
	'id' => 5, 
	'format' => 'yy/mm/dd'
);
$config['date_formats'][6] = array(
	'id' => 6,
	'format' => 'd/MM/yy'
);

$config["st_format_date_numeric"] ="%Y-%m-%d";
$config["st_format_date_literal"] ="%B %d, %Y";
$config["st_format_date_time_numeric"] ="%Y-%m-%d %H:%M:%S";
$config["st_format_date_time_literal"] ="%d %B %Y, %H:%M";
$config["st_format_time_numeric"] ="%H:%M:%S";
$config["st_format_time_literal"] ="%H:%M";

$config["date_format_date_numeric"] ="Y-m-d";
$config["date_format_date_literal"] ="d F Y";
$config["date_format_date_time_numeric"] ="Y-m-d H:i:s";
$config["date_format_date_time_literal"] ="d F Y, H:i";
$config["date_format_time_numeric"] ="H:i:s";
$config["date_format_time_literal"] ="H:i";

$config["mysql_format_date_numeric"] ="%Y-%m-%d";
$config["mysql_format_date_literal"] ="%d %M %Y";
$config["mysql_format_date_time_numeric"] ="%Y-%m-%d %H:%i:%s";
$config["mysql_format_date_time_literal"] ="%d %M %Y, %H:%i";
$config["mysql_format_time_numeric"] ="%H:%i:%s";
$config["mysql_format_time_literal"] ="%H:%i";
