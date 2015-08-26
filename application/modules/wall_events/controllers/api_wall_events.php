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
class Api_Wall_events extends Controller {

	private $user_id;

	public function __construct() {
		parent::Controller();
		$this->load->model('Wall_events_model');
		$this->load->model('wall_events/models/Wall_events_types_model');
		$this->load->model('wall_events/models/Wall_events_permissions_model');
		$this->user_id = intval($this->session->userdata('user_id'));
	}

	public function get_events() {
		$type = trim(strip_tags($this->input->post('type', true)));
		if (!$type)
			$type = 'all';

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
		$data = $this->_get_events($post_data, $use_load, $items_on_page);
		$this->set_api_content('data', $data);
	}

	private function _get_events($data, $use_load = false, $items_on_page = null, $page = null) {
		$result['status'] = 0;
		$result['error'] = l('error', 'wall_events');
		$result['all_loaded'] = 0;
		$result['min_id'] = 0;
		$result['max_id'] = 0;

		$api_data['user_id'] = $this->user_id;

		$params['where']['status'] = '1';

		if (isset($data['min_id']) && $data['min_id']) {
			$params['where']['id <'] = $data['min_id'];
		} elseif (isset($data['max_id']) && $data['max_id']) {
			$params['where']['id >'] = $data['max_id'];
		}

		$types_params['where']['status'] = '1';
		$wall_events_types = $this->Wall_events_types_model->get_wall_events_types_gids($types_params);
		$user_feeds = $this->Wall_events_permissions_model->get_user_feeds($this->user_id);
		$show_wall_events_types = array();

		//$is_lists_installed = $this->pg_module->is_module_installed('users_lists');
		$is_friendlist_installed = $this->pg_module->is_module_installed('friendlist');
		$is_favourites_installed = $this->pg_module->is_module_installed('favourites');
		/*if ($is_lists_installed) {
			$this->load->model('Users_lists_model');
		} else*/if ($is_friendlist_installed) {
			$this->load->model('Friendlist_model');
		}
		if($is_favourites_installed) {
			$this->load->model('Favourites_model');
		}
		switch ($data['place']) {
			case 'myprofile':
				if (!($this->user_id && $this->user_id == $data['id_wall'])) {
					$params['where']['permissions'] = 3; // permissions 0 - only for me, 1 - for me and friends, 2 - for registered, 3 - for all
				}
				//$show_wall_events_types = $wall_events_types;
				$show_wall_events_types = array_intersect($wall_events_types, $user_feeds);
				$params['where']['id_wall'] = $this->user_id;
				break;
			case 'viewprofile':
				/*if ($is_lists_installed) {
					$is_friend = $this->Users_lists_model->is_friend($data['id_wall'], $this->user_id);
				} else*/if ($is_friendlist_installed) {
					$is_friend = $this->Friendlist_model->is_friend($data['id_wall'], $this->user_id);
				} else {
					$is_friend = false;
				}
				if ($this->user_id && $is_friend) {
					$params['where']['permissions >='] = 1; // permissions 0 - only for me, 1 - for me and friends, 2 - for registered, 3 - for all
				} elseif ($this->user_id) {
					$params['where']['permissions >='] = 2;
				} else {
					$params['where']['permissions'] = 3;
				}
				if ($data['id_wall']) {
					$params['where']['id_wall'] = $data['id_wall'];
				}
				$show_wall_events_types = $wall_events_types;
				break;
			case 'homepage':
			default:
				if (!($this->user_id && $this->user_id == $data['id_wall'])) {
					$is_wall_owner = false;
					$perm_sql_str = 'permissions=3';
				} else {
					$is_wall_owner = true;
					$perm_sql_str = 'permissions>=1';
				}
				$friends_ids = array();
				/*if ($is_lists_installed) {
					$friends_ids = $this->Users_lists_model->get_friendlist_users_ids($this->user_id);
				} else*/if ($is_friendlist_installed) {
					$friends_ids = $this->Friendlist_model->get_friendlist_users_ids($this->user_id);
				}
				if($is_favourites_installed) {
					$favourites_ids = $this->Friendlist_model->get_list_users_ids($this->user_id);
				}
				if ($friends_ids || $favourites_ids) {
					$friends_ids = array_merge($friends_ids, $favourites_ids);
					if ($is_wall_owner) {
						$params['where_sql'][] = "( ({$perm_sql_str} AND id_wall IN(" . implode(',', $friends_ids) . ") AND id_wall = id_poster) OR id_wall={$this->user_id} )";
					} else {
						$params['where_sql'][] = "( {$perm_sql_str} AND id_wall IN(" . implode(',', $friends_ids) . ", {$this->user_id}) AND id_wall = id_poster )";
					}
				} else {
					$params['where']['id_wall'] = $this->user_id;
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
					/* 	 */
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
					$wall_events_users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $wall_events_users_ids, true, true);
					foreach ($wall_events_users as $e_user) {
						$result['users'][$e_user['id']] = array(
							'output_name' => $e_user['output_name'],
							'media' => array('user_logo' => $e_user['media']['user_logo']),
						);
						$users[$e_user['id']] = $e_user;
					}
				}
				$result['users'][0] = $users[0] = $this->Users_model->format_default_user();
				$api_data['events'] = $result['events'];
				$api_data['users'] = $result['$users'];
				//$result['html'] = trim($this->template_lite->fetch('wall_events'));
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

	public function user_permissions() {
		$user_perm = $this->Wall_events_permissions_model->get_user_permissions($this->user_id);
		$redirect_url = $this->input->post('redirect_url', true);
		$data['redirect_url'] = $redirect_url;
		$data['user_perm'] = $user_perm;
		$this->set_api_content('data', $data);
	}

	public function save_user_permissions() {
		$user_perm = $this->Wall_events_permissions_model->get_user_permissions($this->user_id);
		$redirect_url = trim($this->input->post('redirect_url', true));
		$post_perm = $this->input->post('perm', true);
		$this->Wall_events_permissions_model->set_user_permissions($this->user_id, $post_perm);
		foreach ($post_perm as $gid => $p) {
			$permissions = isset($p['permissions']) ? intval($p['permissions']) : 3;
			//if(!(isset($user_perm[$gid]['permissions']) && $user_perm[$gid]['permissions'] == $permissions)){
			$this->Wall_events_model->update_permissions($this->user_id, $gid, $permissions);
			//}
		}
	}

	public function post() {
		$post_data = $this->input->post('post', true);
		$id_wall = ($post_data['place'] == 'homepage' || $post_data['place'] == 'myprofile') ? $this->user_id : intval($post_data['id_wall']);
		$text = trim(strip_tags($post_data['text']));
		if ($text == l('post_placeholder', 'wall_events')) {
			$text = '';
		}

		$result = $this->_post($id_wall, $text, 0, array('text'));
		$this->set_api_content('data', $result);
	}

	public function post_delete() {
		$result['error'] = 1;
		$result['status'] = 0;
		$result['msg'] = '';
		$event_type_gid = $this->Wall_events_model->wall_event_gid;
		$event_id = intval($this->input->post('event_id', true));
		$event = $this->Wall_events_model->get_event_by_id($event_id);
		if ($event && ($event['id_wall'] == $this->user_id)) {
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
		$this->set_api_content('data', $result);
	}

	public function post_upload() {
		$id_wall = intval($this->input->post('id_wall'));
		$place = trim(strip_tags($this->input->post('place', true)));
		if (!$place)
			$place = 'homepage';

		$id_wall = ($place == 'homepage' || $place == 'myprofile') ? $this->user_id : $id_wall;
		$text = trim(strip_tags($this->input->post('text')));
		if ($text == l('post_placeholder', 'wall_events')) {
			$text = '';
		}
		$id = intval($this->input->post('id'));

		$result = $this->_post($id_wall, $text, $id);
		$this->set_api_content('data', $result);
	}

	private function _post($id_wall, $text = '', $id = 0, $required_fields = array()) {
		$data['text'] = $text;

		$event_type_gid = $this->Wall_events_model->wall_event_gid;
		$this->load->helper('wall_events_default');
		if ($id) {
			$result = add_event_data($id, $this->user_id, $data, 'multiupload');
		} else {
			$result = add_wall_event($event_type_gid, $id_wall, $this->user_id, $data, 0, 'multiupload', $required_fields);
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
			}
			$result['status'] = 1;
		}

		return $result;
	}

}
