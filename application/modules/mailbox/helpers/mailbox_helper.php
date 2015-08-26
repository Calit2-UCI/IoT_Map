<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('new_messages')) {

	function new_messages($attrs) {
		$CI = & get_instance();
		if ('user' != $CI->session->userdata("auth_type")) {
			return false;
		}
		$user_id = $CI->session->userdata("user_id");
		if(!$user_id) {
			log_message('Empty user id');
			return false;
		}
		if(empty($attrs['template'])) {
			$attrs['template'] = 'header';
		}
		$CI->load->model('Mailbox_model');
		$count = $CI->Mailbox_model->get_new_messages_count($user_id);

		$CI->template_lite->assign('messages_count', $count);
		return $CI->template_lite->fetch('helper_new_messages_' . $attrs['template'], 'user', 'mailbox');
	}

}

if(!function_exists('send_message_button')){
	/**
	 * Return new message button
	 * @param array $user_id user identifier
	 * @return string
	 */
	function send_message_button($params){
		$CI = & get_instance();
		
		if(!isset($params['id_user'])) return '';
		
		if($CI->session->userdata('auth_type') == 'user'){
			$user_id = $CI->session->userdata('user_id');
			if($params['id_user'] == $user_id) return '';
		}

		$CI->template_lite->assign('user_id', $params['id_user']);
        $CI->template_lite->assign('message_button_rand', rand(100000, 999999));
		return $CI->template_lite->fetch('helper_message_button', 'user', 'mailbox');
	}
}
