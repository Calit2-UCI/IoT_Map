<?php

/**
 * Show moderators
 *
 * @package PG_Core
 * @subpackage application
 * @category	helpers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Nikita Savanaev <nsavanaev@pilotgroup>
 * */
 
if ( ! function_exists('count_ausers'))
{
	function count_ausers(){
		$CI = &get_instance();
		$CI->load->model('moderators/models/Moderators_model');
		$data["all"] = $CI->Moderators_model->get_users_count();
		$data["moderators"] = $CI->Moderators_model->get_users_count(array("where"=>array("user_type"=>'moderator')));
		$CI->template_lite->assign('count_data', $data);
		return $CI->template_lite->fetch('helper_count', 'admin', 'moderators');
	}
}

if ( ! function_exists('add_moderator'))
{
	function add_moderator(){
		$CI = &get_instance();
		$CI->load->model('moderators/models/Moderators_model');
		return $CI->template_lite->fetch('helper_add_button', 'admin', 'moderators');
	}
}
