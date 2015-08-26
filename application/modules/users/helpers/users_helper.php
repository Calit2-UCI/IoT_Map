<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('login_form')) {

	function login_form() {
		$CI = & get_instance();
		if ($CI->session->userdata("auth_type") == "user") {
			$CI->load->model("Users_model");
			$user_data = $CI->Users_model->get_user_by_id($CI->session->userdata("user_id"));
			$user_data = $CI->Users_model->format_user($user_data);
			$CI->template_lite->assign('user_data', $user_data);
		}
		return $CI->template_lite->fetch('helper_login_form', 'user', 'users');
	}

}

if ( ! function_exists('users_lang_select')){
	function users_lang_select($attrs = array()){
		$CI = & get_instance();

		$count_active = 0;
		foreach($CI->pg_language->languages as $language){
			if($language["status"]){
				$count_active++;
			}
		}

		$CI->template_lite->assign("type", isset($attrs['type']) ? $attrs['type'] : '');
		$CI->template_lite->assign("count_active", $count_active);
		$CI->template_lite->assign("current_lang", $CI->pg_language->current_lang_id);
		$CI->template_lite->assign("languages", $CI->pg_language->languages);
		return $CI->template_lite->fetch('helper_lang_select', 'user', 'users');
	}
}

if ( ! function_exists('top_menu')){
	function top_menu(){
		$CI = & get_instance();
		return $CI->template_lite->fetch('helper_top_menu', 'user', 'users');
	}
}

if (!function_exists('auth_links')) {

	function auth_links() {
		$CI = & get_instance();
		return $CI->template_lite->fetch('helper_auth_links', 'user', 'users');
	}

}

if (!function_exists('last_registered')) {

	function last_registered($count = 9) {
		$CI = & get_instance();
		$CI->load->model("Users_model");
		$attrs["where"]["user_logo !="] = '';
		$users = $CI->Users_model->get_active_users_list(1, $count, array("date_created" => "DESC"), $attrs);
		$CI->template_lite->assign('users', $users);
		return $CI->template_lite->fetch('helper_last_registered', 'user', 'users');
	}

}

if (!function_exists('user_select')) {

	function user_select($selected = array(), $max_select = 0, $var_name = 'id_user') {
		$CI = & get_instance();
		$CI->load->model("Users_model");

		if ($max_select == 1 && !empty($selected) && !is_array($selected)) {
			$selected = array($selected);
		}

		if (!empty($selected)) {
			$data["selected"] = $CI->Users_model->get_users_list(null, null, null, array(), $selected, true);
			$data["selected_str"] = implode(",", $selected);
		} else {
			$data["selected_str"] = "";
		}

		$data["var_name"] = $var_name ? $var_name : "id_user";
		$data["max_select"] = $max_select ? $max_select : 0;

		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('select_data', $data);
		return $CI->template_lite->fetch('helper_user_select', 'user', 'users');
	}

}

if (!function_exists('admin_home_users_block')) {

	function admin_home_users_block() {
		$CI = & get_instance();

		$auth_type = $CI->session->userdata("auth_type");
		if($auth_type != "admin") return '';

		$user_type = $CI->session->userdata("user_type");

		$show = true;

		$stat_users = array(
			'index_method' => true,
			'moderation_method' => true
		);

		if($user_type == 'moderator'){
			$show = false;
			$CI->load->model('Moderators_model');
			$methods_users = $CI->Moderators_model->get_module_methods('users');
			$methods_moderation = $CI->Moderators_model->get_module_methods('moderation');
			if( (is_array($methods_users) && !in_array('index', $methods_users)) || (is_array($methods_moderation) && !in_array('index', $methods_moderation))){
				$show = true;
			}else{
				$permission_data = $CI->session->userdata("permission_data");
				if(
					(isset($permission_data['users']['index']) && $permission_data['users']['index'] == 1) ||
					(isset($permission_data['moderation']['index']) && $permission_data['moderation']['index'] == 1)
				){
					$show = true;
					$stat_users['index_method'] = (bool)$permission_data['users']['index'];
					$stat_users['moderation_method'] = (bool)$permission_data['moderation']['index'];
				}
			}
		}

		if(!$show){
			return '';
		}

		$CI->load->model('Users_model');
		$stat_users["all"] = $CI->Users_model->get_users_count();
		$stat_users["active"] = $CI->Users_model->get_active_users_count();
		$stat_users["blocked"] = $CI->Users_model->get_users_count(array("where"=>array('approved'=>0)));
		$stat_users["unconfirm"]= $CI->Users_model->get_users_count(array("where"=>array('confirm'=>0)));

		$CI->load->model('Moderation_model');
		$stat_users["icons"] = $CI->Moderation_model->get_moderation_list_count('user_logo');

		$CI->template_lite->assign("stat_users", $stat_users);
		return $CI->template_lite->fetch('helper_admin_home_block', 'admin', 'users');
	}
}

