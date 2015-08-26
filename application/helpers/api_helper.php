<?php

if (!function_exists('get_api_content')) {

	function get_api_content() {
		$CI = & get_instance();
		$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
		if ($type === 'xml' && $CI->pg_module->get_module_config('get_token', 'use_xml')){
			$CI->load->library('array2xml');
			return $CI->array2xml->convert($CI->api_content);
		} else {
			$force_json = filter_input(INPUT_POST, 'force_object', FILTER_VALIDATE_BOOLEAN);
			return json_encode($CI->api_content, $force_json ? JSON_FORCE_OBJECT : null);
		}
	}

}