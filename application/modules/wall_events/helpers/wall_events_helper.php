<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('wall_block')) {

	function wall_block($params) {
		$CI = & get_instance();
		$CI->load->model('Wall_events_model');
		$CI->load->model('wall_events/models/Wall_events_types_model');
		$CI->load->model('Uploads_model');
		$CI->load->model('Video_uploads_model');
		$image_upload_config = $CI->Uploads_model->get_config($CI->Wall_events_model->image_upload_gid);
		$video_upload_config = $CI->Video_uploads_model->get_config($CI->Wall_events_model->video_upload_gid);
		$max_upload_size = ($image_upload_config['max_size'] == 0 || $video_upload_config['max_size'] == 0) ? 0 : ($image_upload_config['max_size'] > $video_upload_config['max_size'] ? $image_upload_config['max_size'] : $video_upload_config['max_size']);

		$user_id = $CI->session->userdata("auth_type") == "user" ? intval($CI->session->userdata("user_id")) : 0;
		$id_wall = !empty($params['id_wall']) ? intval($params['id_wall']) : $user_id;
		$place = !empty($params['place']) ? $params['place'] : 'homepage';
		$wall_params = array(
			'id' => 'wall',
			'place' => $place,
			'id_user' => $user_id,
			'id_wall' => $id_wall,
			'url_load' => site_url() . 'wall_events/ajax_get_events/all/',
			'url_load_new' => site_url() . 'wall_events/ajax_get_events/new/',
			'url_post' => site_url() . 'wall_events/ajax_post/',
			'url_delete' => site_url() . 'wall_events/ajax_post_delete/',
			'url_upload' => "wall_events/post_upload/{$id_wall}/{$place}",
			'view_button_title' => l('btn_view_more', 'start'),
			'image_lng' => l('image', 'wall_events'),
			'image_upload_config' => $image_upload_config,
			'video_upload_config' => $video_upload_config,
			'max_upload_size' => $max_upload_size,
			'allowed_mimes' => array_merge($image_upload_config['allowed_mimes'], $video_upload_config['allowed_mimes']),
		);

		$e_type_gid = $CI->Wall_events_model->wall_event_gid;
		$e_type = $CI->Wall_events_types_model->get_wall_events_type($e_type_gid);

		$wall_post_gid = $CI->Wall_events_model->wall_event_gid;
		$wall_params['show_post_form'] = 0;
		if ($id_wall && $user_id && $id_wall != $user_id) {
			$CI->load->model('wall_events/models/Wall_events_permissions_model');
			$wall_permissions = $CI->Wall_events_permissions_model->get_user_permissions($id_wall);

			$is_post_visible = false;
			if (!empty($wall_permissions[$wall_post_gid]['permissions'])) {
				/*if ($CI->pg_module->is_module_installed('users_lists')) {
					$CI->load->model('Users_lists_model');
					$is_friend = $CI->Users_lists_model->is_friend($data['id_wall'], $user_id);
				} else*/if ($CI->pg_module->is_module_installed('friendlist')) {
					$CI->load->model('Friendlist_model');
					$is_friend = $CI->Friendlist_model->is_friend($data['id_wall'], $user_id);
				} else {
					$is_friend = false;
				}
				$is_post_visible = ($wall_permissions[$wall_post_gid]['permissions'] >= 2 || ($is_friend && $wall_permissions[$wall_post_gid]['permissions'] >= 1)) ? true : false;
			}

			$wall_params['show_post_form'] = (!empty($e_type['status']) && !empty($wall_permissions[$wall_post_gid]['post_allow']) && $is_post_visible) ? 1 : 0;
		} elseif ($user_id && ($place == 'myprofile' || $place == 'homepage')) {
			$wall_params['show_post_form'] = !empty($e_type['status']) ? 1 : 0;
		}
		$CI->template_lite->assign('wall_params', $wall_params);
		$CI->template_lite->view('wall_block', 'user', 'wall_events');
	}

}
