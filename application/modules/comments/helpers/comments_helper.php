<?php
/**
* Comments helper
*
* @package PG_Dating
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/

if(!function_exists('comments_form')){
	function comments_form($params){
		if(!(isset($params['gid']) && $params['gid'] && isset($params['id_obj']) && $params['id_obj'])){
			return false;
		}
		$CI = &get_instance();
		$CI->load->model('comments/models/Comments_types_model');
		$comments_type = $CI->Comments_types_model->get_comments_type_by_gid($params['gid']);
		if(!($comments_type && $comments_type['status'])){
			return '<span>'.l('comments_disabled', 'comments').'</span>';
		}
		$tpl = &$CI->template_lite;
		$tpl->assign('date_format', $CI->pg_date->get_format('date_time_literal', 'st'));

		$params['calc_count'] = isset($params['calc_count']) ? intval($params['calc_count']) : 1;
		$CI->load->model('comments/models/Comments_model');
		if(!empty($params['hidden'])){
			$comments['hidden'] = 1;
			$comments['gid'] = $params['gid'];
			$comments['id_obj'] = $params['id_obj'];
			if(isset($params['count'])){
				$comments['count_all'] = $params['count'];
				$comments['calc_count'] = 1;
			}elseif($params['calc_count'] == 1){
				$comments['calc_count'] = $params['calc_count'];
				$comments['count_all'] = $CI->Comments_model->get_comments_cnt($params['gid'], $params['id_obj']);
			}
		}else{
			$comments = $CI->Comments_model->get_comments_by_gid_obj($params['gid'], $params['id_obj']);
			$comments['hidden'] = 0;
		}
		if(!empty($params['max_height'])){
			$comments['max_height'] = (strstr($params['max_height'], '%') !== false) ? intval($params['max_height']).'%' : intval($params['max_height']).'px';
		}

		$js_lng = array(
			'error' => l('error', 'comments'),
			'error_comment_text' => l('error_comment_text', 'comments'),
			'error_user_name' => l('error_user_name', 'comments'),
			'added' => l('added', 'comments'),
			'added_moderation' => l('added_moderation', 'comments'),
			'deleted' => l('deleted', 'comments')
		);

		$tpl->assign('js_lng', json_encode($js_lng));

		$comments['show_form'] = ($CI->session->userdata('auth_type') == 'user' || ($comments_type['settings']['guest_access'] && $CI->session->userdata('auth_type') != 'admin')) ? 1 : 0;
		$tpl->assign('comments', $comments);
		$tpl->assign('comments_type', $comments_type);
		$tpl->assign('ajax', 0);
		$comments_html = $tpl->fetch('comments_form', 'user', 'comments');

		return $comments_html;
	}
}