if ( ! function_exists('users_search_form')){
	function users_search_form($object = 'user', $type='line', $show_data=false){
		$CI = &get_instance();

		$CI->load->model('Users_model');
		$CI->load->model('users/models/Users_perfect_match_model');
		$CI->load->model('Field_editor_model');

		$page_data = array(
			'type' => $type,
			'form_id' => $object.'_'.$type,
			'use_advanced' => false,
			'action' => site_url().'users/search',
			'object' => $object
		);

		$user_id = $CI->session->userdata('user_id');
		$auth_type = $CI->session->userdata('auth_type');

		if($type != 'line'){
			$CI->load->model('Properties_model');
			$user_types = $CI->Properties_model->get_property('user_type');
			$CI->template_lite->assign('user_types', $user_types);
			$min_age = $CI->pg_module->get_module_config('users', 'age_min');
			$max_age = $CI->pg_module->get_module_config('users', 'age_max');
			for($i = $min_age; $i <= $max_age; $i++){
				$age_range[$i] = $i;
			}
			$CI->template_lite->assign('age_range', $age_range);
		}

		$validate_settings = array();
		if($show_data){
			if($object == 'perfect_match'){
				$CI->Field_editor_model->initialize($CI->Users_perfect_match_model->form_editor_type);
				$fields_for_select = $CI->Field_editor_model->get_fields_for_select();
				$CI->Users_perfect_match_model->set_additional_fields($fields_for_select);
				$current_settings = ($CI->session->userdata("perfect_match")) ? $CI->session->userdata("perfect_match") : $CI->Users_perfect_match_model->get_user_perfect_match_params($user_id);
				$validate_settings = $CI->Users_perfect_match_model->validate($current_settings, 'select');
			}else{
				$CI->Field_editor_model->initialize($CI->Users_model->form_editor_type);
				$fields_for_select = $CI->Field_editor_model->get_fields_for_select();
				$CI->Users_model->set_additional_fields($fields_for_select);
				$current_settings = ($CI->session->userdata('users_search')) ? $CI->session->userdata('users_search') : $CI->Users_model->get_default_search_data();
				$validate_settings = $CI->Users_model->validate(0, $current_settings, '', '', 'select');
			}
			foreach($fields_for_select as $field){
				if(!empty($validate_settings['data'][$field]) || !empty($validate_settings['data'][$field.'_min']) || !empty($validate_settings['data'][$field.'_max'])){
					$page_data["type"] = 'full';
					break;
				}
			}
		}

		if($object == 'user' && $type == 'advanced'){
			$CI->Field_editor_model->initialize($CI->Users_model->form_editor_type);
			$CI->load->model('field_editor/models/Field_editor_forms_model');
			$form = $CI->Field_editor_forms_model->get_form_by_gid($CI->Users_model->advanced_search_form_gid, $CI->Users_model->form_editor_type);
			$form = $CI->Field_editor_forms_model->format_output_form($form, $validate_settings['data']);
			if($form["field_data"]){
				$page_data["use_advanced"] = true;
				$CI->template_lite->assign('advanced_form', $form["field_data"]);
			}else{
				$page_data["use_advanced"] = false;
			}
			$page_data["type"] = $page_data["type"] == 'full' ? 'full' : 'short';
		}elseif($object == 'perfect_match'){
			$CI->Field_editor_model->initialize($CI->Users_perfect_match_model->form_editor_type);
			$CI->load->model('field_editor/models/Field_editor_forms_model');
			$form = $CI->Field_editor_forms_model->get_form_by_gid($CI->Users_perfect_match_model->perfect_match_form_gid, $CI->Users_perfect_match_model->form_editor_type);
			$form = $CI->Field_editor_forms_model->format_output_form($form, $validate_settings['data']);
			$page_data["type"] = $page_data["type"] == 'full' ? 'full' : 'short';
			if($form['field_data']){
				$page_data["use_advanced"] = true;
				$CI->template_lite->assign('advanced_form', $form['field_data']);
			}else{
				$page_data["use_advanced"] = false;
			}
			$page_data['action'] = site_url()."users/perfect_match";
		}

		$CI->template_lite->assign('data', !empty($validate_settings["data"]) ? $validate_settings["data"] : array());
		$CI->template_lite->assign('form_settings', $page_data);
		$html = $CI->template_lite->fetch("helper_search_form", 'user', 'users');
		return $html;
	}
}

if(!function_exists('user_input')){
	function user_input($params){
		$CI = & get_instance();
		$CI->load->model('Users_model');

		if(isset($params['id_user']) && !empty($params['id_user'])){
			$data['user'] = $CI->Users_model->get_user($params['id_user']);
		}

		$data['var_user_name'] = isset($params['var_user_name']) ? $params['var_user_name'] : 'id_user';
		$data['var_js_name'] = isset($params['var_js_name']) ? $params['var_js_name'] : '';
		$data['autocomplete'] = isset($params['autocomplete']) ? (bool)$params['autocomplete'] : false;
		$data['placeholder'] = isset($params['placeholder']) ? $params['placeholder'] : '';

		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('user_helper_data', $data);
		return $CI->template_lite->fetch('helper_user_input', 'user', 'users');
	}
}

