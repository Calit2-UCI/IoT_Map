<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$_theme["gid"] = "admin";
$_theme["type"] = "admin";
$_theme["name"] = "Admin area theme";
$_theme["description"] = "Default admin template; PilotGroup";
$_theme["default_scheme"] = "default";
$_theme["setable"] = "0";

$_theme["logo_width"] = "160";
$_theme["logo_height"] = "160";
$_theme["logo_default"] = "logo.png";


$_theme["mini_logo_width"] = "160";
$_theme["mini_logo_height"] = "160";
$_theme["mini_logo_default"] = "logo.png";

$_theme["mobile_logo_default"] = "mobile_logo.png";
$_theme["mobile_logo_width"] = "160";
$_theme["mobile_logo_height"] = "32";


$_theme["schemes"] = array(
	"default" => array(
			"name" => "Default color scheme",
			"active" => "1",
			"color_settings" => 'a:24:{s:7:"html_bg";s:6:"FFFFFF";s:7:"main_bg";s:6:"0090DB";s:9:"header_bg";s:6:"E5E5E5";s:9:"footer_bg";s:6:"CDCDCD";s:13:"menu_hover_bg";s:6:"F2F2F2";s:8:"hover_bg";s:6:"E1EAD8";s:8:"popup_bg";s:6:"FFFFFF";s:12:"highlight_bg";s:6:"6078BF";s:11:"input_color";s:6:"00547F";s:14:"input_bg_color";s:6:"FFFFFF";s:12:"status_color";s:6:"E6930C";s:10:"link_color";s:6:"2077A4";s:10:"font_color";s:6:"4C4C4C";s:12:"header_color";s:6:"000000";s:11:"descr_color";s:6:"808080";s:14:"contrast_color";s:6:"FFFFFF";s:15:"delimiter_color";s:6:"C5C5C5";s:11:"font_family";s:82:"\'SegoeUINormal\', Arial, \'Lucida Grande\',\'Lucida Sans Unicode\', Verdana, sans-serif";s:14:"main_font_size";s:2:"13";s:15:"input_font_size";s:2:"15";s:12:"h1_font_size";s:2:"20";s:12:"h2_font_size";s:2:"17";s:15:"small_font_size";s:2:"12";s:20:"search_btn_font_size";s:2:"22";}',
			"scheme_type" => 'light'
		)
);


$_theme["css"] = array(
	"general" => array("file"=>"general-[rtl].css", "media"=>"screen"),
	"style" => array("file"=>"style-[rtl].css", "media"=>"screen"),
	"print" => array("file"=>"print-[rtl].css", "media"=>"print")
);
