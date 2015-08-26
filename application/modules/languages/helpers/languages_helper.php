<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('lang_select')){
	function lang_select(){
		$CI = & get_instance();

		$count_active = 0;
		foreach($CI->pg_language->languages as $language){
			if($language["status"]){
				$count_active++;
			}
		}

		$CI->template_lite->assign("count_active", $count_active);
		$CI->template_lite->assign("current_lang", $CI->pg_language->current_lang_id);
		$CI->template_lite->assign("languages", $CI->pg_language->languages);
		return $CI->template_lite->fetch('helper_lang_select', 'user', 'languages');
	}
}

if(! function_exists('lang_editor')){
	function lang_editor(){
		if(INSTALL_DONE && ADD_LANG_MODE){
			$CI = &get_instance();
			$lang_editor = $CI->system_messages->get_data('lang-editor');
			$CI->template_lite->assign('lang_editor', $lang_editor);
			return $CI->template_lite->fetch('lang_editor', 'user', 'languages');
		}else{
			return "";
		}
	}
}