if(!function_exists('users_carousel')){
	function users_carousel($params){
		$CI = & get_instance();

		if(empty($params['users'])) return '';

		$data['header'] = !empty($params['header']) ? $params['header'] : '';
		$data['users'] = $params['users'];
		$data['carousel']['users_count'] = count($params['users']);
		$data['rand'] = $data['carousel']['rand'] = rand(1, 999999);
		$data['carousel']['visible'] = !empty($params['visible']) ? intval($params['visible']) : 3;
		$data['carousel']['scroll'] = (!empty($params['scroll']) && $params['scroll'] != 'auto') ? intval($params['scroll']) : 'auto';
		$data['carousel']['class'] = !empty($params['class']) ? $params['class'] : '';
		$data['carousel']['thumb_name'] = !empty($params['thumb_name']) ? $params['thumb_name'] : 'middle';
		if(!$data['carousel']['scroll']){
			$data['carousel']['scroll'] = 1;
		}

		$CI->template_lite->assign('users_carousel_data', $data);
		return $CI->template_lite->fetch('helper_users_carousel', 'user', 'users');
	}
}

if(!function_exists('featured_users')){
	function featured_users(){
		$CI = & get_instance();
		$CI->load->model('Users_model');
		
		$users = $CI->Users_model->get_featured_users(50);
		if(empty($users)) return '';
		$data['rand'] = rand(1, 999999);
		$data['buy_ability'] = false;
		$data['users'] = $users;
		
		if($CI->session->userdata("auth_type") == "user"){
			$user_id = $CI->session->userdata("user_id");
			$user = $CI->Users_model->get_user_by_id($user_id);
			$user = $CI->Users_model->format_user($user);
			
			if(!empty($user)){
				$data['service_status'] = $CI->Users_model->service_status_users_featured($user);
				$data['buy_ability'] = $data['service_status']['status'];
				if($data['buy_ability']){
					$user['carousel_data']['class'] = 'with-overlay-add';
					$user['carousel_data']['icon_class'] = 'fa-plus edge icon-big w';
					$user['carousel_data']['id'] = 'featured_add_'.$data['rand'];
					array_unshift($data['users'], $user);
					$data['user_id'] = $user['id'];
				}
			}
		}
		$CI->template_lite->assign('helper_featured_users_data', $data);
		return $CI->template_lite->fetch('helper_featured_users', 'user', 'users');
	}
}

if(!function_exists('active_users_block')){
	function active_users_block($params){
		$CI = & get_instance();
		$CI->load->model('Users_model');      
        $attrs["where_sql"][] = " id!='".$CI->session->userdata("user_id")."'";
        $data['users'] = $CI->Users_model->get_active_users($params['count'],0,$attrs);
        if(!empty($data['users'])){
            $users_count = 16 - count($data['users']);
            switch($users_count){
                case 13:	$recent_thumb['name'] = 'middle';
							$recent_thumb['width'] = '82px';
                    break;
                case 14:	$recent_thumb['name'] = 'big'; 
							$recent_thumb['width'] = '125px';
                    break;
                case 15:	$recent_thumb['name'] = 'great'; 
							$recent_thumb['width'] = '255px';
                    break;
                default:	$recent_thumb['name'] = 'small';
							$recent_thumb['width'] = '60px';
            }
            $CI->template_lite->assign('recent_thumb', $recent_thumb);
            $CI->template_lite->assign('active_users_block_data', $data);
            return $CI->template_lite->fetch('helper_active_users_block', 'user', 'users');
		}
		return false;               
    }	
}

if(!function_exists('delete_select_block')) {
	function delete_select_block($params) {
		$CI = & get_instance();
		$display = true;
		$params['title_text'] = l('link_delete_user', 'users');
		if(!empty($params['deleted']) && $params['deleted']==1){
			$params['title_text'] = l('link_delete_data_user', 'users');
			$CI->load->model("users/models/Users_delete_callbacks_model");
			$callbacks = $CI->Users_delete_callbacks_model->get_all_callbacks_gid();
			$diff_callback = array_diff($callbacks, unserialize($params['callback_user']));
			if(empty($diff_callback))return;
		}
		$CI->template_lite->assign('params', $params);
		return $CI->template_lite->fetch('helper_delete_select_block', 'admin', 'users');
	}
}

if(!function_exists('visitors')) {

	function visitors($attrs) {
		$CI = & get_instance();
		$CI->load->model('users/models/Users_views_model');
		$count = count($CI->Users_views_model->get_viewers_daily_unique($CI->session->userdata('user_id'), null, null, array('view_date' => 'DESC') ,array(), 'all'));
		$CI->template_lite->assign('visitors_count', $count);
		return $CI->template_lite->fetch('helper_visitors_' . $attrs['template'], 'user', 'users');
	}

}
