<?php

/**
 * Wall events controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
class Wall_events extends Controller {

	public function __construct() {
		parent::Controller();
		$this->load->model('Wall_events_model');
		$this->load->model('wall_events/models/Wall_events_types_model');
		$this->load->model('wall_events/models/Wall_events_permissions_model');
		$this->template_lite->assign('date_format', $this->pg_date->get_format('date_time_literal', 'st'));
	}

	public function ajax_get_events($type = 'all') {
		$post_data['place'] = trim(strip_tags($this->input->post('place', true)));
		$post_data['id_wall'] = intval($this->input->post('id_wall', true));
		if ($type == 'new') {
			$post_data['max_id'] = intval($this->input->post('max_id', true));
			$items_on_page = null;
			$use_load = false;
		} else {
			$post_data['min_id'] = intval($this->input->post('min_id', true));
			$items_on_page = $this->pg_module->get_module_config('wall_events', 'items_per_page');
			$use_load = true;
		}
		$result = $this->_get_events($post_data, $use_load, $items_on_page);

		exit(json_encode($result));
	}

	private function _get_events($data, $use_load = false, $items_on_page = null, $page = null) {
		$result['status'] = 0;
		$result['error'] = '';//l('error', 'wall_events');
		$result['all_loaded'] = 0;
		$result['min_id'] = 0;
		$result['max_id'] = 0;

		$user_id = intval($this->session->userdata('user_id'));
		$this->template_lite->assign('user_id', $user_id);

		$params['where']['status'] = '1';

		if (isset($data['min_id']) && $data['min_id']) {
			$params['where']['id <'] = $data['min_id'];
		} elseif (isset($data['max_id']) && $data['max_id']) {
			$params['where']['id >'] = $data['max_id'];
		}

		$types_params['where']['status'] = '1';
		$wall_events_types = $this->Wall_events_types_model->get_wall_events_types_gids($types_params);
		$user_feeds = $this->Wall_events_permissions_model->get_user_feeds($user_id);
		$show_wall_events_types = array();

		//$is_lists_installed = $this->pg_module->is_module_installed('users_lists');
		$is_friendlist_installed = $this->pg_module->is_module_installed('friendlist');
		$is_favourites_installed = $this->pg_module->is_module_installed('favourites');
		/*if($is_lists_installed){
			$this->load->model('Users_lists_model');
		}else*/if($is_friendlist_installed){
			$this->load->model('Friendlist_model');
		}
		if($is_favourites_installed) {
			$this->load->model('Favourites_model');
		}
		switch($data['place']){
			case 'myprofile':
				if(!($user_id && $user_id == $data['id_wall'])){
					$params['where']['permissions'] = 3; // permissions 0 - only for me, 1 - for me and friends, 2 - for registered, 3 - for all
				}
				//$show_wall_events_types = $wall_events_types;
				$show_wall_events_types = array_intersect($wall_events_types, $user_feeds);
				$params['where']['id_wall'] = $user_id;
				break;
			case 'viewprofile':
				/*if($is_lists_installed) {
					$is_friend = $this->Users_lists_model->is_friend($data['id_wall'], $user_id);
				}else*/if($is_friendlist_installed) {
					$is_friend = $this->Friendlist_model->is_friend($data['id_wall'], $user_id);
				} else {
					$is_friend = false;
				}

				if($user_id && $is_friend){
					$params['where']['permissions >='] = 1; // permissions 0 - only for me, 1 - for me and friends, 2 - for registered, 3 - for all
				}elseif($user_id){
					$params['where']['permissions >='] = 2;
				}else{
					$params['where']['permissions'] = 3;
				}
				if($data['id_wall']){
					$params['where']['id_wall'] = $data['id_wall'];
				}
				$show_wall_events_types = $wall_events_types;
				break;
			case 'homepage':
			default:
				if(!($user_id && $user_id == $data['id_wall'])){
					$is_wall_owner = false;
					$perm_sql_str = 'permissions=3';
				}else{
					$is_wall_owner = true;
					$perm_sql_str = 'permissions>=1';
				}
				$friends_ids = array();
				$favourites_ids = array();
				/*if($is_lists_installed){
					$friends_ids = $this->Users_lists_model->get_friendlist_users_ids($user_id);
				}else*/if($is_friendlist_installed) {
					$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($user_id);
				}
				if($is_favourites_installed) {
					$favourites_ids = $this->Favourites_model->get_list_users_ids($user_id);
				}
				if($friends_ids || $favourites_ids){
					$friends_ids = array_unique(array_merge($friends_ids, $favourites_ids));
					if($is_wall_owner){
						$params['where_sql'][] = "( ({$perm_sql_str} AND id_wall IN(" . implode(',', $friends_ids) . ") AND id_wall = id_poster) OR id_wall={$user_id} )";
					}else{
						$params['where_sql'][] = "( {$perm_sql_str} AND id_wall IN(" . implode(',', $friends_ids) . ", {$user_id}) AND id_wall = id_poster )";
					}
				} else {
					$params['where']['id_wall'] = $user_id;
					if (!$is_wall_owner) {
						$params['where']['permissions'] = 3;
						$params['where']['id_wall = `id_poster`'] = null; // filter alien posts on my friends walls
					}
				}
				$show_wall_events_types = array_intersect($wall_events_types, $user_feeds);
				break;
		}
		$params['where_in']['event_type_gid'] = $show_wall_events_types;

		$result['count_all'] = $show_wall_events_types ? intval($this->Wall_events_model->get_events_count($params)) : 0;
		$result['events'] = array();
		if ($result['count_all']) {
			$wall_events = $this->Wall_events_model->get_events($params, $items_on_page, $page);
			if ($wall_events) {
				$result['all_loaded'] = intval(count($wall_events) == $result['count_all']);
				$result['events'] = $this->Wall_events_model->format_events($wall_events);

				if ($use_load) {
					/*
					 * подгрузка еще событий если после форматирования осталось меньше 70% от запрошенного кол-ва
					 * 70% - чтоб не подгружать события, если отсеялись только 1-2 штуки
					 */
					$requested_count = count($wall_events) * 0.7;
					$load_counter = 0; //for exclude overload
					while (count($result['events']) < $requested_count && !$result['all_loaded'] && $load_counter < 10) {
						$params['where']['id <'] = $wall_events[0]['id'];
						foreach ($wall_events as $e) {
							if ($params['where']['id <'] > $e['id']) {
								$params['where']['id <'] = $e['id'];
							}
						}
						$load_count = intval($this->Wall_events_model->get_events_count($params));
						$load_events = array();
						if ($load_count) {
							$load_events = $this->Wall_events_model->get_events($params, $items_on_page, $page);
							$wall_events = array_merge($wall_events, $load_events);
							$result['events'] += $this->Wall_events_model->format_events($load_events);
						}
						$result['all_loaded'] = intval(count($load_events) == $load_count || !$load_count);
						$load_counter++;
					}
					$result['events'] = array_values($result['events']);
				}

				$result['min_id'] = $result['max_id'] = intval($wall_events[0]['id']);
				foreach ($wall_events as $e) {
					$eid = intval($e['id']);
					if ($result['min_id'] > $eid) {
						$result['min_id'] = $eid;
					}
					if ($result['max_id'] < $eid) {
						$result['max_id'] = $eid;
					}
				}

				$wall_events_users_ids = $wall_events_users = array();
				foreach ($wall_events as $w_e) {
					$wall_events_users_ids[$w_e['id_wall']] = $w_e['id_wall'];
					$wall_events_users_ids[$w_e['id_poster']] = $w_e['id_poster'];
				}
				if ($wall_events_users_ids) {
					$this->load->model('Users_model');
					$wall_events_users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $wall_events_users_ids);
					foreach ($wall_events_users as $e_user) {
						$result['users'][$e_user['id']] = array(
							'output_name' => $e_user['output_name'],
							'media' => array('user_logo' => $e_user['media']['user_logo']),
						);
						$users[$e_user['id']] = $e_user;
					}
				}
				$result['users'][0] = $users[0] = $this->Users_model->format_default_user(1);

				$this->template_lite->assign('events', $result['events']);
				$this->template_lite->assign('users', $users);
				$result['html'] = trim($this->template_lite->fetch_final('wall_events'));
				$result['status'] = 1;
			} else {
				$result['all_loaded'] = 1;
			}
		} else {
			$result['all_loaded'] = 1;
		}
		$result['count'] = count($result['events']);

		return $result;
	}

	public function ajax_user_permissions() {
		$user_id = $this->session->userdata('user_id');
		$user_perm = $this->Wall_events_permissions_model->get_user_permissions($user_id);
		$redirect_url = $this->input->post('redirect_url', true);
		$this->template_lite->assign('redirect_url', $redirect_url);
		$this->template_lite->assign('user_perm', $user_perm);
		exit($this->template_lite->fetch('wall_permissions'));
	}

	public function save_user_permissions() {
		$user_id = $this->session->userdata('user_id');
		//$user_perm = $this->Wall_events_permissions_model->get_user_permissions($user_id);
		$redirect_url = trim($this->input->post('redirect_url', true));
		$post_perm = $this->input->post('perm', true);
		$this->Wall_events_permissions_model->set_user_permissions($user_id, $post_perm);
		foreach ($post_perm as $gid => $p) {
			$permissions = isset($p['permissions']) ? intval($p['permissions']) : 3;
			//if(!(isset($user_perm[$gid]['permissions']) && $user_perm[$gid]['permissions'] == $permissions)){
			$this->Wall_events_model->update_permissions($user_id, $gid, $permissions);
			//}
		}
		redirect($redirect_url);
	}

	public function ajax_post() {
		$post_data = $this->input->post('post', true);
		$user_id = $this->session->userdata('user_id');
		$id_wall = ($post_data['place'] == 'homepage' || $post_data['place'] == 'myprofile') ? $user_id : intval($post_data['id_wall']);
		$data['text'] = trim(strip_tags($post_data['text']));
		$data['embed_code'] = trim($post_data['embed_code']);
		if ($data['text'] == l('post_placeholder', 'wall_events')) {
			$data['text'] = '';
		}

		$required_fields = (!$data['text'] && $data['embed_code']) ? array('embed_data') : array('text');
		$result = $this->_post($id_wall, $data, 0, $required_fields);
		exit(json_encode($result));
	}

	public function ajax_post_delete() {
		$result['error'] = 1;
		$result['status'] = 0;
		$result['msg'] = '';
		$user_id = $this->session->userdata('user_id');
		//$event_type_gid = $this->Wall_events_model->wall_event_gid;
		$event_id = intval($this->input->post('event_id', true));
		$event = $this->Wall_events_model->get_event_by_id($event_id);
		if ($event && ($event['id_wall'] == $user_id || $event['id_poster'] == $user_id )) {
			$where['id'] = $event_id;
			$result['status'] = $this->Wall_events_model->delete_events($where);
			$result['error'] = 0;
			$result['msg'] = l('post_deleted', 'wall_events');

			//delete comments
			/* if($this->pg_module->is_module_installed('comments')){
			  $this->load->model('Comments_model');
			  $this->Comments_model->delete_comments_by_gid_obj('wall_events', $event_id);
			  } */
		}
		exit(json_encode($result));
	}

	public function post_upload($id_wall, $place = 'homepage') {
		$user_id = $this->session->userdata('user_id');
		$id_wall = ($place == 'homepage' || $place == 'myprofile') ? $user_id : intval($id_wall);
		$data['text'] = trim(strip_tags($this->input->post('text', true)));
		$data['embed_code'] = trim($this->input->post('embed_code', true));
		if ($data['text'] == l('post_placeholder', 'wall_events')) {
			$data['text'] = '';
		}
		$id = intval($this->input->post('id'));

		$result = $this->_post($id_wall, $data, $id);

		exit(json_encode($result));
	}

	public function post_form($id_wall, $place = 'homepage') {
		$user_id = $this->session->userdata('user_id');
		$id_wall = ($place == 'homepage' || $place == 'myprofile') ? $user_id : intval($id_wall);
		$data['text'] = trim(strip_tags($this->input->post('text', true)));
		$data['embed_code'] = trim($this->input->post('embed_code', true));
		if ($data['text'] == l('post_placeholder', 'wall_events')) {
			$data['text'] = '';
		}
		$required_fields = (!$data['text'] && $data['embed_code']) ? array('embed_data') : array('text');
		$this->_post($id_wall, $data, 0, $required_fields);
		redirect();
	}

	private function _post($id_wall, $data = array(), $id = 0, $required_fields = array()) {
		$user_id = $this->session->userdata('user_id');
		$event_type_gid = $this->Wall_events_model->wall_event_gid;
		$this->load->helper('wall_events_default');
		if ($id) {
			$result = add_event_data($id, $user_id, $data, 'multiupload');
		} else {
			$result = add_wall_event($event_type_gid, $id_wall, $user_id, $data, 0, 'multiupload', $required_fields);
		}
		$result['error'] = 1;
		$result['status'] = 0;
		$result['msg'] = '';

		if ($result) {
			if (!empty($result['errors'])) {
				$result['msg'] = implode('<br>', (array) $result['errors']);
			} else {
				unset($result['errors']);
				$result['error'] = 0;
				$result['status'] = 1;
			}
		}

		return $result;
	}

}
