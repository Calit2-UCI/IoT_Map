<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('l')) {

	function l($gid, $module_gid, $lang_id = '', $type = "text", $replace_array = array()) {
		$CI = &get_instance();
		$lang_value = $CI->pg_language->get_string($module_gid, $gid, $lang_id);

		if ($type == "text" || $type == "seo") {
			//$lang_value = $lang_value;
		} elseif ($type == "button") {
			$lang_value = addslashes(strip_tags($lang_value));
		} elseif ($type == "js") {
			//$lang_value = str_replace("'", "\'", strip_tags($lang_value));
			$lang_value = str_replace("'", "\'", $lang_value);
			$lang_value = preg_replace('/[\n\t\r]+/', '', $lang_value);
		}
		//$bodytag = str_replace("%body%", "black", "<body text='%body%'>");
		if (!empty($replace_array)) {
			foreach ($replace_array as $key => $value) {
				if (!is_array($value)) {
					$lang_value = str_replace('[' . $key . ']', $value, $lang_value);
				}
			}
		}

		if (INSTALL_DONE && ADD_LANG_MODE) {
			$data = array(
				"module_gid" => $module_gid,
				"gid" => $gid,
				"lang_id" => $lang_id,
				"value" => $lang_value,
				"edit_type" => $type
			);
			$CI->system_messages->set_data_array('lang-editor', $module_gid . "_" . $gid, $data);
			if ($type == "text") {
				$lang_value = "<span langid='" . $module_gid . "_" . $gid . "'>" . $lang_value . "</span>";
			} elseif ($type == "button") {
				$lang_value = $lang_value . "\" langid=\"" . $module_gid . "_" . $gid;
			} /*elseif ($type == "js") {
				$lang_value = $lang_value;
			} elseif ($type == "seo") {
				$lang_value = $lang_value;
			}*/
		}
		return $lang_value;
	}

}

if (!function_exists('ld')) {

	function ld($gid, $module_gid, $lang_id = '') {
		$CI = &get_instance();
		return $CI->pg_language->get_reference($module_gid, $gid, $lang_id);
	}

}

if (!function_exists('ld_option')) {

	function ld_option($gid, $module_gid, $option_gid, $lang_id = '', $default = '') {
		$CI = &get_instance();
		$reference = $CI->pg_language->get_reference($module_gid, $gid, $lang_id);
		$option = false;
		if (!empty($reference['option'])) {
			foreach ($reference['option'] as $opt_gid => $item) {
				if ($opt_gid == $option_gid) {
					$option = $item;
				}
			}
		}
		if ($option === false) {
			$option = $default;
		}
		return $option;
	}

}

if (!function_exists('ld_header')) {

	function ld_header($gid, $module_gid, $lang_id = '') {
		$CI = &get_instance();
		$reference = $CI->pg_language->get_reference($module_gid, $gid, $lang_id);

		$header = '';
		if (!empty($reference['header'])) {
			$header = $reference['header'];
		}

		return $header;
	}

}
