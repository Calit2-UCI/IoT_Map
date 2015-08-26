<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_user_online_status')){

	function set_user_online_status(){
		if(INSTALL_MODULE_DONE){
			$CI = &get_instance();
			$not_update_online_status = $CI->input->post('not_update_online_status', true);
			if($not_update_online_status){
				return;
			}
			
			if($CI->pg_module->is_module_installed('users') && $CI->load->model('Users_model', '', false, true, true) && method_exists($CI->Users_model, 'update_online_status') && $CI->session->userdata('auth_type') == 'user'){
				$id_user = $CI->session->userdata('user_id');
				$CI->Users_model->update_online_status($id_user, '1');
			}
		}
		return;
	}

}
