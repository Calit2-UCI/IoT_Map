<?php

/**
 * Aviary module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Aviary management
 * 
 * @package 	PG_Dating
 * @subpackage 	Aviary
 * @category	helpers
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if(!function_exists('aviary_editor_button')){
	/**
	 * Return aviary editor button
	 * 
	 * @param array $params block parameters
	 * @return string
	 */
	function aviary_editor_button($params){
		$CI = &get_instance();
	
		if(empty($params['id']) || empty($params['source']) || 
		   empty($params['module_gid']) || empty($params['save_callback'])) return '';
		
		$used = $CI->pg_module->get_module_config('aviary', 'used');
		$api_key = $CI->pg_module->get_module_config('aviary', 'api_key');
		
		if(!$used || !$api_key) return '';
		
		if(empty($params['post_data'])) $params['post_data'] = array();
		
		$CI->template_lite->assign('aviary_api_key', $api_key);
			
		$CI->template_lite->assign('aviary_module', $params['module_gid']);	
		$CI->template_lite->assign('aviary_photo_id', $params['id']);
		$CI->template_lite->assign('aviary_photo_source', $params['source']);
		$CI->template_lite->assign('aviary_post_url', $params['post_url']);
		$CI->template_lite->assign('aviary_post_data', json_encode($params['post_data']));
		$CI->template_lite->assign('aviary_save_callback', $params['save_callback']);
		
		$CI->load->model('Aviary_model');
		$aviary_code = $CI->Aviary_model->save_encode($params['module_gid'], $params['post_data']);
		$CI->template_lite->assign('aviary_code', $aviary_code);
		
		$CI->template_lite->assign('avaiary_button_rand', rand(100000, 999999));
		
		if(include(APPPATH . 'modules/aviary/install/languages' . EXT)){
			$lang_code = $CI->pg_language->languages[$CI->pg_language->current_lang_id]['code'];
			if(isset($languages['aviary'][$lang_code])){
				$CI->template_lite->assign('aviary_lang_code', $languages['aviary'][$lang_code]);
			}
		}
		
		return $CI->template_lite->fetch('helper_button', null, 'aviary');
	}
}
