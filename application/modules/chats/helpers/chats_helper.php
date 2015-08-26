<?php

/**
 * Show chat
 *
 * @package PG_Core
 * @subpackage application
 * @category	helpers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * */
if (!function_exists('chats_block')) {

	function chats_block() {
		$CI = &get_instance();
		$tpl = &$CI->template_lite;
		$CI->load->model('Chats_model');
		$chat = $CI->Chats_model->get_active(true);
		if (empty($chat) || !in_array('include', $chat->get_activities())) {
			return '';
		}
		$tpl->assign('include_block', $chat->include_block());
		return $tpl->fetch('helper_block', 'user', 'chats');
	}

}

if (!function_exists('helper_btn_chat')) {

	function helper_btn_chat($params) {
		$CI = &get_instance();
		$tpl = &$CI->template_lite;
		$CI->load->model('Chats_model');
		$chat = $CI->Chats_model->get_active(true);
		$tpl->assign('chat_gid', $chat->get_gid());
		$tpl->assign('user_id', intval($params['user_id']));
		return $tpl->fetch('helper_btn_chat', 'user', 'chats');
	}

}