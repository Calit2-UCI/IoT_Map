<?php

/**
* like me helper
*
* @package PG_Dating
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanaev@pilotgroup.net>
**/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('play')){
	function play($params){
		$CI = & get_instance();
		$CI->load->model("like_me/models/Like_me_model");
		
		if (!empty($params['value']['user'])) {
			$user_data = $params['value']['user'];
			$CI->template_lite->assign('user_data', $user_data);
		} else {
			$play_more = unserialize($CI->pg_module->get_module_config('like_me', 'play_more'));
			$CI->template_lite->assign('play_more', $play_more);
		}
		if ($params['value']['type'] == 'matches') {
			$module_gid = $CI->pg_module->get_module_config('like_me', 'chat_more');
			$settings = $CI->Like_me_model->getActionModules($module_gid);
			$CI->template_lite->assign('settings', $settings);
			return $CI->template_lite->fetch('like_me_matches', 'user', 'like_me');
		} else {
			return $CI->template_lite->fetch('helper_play', 'user', 'like_me');
		}
	}
}
