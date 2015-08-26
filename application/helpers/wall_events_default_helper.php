<?php

if (!function_exists('add_wall_event')) {

	function add_wall_event($event_type_gid, $id_wall, $id_poster, $data, $id_object = 0, $file_name = '', $required_fields = array()) {
		$result['errors'] = array();
		$CI = & get_instance();
		if($CI->pg_module->is_module_installed('wall_events')){
			$CI->load->model('Wall_events_model');
			$CI->load->model('wall_events/models/Wall_events_permissions_model');
			$CI->load->model('wall_events/models/Wall_events_types_model');
			$event_type = $CI->Wall_events_types_model->get_wall_events_type($event_type_gid);
			$CI->Wall_events_model->event_type = $event_type;
			if(!$event_type || !$event_type['status']){
				$result['errors'][] = l('error_no_events_type', 'wall_events');
				return $result;
			}
			
			$wall_perm = $CI->Wall_events_permissions_model->get_user_permissions($id_wall);
			$is_post_allowed = ($event_type_gid == 'wall_post') ? ($id_wall == $id_poster || !empty($wall_perm[$event_type_gid]['post_allow'])) : true;
			if($is_post_allowed){
				$permissions = isset($wall_perm[$event_type_gid]['permissions']) ? $wall_perm[$event_type_gid]['permissions'] : 0;
				$event_permissions = isset($wall_perm[$event_type_gid]['permissions']) ? $wall_perm[$event_type_gid]['permissions'] : 3;
				if($CI->Wall_events_permissions_model->is_permissions_allowed($permissions, $id_wall, $id_poster)){
					$event_data['id_wall'] = $id_wall;
					$event_data['id_poster'] = $id_poster;
					$event_data['data'] = $data;
					$validate_data = $CI->Wall_events_model->validate($event_data, $file_name, $required_fields);
					if(!$validate_data['errors']){
						$result = $CI->Wall_events_model->add_event($event_type_gid, $validate_data['data']['id_wall'], $id_poster, $validate_data['data']['data'], $event_permissions, $id_object, $file_name);
					}else{
						$result['errors'] = $validate_data['errors'];
					}
				}else{
					$result['errors'][] = l('error_action_not_allowed', 'wall_events');
				}
			}else{
				$result['errors'][] = l('error_action_not_allowed', 'wall_events');
			}
		}
		return $result;
	}

}

if (!function_exists('add_event_data')) {

	function add_event_data($id, $id_poster, $data, $file_name = '') {
		$CI = & get_instance();
		if($CI->pg_module->is_module_installed('wall_events')){
			$CI->load->model('Wall_events_model');
			$orig_event = $CI->Wall_events_model->get_event_by_id($id);
			if($orig_event && $orig_event['id_poster'] == $id_poster){
				$event_data['data'] = $data;
				$validate_data = $CI->Wall_events_model->validate($event_data, 'multiupload');
				if(!$validate_data['errors']){
					$result = $CI->Wall_events_model->add_event_data($id, $validate_data['data']['data'], $file_name);
				}else{
					$result['errors'] = $validate_data['errors'];
				}
			}else{
				$result['errors'][] = l('error_action_not_allowed', 'wall_events');
			}
		}
		return $result;
	}

}

if (!function_exists('delete_wall_event')) {

	function delete_wall_events($params) {
		$CI = & get_instance();
		if($CI->pg_module->is_module_installed('wall_events')){
			$CI->load->model('Wall_events_model');
			return $CI->Wall_events_model->delete_events($params);
		}
		return false;
	}

}
