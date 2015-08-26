<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('shoutbox_form')){
	function shoutbox_form(){
		$CI = & get_instance();
		$CI->load->model('Shoutbox_model');
		$shoutbox_status = $CI->Shoutbox_model->shoutbox_status();
		if(!$shoutbox_status['id_user'] || !$shoutbox_status['shoutbox_on']){
			return false;
		}
		$data = array();
		$data['id_user'] = $CI->session->userdata('user_id');
		$data['new_msgs'] = $CI->Shoutbox_model->get_new_messages();
		
		$data['max_id'] = $data['min_id'] = 0;
		foreach($data['new_msgs'] as $key => &$message){
			if($data['max_id'] == 0 || $data['max_id'] < $message['id']){
				$data['max_id'] = intval($message['id']);
			}
			if($data['min_id'] == 0 || $data['min_id'] > $message['id']){
				$data['min_id'] = intval($message['id']);
			}
		}
		
		$data['user_name'] = $CI->session->userdata('output_name');
		$data['msg_max_length'] = $CI->pg_module->get_module_config('shoutbox', 'message_max_chars');
		$data['items_per_page'] = 5;
		$data['top_top_icon'] = 60*($data['items_per_page']/2)+18.5;
		$data['height_block_messages'] = 60*intval($data['items_per_page']);
		$CI->template_lite->assign('shoutbox_data', $data);
		$CI->template_lite->assign('shoutbox_json_data', json_encode($data));
		return $CI->template_lite->fetch('helper_shoutbox', 'user', 'shoutbox');
	}
}

