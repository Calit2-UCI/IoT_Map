<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_likes')) {
	function get_likes() {
		if(!INSTALL_MODULE_DONE){
			return false;
		}
		$CI = &get_instance();
		if(!$CI->pg_module->is_module_installed('likes')) {
			return false;
		}
		$CI->load->model('Likes_model');
		$id_likes_on_page = $CI->Likes_model->recall_gids();
		if(empty($id_likes_on_page)) {
			return false;
		}

		$count = $CI->Likes_model->get_count($id_likes_on_page);
		$id_user = $CI->session->userdata('user_id');

		$search = array();
		$replace = array();
		if($id_user) {
			$user_likes = $CI->Likes_model->get_likes_by_user($id_user, $id_likes_on_page);
			$unlikeTitle = l('unlike', 'likes');
			foreach($user_likes as $user_like) {
				$search[] = '[' . $user_like . '_action]';
				$replace[] = 'unlike';
				$search[] = '[' . $user_like . '_class]';
				$replace[] = 'fa-heart';
				$search[] = '[' . $user_like . '_title]';
				$replace[] = $unlikeTitle;
			}
		}
		$likeTitle = l('like', 'likes');
		foreach($id_likes_on_page as $id_like) {
			$search[] = '[' . $id_like . '_action]';
			$replace[] = 'like';
			$search[] = '[' . $id_like . ']';
			$replace[] = (string)!(empty($count[$id_like])) ? $count[$id_like] : 0;
			$search[] = '[' . $id_like . '_class]';
			$replace[] = 'fa-heart-empty';
			$search[] = '[' . $id_like . '_title]';
			$replace[] =  $likeTitle;
		}
		$CI->template_lite->output = str_replace($search, $replace, $CI->template_lite->output);
	}
}
